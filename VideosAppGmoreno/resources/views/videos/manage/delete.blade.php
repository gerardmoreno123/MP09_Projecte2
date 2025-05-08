@extends('layouts.admin')

@section('admin-content')
    <div class="container mx-auto px-4 py-12">
        <!-- Breadcrumbs -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('videos.manage.index') }}" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-blue-400 transition-colors">
                        <i class="fas fa-video mr-2" aria-hidden="true"></i>
                        Videos
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-xs text-slate-500 mx-2" aria-hidden="true"></i>
                        <span class="text-sm font-medium text-blue-400">Eliminar video</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Delete Confirmation -->
        <div class="bg-slate-800 rounded-xl shadow-lg p-4 sm:p-6 max-w-md mx-auto text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-r from-red-900 to-slate-800 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation text-white text-2xl" aria-hidden="true"></i>
            </div>

            <h2 class="text-xl font-bold text-white mb-2">¿Eliminar video permanentemente?</h2>
            <p class="text-slate-400 mb-6">Esta acción no se puede deshacer. Se eliminará el video "{{ $video->title }}"</p>

            <div class="flex flex-col sm:flex-row justify-center space-y-2 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('videos.manage.index') }}"
                   class="btn btn-secondary">
                    Cancelar
                </a>
                <form action="{{ route('videos.manage.destroy', $video->id) }}" method="POST" class="inline flex flex-col">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="btn btn-danger flex items-center justify-center">
                        <i class="fas fa-trash-alt mr-2" aria-hidden="true"></i>
                        Sí, eliminar video
                    </button>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2563eb;
        }

        .btn-secondary {
            background-color: #4b5563;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #374151;
        }

        .btn-danger {
            background-color: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background-color: #dc2626;
        }
    </style>
@endsection
