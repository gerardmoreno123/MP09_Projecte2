<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class VideoCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $video;

    public function __construct($video)
    {
        $this->video = $video;
        Log::info('Video created event fired', ['video' => $video]);
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('video-created-channel.' . $this->video->user_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'video.created';
    }

    public function broadcastWith(): array
    {
        return [
            'video_title' => $this->video->title,
            'message' => "Tu video '{$this->video->title}' ha sido procesado y ya está disponible.",
            'timestamp' => now()->toDateTimeString(),
            'video_id' => $this->video->id,
        ];
    }
}
