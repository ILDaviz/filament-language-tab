<?php

namespace FilamentLanguageTab\Components;

use Closure;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use FilamentLanguageTab\Concerns\HasLanguage;
use Illuminate\Support\Str;

class TabsTranslatable extends Component
{
    use HasLanguage;

    protected string $locale;

    public static function make(?string $name = null): static
    {
        $static = app(static::class, [
            'name' => $name ?? static::getDefaultName(),
        ]);
        $static->configure();

        return $static;
    }

    public static function getDefaultName(): string
    {
        return 'tabs-translatable';
    }

    public static function getTranslationTab($labelTab, $lang): string
    {
        return Str::slug($labelTab.'-'.$lang);
    }

    /**
     * @param  Closure  $getLabelTab  (string $lang) Set the label for each tab
     * @param  Closure  $getFormTranslatableContent  (string $lang) Set the content for each tab. It must return an array of Field
     */
    public function makeForm(
        Closure $getLabelTab,
        Closure $getFormTranslatableContent
    ): Tabs {
        $tabs = [];

        foreach ($this->getLanguages() as $lang) {

            $this->setLocale($lang);

            $tabs[] = Tab::make('tab-'.$lang)
                ->label($this->evaluate($getLabelTab))
                ->schema($this->evaluate($getFormTranslatableContent));
        }

        return Tabs::make('translations')
            ->tabs($tabs);
    }

    protected function resolveDefaultClosureDependencyForEvaluationByName(string $parameterName): array
    {
        return match ($parameterName) {
            'context', 'operation' => [$this->getContainer()->getOperation()],
            'get' => [$this->getGetCallback()],
            'livewire' => [$this->getLivewire()],
            'model' => [$this->getModel()],
            'record' => [$this->getRecord()],
            'set' => [$this->getSetCallback()],
            'state' => [$this->getState()],
            'lang' => [$this->getLocale()],
            default => parent::resolveDefaultClosureDependencyForEvaluationByName($parameterName),
        };
    }

    /**
     * @return mixed
     */
    protected function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * @param mixed $lang
     * @return void
     */
    protected function setLocale(string $lang): void
    {
        $this->locale = $lang;
    }
}
