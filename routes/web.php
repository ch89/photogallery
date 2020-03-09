<?php

Route::get("/", function() {
    return view("app");
});

Route::post("photos/{photo}/like", "PhotoController@like");
Route::post("photos/{photo}/rate", "PhotoController@rate");
Route::resource("photos", "PhotoController");

Route::post("photos/{photo}/comments", "CommentController@store");
Route::delete("comments/{comment}", "CommentController@destroy");

Route::resource("channels", "ChannelController");

Route::get("tags", "TagController@index");

Route::get("notifications", "NotificationController@index");
Route::patch("notifications", "NotificationController@update");

Route::get("users", "UserController@index");
Route::post("users/{user}/follow", "UserController@follow");

Route::get("countdowns", "CountdownController@index");
Route::post("countdowns", "CountdownController@store");

Auth::routes();