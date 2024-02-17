<?php

use FilamentLanguageTab\FilamentLanguageTab;
use FilamentLanguageTab\Tests\Stubs\Demo;
use Mockery\MockInterface;

it('generate label', function () {
    $filamentLanguageTab = new FilamentLanguageTab();
    $label = $filamentLanguageTab->makeName('name', 'it');
    expect($label)->toBe('name-it');
});

it('beforeCreate correct change elements', function () {
    $model = new Demo();

    $filamentLanguageTab = new FilamentLanguageTab();

    $data = $filamentLanguageTab->init()
        ->setTranslatableLocales([
            'it',
            'en',
        ])
        ->setTranslatableAttributes([
            'name',
        ])
        ->beforeCreate($model::class, [
            $filamentLanguageTab->makeName('name', 'it') => 'nomeIt',
            $filamentLanguageTab->makeName('name', 'en') => 'nameEn',
        ]);

    expect($data)->toBeArray()
        ->and($data)->toHaveKey('name')
        ->and($data['name']['it'])->toBe('nomeIt')
        ->and($data['name']['en'])->toBe('nameEn');
});

it('beforeFill correct structure', function () {
    $mock = $this->mock(Demo::class, function (MockInterface $mock) {
        $mock->shouldReceive('getTranslatableAttributes')
            ->andReturn(['it']);
        $mock->shouldReceive('getTranslation')
            ->with('name', 'it', false)
            ->andReturn('nomeIt');
    });

    $filamentLanguageTab = new FilamentLanguageTab();

    $data = $filamentLanguageTab->init()
        ->setTranslatableLocales([
            'it',
        ])
        ->setTranslatableAttributes([
            'name',
        ])
        ->beforeFill($mock, [
            'name' => [
                'it' => 'nomeIt',
            ],
        ]);

    expect($data)->toBeArray()
        ->and($data)->toHaveKey('name-it')
        ->and($data['name-it'])->toBe('nomeIt');
});

it('beforeSave correct structure', function () {
    $filamentLanguageTab = new FilamentLanguageTab();

    $data = $filamentLanguageTab->init()
        ->setTranslatableLocales([
            'it',
            'en'
        ])
        ->setTranslatableAttributes([
            'name',
        ])
        ->beforeSave([
            'name-it' => 'nomeIt',
            'name-en' => 'nameEn',
        ]);

    expect($data)->toBeArray()
        ->and($data)->toHaveKey('name')
        ->and($data['name']['it'])->toBe('nomeIt')
        ->and($data['name']['en'])->toBe('nameEn');
});
