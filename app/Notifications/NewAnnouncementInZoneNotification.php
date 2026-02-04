<?php

namespace App\Notifications;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewAnnouncementInZoneNotification extends Notification
{
    use Queueable;

    protected $announcement;

    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'zone_alert',
            'announcement_id' => $this->announcement->announcement_id,
            'pet_name' => $this->announcement->pet_name,
            'pet_breed' => $this->announcement->pet_breed,
            'pet_type' => $this->announcement->pet_type,
            'location_address' => $this->announcement->location_address,
            'photo_url' => $this->announcement->photos->first()?->url,
            'created_at' => $this->announcement->created_at,
            'user_name' => $this->announcement->user->name,
        ];
    }
}