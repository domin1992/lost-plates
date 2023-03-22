<?php

namespace App\Libraries;

class MetaTagsManager
{
    static string $title;
    static string $description;
    static string $image;
    static string $follow;
    static string $index;

    public static function setTitle(string $title): void
    {
        self::$title = $title;
    }

    public static function title(): string
    {
        return sprintf('%s - %s', self::$title ?? trans('defaultMeta.metaTitle'), config('app.name'));
    }

    public static function setDescription(string $description): void
    {
        self::$description = $description;
    }

    public static function description(): string
    {
        return self::$description ?? trans('defaultMeta.metaDescription');
    }

    public static function setImage(string $image): void
    {
        self::$image = $image;
    }

    public static function image(): string
    {
        return self::$image ?? asset('images/og.png');
    }

    public static function setUnfollow(): void
    {
        self::$follow = 'nofollow';
    }

    public static function setFollow(): void
    {
        self::$follow = 'follow';
    }

    public static function follow(): string
    {
        return self::$follow ?? 'follow';
    }

    public static function setNoindex(): void
    {
        self::$index = 'noindex';
    }

    public static function setIndex(): void
    {
        self::$index = 'index';
    }

    public static function index(): string
    {
        return self::$index ?? 'index';
    }
}