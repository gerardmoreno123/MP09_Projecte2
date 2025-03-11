@extends('layouts.videos-app-layout')

@section('content')
    <div class="container mx-auto px-6 py-12">
        <h2 class="text-4xl font-bold text-center mb-12">Lista de Usuarios</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach($users as $user)
                <div class="bg-gray-800 p-6 rounded-lg shadow-md hover:shadow-xl transition duration-300 transform hover:-translate-y-1 hover:scale-105 text-center">
                    <a href="{{ route('users.show', $user->id) }}" class="block">
                        <div class="relative w-24 h-24 mx-auto mb-4">
                            <img src="{{ $user->profile_photo_url }}" alt="Foto de {{ $user->name }}" class="w-full h-full object-cover rounded-full">
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-1">{{ $user->name }}</h3>
                        <p class="text-gray-400 text-sm">{{ $user->email }}</p>
                        <span class="inline-block mt-3 px-4 py-1 text-sm font-medium text-white bg-green-600 rounded-full">Ver perfil</span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
