## Filament Language Tab
Important Note: In Development, Not Ready for Production. This package provides a language tab for the Filament admin panel. The tab allows users to manage the languages of the application and their translations.

### Description
The Filament Language Tab package provides a tab for managing the languages of the application and their translations. It is designed to be used with the Filament admin panel and is intended to be integrated into Laravel applications.

### Installation
``` bash
composer require ildaviz/filament-language-tab:dev-main
```

Publish the configuration:
```
php artisan vendor:publish
select: Tag: filament-language-tab-config
```
### Set Spatie Translatable

```php
namespace App\Models;

use Spatie\Translatable\HasTranslations;

class Posts extends Model
{
    ...

    use HasTranslations;

    public $translatable = [
        'title',
        'content',
        'slug',
        'images',
    ];
    
    ...
}
```


### Usage for resources

1. Add on config/filament-language-tab.php your languages.
2. Add this element on your create and edit page.

Add on Crate page and Edit page this "use" statement.

```php
use FilamentLanguageTab\Concerns\UseTranslatablePage;

class CreatePost ...
{
    use UseTranslatablePage;
    
    ...
```

```php
use FilamentLanguageTab\Concerns\UseTranslatablePage;

class EditPost ...
{
    use UseTranslatablePage;
    
    ...
```

For change the locales or translatable attributes of the specific page add this methods

```php
public function setTranslatableLocales(): array
{
    return [
        'en',
        'es',
        'fr',
    ];
}

public function setTranslatableAttributes(): array
{
    return [
        'title',
        'content',
        'slug',
        'images',
    ];
}

```


3. Add on your form this component. Example:

```php
use FilamentLanguageTab\Components\TabsTranslatable;
use FilamentLanguageTab\Facades\LanguageTab;

TabsTranslatable::make('translations')
    ->makeForm(
        getLabelTab: function ($lang) {
            return 'Translations ' . $lang;
        },
        getFormTranslatableContent: function ($lang) {
            return [
                Forms\Components\Group::make([
                    Forms\Components\TextInput::make(LanguageTab::makeName('title', $lang))
                        ->label('Title')
                        ->placeholder('Title')
                        ->required(),
                    Forms\Components\Textarea::make(LanguageTab::makeName('content', $lang))
                        ->label('Content')
                        ->placeholder('Content')
                        ->required(),
                    Forms\Components\TextInput::make(LanguageTab::makeName('slug', $lang))
                        ->label('Slug')
                        ->placeholder('Slug')
                        ->required(),
                    Forms\Components\FileUpload::make(LanguageTab::makeName('images', $lang))
                        ->multiple()
                        ->label('Images')
                        ->placeholder('Images')
                        ->required(),
                ])
            ];
        }
    )

```

TabsTranslatable is a wrapper for the Tabs component. It is used to create a tab for each language and to manage the translations of the fields.

```php
->languages(['en', 'es', 'fr'])
```
For add custom languages.

```php
->makeForm(
    // For change the label of the tab
    getLabelTab: function (string $lang) {}
    // For add the fields of the form for each language
    getFormTranslatableContent: function (string $lang) {}
)
```
Add correct label for fields.
```php
LanguageTab::makeName('name of field', $lang)
```
### Usage on modals or other components

```php
// Use LanguageTab facade.

use FilamentLanguageTab\Facades\LanguageTab;

// Initialize the LanguageTab facade.
init()
// For change locales
setTranslatableLocales(array $locales = [])
// Set the translatable attributes from the model.
getTranslatableAttributes(Model $model)
// Set the translatable attributes.
setTranslatableAttributes(array $translatableAttributes = [])
// Use before create event
beforeCreate(string $modelClass, array $data)
// Use before fill event
beforeFill(Model $model, array $data)
// Use before save event
beforeSave(Model $model, array $data)
// Get the locales from the configuration.
array getLocales()
// Make the name of the field for the form.
string makeName(string $name, string $locale)

```

### Issue Reporting
Please report any problems or bugs in the dedicated issues section on GitHub. Contributions and suggestions are welcome.

### License
This package is distributed under the MIT license. For more information, refer to the LICENSE file.
