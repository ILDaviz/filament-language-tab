<?php

namespace FilamentLanguageTab\Facades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string makeName(string $name, string $locale)
 * @method static init()
 * @method static getTranslatableAttributes(Model $model)
 * @method static array getLocales()
 * @method static setTranslatableLocales(array $locales = [])
 * @method static setTranslatableAttributes(array $translatableAttributes = [])
 * @method static beforeCreate(string $modelClass, array $data)
 * @method static beforeFill(Model $model, array $data)
 * @method static beforeSave(Model $model, array $data)
 */
class LanguageTab extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'filament-language-tab';
    }
}
