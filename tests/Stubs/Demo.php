<?php

namespace FilamentLanguageTab\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Demo extends Model
{
    use HasTranslations;

    public $translatable = [
        'name',
    ];

    protected $fillable = [
        'name',
    ];
}
