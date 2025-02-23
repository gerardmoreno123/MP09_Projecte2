@extends('layouts.videos-app-layout')

@section('content')
    <div class="container mx-auto px-4 text-center">
        <!-- Pestanyes -->
        <div class="flex justify-center space-x-6 pb-2 mb-6">
            <a href="{{ route('videos.manage.index') }}" class="text-lg font-semibold text-green-400 border-b-2 pb-2 inline-block text-center w-full">Llista de Vídeos</a>
            <a href="{{ route('videos.manage.create') }}" class="text-lg font-semibold text-gray-400 pb-2 inline-block text-center w-full hover:text-green-400 hover:border-green-400 hover:border-b-2 transition duration-200">Afegir Vídeo</a>
        </div>

        <!-- Taula de vídeos -->
        <div class="mt-4 flex justify-center overflow-x-auto">
            <table class="w-full text-left border-collapse text-white">
                <thead>
                <tr class="border-b border-white">
                    <th class="py-4 px-2 text-center">#</th>
                    <th class="py-4 px-2 w-3/4">Títol</th>
                    <th class="py-4 px-2 text-center">Data</th>
                    <th class="py-4 px-2 text-center">Accions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($videos as $index => $video)
                    <tr onclick="window.location='{{ route('videos.manage.show', $video) }}';"
                        class="border-b border-gray-600 hover:bg-gray-700 cursor-pointer transition duration-200">
                        <td class="py-4 px-2 text-center">{{ $index + 1 }}</td>
                        <td class="py-4 px-2 text-green-400">{{ $video->title }}</td>
                        <td class="py-4 px-2 text-center">{{ $video->published_at ? $video->published_at->format('d/m/Y') : 'No publicat' }}</td>
                        <td class="py-4 px-2 text-center">
                            <a href="{{ route('videos.manage.edit', $video) }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-500 transition duration-200">Editar</a>
                            <a href="{{ route('videos.manage.delete', $video) }}" class="bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-500 transition duration-200">Eliminar</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
