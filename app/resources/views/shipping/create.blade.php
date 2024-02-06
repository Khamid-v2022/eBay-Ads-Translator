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
        <form action="{{ route('shipping.store') }}" method="post" class="my-4" id="store-manage-form">
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
                                    @foreach($shipping as $ship)
                                    <option value="{{$ship->site_id}}">
                                        {{$ship->site_id }}"{{$ship->site_name}}"
                                    </option>
                                    @endforeach
                                </select>
                                
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-center">
                        <div class="mb-4 col-lg-6 col-md-6 col-sm-12">
                            <label for="source-store-${row_count}" class="form-label text-gray-600 font-semibold">Servicio de Envío:</label>
                            <div class="input-group mb-3">
                                <select class="form-select" required name="shipping_service" id="shipping-methods">
                                    <option value="" selected disabled>Seleccionar tienda</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-center">
                        <div class="mb-4 col-lg-6 col-md-6 col-sm-12">
                            <label for="shipping_service_cost" class="form-label block text-gray-600 font-semibold">Costo Servicio Envio:</label>
                            <input type="number" name="shipping_service_cost" class="border border-gray-500 rounded py-2 px-3 w-full" required>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-center">
                        <div class="mb-4 col-lg-6 col-md-6 col-sm-12">
                            <label for="location" class="form-label block text-gray-600 font-semibold">Ubicación:</label>
                            <input type="text" name="location" class="border border-gray-500 rounded py-2 px-3 w-full" required>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-center">
                        <div class="mb-4 col-lg-6 col-md-6 col-sm-12">
                            <label for="free_shipping" class="form-label block text-gray-600 font-semibold">Envío Gratis:</label>
                            <select class="form-select" name="free_shipping" id="" >
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-center">
                        <div class="mb-4 col-lg-6 col-md-6 col-sm-12">
                            <label for="shipping_type" class="form-label block text-gray-600 font-semibold">Tipo de Envío:</label>
                            <input type="text" name="shipping_type" class="border border-gray-500 rounded py-2 px-3 w-full" required>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-center">
                        <div class="mb-4 col-lg-6 col-md-6 col-sm-12">
                            <label for="dispatch_time_max" class="form-label block text-gray-600 font-semibold">Despacho Time Max:</label>
                            <input type="text" name="dispatch_time_max" class="border border-gray-500 rounded py-2 px-3 w-full" required>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-center">
                        <div class="mb-4 col-lg-6 col-md-6 col-sm-12">
                            <label for="returns_accepted" class="form-label block text-gray-600 font-semibold">Aceptar Devoluciones:</label>
                            <input type="text" name="returns_accepted" class="border border-gray-500 rounded py-2 px-3 w-full" required>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 d-flex justify-content-center">
                        <div class="mb-4 col-lg-6 col-md-6 col-sm-12">
                            <label for="returns_accepted_option" class="form-label block text-gray-600 font-semibold">Opción Devolución Aceptada:</label>
                            <input type="text" name="returns_accepted_option" maxlength="15" class="border border-gray-500 rounded py-2 px-3 w-full" required>
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
         $(document).ready(function() {
            $(".single-select").change(function() {
                var selectedValue = $(this).val();
                
                if (selectedValue) {
                    // Make an AJAX request to eBay's API using the selected value
                    $.ajax({
                        url: '/api/shipservice',  // Replace with your eBay API endpoint
                        type: 'GET',
                        data: { id: selectedValue },  // Pass any required parameters
                        success: function(response) {
                            console.log(response);
                            // Split the response string into an array using <br> as the delimiter
                            var methodsArray = response.split('<br>');
                            // Update the options in the select field
                            var select = $('#shipping-methods');
                            select.empty(); // Clear existing options
                            $.each(methodsArray, function(index, method) {
                                select.append('<option value="' + method + '">' + method + '</option>');
                            });
                        },
                        error: function(error) {
                            console.error('Error fetching shipping methods:', error);
                        }
                    });
                }
            });
        });
    </script>
        
@endsection
