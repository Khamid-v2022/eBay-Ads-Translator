<?php

use App\Http\Controllers\EbayController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\ItemController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store']);

Route::get('/products', [EbayController::class, 'getAllProductsFromEbay']);

// Mostrar todas las tiendas (Listar)
Route::get('/tiendas', [TiendaController::class, 'index'])->name('tiendas.index');

// Mostrar el formulario para crear una nueva tienda (Crear)
Route::get('/tiendas/create', [TiendaController::class, 'create'])->name('tiendas.create');

// Almacenar una nueva tienda en la base de datos (Crear)
Route::post('/tiendas', [TiendaController::class, 'store'])->name('tiendas.store');

// Mostrar los detalles de una tienda específica (Leer)
Route::get('/tiendas/{tienda}', [TiendaController::class, 'show'])->name('tiendas.show');

// Mostrar el formulario de edición de una tienda (Actualizar)
Route::get('/tiendas/{tienda}/edit', [TiendaController::class, 'edit'])->name('tiendas.edit');

// Actualizar una tienda en la base de datos (Actualizar)
Route::put('/tiendas/{tienda}', [TiendaController::class, 'update'])->name('tiendas.update');

// Eliminar una tienda de la base de datos (Eliminar)
Route::post('/tiendas_delete', [TiendaController::class, 'destroy'])->name('tiendas.destroy');
/**
 * 
 * 
 * Store route
 */
Route::post('/stores_markets/destroy/{id}', [StoreController::class, 'store_market_destroy'])->name('store.markets.destroy');
Route::get('/manage-store', [StoreController::class, 'index'])->name('store.manage');
Route::get('/stores_markets', [StoreController::class, 'store_market_list'])->name('store.markets');
Route::get('/stores_markets_edit/{id}', [StoreController::class, 'store_market_edit'])->name('store.markets.edit');
Route::post('/store/create', [StoreController::class, 'store'])->name('store.manage_create');
Route::put('/stores_markets_update', [StoreController::class, 'store_market_update'])->name('store.markets.update');

Route::get('/shipping', [ShippingController::class, 'index'])->name('shipping.index');
Route::get('/shipping/create', [ShippingController::class, 'create'])->name('shipping.create');
Route::post('/shipping/store', [ShippingController::class, 'store'])->name('shipping.store');
Route::get('/shipping/edit/{id}', [ShippingController::class, 'edit'])->name('shipping.edit');
Route::post('/shipping/update/{id}', [ShippingController::class, 'update'])->name('shipping.update');
Route::post('/shipping-destroy', [ShippingController::class, 'destroy'])->name('shipping.destroy');
/**
 *
 *
 * Item_specifc route
 */
Route::get('/item', [ItemController::class, 'create'])->name('item.create');
Route::post('/item/store', [ItemController::class, 'store'])->name('item.store');