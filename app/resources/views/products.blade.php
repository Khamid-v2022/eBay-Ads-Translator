@extends('layout.main')

@section('content')
    <div class="container mx-auto">
        <h1 class="text-2xl font-semibold my-4 text-center mb-5">Productos de eBay</h1>
        <table class="w-full bg-white shadow-md rounded my-6 mx-auto">
            <thead class="bg-gray-200 text-gray-600">
                <tr>
                    <th class="py-2 px-4">ID</th>
                    <th class="py-2 px-4">Título</th>
                    <th class="py-2 px-4">Precio Actual</th>
                </tr>
            </thead>
            <tbody>
                @if (count($products['item']) > 0)
                    @foreach ($products['item'] as $product)
                        <tr class="border-b hover:bg-orange-100">
                            <td class="py-2 px-4">{{ $product['itemId'][0] }}</td>
                            <td class="py-2 px-4">{{ $product['title'][0] }}</td>
                            <td class="py-2 px-4">{{ $product['sellingStatus'][0]['currentPrice'][0]['__value__'] }} {{ $product['sellingStatus'][0]['currentPrice'][0]['@currencyId'] }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="py-4 px-4" colspan="3">No se encontraron productos.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection
