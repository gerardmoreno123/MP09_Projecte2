<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('video-created-channel.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
