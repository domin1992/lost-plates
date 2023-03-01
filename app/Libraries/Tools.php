<?php

namespace App\Libraries;

class Tools
{
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
}