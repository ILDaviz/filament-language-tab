<?php

namespace FilamentLanguageTab\Concerns;

use Closure;

trait HasLanguage
{
    protected array | Closure | null $languages = [];

    public function languages(array | Closure | null $languages): static
    {
        $this->languages = $languages;

        return $this;
    }

    public function getLanguages(): ?array
    {
        if (empty($this->languages)) {
            return config('filament-language-tab.locales', []);
        }

        return $this->evaluate($this->languages);
    }
}
