<?php

namespace FilamentLanguageTab;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentLanguageTabServiceProvider extends PackageServiceProvider
{
    public function register()
    {
        $this->app->bind('filament-language-tab', function ($app) {
            return new FilamentLanguageTab();
        });

        return parent::register();
    }

    public function configurePackage(Package $package): void
    {
        /* Info: https://github.com/spatie/laravel-package-tools */
        $package
            ->name('filament-language-tab')
            ->hasConfigFile([
                'filament-language-tab',
            ]);
    }
}
