<?php

namespace App\Controllers;

use App\Models\Weather;
use Carbon\Carbon;

class WeatherController extends Controller {
    public function getWeather($request, $response) {
        $weather = Weather::whereDate('dt', Carbon::today()->format('Y-m-d'))->orderByDesc('dt')->first();
        if ($weather) {            
            return $this->response->withJson($weather);
        }
        return $this->response->withJson(['msg' => 'На сегодня данных нет.']);        
    }
}