<?php

use Illuminate\Support\Facades\Route;

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
    return \App\Title::all();
});

// add a new title
Route::post('/title/add/{isbn}', function ($isbn) {
    return \App\Title::create($title, $isbn);
});

// list all overdue books
Route::get('/copies/', function () {
    return \App\Copy::copies();
});

// list all overdue books
Route::get('/copies/overdue', function () {
    return \App\Copy::overdue();
});

// add a copy
Route::post('/copy/add/{isbn}', function ($isbn) {
    return \App\Copy::create($isbn);
});

// delete a copy
Route::delete('/copy/{sn}', function($sn) {
    \App\Copy::where('sn',$sn)->delete();
    return response()->json(['message'=>'success'], 200);
});

// patron endpoints

// checkout a book
Route::put('/checkout/{$sn}', function($sn) {
    return \App\Copy::checkout($sn);
});

// checkout a book
Route::delete('/return/{$sn}', function($sn) {
    return \App\Copy::return($sn);
});

Route::get('/checkouts/', function($id) {
    return \App\User::checkouts();
});
