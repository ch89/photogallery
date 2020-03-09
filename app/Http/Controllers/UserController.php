<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller {
    public function index() {
    	return User::where("id", "!=", auth()->id())->get();
    }

    public function follow(User $user) {
    	auth()->user()->following()->toggle($user);
    }
}