<?php

namespace Zencoreitservices\Translator\Processors;

use Illuminate\Support\Collection;
use Zencoreitservices\Translator\Engines\DeepLEngine;
use Illuminate\Support\Arr;

class TranslationProcessor
{
    private ?Collection $filesCollection = null;
    private string $sourceLang;
    private array $targetLangs;
    private int $limitTranslationsPerRun = 0;
    private int $translationsDone = 0;

    public function __construct(
        private DeepLEngine $deepLEngine
    ) {
        $this->limitTranslationsPerRun = config('translator.limitTranslationsPerRun');
    }

    public function setSourceLang(string $sourceLang): void
    {
        $this->sourceLang = $sourceLang;
    }

    public function setTargetLangs(array $targetLangs): void
    {
        $this->targetLangs = $targetLangs;
    }

    public function createMissingFiles(): void
    {
        if (!file_exists(lang_path($this->sourceLang))) {
            throw new \InvalidArgumentException('Source language folder does not exist');
        }

        $this->createMissingDirectories($this->targetLangs);

        $sourcePath = lang_path($this->sourceLang);

        if (!$this->filesCollection || !$this->filesCollection->count()) {
            $this->collectFiles($sourcePath);
        }

        collect($this->targetLangs)
            ->each(function ($lang) use ($sourcePath) {
                $this->filesCollection
                    ->filter(fn ($file) => !file_exists(sprintf('%s/%s', lang_path($lang), $file)))
                    ->each(fn ($file) => $this->copyFile(
                        sprintf('%s/%s', $sourcePath, $file),
                        sprintf('%s/%s', lang_path($lang), $file)
                    ));
            });
    }

    private function collectFiles(string $sourcePath): void
    {
        $this->filesCollection = collect(scandir($sourcePath))
            ->filter(fn ($file) => !in_array($file, ['.', '..']));
    }

    private function createMissingDirectories(array $targetLangs): void
    {
        collect($targetLangs)
            ->filter(fn ($lang) => !file_exists(lang_path($lang)))
            ->each(fn ($lang) => mkdir(lang_path($lang)));
    }

    private function copyFile(string $sourcePath, string $targetPath): void
    {
        $content = include($sourcePath);
        $this->clearArray($content);

        file_put_contents($targetPath, $this->generateLangFile($content));
    }

    private function clearArray(array &$array): void
    {
        foreach ($array as &$arrayItem) {
            if (is_array($arrayItem)) {
                $this->clearArray($arrayItem);
            } else {
                $arrayItem = '';
            }
        }
    }

    private function generateLangFile(array $content): string
    {
        return view('translator::lang', [
            'translations' => $content,
        ])->render();
    }

    public function manageMissingTranslations(): void
    {
        if (!file_exists(lang_path($this->sourceLang))) {
            throw new \InvalidArgumentException('Source language folder does not exist');
        }

        $sourcePath = lang_path($this->sourceLang);

        if (!$this->filesCollection || !$this->filesCollection->count()) {
            $this->collectFiles($sourcePath);
        }

        collect($this->targetLangs)
            ->each(function ($lang) use ($sourcePath) {
                $this->filesCollection
                    ->each(function ($file) use ($lang, $sourcePath) {
                        $targetPath = sprintf('%s/%s', lang_path($lang), $file);

                        $translations = include($targetPath);
                        $sourceTranslations = include(sprintf('%s/%s', $sourcePath, $file));

                        $this->determineArrayMissingKeys($translations, $sourceTranslations);

                        $this->determineArrayTranslations($translations, $sourceTranslations, '', $lang);

                        file_put_contents($targetPath, $this->generateLangFile($translations));

                        if ($this->shouldStop()) {
                            return false;
                        }
                    });

                if ($this->shouldStop()) {
                    return false;
                }
            });
    }

    private function determineArrayMissingKeys(array &$translations, array $sourceTranslations): void
    {
        foreach ($sourceTranslations as $key => $translation) {
            if (is_array($translation)) {
                if (!isset($translations[$key])) {
                    $translations[$key] = [];
                }

                $this->determineArrayMissingKeys($translations[$key], $translation);
            } else {
                if (!isset($translations[$key])) {
                    $translations[$key] = '';
                }
            }
        }
    }

    private function determineArrayTranslations(
        array &$translations,
        array $sourceTranslations,
        string $keyChain,
        string $lang
    ): void {
        foreach ($translations as $key => &$translation) {
            if (is_array($translation)) {
                $this->determineArrayTranslations(
                    $translation,
                    $sourceTranslations,
                    $keyChain ? $keyChain . '.' . $key : $key,
                    $lang
                );
            } else {
                if (empty($translation)) {
                    $translation = $this->translate(
                        Arr::get(
                            $sourceTranslations,
                            $keyChain ? $keyChain . '.' . $key : $key
                        ),
                        $lang
                    );
                }
            }
        }
    }

    private function translate(string $text, string $targetLang): string
    {
        if ($this->shouldStop()) {
            return '';
        }

        if (!$text) {
            return '';
        }

        $usage = $this->deepLEngine->usage();
        if (strlen($text) + $usage['character_count'] > $usage['character_limit']) {
            return '';
        }

        $text = preg_replace('/(?<!\w):(\w+)/', '<keep>:$1</keep>', $text);

        $translated = $this->deepLEngine->translate($text, $this->sourceLang, $targetLang)['translations'][0]['text'];

        $translated = str_replace('<keep>', '', $translated);
        $translated = str_replace('</keep>', '', $translated);

        $this->translationsDone++;

        return $translated;
    }

    private function shouldStop(): bool
    {
        return $this->translationsDone >= $this->limitTranslationsPerRun;
    }
}
