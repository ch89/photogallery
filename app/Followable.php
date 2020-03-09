<?php

namespace App;

trait Followable {
	public function following() {
		return $this->belongsToMany(static::class, "follows", "follower_id", "followed_id");
	}

	public function followers() {
		return $this->belongsToMany(static::class, "follows", "followed_id", "follower_id");
	}

	public function getFollowedAttribute() {
		return $this->followers()->where("follower_id", auth()->id())->exists();
	}
}