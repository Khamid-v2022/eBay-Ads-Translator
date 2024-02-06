@extends('layout.main')

@section('content')
    <h1 class="text-3xl font-semibold mb-6">Listado de Tiendas</h1>

    
    <div class="row">
        <div class="col-md-3">
            <a href="{{ route('tiendas.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Nueva Tienda</a>
        </div>
        <div class="col-md-6">
        </div>
        <div class="col-md-3">
            <a href="{{ route('store.markets') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"> <i class="fa fa-gear"></i> Publicar productos</a>
        </div>
    </div>

    <table class="table-auto mx-auto mt-8 bg-gray-800 text-white">
        <thead>
            <tr>
                <th class="px-4 py-2">Nombre de la Tienda</th>
                <th class="px-4 py-2">Access Token</th>
                <th class="px-4 py-2">Marketplaces</th>
                <th class="px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tiendas as $tienda)
                <tr>
                    <td class="border px-4 py-2">{{ $tienda->store_name }}</td>
                    <td class="border px-4 py-2">{{ Str::limit($tienda->access_token, $limit = 30, $end = '...') }}
</td>
                    <td class="border px-4 py-2">{{ $tienda->marketplaces }}</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('tiendas.show', $tienda) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">Ver</a>
                        <a href="{{ route('tiendas.edit', $tienda) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2">Editar</a>
                        <form method="POST" action="{{ route('tiendas.destroy', $tienda) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
