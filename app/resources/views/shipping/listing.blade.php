@extends('layout.main')

@section('content')


    <h1 class="text-3xl font-semibold mb-6">Listado de Envío</h1>

    
    <div class="row">
        <div class="col-md-3">
            <a href="{{ route('tiendas.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Tienda</a>
            <a href="{{ route('store.markets') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"> <i class="fa fa-gear"></i>ver productos</a>
        </div>
        <div class="col-md-7">
        </div>
        <div class="col-md-2">
            <a href="{{ route('shipping.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Crear Envío</a>
            
        </div>
    </div>

    <table class="table-auto table-sm mx-auto mt-8 bg-gray-800 text-white">
        <thead>
            <tr>
                <th class="p-1 col-auto text-12">Mercado Id</th>
                <th class="p-1 col-auto text-12">Mercado Nombre</th>
                <th class="p-1 col-auto text-12">Servicio Envio</th>
                <th class="p-1 col-auto text-12">Ubicación</th>
                <th class="p-1 col-auto text-12">Costo Envio</th>
                <th class="p-1 col-auto text-12">Envío Gratis</th>
                <th class="p-1 col-auto text-12">Tipo Envío</th>
                <th class="p-1 col-auto text-12">Despacho time max</th>
                <th class="p-1 col-auto text-12">Devolución Aceptada</th>
                <th class="p-1 col-auto text-12">Returns Accepted	</th>
                <th class="p-1 col-auto text-12">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shipping as $item)
                <tr id="shipping-{{ $item->id }}">
                    <td class="border col-auto ">{{ $item->marketplace->site_id }}</td>
                    <td class="border col-auto ">{{ $item->marketplace->site_name }}</td>
                    <td class="border col-auto ">{{ Str::limit($item->shipping_service, $limit = 20, $end = '...') }}</td>
                    <td class="border col-auto ">{{ $item->location }}</td>
                    <td class="border col-auto ">{{ $item->shipping_service_cost }}</td>
                    <td class="border col-auto ">{{ $item->free_shipping }}</td>
                    <td class="border col-auto ">{{ $item->shipping_type }}</td>
                    <td class="border col-auto ">{{ $item->dispatch_time_max }}</td>
                    <td class="border col-auto ">{{ $item->returns_accepted_option }}</td>
                    <td class="border col-auto ">{{ $item->returns_accepted }}</td>
                    <td class="border col-auto d-flex">
                        {{-- <a href="{{ route('tiendas.show', $item) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">Ver</a> --}}
                        <a href="{{ route('shipping.edit', $item) }}" class="bg-green-500 hover:bg-green-700 text-white py-1 px-2 rounded mr-2">
                            <i class="fa fa-edit"></i>
                        </a>
                        {{-- {{-- <form method="POST" action="{{ route('tiendas.destroy', $item) }}" class="inline" id="delete-form"> --}}
                        
                            <button type="button" class="bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded delete-button" id="" data-shipping-id="{{ $item->id }}">X</button>
                        </form> 
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
    $(document).ready(function () {
        $('.delete-button').on('click', function () {
            var shippingId = $(this).data('shipping-id');
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
                                url: '{{ route('shipping.destroy') }}',
                                data: {
                                    '_token': '{{ csrf_token() }}',
                                    'id': shippingId
                                },
                                success: function (data) {
                                    console.log('Delete request successful.');
                                    console.log('Response data:', data);
                                    // Remove the associated data on the client side (e.g., remove the item from the page)
                                    $('#shipping-' + shippingId).remove();
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
