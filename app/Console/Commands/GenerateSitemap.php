<?php

namespace App\Console\Commands;

use App\Libraries\SitemapCreator;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates sitemap';

    /**
     * Execute the console command.
     */
    public function handle(SitemapCreator $sitemapCreator): void
    {
        $sitemapCreator->generateAll();
    }
}
