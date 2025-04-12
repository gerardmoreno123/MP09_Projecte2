@extends('layouts.admin')

@section('admin-content')
    <div class="container mx-auto px-4 py-12">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4">
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
                   class="bg-slate-800 rounded-lg shadow-lg p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <i class="fas {{ $crud['icon'] }} text-blue-400 text-3xl mr-3"></i>
                        <h2 class="text-xl font-semibold text-white">{{ $crud['name'] }}</h2>
                    </div>
                    <p class="text-slate-400 text-sm">{{ $crud['description'] }}</p>
                </a>
            @empty
                <div class="col-span-full bg-slate-800 rounded-lg p-8 text-center">
                    <i class="fas fa-lock text-5xl text-slate-600 mb-4"></i>
                    <h3 class="text-xl font-medium text-white mb-2">Sin acceso a módulos</h3>
                    <p class="text-slate-400">No tienes permisos para administrar ningún módulo en este momento.</p>
                </div>
            @endforelse
        </div>

        <!-- Back Button -->
        <div class="mt-12 text-center">
            <a href="{{ route('videos.index') }}"
               class="inline-flex items-center px-6 py-3 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver a la Plataforma
            </a>
        </div>
    </div>
@endsection
