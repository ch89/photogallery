<?php

namespace App\Http\Controllers;

use App\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller {
    public function index() {
        return Channel::all();
    }

    public function store(Request $request) {
        Channel::create(request()->validate([
            "title" => "required",
            "color" => "required"
        ]));
    }

    public function destroy(Channel $channel) {
        //
    }
}