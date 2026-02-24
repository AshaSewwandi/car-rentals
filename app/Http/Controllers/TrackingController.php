<?php

namespace App\Http\Controllers;

use App\Models\Car;

class TrackingController extends Controller
{
    public function index()
    {
        $cars = Car::query()
            ->whereNotNull('latest_latitude')
            ->whereNotNull('latest_longitude')
            ->orderBy('name')
            ->get();

        return view('tracking.index', compact('cars'));
    }
}
