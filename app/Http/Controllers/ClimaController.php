<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClimaController extends Controller
{
    public function getClima(Request $request)
    {
        try {
            $lat = $request->input('lat', 19.4326);
            $lon = $request->input('lon', -99.1332);

            $weatherUrl = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lon}&current=temperature_2m,relative_humidity_2m,precipitation_probability";
            $response = Http::timeout(5)->get($weatherUrl)->json();

            $temp = $response['current']['temperature_2m'] ?? 22;
            $humedad = $response['current']['relative_humidity_2m'] ?? 50;
            $probLluvia = $response['current']['precipitation_probability'] ?? 10;

            return response()->json([
                'success' => true,
                'temperatura' => round($temp, 1),
                'humedad' => $humedad,
                'probabilidad_lluvia' => $probLluvia,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'temperatura' => 24.5,
                'humedad' => 45,
                'probabilidad_lluvia' => 5,
            ]);
        }
    }
}
