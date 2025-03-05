<?php
namespace App\Helpers;

class LanguageHelper
{
    public static function getCurrentLanguage()
    {
        return config('languages.available.' . app()->getLocale());
    }

    public static function getDirection()
    {
        return self::getCurrentLanguage()['dir'];
    }

    public static function isRtl()
    {
        return self::getDirection() === 'rtl';
    }
} 