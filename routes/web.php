<?php

use Illuminate\Support\Facades\Route;
use App\Title;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// librarian endpoints

// list all titles
Route::get('/titles/', function () {
    return Title::all();
});

// add a new title
Route::post('/title/add/{isbn}', function ($isbn) {
    return Title::create($title, $isbn);
});

// list all overdue books
Route::get('/copy/overdue', function () {
    return Copy::overdue();
});

// add a copy
Route::post('/copy/add/{isbn}', function ($isbn) {
    returnCopy::create($isbn);
});

// delete a copy
Route::delete('/copy/{sn}', function($sn) {
    Copy::find($sn)->delete();
    return 204;
});

// patron endpoints

// checkout a book
Route::put('/checkout/{$sn}', function($sn) {
  return Copy::checkout($sn);
});

// checkout a book
Route::delete('/return/{$sn}', function($sn) {
  return Copy::return($sn);
});


Route::get('/checkouts/', function($id) {
  return User::checkouts();
});
