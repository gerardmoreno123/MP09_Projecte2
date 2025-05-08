@extends('layouts.admin')

@section('admin-content')
    <div>
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4">
                <span class="bg-gradient-to-r from-blue-500 to-emerald-500 bg-clip-text text-transparent">Panel de Administración</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-300 max-w-3xl mx-auto">
                Bienvenido a la zona de gestión. Aquí puedes administrar los recursos de la plataforma según tus permisos.
            </p>
        </div>

        <!-- CRUDs Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($cruds as $crud)
                <a href="{{ $crud['route'] }}"
                   class="crud-card"
                   aria-label="Gestionar {{ $crud['name'] }}">
                    <div class="flex items-center mb-4">
                        <i class="fas {{ $crud['icon'] }} text-blue-400 text-3xl mr-3" aria-hidden="true"></i>
                        <h2 class="text-xl font-semibold text-white">{{ $crud['name'] }}</h2>
                    </div>
                    <p class="text-slate-400 text-sm">{{ $crud['description'] }}</p>
                </a>
            @empty
                <div class="col-span-full bg-slate-800 rounded-xl p-12 text-center">
                    <i class="fas fa-lock text-5xl text-slate-600 mb-4" aria-hidden="true"></i>
                    <h3 class="text-xl font-medium text-white mb-2">Sin acceso a módulos</h3>
                    <p class="text-slate-400">No tienes permisos para administrar ningún módulo en este momento.</p>
                </div>
            @endforelse
        </div>

        <!-- Back Button -->
        <div class="mt-12 text-center">
            <a href="{{ route('videos.index') }}"
               class="btn btn-secondary"
               aria-label="Volver a la plataforma pública">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver a la Plataforma
            </a>
        </div>
    </div>

    <style>
        /* CRUD Card Styles */
        .crud-card {
            display: block;
            background-color: #1e293b;
            padding: 1.25rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .crud-card:hover {
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
            transform: translateY(-4px);
        }

        /* Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .btn-secondary {
            background-color: #4b5563;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #374151;
        }
    </style>
@endsection
