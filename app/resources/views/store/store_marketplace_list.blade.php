@extends('layout.main')

@section('content')
    <h1 class="text-3xl font-semibold mb-6">listado de trabajo</h1>

    <div class="bg-white p-4 rounded shadow mb-4">
    <a href="{{ route('store.manage') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"> <i class="fa fa-gear"></i> Publicar productos</a>
    <a href="{{ route('tiendas.index') }}"
            class="bg-gray-300 hover:bg-gray-500 text-gray-700 font-bold py-2 px-4 rounded">Volver al Listado</a>
        <div id="dynamic-input-container">
            <div class="row">
                <div class="col-md-12">
                <table class="table-auto mx-auto mt-8 bg-gray-800 text-white col-12">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Nombre de la tienda</th>
                            <th class="px-4 py-2">Mercado fuente</th>
                            <th class="px-4 py-2">Mercado objetivoLugares</th>
                            <th class="px-4 py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($data)
                    @foreach($data as $list)
                        <tr id="source-{{ $list['source_id'] }}">
                            <td class="border px-4 py-2">{{$list['store_name']}}</td>
                            <td class="border px-4 py-2">{{$list['source_market']}}</td>
                            <td class="border px-4 py-2">
                                {{ implode(', ', $list['target_markets']) }}
                            </td>
                            <td class="border px-4 py-2">
                                <a href="{{ route('store.markets.edit',['id' => $list['source_id']]) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2">Editar</a>
                                <button type="button"  data-source-id="{{ $list['source_id'] }}" class="delete-button bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2 ">Eliminar</a>
                            </td>
                        </tr>
                    @endforeach
                    @endif
                    </tbody>
                </table>
                </div>
            </div>
            
        </div>
    </div>

    @if(!empty($list['source_id']))
    <script>

    $(document).ready(function () {
        $('.delete-button').on('click', function () {
            var sourceId = $(this).data('source-id');
           
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
                                url: '{{ route('store.markets.destroy',['id' => $list['source_id']]) }}',
                                data: {
                                    '_token': '{{ csrf_token() }}',
                                    'source_id': sourceId
                                },
                                success: function (data) {
                                    console.log('Delete request successful.');
                                    console.log('Response data:', data);
                                    // Remove the associated data on the client side (e.g., remove the item from the page)
                                    $('#source-' + sourceId).remove();
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
@endif
@endsection
