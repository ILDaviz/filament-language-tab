<?php

namespace FilamentLanguageTab;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\Translatable\HasTranslations;

class FilamentLanguageTab
{
    protected array $locales = [];

    protected array $translatableAttributes = [];

    public function makeName($label, $lang): string
    {
        return Str::of($label)->append('-')->append($lang)->__toString();
    }

    public function init(): FilamentLanguageTab
    {
        $this->locales = $this->getLocales();
        return $this;
    }

    public function getTranslatableAttributes(string $modelClass): array
    {
        if (! method_exists((new $modelClass), 'getTranslatableAttributes')) {
            throw new Exception("Model [{$modelClass}] must use trait [".HasTranslations::class.'].');
        }

        $attributes = (new $modelClass)->getTranslatableAttributes();

        if (! count($attributes)) {
            throw new Exception("Model [{$modelClass}] must have [\$translatable] properties defined.");
        }

        return $attributes;
    }

    public function getLocales(): array
    {
        return config('filament-language-tab.locales', []);
    }

    public function setTranslatableLocales(array $locales = []): FilamentLanguageTab
    {
        $this->locales = $locales;
        return $this;
    }

    public function setTranslatableAttributes(array $translatableAttributes = []): FilamentLanguageTab
    {
        $this->translatableAttributes = $translatableAttributes;
        return $this;
    }

    protected function getLangMapTranslatable(): Collection
    {
        $langMap = [];

        foreach ($this->locales as $translatableLang) {
            foreach ($this->translatableAttributes as $translatableAttribute) {
                $langMap[] = [
                    'lang' => $translatableLang,
                    'lang_attribute' => Str::of($translatableAttribute)->append('-')->append($translatableLang)->toString(),
                    'attribute' => $translatableAttribute,
                ];
            }
        }

        return collect($langMap);
    }

    public function beforeCreate(string $modelClass, $data): array
    {
        if (! method_exists((new $modelClass), 'getTranslatableAttributes')) {
            throw new Exception("Model [{$modelClass}] must use trait [".HasTranslations::class.'].');
        }

        $attributes = (new $modelClass)->getTranslatableAttributes();

        if (! count($attributes)) {
            throw new Exception("Model [{$modelClass}] must have [\$translatable] properties defined.");
        }

        $data = $this->getAttributeFiltered($data);

        return $data;
    }

    public function beforeFill(Model $model, $data): array
    {
        if (! method_exists($model, 'getTranslatableAttributes')) {
            throw new Exception("Model [{$model}] must use trait [".HasTranslations::class.'].');
        }

        $attributes = $model->getTranslatableAttributes();

        if (! count($attributes)) {
            throw new Exception("Model [{$model}] must have [\$translatable] properties defined.");
        }


        collect($this->getLangMapTranslatable())->each(function ($mapAttribute) use (&$data, $model) {
            $lang = $mapAttribute['lang'];
            $attribute = $mapAttribute['attribute'];
            $langAttribute = $mapAttribute['lang_attribute'];

            $data[$langAttribute] = $model->getTranslation($attribute, $lang, false);
        });

        return $data;
    }

    public function beforeSave(array $data): array
    {
        $data = $this->getAttributeFiltered($data);

        return $data;
    }

    /**
     * @param $data
     * @return mixed
     */
    protected function getAttributeFiltered($data): mixed
    {
        collect($this->getLangMapTranslatable())->each(function ($mapAttribute) use (&$data) {
            $lang = $mapAttribute['lang'];
            $attribute = $mapAttribute['attribute'];
            $langAttribute = $mapAttribute['lang_attribute'];

            $data[$attribute][$lang] = Arr::get($data, $langAttribute);

            unset($data[$langAttribute]);
        });
        return $data;
    }

}
