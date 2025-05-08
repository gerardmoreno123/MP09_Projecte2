@extends('layouts.admin')

@section('admin-content')
    <div class="container mx-auto px-4 py-12">
        <!-- Breadcrumbs -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('series.manage.index') }}" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-blue-400 transition-colors"
                       aria-label="Volver a la lista de series">
                        <i class="fas fa-list mr-2" aria-hidden="true"></i>
                        Series
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-xs text-slate-500 mx-2" aria-hidden="true"></i>
                        <span class="text-sm font-medium text-blue-400">Eliminar serie</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Delete Confirmation -->
        <div class="bg-slate-800 rounded-xl shadow-lg p-4 sm:p-6 text-center max-w-md mx-auto" role="alertdialog">
            <div class="alert-icon mx-auto mb-4">
                <i class="fas fa-exclamation text-white text-2xl" aria-hidden="true"></i>
            </div>

            <h2 class="text-xl font-bold text-white mb-2">¿Eliminar serie permanentemente?</h2>
            <p class="text-slate-400 mb-6">Esta acción no se puede deshacer. Se eliminará la serie "{{ $serie->title }}" y se desvincularán todos sus videos.</p>

            <div class="flex flex-col sm:flex-row justify-center space-y-2 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('series.manage.index') }}"
                   class="btn btn-secondary"
                   aria-label="Cancelar eliminación">
                    Cancelar
                </a>
                <form action="{{ route('series.manage.destroy', $serie->id) }}" method="POST" class="inline flex flex-col">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="btn btn-danger flex items-center justify-center"
                            aria-label="Confirmar eliminación de serie">
                        <i class="fas fa-trash-alt mr-2" aria-hidden="true"></i>
                        Sí, eliminar serie
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

        /* Alert Icon Styles */
        .alert-icon {
            width: 4rem;
            height: 4rem;
            background-color: #ef4444;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
@endsection
