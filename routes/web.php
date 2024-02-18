<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;


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

// Route::get('/', function () {
//     return view('welcome');
// })->name('sales.start');

Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
Route::get('/sales/start', [SaleController::class, 'start'])->name('sales.start');
Route::post('/sales/addItem', [SaleController::class, 'addSaleLineItem'])->name('sales.addItem');
Route::put('/sales/updateItem/{itemId}', [SaleController::class, 'updateSaleLineItem'])->name('sales.updateItem');
Route::delete('/sales/removeItem/{itemId}', [SaleController::class, 'removeSaleLineItem'])->name('sales.removeItem');
Route::post('/sales/pay', [SaleController::class, 'pay'])->name('sales.pay');

// Verb          Path                        Action  Route Name
// GET           /items                      index   items.index
// GET           /items/create               create  items.create
// POST          /items                      store   items.store
// GET           /items/{item}               show    items.show
// GET           /items/{item}/edit          edit    items.edit
// PUT|PATCH     /items/{item}               update  items.update
// DELETE        /items/{item}               destroy items.destroy
Route::resource('items', ItemController::class);

// Verb          Path                          Action  Route Name
// GET           /members                      index   members.index
// GET           /members/create               create  members.create
// POST          /members                      store   members.store
// GET           /members/{member}             show    members.show
// GET           /members/{member}/edit        edit    members.edit
// PUT|PATCH     /members/{member}             update  members.update
// DELETE        /members/{member}             destroy members.destroy
Route::resource('members', MemberController::class);