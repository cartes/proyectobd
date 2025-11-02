@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 via-purple-50 to-pink-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-red-600" style="font-family: 'Playfair Display', serif;">
                    🚫 Palabras Bloqueadas
                </h1>
                <p class="text-gray-600 mt-2">Total: {{ $words->total() }} palabras</p>
            </div>
        </div>

        <!-- Agregar Nueva Palabra -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Agregar Palabra Bloqueada</h2>
            
            <form method="POST" action="{{ route('admin.moderation.blocked-words.store') }}" class="flex gap-4">
                @csrf
                
                <input type="text" name="word" placeholder="Palabra..." 
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500" required>
                
                <select name="severity" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500" required>
                    <option value="">Severidad</option>
                    <option value="low">Baja</option>
                    <option value="medium">Media</option>
                    <option value="high">Alta</option>
                </select>
                
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-lg hover:shadow-lg transition-all">
                    Agregar
                </button>
            </form>

            @if($errors->has('word'))
                <div class="mt-3 p-3 bg-red-100 text-red-800 rounded">
                    {{ $errors->first('word') }}
                </div>
            @endif
        </div>

        <!-- Tabla de Palabras -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Palabra</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Severidad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Agregada</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($words as $word)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <code class="px-2 py-1 bg-gray-100 text-gray-900 rounded font-mono text-sm">
                                    {{ $word->word }}
                                </code>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                    {{ $word->severity === 'high' ? 'bg-red-100 text-red-800' : 
                                       ($word->severity === 'medium' ? 'bg-orange-100 text-orange-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($word->severity) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                    {{ $word->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $word->is_active ? 'Activa' : 'Inactiva' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $word->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('admin.moderation.blocked-words.destroy', $word) }}" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('¿Eliminar esta palabra?')" 
                                            class="text-red-600 hover:text-red-800 font-medium">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                No hay palabras bloqueadas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Paginación -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $words->links() }}
            </div>
        </div>
    </div>
</div>
@endsection