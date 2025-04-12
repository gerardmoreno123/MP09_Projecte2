@extends('layouts.admin')

@section('admin-content')
    <div class="max-w-md mx-auto px-4 py-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('series.manage.index') }}" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-blue-400 transition-colors">
                        <i class="fas fa-list mr-2"></i>
                        Series
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-xs text-slate-500 mx-2"></i>
                        <span class="text-sm font-medium text-blue-400">Eliminar serie</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Delete Confirmation -->
        <div class="bg-slate-800 rounded-xl shadow-lg p-6 text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-red-500 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation text-white text-2xl"></i>
            </div>

            <h2 class="text-xl font-bold text-white mb-2">¿Eliminar serie permanentemente?</h2>
            <p class="text-slate-400 mb-6">Esta acción no se puede deshacer. Se eliminará la serie "{{ $serie->title }}" y se desvincularán todos sus videos.</p>

            <div class="flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('series.manage.index') }}"
                   class="px-6 py-2 bg-slate-700 hover:bg-slate-600 text-white font-medium rounded-lg transition-colors">
                    Cancelar
                </a>
                <form action="{{ route('series.manage.destroy', $serie->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center">
                        <i class="fas fa-trash-alt mr-2"></i>
                        Sí, eliminar serie
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
