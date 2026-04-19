<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class VtsService
{
    /**
     * Get current vehicle location from EasyTrax
     */
    public function getCurrentLocationETracks($vehicle, $appkey)
    {
        $url = "http://track.easytrax.com.bd/api/api.php";

        $response = Http::withoutVerifying()->post($url, [
            'api' => 'user',
            'ver' => '1.0',
            'key' => $appkey,
            'cmd' => "OBJECT_GET_LOCATIONS,{$vehicle}",
        ]);

        return $response->body();
    }

    /**
     * Get human readable address from lat/lng
     */
    public function getAddressETracks($latitude, $longitude, $appkey)
    {
        $url = "http://track.easytrax.com.bd/api/api.php";

        $response = Http::withoutVerifying()->post($url, [
            'api' => 'user',
            'ver' => '1.0',
            'key' => $appkey,
            'cmd' => "GET_ADDRESS,{$latitude},{$longitude}",
        ]);

        return $response->body();
    }
}