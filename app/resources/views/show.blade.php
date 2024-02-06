@extends('layout.main')

@section('content')
    <h1 class="text-3xl font-semibold mb-6">Detalles de la Tienda</h1>
    
    <div class="bg-white p-4 rounded shadow mb-4">
        <p><strong class="font-semibold">Nombre de la Tienda:</strong> {{ $tienda->store_name }}</p>
        <p><strong class="font-semibold">Access Token:</strong> {{ $tienda->access_token }}</p>
        <p><strong class="font-semibold">Marketplaces:</strong> {{ $tienda->marketplaces }}</p>
    </div>
    
    <a href="{{ route('tiendas.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Volver al Listado</a>
@endsection
