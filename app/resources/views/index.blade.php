@extends('layout.main')

@section('content')
    <h1 class="text-3xl font-semibold mb-6">Listado de Tiendas</h1>

    
    <div class="row">
        <div class="col-md-3 d-flex justify-content-between">
            <a href="{{ route('tiendas.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Nueva Tienda</a>
            <a href="{{ route('shipping.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">lista envío</a>
        </div>
        <div class="col-md-6">
        </div>
        <div class="col-md-3">
            <a href="{{ route('store.markets') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"> <i class="fa fa-gear"></i>ver productos</a>
        </div>
    </div>

    <table class="table-auto mx-auto mt-8 bg-gray-800 text-white">
        <thead>
            <tr>
                <th class="px-4 py-2">Nombre de la Tienda</th>
                <th class="px-4 py-2">Token de autenticación</th>
                <th class="px-4 py-2">Mercados</th>
                <th class="px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tiendas as $tienda)
                <tr id="tienda-{{ $tienda->id }}">
                    <td class="border px-4 py-2">{{ $tienda->store_name }}</td>
                    <td class="border px-4 py-2">{{ Str::limit($tienda->access_token, $limit = 30, $end = '...') }}
                    </td>
                    <td class="border px-4 py-2">{{ $tienda->marketplaces }}</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('tiendas.show', $tienda) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">Ver</a>
                        <a href="{{ route('tiendas.edit', $tienda) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2">Editar</a>
                        {{-- {{-- <form method="POST" action="{{ route('tiendas.destroy', $tienda) }}" class="inline" id="delete-form"> --}}
                        <button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded delete-button" id="" data-tienda-id="{{ $tienda->id }}">Eliminar</button>
                        
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
    $(document).ready(function () {
        $('.delete-button').on('click', function () {
            var tiendaId = $(this).data('tienda-id');
            
            $.confirm({
                title: 'Confirm Delete',
                content: 'Are you sure you want to delete this item?',
                type: 'red',
                buttons: {
                    confirm: {
                        text: 'Yes',
                        btnClass: 'btn-red',
                        action: function () {
                            // Send an AJAX request to delete the item
                            $.ajax({
                                type: 'POST',
                                url: '{{ route('tiendas.destroy') }}',
                                data: {
                                    '_token': '{{ csrf_token() }}',
                                    'tienda_id': tiendaId
                                },
                                success: function (data) {
                                    console.log('Delete request successful.');
                                    console.log('Response data:', data);
                                    // Remove the associated data on the client side (e.g., remove the item from the page)
                                    $('#tienda-' + tiendaId).remove();
                                    alert("Deleted successfuly")
                                    
                                },
                                error: function (data) {
                                    console.log('Delete request failed.');
                                    console.log('Error data:', data);
                                    // Handle errors here (e.g., show an error message)
                                }
                            });
                        }
                    },
                    cancel: {
                        text: 'No',
                        btnClass: 'btn-default',
                        action: function () {
                            // If the user cancels, do nothing
                        }
                    }
                }
            });
        });
    });
</script>
    
@endsection
