@extends('layout.main')

@section('content')
<div class="row justify-content-center">

    <h1 class="text-3xl font-semibold mb-6">Editar Tienda</h1>

    <div class="bg-white p-4 rounded shadow mb-4 col-7">
        <form method="POST" action="{{ route('tiendas.update', $tienda) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="store_name" class="block text-gray-600 font-semibold">Nombre de la Tienda:</label>
                <input type="text" name="store_name" value="{{ $tienda->store_name }}" class="border border-gray-500 rounded py-2 px-3 w-full" required>
            </div>

            <div class="mb-4">
                <label for="access_token" class="block text-gray-600 font-semibold">Access Token:</label>
                <input type="text" name="access_token" value="{{ $tienda->access_token }}" class="border border-gray-500 rounded py-2 px-3 w-full" required>
            </div>

            <div class="mb-4">
                <label for="marketplaces" class="block text-gray-600 font-semibold">Marketplaces:</label>
                <input type="number" name="marketplaces" value="{{ $tienda->marketplaces }}" class="border border-gray-500 rounded py-2 px-3 w-full" required>
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar Cambios</button>
        </form>
        
    </div>
</div>

<div class=" d-flex justify-content-center">
    <a href="{{ route('tiendas.index') }}" class="bg-gray-200 hover:bg-gray-500 text-gray-700 font-bold py-2 px-4 rounded">Volver al Listado</a>
</div>

@endsection
