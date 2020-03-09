<?php

namespace App\Notifications;

use App\Photo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PhotoRated extends Notification
{
    use Queueable;

    protected $photo;
    protected $rating;

    public function __construct(Photo $photo, $rating) {
        $this->photo = $photo;
        $this->rating = $rating;
    }

    public function via($notifiable) {
        return ["database"];
    }

    public function toArray($notifiable) {
        return [
            "photo" => $this->photo->only(["id", "title"]),
            "name" => auth()->user()->name,
            "rating" => $this->rating
        ];
    }
}