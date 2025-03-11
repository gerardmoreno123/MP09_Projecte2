@extends('layouts.admin')

@section('admin-content')
    <div class="container mx-auto px-4 text-center">
        <!-- Tabs -->
        <div class="flex flex-col md:flex-row justify-center space-y-2 md:space-y-0 md:space-x-6 pb-2 mb-6">
            <a href="{{ route('videos.manage.index') }}" class="text-lg font-semibold text-gray-400 pb-2 inline-block text-center w-full hover:text-green-400 hover:border-green-400 hover:border-b-2 transition duration-200">Llista de Vídeos</a>
            <a href="{{ route('videos.manage.create') }}" class="text-lg font-semibold text-gray-400 pb-2 inline-block text-center w-full hover:text-green-400 hover:border-green-400 hover:border-b-2 transition duration-200">Afegir Vídeo</a>
            <a href="{{ route('videos.manage.delete', $video->id) }}" class="text-lg font-semibold text-red-400 border-b-2 pb-2 inline-block text-center w-full">Eliminar Vídeo</a>
        </div>

        <!-- Contenedor del formulari -->
        <div class="max-w-lg mx-auto bg-gray-800 p-8 rounded-lg shadow-lg">
            <h2 class="text-3xl font-semibold text-teal-400 mb-6">Eliminar Vídeo</h2>

            <p class="text-lg text-gray-300 mb-6">Estàs segur que vols eliminar el vídeo titulat <strong>"{{ $video->title }}"</strong>?</p>

            <form action="{{ route('videos.manage.destroy', $video->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('DELETE')

                <!-- Botó de eliminar -->
                <button type="submit" class="w-full bg-red-500 text-white font-semibold text-lg py-3 rounded-md hover:bg-red-600 transition">
                    Eliminar Vídeo
                </button>

                <!-- Botó de cancel·lar -->
                <button type="button" onclick="window.location='{{ route('videos.manage.index') }}'" class="w-full bg-gray-500 text-white font-semibold text-lg py-3 rounded-md hover:bg-gray-600 transition">
                    Cancel·lar
                </button>
            </form>
        </div>
    </div>
@endsection
