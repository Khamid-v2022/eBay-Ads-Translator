@extends('layout.main')

@section('content')
    <h1 class="text-3xl font-semibold mb-6">Crear Nueva Tienda</h1>

    <div class="bg-white p-4 rounded shadow mb-4">
        <div class="col-md-4">
            <form method="POST" action="{{ route('tiendas.store') }}">
                @csrf
    
                <div class="mb-4">
                    <label for="store_name" class="block text-gray-600 font-semibold">Nombre de la Tienda:</label>
                    <input type="text" name="store_name" class="border border-gray-500 rounded py-2 px-3 w-full" required>
                </div>
    
                <div class="mb-4">
                    <label for="access_token" class="block text-gray-600 font-semibold">Access Token:</label>
                    <input type="text" name="access_token" class="border border-gray-500 rounded py-2 px-3 w-full" required>
                </div>
    
                <div class="mb-4">
                    <label for="marketplaces" class="block text-gray-600 font-semibold">Marketplaces:</label>
                    <input type="number" name="marketplaces" class="border border-gray-500 rounded py-2 px-3 w-full" required>
                </div>
    
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar</button>
            </form>
        </div>
    </div>
    <a href="{{ route('tiendas.index') }}" class="bg-gray-300 hover:bg-gray-500 text-gray-700 font-bold py-2 px-4 rounded mt-4">Volver al Listado</a>
@endsection
