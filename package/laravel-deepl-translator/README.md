# Laravel DeepL Translator

## Install

Download package

`composer require zencoreitservices\laravel-deepl-translator`

Ignore language files except default one to avoid git conflicts

```
!/lang/
/lang/*
!/lang/en/
```

Add service provider to your `config/app.php`

`Zencoreitservices\Translator\Providers\DeepLTranslatorProvider::class`

Add command to your `app/Console/Kernel.php`

`$schedule->command('zncr:translate')->everyHour();`