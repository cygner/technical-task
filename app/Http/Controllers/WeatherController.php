<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function index()
    {
        return view('weather');
    }

    public function getWeather(Request $request)
    {
        try {
            $endpoint = "https://api.openweathermap.org/data/2.5/weather?q={$request->city}&units=metric&appid=" . config("app.weather-api-key");

            $weatherData = Http::get($endpoint)->json();

            if ($weatherData['cod'] == 200) {
                return response()->json([
                    "status" => "success",
                    "weatherData" => $weatherData
                ]);
            } else {
                return response()->json([
                    "status" => "error",
                    "message" => ucfirst($weatherData['message'])
                ]);
            }
        }catch (\Exception $e) {
            report($e);
            return response()->json([
                "status" => "error",
                "message" => "Something went wrong. Please try again.",
            ], 500);
        }
    }
}
