<?php

namespace Zencoreitservices\Translator\Engines;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DeepLEngine
{
    private string $baseUri = 'https://api-free.deepl.com/v2/';
    private ?PendingRequest $client = null;

    public function __construct(private string $apiKey)
    {
        $this->client = Http::withHeaders([
            'Authorization' => sprintf('DeepL-Auth-Key %s', $this->apiKey),
        ]);
    }

    public function usage()
    {
        return $this->client->get(sprintf('%susage', $this->baseUri))->json();
    }

    public function translate(string $text, string $sourceLang, string $targetLang)
    {
        return $this->client->post(sprintf('%stranslate', $this->baseUri), [
            'text' => [
                $text
            ],
            'source_lang' => Str::upper($sourceLang),
            'target_lang' => Str::upper($targetLang),
            // 'tag_handling' => 'html',
            'ignore_tags' => [
                'keep'
            ],
        ])->json();
    }
}
