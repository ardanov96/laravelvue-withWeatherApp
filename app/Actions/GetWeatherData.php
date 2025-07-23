<?php

namespace App\Http\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; 
use Illuminate\Validation\ValidationException; 

class GetWeatherData
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'city' => 'required|string|max:255',
        ]);

        $city = $request->input('city');
        $apiKey = config('services.openweathermap.key'); 

        if (!$apiKey) {
            return response()->json(['error' => 'OpenWeatherMap API key not configured.'], 500);
        }

        try {
            $response = Http::get("https://api.openweathermap.org/data/2.5/weather", [
                'q' => $city,
                'appid' => $apiKey,
                'units' => 'metric' // Untuk Celsius
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json($data);
            } else {
                $statusCode = $response->status();
                $errorMessage = $response->json('message', 'Could not retrieve weather data.');
                return response()->json(['error' => $errorMessage], $statusCode);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
        }
    }
}