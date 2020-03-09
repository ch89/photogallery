<?php

namespace App\Http\Controllers;

use App\Events\NewPhoto;
use App\Events\RemovePhoto;
use App\Notifications\PhotoLiked;
use App\Notifications\PhotoRated;
use App\Photo;

class PhotoController extends Controller {
    protected $rules = [
        "title" => "required",
        "description" => "required",
        "channel_id" => "required|exists:channels,id"
    ];

    public function __construct() {
        $this->middleware("auth");
    }

    public function index() {
        $query = Photo::query();

        if(request()->filled("title")) {
            $query->where("title", "like", "%" . request("title") . "%");
        }

        if(request()->has("favorites")) {
            // return auth()->user()->likes;
            $query->join("likes", "id", "photo_id")->where("likes.user_id", auth()->id());
        }

        if(request("sort") == "rating") {
            $query->join("ratings", "photos.id", "photo_id")->where("ratings.user_id", auth()->id());
        }

        if(request()->filled("channel_id")) {
            $query->where("channel_id", request("channel_id"));
        }

        if(request()->filled("tag_ids")) {
            $query->whereHas("tags", function($query) {
                $query->whereIn("tags.id", request("tag_ids"));
            });
        }

        $user_ids = auth()->user()->following()->pluck("id");
        $user_ids->push(auth()->id());

        $query->whereIn("photos.user_id", $user_ids);

        $query->orderBy(request("sort", "created_at"), request("direction", "desc"));

        return $query->paginate(request("limit", 4));
        // return Photo::all();
    }
    
    // Without image
    // public function store() {
    //     $photo = auth()->user()->photos()->create(request()->validate([
    //         "title" => "required",
    //         "description" => "required",
    //         "channel_id" => "required|exists:channels,id"
    //     ]));

    //     // broadcast(new NewPhoto($photo))->toOthers();

    //     $photo->tags()->sync(request("tag_ids"));

    //     return $photo->load("user", "channel", "comments", "tags");
    // }

    // With image
    public function store() {
        $photo = new Photo(request()->validate([
            "title" => "required",
            "description" => "required",
            "channel_id" => "required|exists:channels,id",
            "image" => "required|image"
        ]));

        $photo->image = request()->file("image")->store("images", "public");

        auth()->user()->photos()->save($photo);

        $photo->tags()->sync(request("tag_ids"));

        return $photo->load("user", "channel", "comments", "tags");
    }

    public function update(Photo $photo) {
        $this->authorize("update", $photo);
        
        $photo->update(request()->validate($this->rules));

        // if($photo->wasChanged("channel_id")) $photo->load("channel");

        $photo->tags()->sync(request("tag_ids"));

        return $photo->load("channel", "tags");
    }

    public function destroy(Photo $photo) {
        // broadcast(new RemovePhoto($photo))->toOthers();

        $this->authorize("delete", $photo);

        $photo->delete();
    }

    public function like(Photo $photo) {
        auth()->user()->likes()->toggle($photo);

        $photo->user->notify(new PhotoLiked($photo));
    }

    public function rate(Photo $photo) {
        $photo->ratings()->updateOrCreate(
            ["user_id" => auth()->id()],
            request()->validate(["rating" => "required|integer|between:1,5"])
        );

        $photo->user->notify(new PhotoRated($photo, request("rating")));
    }
}