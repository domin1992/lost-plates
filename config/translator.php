<?php

return [
    'translateFrom' => 'pl',

    'translateTo' => activeLanguages()
        ->filter(fn ($lang) => $lang !== 'pl')
        ->toArray(),

    'limitTranslationsPerRun' => 50,

    'deeplApiKey' => env('DEEPL_API_KEY'),
];
