<?php

namespace FilamentLanguageTab\Concerns;

use FilamentLanguageTab\Facades\LanguageTab;
use Illuminate\Support\Str;

trait UseTranslatablePage
{
    public function setTranslatableLocales(): array
    {
        return LanguageTab::getLocales();
    }

    public function setTranslatableAttributes(): array
    {
        return LanguageTab::getTranslatableAttributes(static::getModel());
    }

    /**
     * I set the languages.
     */
    protected function updateTranslatableLocales(): array
    {
        return $this->setTranslatableLocales();
    }

    /**
     * I set the attributes to be translated.
     */
    protected function updateTranslatableAttributes(): array
    {
        return $this->setTranslatableAttributes();
    }

    protected function getStringAttributeLocale(string $attribute, string $locale): string
    {
        return Str::of($attribute)->append('-', $locale)->toString();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $model = static::getModel();

        $data = LanguageTab::init()
            ->setTranslatableLocales($this->updateTranslatableLocales())
            ->setTranslatableAttributes($this->updateTranslatableAttributes())
            ->beforeCreate($model, $data);

        return parent::mutateFormDataBeforeCreate($data);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $model = $this->getRecord();

        $data = LanguageTab::init()
            ->setTranslatableLocales($this->updateTranslatableLocales())
            ->setTranslatableAttributes($this->updateTranslatableAttributes())
            ->beforeFill($model, $data);

        return parent::mutateFormDataBeforeFill($data);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = LanguageTab::init()
            ->setTranslatableLocales($this->updateTranslatableLocales())
            ->setTranslatableAttributes($this->updateTranslatableAttributes())
            ->beforeSave($data);

        return parent::mutateFormDataBeforeSave($data);
    }
}
