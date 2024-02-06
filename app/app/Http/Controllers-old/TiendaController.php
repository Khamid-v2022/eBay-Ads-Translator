<?php

namespace App\Http\Controllers;

use App\Models\Tienda;
use Illuminate\Http\Request;

class TiendaController extends Controller
{
    public function index()
    {
        $tiendas = Tienda::all();
        return view('index', ['component' => 'index', 'tiendas' => $tiendas]);
    }

    public function create()
    {
        return view('create', ['component' => 'create']);
    }

    public function store(Request $request)
{
    $request->validate([
        'access_token' => 'required',
        'store_name' => 'required',
        'marketplaces' => 'required|integer',
    ]);

    $tienda = new Tienda();
    $tienda->access_token = $request->input('access_token');
    $tienda->store_name = $request->input('store_name');
    $tienda->marketplaces = $request->input('marketplaces');
    $tienda->save();

    return redirect()->route('tiendas.index')->with('success', 'La tienda ha sido creada con éxito');
}

    public function show(Tienda $tienda)
    {
        return view('show', ['component' => 'show', 'tienda' => $tienda]);
    }

    public function edit(Tienda $tienda)
    {
        return view('edit', ['component' => 'edit', 'tienda' => $tienda]);
    }

    public function update(Request $request, Tienda $tienda)
{
    $request->validate([
        'access_token' => 'required',
        'store_name' => 'required',
        'marketplaces' => 'required|integer',
    ]);

    // Actualiza los datos de la tienda con los datos del formulario
    $tienda->access_token = $request->input('access_token');
    $tienda->store_name = $request->input('store_name');
    $tienda->marketplaces = $request->input('marketplaces');

    // Guarda los cambios en la base de datos
    $tienda->save();

    return redirect()->route('tiendas.index')->with('success', 'La tienda ha sido actualizada con éxito');
}

public function destroy(Tienda $tienda)
{
    // Elimina la tienda
    $tienda->delete();

    return redirect()->route('tiendas.index')->with('success', 'La tienda ha sido eliminada con éxito');
}

}
