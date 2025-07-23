<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Actions\GetWeatherData; 

Route::get('/weather-data', GetWeatherData::class);