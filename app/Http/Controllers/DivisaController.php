<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DivisaController extends Controller
{
    public function getTasaDolar(Request $request)
    {
        try {
            $response = Http::timeout(5)->get('https://open.er-api.com/v6/latest/USD')->json();
            $tasaDolar = $response['rates']['MXN'] ?? 20.25;

            return response()->json([
                'success' => true,
                'usd_mxn' => number_format($tasaDolar, 2),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'usd_mxn' => '20.25',
            ]);
        }
    }
}
