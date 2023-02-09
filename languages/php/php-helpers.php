<?php

class Helper
{

    /*********** base64 methods ***********/
    public static function isValidBase64(string $base64): bool
    {
        $bin = base64_decode($base64);
        $size = getImageSizeFromString($bin);

        if (!$size || empty($size['mime'])) return false;

        return true;
    }


    public static function getFielExtenstion(string $base64): string
    {
        $size = getImageSizeFromString(base64_decode($base64));

        return explode('/', $size['mime'])[1];
    }


    /*********** tokens ***********/
    public static function getTokenName(): ?string
    {
        return auth()->check() ? auth()->user()->currentAccessToken()->name : '';
    }


    /*********** numbers ***********/
    public static function formatFloat(float $value, int $decimals = 2): float
    {
        return floatval(number_format((float)$value, $decimals, '.', ''));
    }
}
