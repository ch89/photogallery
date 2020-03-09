<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model {
	protected $fillable = ["title", "color"];
	
    public function photos() {
    	return $this->hasMany(Photo::class);
    }
}