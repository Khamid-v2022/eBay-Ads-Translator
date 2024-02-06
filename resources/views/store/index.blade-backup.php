@extends('layout.main')

@section('content')
    <h1 class="text-3xl font-semibold mb-6">Publicar lista de trabajos</h1>

    <div class="bg-white p-4 rounded shadow mb-4">
    @if (session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
    @endif
        <form action="{{ route('store.manage_create') }}" method="post" id="store-manage-form">
            @csrf
            <!-- <button type="button" class="btn btn-outline-primary add-button mb-4 float-end">
                <i class="fa fa-plus"></i> Crear trabajo
            </button> -->
            <div class="clearfix"></div>
            
            <div id="dynamic-input-container">
                <div class="row">
                    <div class="mb-4 col-6 col-md-6 col-sm-12">
                        <label for="source-store-${row_count}" class="form-label text-gray-600 font-semibold">Stores:</label>
                        <div class="input-group mb-3">
                            <select class="form-select single-select" name="store">
                                <option selected>Select Store</option>
                                @foreach($tienda as $stores)
                                <option value="{{$stores->id}}">
                                {{$stores->store_name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row job-row">
                    <div class="mb-4 col-6 col-md-6 col-sm-12">
                        <label for="source-store-${row_count}" class="form-label text-gray-600 font-semibold">Source MarketPlace:</label>
                        <div class="input-group mb-3">
                            <select class="form-select single-select" name="source_marketplace" id="source-store-${row_count}" aria-label="Source Store">
                                <option selected>Select Source MarketPlace</option>
                                @foreach($marketplaces as $marketplace)
                                <option value="{{$marketplace->site_id}}">
                                    ({{$marketplace->site_id}}) {{$marketplace->site_name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-4 col-6 col-md-6 col-sm-12">
                        <label for="source-store-${row_count}" class="form-label text-gray-600 font-semibold">Target MarketPlaces:</label>
                        <div class="input-group mb-3">
                            <select class="form-select" name="target_marketplace[]" id="target_marketplace" aria-label="Source Store" multiple>
                                <option selected>Select Target MarketPlace</option>
                                @foreach($marketplaces as $marketplace)
                                <option value="{{$marketplace->site_id}}">
                                    ({{$marketplace->site_id}}) {{$marketplace->site_name}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>  
            </div>

            <!-- <div id="dynamic-input-container">
                <input type="hidden" name="store-list" value="{{ json_encode($tienda) }}">
                <input type="hidden" name="field_count" value="0" id="field_count">
            </div> -->

            <a href="{{ route('tiendas.index') }}"
            class="bg-gray-300 hover:bg-gray-500 text-gray-700 font-bold py-2 px-4 rounded float-end">Volver al Listado</a>
            <button type="submit"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded float-end">Guardar</button>
            <div class="clearfix"></div>
        </form>
        
    </div>
    
@endsection
