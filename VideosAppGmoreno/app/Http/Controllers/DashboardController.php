<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\User;
use App\Models\Serie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $cruds = [];
        $stats = [];

        // Lista de CRUDs
        $allCruds = [
            [
                'name' => 'Gestionar Videos',
                'route' => route('videos.manage.index'),
                'roles' => ['super-admin', 'video-manager'],
                'icon' => 'fa-video',
                'description' => 'Administra los videos de la plataforma.',
            ],
            [
                'name' => 'Gestionar Usuarios',
                'route' => route('users.manage.index'),
                'roles' => ['super-admin', 'user-manager'],
                'icon' => 'fa-users',
                'description' => 'Controla los usuarios registrados.',
            ],
            [
                'name' => 'Gestionar Series',
                'route' => route('series.manage.index'),
                'roles' => ['super-admin', 'serie-manager'],
                'icon' => 'fa-film',
                'description' => 'Organiza las series de videos.',
            ],
        ];

        // Filtrar CRUDs según roles
        foreach ($allCruds as $crud) {
            foreach ($crud['roles'] as $role) {
                if ($user->hasRole($role)) {
                    $cruds[] = $crud;
                    break;
                }
            }
        }

        return view('dashboard', compact('cruds', 'stats'));
    }
}
