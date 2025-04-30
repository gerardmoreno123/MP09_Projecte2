@extends('layouts.videos-app-layout')

@section('content')
    <div class="container mx-auto px-4 py-12">
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4">
                <span class="bg-gradient-to-r from-blue-500 to-emerald-500 bg-clip-text text-transparent">Tus Notificaciones</span>
            </h1>
            <p class="text-lg md:text-xl text-slate-300 max-w-3xl mx-auto">
                Revisa el estado de tus acciones en la plataforma
            </p>
        </div>

        <!-- Notifications Content -->
        <div class="bg-slate-800 rounded-xl shadow-lg overflow-hidden">
            <!-- Header with possible filters -->
            <div class="border-b border-slate-700 p-4 flex flex-col md:flex-row justify-between items-center">
                <h2 class="text-xl font-bold text-white mb-2 md:mb-0">
                    <i class="fas fa-bell text-yellow-500 mr-2"></i>
                    Actividad Reciente
                </h2>
            </div>

            <!-- Notifications List -->
            <div id="notifications-list" class="divide-y divide-slate-700">
                <!-- Las notificaciones se añadirán dinámicamente aquí -->
            </div>
        </div>
    </div>
@endsection
