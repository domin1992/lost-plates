<?php

namespace Zencoreitservices\Translator\Console\Commands;

use Illuminate\Console\Command;
use Zencoreitservices\Translator\Processors\TranslationProcessor;

class Translate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zncr:translate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translates default laravel\'s language files';

    /**
     * Execute the console command.
     */
    public function handle(TranslationProcessor $translationProcessor): void
    {
        $translationProcessor->setSourceLang(config('translator.translateFrom'));
        $translationProcessor->setTargetLangs(config('translator.translateTo'));
        $translationProcessor->setFilesFormat(config('translator.filesFormat'));

        $this->info('Creating missing files...');

        $translationProcessor->createMissingFiles();

        $this->info('Managing missing translations...');

        $translationProcessor->manageMissingTranslations();

        $this->info('Done');
    }
}
