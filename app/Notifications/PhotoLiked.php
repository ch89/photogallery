<?php

namespace App\Notifications;

use App\Photo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PhotoLiked extends Notification
{
    use Queueable;

    protected $photo;

    public function __construct(Photo $photo) {
        $this->photo = $photo;
    }

    public function via($notifiable) {
        return ["database"];
    }

    public function toArray($notifiable) {
        return [
            "photo" => $this->photo->only(["id", "title"]),
            "name" => auth()->user()->name
        ];
    }
}