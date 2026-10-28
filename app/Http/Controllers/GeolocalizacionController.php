<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeolocalizacionController extends Controller
{
    public function getUbicacion(Request $request)
    {
        try {
            $ip = $request->ip();

            if ($ip === '127.0.0.1' || $ip === '::1' || str_contains($ip, '192.168.')) {
                $response = Http::timeout(5)->get('http://ip-api.com/json/')->json();
            } else {
                $response = Http::timeout(5)->get("http://ip-api.com/json/{$ip}")->json();
            }

            return response()->json([
                'success' => true,
                'ciudad' => $response['city'] ?? 'Ciudad de México',
                'estado' => $response['regionName'] ?? $response['region'] ?? 'CDMX',
                'pais' => $response['country'] ?? 'México',
                'lat' => $response['lat'] ?? 19.4326,
                'lon' => $response['lon'] ?? -99.1332,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'ciudad' => 'Ciudad de México',
                'estado' => 'CDMX',
                'pais' => 'México',
                'lat' => 19.4326,
                'lon' => -99.1332,
            ]);
        }
    }
}
