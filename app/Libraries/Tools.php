<?php

namespace App\Libraries;

class Tools
{
    public static function generateRandomString($length = 10, $set = 'nlu'){
        switch($set){
            case 'n':
                $characters = '0123456789';
                break;
            case 'l':
                $characters = 'abcdefghijklmnopqrstuvwxyz';
                break;
            case 'u':
                $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'nl':
                $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
                break;
            case 'nu':
                $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'lu':
                $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
            case 'nlu':
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
        }

        $charactersLength = strlen($characters);
        $randomString = '';

        for($i = 0; $i < $length; $i++){
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public static function formatPlateNumber($plateNumber)
    {
        $plateNumber = str_replace(' ', '', $plateNumber);
        $plateNumber = str_replace('-', '', $plateNumber);
        $plateNumber = strtoupper($plateNumber);
        return $plateNumber;
    }

    public static function simplifyPhoneNumber($phoneNumber)
    {
        $phoneNumber = str_replace(' ', '', $phoneNumber);
        $phoneNumber = str_replace('-', '', $phoneNumber);
        return $phoneNumber;
    }

    public static function initDirectories()
    {
        if(!file_exists(storage_path('app/public/images'))){
            mkdir(storage_path('app/public/images'));
        }

        $imageSizes = \App\Defines\ImageSize::getConstants();
        foreach($imageSizes as $imageSizesItem){
            if(!file_exists(storage_path('app/public/images/'.$imageSizesItem['slug']))){
                mkdir(storage_path('app/public/images/'.$imageSizesItem['slug']));
            }
        }

        /*
        if(!file_exists(storage_path('app/public/sitemaps'))){
            mkdir(storage_path('app/public/sitemaps'));
        }
        */
    }
}