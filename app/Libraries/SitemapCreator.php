<?php

namespace App\Libraries;

use App\Models\Marker;
use App\Models\Plate;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Sitemap;

class SitemapCreator
{
    public function generateAll(): void
    {
        if (!file_exists(public_path('sitemaps'))) {
            mkdir(public_path('sitemaps'), 0777, true);
        }

        $sitemap = SitemapIndex::create();

        activeLanguages()->each(function ($lang) use (&$sitemap) {
            $sitemap
                ->add(sprintf('/sitemaps/%s-general.xml', $lang))
                ->add(sprintf('/sitemaps/%s-markers.xml', $lang))
                ->add(sprintf('/sitemaps/%s-plates.xml', $lang));

            $this->general($lang);
            $this->markers($lang);
            $this->plates($lang);
        });

        $sitemap->writeToFile(public_path('sitemaps/sitemap.xml'));
    }

    public function general(string $lang): void
    {
        Sitemap::create()
            ->add(route('front.maps.index', ['lang' => $lang]))
            ->add(route('front.markers.index', ['lang' => $lang, 'type' => Marker::TYPE_FOUND]))
            ->add(route('front.markers.index', ['lang' => $lang, 'type' => Marker::TYPE_LOST]))
            ->writeToFile(public_path(sprintf('sitemaps/%s-general.xml', $lang)));
    }

    public function markers(string $lang): void
    {
        $sitemap = Sitemap::create();

        Marker::all()->each(fn ($marker) => $sitemap->add($marker->link()));

        $sitemap->writeToFile(public_path(sprintf('sitemaps/%s-markers.xml', $lang)));
    }

    public function plates(string $lang): void
    {
        $sitemap = Sitemap::create();

        Plate::all()->each(fn ($plate) => $sitemap->add($plate->link()));

        $sitemap->writeToFile(public_path(sprintf('sitemaps/%s-offers.xml', $lang)));
    }
}