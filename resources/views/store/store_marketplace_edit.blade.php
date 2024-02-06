@extends('layout.main')

@section('content')
<div class="row justify-content-center">

    <h1 class="text-3xl font-semibold mb-6">Editar Tienda</h1>
    @php 
        $store_id = $data['store_id'];
        $site_id = $data['site_id'];
        $sourceid = $data['source_id'];
    @endphp
    <div class="bg-white p-4 rounded shadow mb-4 col-7">
        @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
        @endif
        <form action="{{route('store.markets.update')}}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label for="store_name" class="block text-gray-600 font-semibold">Almacenar</label>
                <select class="form-select single-select" name="store">
                    <option selected>Select Store</option>
                    @foreach($tienda as $stores)
                    <option value="{{ $stores->id }}" {{ $stores->id == $store_id ? 'selected' : '' }}>
                    {{$stores->store_name}}
                    </option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" value={{$sourceid}} name="source_id">
            <div class="mb-4">
                <label for="access_token" class="block text-gray-600 font-semibold">Mercado de origen</label>
                <select class="form-select single-select" name="source_marketplace" id="source-store-${row_count}" aria-label="Source Store">
                    <option selected>Select Source MarketPlace</option>
                    @foreach($marketplaces as $marketplace)
                    <option value="{{ $marketplace->site_id }}" {{ $marketplace->site_id == $site_id ? 'selected' : '' }}>
                    
                        ({{$marketplace->site_id}}) {{$marketplace->site_name}}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="marketplaces" class="block text-gray-600 font-semibold">Mercado objetivo</label>
                <select class="form-select" name="target_marketplace[]" id="target_marketplace" aria-label="Source Store" multiple>
                    <option>Select Target MarketPlace</option>
                    @foreach($marketplaces as $marketplace)
                        <option value="{{ $marketplace->site_id }}" 
                            {{ in_array($marketplace->site_id, array_column($target_data_array, 'sites_id')) ? 'selected' : '' }}>
                            ({{ $marketplace->site_id }}) {{ $marketplace->site_name }}
                        </option>
                    @endforeach
                </select>

            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Actualizar</button>
        </form>
        
    </div>
</div>

<div class=" d-flex justify-content-center">
    <a href="{{ route('store.markets') }}" class="bg-gray-200 hover:bg-gray-500 text-gray-700 font-bold py-2 px-4 rounded">atrás</a>
</div>

@endsection
