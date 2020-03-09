<?php

namespace App\Http\Controllers;

use App\Countdown;

class CountdownController extends Controller {
    public function index() {
    	return Countdown::all();
    }

    public function store() {
    	return Countdown::create(request()->validate([
    		"until" => "required|date",
    		"message" => "required"
    	]));
    }
}