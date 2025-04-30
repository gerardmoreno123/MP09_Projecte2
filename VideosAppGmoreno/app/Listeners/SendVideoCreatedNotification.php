<?php

namespace App\Listeners;

use App\Events\VideoCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class SendVideoCreatedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
        //
    }

    public function handle(VideoCreated $event): void
    {
        try {
            $video = $event->video;
            $admins = User::where('super_admin', true)->get();

            foreach ($admins as $admin) {
                try {
                    Mail::raw(
                        "S'ha creat un nou vídeo: {$video->title}",
                        function ($message) use ($admin) {
                            $message->to($admin->email)
                                ->subject('Nou vídeo creat');
                        }
                    );
                } catch (\Exception $e) {
                    Log::error('Error enviando correo a administrador', [
                        'admin_email' => $admin->email,
                        'video_title' => $video->title,
                        'error' => $e->getMessage(),
                    ]);
                    continue;
                }
            }
        } catch (\Exception $e) {
            Log::error('Error en SendVideoCreatedNotification', [
                'video_title' => $video->title ?? 'Desconocido',
                'error' => $e->getMessage(),
            ]);
            $this->fail($e);
        }
    }

    public function failed(VideoCreated $event, $exception): void
    {
        Log::error('SendVideoCreatedNotification falló', [
            'video_title' => $event->video->title ?? 'Desconocido',
            'error' => $exception->getMessage(),
        ]);
    }
}
