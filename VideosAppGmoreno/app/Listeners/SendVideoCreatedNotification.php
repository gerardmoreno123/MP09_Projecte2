<?php

namespace App\Listeners;

use App\Events\VideoCreated;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class SendVideoCreatedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
        //
    }

    public function handle(VideoCreated $event): void
    {
        Log::info('Iniciant SendVideoCreatedNotification', [
            'video_id' => $event->video->id,
            'video_title' => $event->video->title,
            'user_id' => $event->video->user_id,
        ]);

        try {
            $video = $event->video;
            $admins = User::where('super_admin', true)->get();

            Log::info('Administradors trobats', ['count' => $admins->count()]);

            // Guardar notificació a la base de dades
            try {
                Log::info('Intentant guardar notificació', [
                    'user_id' => $video->user_id,
                    'video_id' => $video->id,
                    'message' => "Tu video '{$video->title}' ha sido procesado y ya está disponible.",
                ]);

                Notification::create([
                    'user_id' => $video->user_id,
                    'video_id' => $video->id,
                    'message' => "Tu video '{$video->title}' ha sido procesado y ya está disponible.",
                ]);

                Log::info('Notificació guardada a la base de dades', ['video_id' => $video->id]);
            } catch (\Exception $e) {
                Log::error('Error guardant notificació a la base de dades', [
                    'video_id' => $video->id,
                    'user_id' => $video->user_id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                // Continuem per no interrompre l'enviament de correus
            }

            // Enviar correus als administradors
            foreach ($admins as $admin) {
                try {
                    Log::info('Enviant correu a administrador', ['admin_email' => $admin->email]);
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

            Log::info('Notificació de vídeo creat enviada als administradors', [
                'video_title' => $video->title,
                'admin_count' => $admins->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error en SendVideoCreatedNotification', [
                'video_title' => $video->title ?? 'Desconocido',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->fail($e);
        }
    }

    public function failed(VideoCreated $event, $exception): void
    {
        Log::error('SendVideoCreatedNotification falló', [
            'video_title' => $event->video->title ?? 'Desconocido',
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
