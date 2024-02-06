@extends('layout.main')

@section('content')
    <h1 class="text-3xl font-semibold mb-6">Crear Envío</h1>
    <div class="d-flex justify-content-end">
        <a href="{{route('shipping.index')}}" class="btn btn-primary ">Lista Envío</a>
    </div>

    <div class="bg-white p-4 rounded shadow mb-4">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" role="danger">
                {{ session('error') }}
            </div>
        @endif
        <form action="{{ route('item.store') }}" method="post" class="my-4">
            @csrf
            <div class="clearfix"></div>
            
            <div id="dynamic-input-container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-center">
                        <div class="mb-4 col-lg-6 col-md-6 col-sm-12">
                            <label for="source-store-${row_count}" class="form-label text-gray-600 font-semibold">Mercados:</label>
                            <div class="input-group mb-3">
                                <select class="form-select single-select" required name="site_id">
                                    <option value="" selected disabled>Seleccionar mercados</option>
                                    @foreach($marketplace as $item)
                                    <option value="{{$item->site_id}}">
                                        {{$item->site_id }}"{{$item->site_name}}"
                                    </option>
                                    @endforeach
                                </select>
                                
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-center">
                        <div class="mb-4 col-lg-6 col-md-6 col-sm-12">
                            <label for="name1" class="form-label block text-gray-600 font-semibold">Nombre1 grupo:</label>
                            <input type="text" name="name1" class="border border-gray-500 rounded py-2 px-3 w-full" required>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-center">
                        <div class="mb-4 col-lg-6 col-md-6 col-sm-12">
                            <label for="value1" class="form-label block text-gray-600 font-semibold">Valor1 grupo:</label>
                            <input type="text" name="value1" class="border border-gray-500 rounded py-2 px-3 w-full" required>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-center">
                        <div class="mb-4 col-lg-6 col-md-6 col-sm-12">
                            <label for="name2" class="form-label block text-gray-600 font-semibold">Nombre2 grupo:</label>
                            <input type="text" name="name2" class="border border-gray-500 rounded py-2 px-3 w-full" required>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-center">
                        <div class="mb-4 col-lg-6 col-md-6 col-sm-12">
                            <label for="value2" class="form-label block text-gray-600 font-semibold">Valor2 grupo:</label>
                            <input type="text" name="value2" class="border border-gray-500 rounded py-2 px-3 w-full" required>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-center">
                        <div class="mb-4 col-lg-6 col-md-6 col-sm-12">
                            <label for="name3" class="form-label block text-gray-600 font-semibold">Nombre3 grupo:</label>
                            <input type="text" name="name3" class="border border-gray-500 rounded py-2 px-3 w-full" required>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-center">
                        <div class="mb-4 col-lg-6 col-md-6 col-sm-12">
                            <label for="value3" class="form-label block text-gray-600 font-semibold">Valor3 grupo:</label>
                            <input type="text" name="value3" class="border border-gray-500 rounded py-2 px-3 w-full" required>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-center">
                        <div class="mb-4 col-lg-6 col-md-6 col-sm-12">
                            <label for="name4" class="form-label block text-gray-600 font-semibold">Nombre4 grupo:</label>
                            <input type="text" name="name4" class="border border-gray-500 rounded py-2 px-3 w-full" required>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-center">
                        <div class="mb-4 col-lg-6 col-md-6 col-sm-12">
                            <label for="value4" class="form-label block text-gray-600 font-semibold">Valor4 grupo:</label>
                            <input type="text" name="value4" class="border border-gray-500 rounded py-2 px-3 w-full" required>
                        </div>
                    </div>

                    
                    
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ">Guardar</button>
                    </div>
                </div>
            </div>

            {{-- <!-- <div id="dynamic-input-container">
                <input type="hidden" name="store-list" value="{{ json_encode($tienda) }}">
                <input type="hidden" name="field_count" value="0" id="field_count">
            </div> --> --}}

            <a href="{{ route('tiendas.index') }}"
            class="bg-gray-300 hover:bg-gray-500 text-gray-700 font-bold py-2 px-4 rounded float-end ms-2">Atrás</a>
            
                <div id="api-response"></div>

        </form>
        
    </div>
        
@endsection
