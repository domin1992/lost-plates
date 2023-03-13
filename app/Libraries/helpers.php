<?php

use Illuminate\Support\Collection;

function activeLanguages(): Collection
{
    return collect([
        'pl',
        'en',
    ]);
}

function defaultLanguage(): string
{
    return 'pl';
}
