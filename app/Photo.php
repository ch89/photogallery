<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class Photo extends Model {
    protected $fillable = ["title", "description", "channel_id"];
    protected $appends = ["liked", "rating", "can"];
    protected $casts = ["created_at" => "date:F j, Y"];
    protected $with = ["user", "channel", "comments", "tags"];
    protected $withCount = ["likes"];

    public function user() {
    	return $this->belongsTo(User::class);
    }

    public function channel() {
        return $this->belongsTo(Channel::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }

    public function ratings() {
        return $this->hasMany(Rating::class);
    }

    public function likes() {
    	return $this->belongsToMany(User::class, "likes");
    }

    public function getLikedAttribute() {
        return $this->likes()->where("user_id", auth()->id())->exists();
    }

    public function getRatingAttribute() {
        return $this->ratings()->where("user_id", auth()->id())->value("rating");
    }

    public function getImageAttribute($value) {
        return Storage::url($value);
    }

    public function getCanAttribute() {
        $abilities = get_class_methods(Gate::getPolicyFor($this));

        return collect($abilities)->mapWithKeys(function($ability) {
            return [$ability => Gate::allows($ability, $this)];
        });
    }
}