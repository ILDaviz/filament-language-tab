## Filament Language Tab
Administers multi-language content in tabs. Each tab is divided by language. You can go and customize the label or tab content to your liking. In addition, there is a Facade to also use this system in modals or wherever you like.
An easy way to manage multi-language sites with Filament!

### Preview
![preview](https://github.com/ILDaviz/filament-language-tab/raw/master/preview.gif)

### Installation
``` bash
composer require ildaviz/filament-language-tab:dev-main
```

Publish the configuration:
```
php artisan vendor:publish
select: Tag: filament-language-tab-config
```
### Set Spatie Translatable on models

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
        ...
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

Add these methods to change the locales or translatable attributes for a given page

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
        // Use the "lang" props to have the language cycled
        // You can also use the other Filament closures.
        getLabelTab: function ($lang) {
            return 'Translations ' . $lang;
        },
        // Use the "lang" props to have the language cycled
        // You can also use the other Filament closures.
        getFormTranslatableContent: function ($lang) {
            return [
                Forms\Components\Group::make([
                    Forms\Components\TextInput::make(LanguageTab::makeName('title', $lang))
                        ->label(__(LanguageTab::makeName('title', $lang)))
                        ->placeholder(__(LanguageTab::makeName('placeholder', $lang)))
                        ->required(),
                    Forms\Components\Textarea::make(LanguageTab::makeName('content', $lang))
                        ->label(__(LanguageTab::makeName('content', $lang)))
                        ->placeholder(__(LanguageTab::makeName('content', $lang)))
                        ->required(),
                    Forms\Components\TextInput::make(LanguageTab::makeName('slug', $lang))
                        ->label(__(LanguageTab::makeName('slug', $lang)))
                        ->placeholder(__(LanguageTab::makeName('slug', $lang)))
                        ->required(),
                    Forms\Components\FileUpload::make(LanguageTab::makeName('images', $lang))
                        ->multiple()
                        ->label(__(LanguageTab::makeName('images', $lang)))
                        ->placeholder(__(LanguageTab::makeName('images', $lang)))
                        ->required(),
                ])
            ];
        }
    )

```

TabsTranslatable is a wrapper for the Tabs component. It is used to create a tab for each language and to manage the translations of the fields.

To add custom languages.
```php
->languages(['en', 'es', 'fr'])
```
For create the form for each language.

```php
->makeForm(
    // Use the "lang" props to have the language cycled
    // You can also use the other Filament closures.
    getLabelTab: function (string $lang) {}
    // Use the "lang" props to have the language cycled
    // You can also use the other Filament closures.
    getFormTranslatableContent: function (string $lang) {}
)
```
Add correct label for fields.
```php
LanguageTab::makeName('{name of field}', $lang)

//example:
LanguageTab::makeName('title', $lang)

//result:
'title-en'

```

### Usage on modals or other components

```php
// Use LanguageTab facade.

use FilamentLanguageTab\Facades\LanguageTab;

// Initialize the LanguageTab facade.
init()
// For change locales (optional)
setTranslatableLocales(array $locales = [])
// Set the translatable attributes from the specific model.
getTranslatableAttributes(string $modelClass)
// Set the translatable attributes. (optional)
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
