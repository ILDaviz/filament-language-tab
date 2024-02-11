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

    protected $form = [];

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

        $getLabelTab = $this->getLabelTab($getLabelTab);

        $getFormTranslatableContent = $this->getClosureContent($getFormTranslatableContent);

        foreach ($this->getLanguages() as $lang) {
            $tabs[] = Tab::make('tab-'.$lang)
                ->label($getLabelTab($lang))
                ->schema($getFormTranslatableContent($lang));
        }

        return Tabs::make('translations')
            ->tabs($tabs);
    }


    /**
     * Get the label for each tab.
     */
    protected function getLabelTab(Closure | null $getLabelTab): Closure
    {
        if (is_null($getLabelTab)) {
            $getLabelTab = function ($lang) {
                return 'Tab '.$lang;
            };
        }

        return $getLabelTab;
    }

    /**
     * Get the content for each tab.
     */
    protected function getClosureContent(Closure | null $getFormTranslatableContext): Closure
    {
        if (is_null($getFormTranslatableContext)) {
            $getFormTranslatableContext = function ($lang) {
                return [
                    //
                ];
            };
        }

        return $getFormTranslatableContext;
    }
}
