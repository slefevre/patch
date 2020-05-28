<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

// list all copies
Route::get('/copies/', function () {
    return \App\Copy::all();
});

// #3 list all overdue copies
Route::get('/copies/overdue', function () {
    return \App\Copy::overdue();
});

// #1 add a copy
Route::post('/copy/add/{isbn}', function($isbn, Request $request) {

    $title = $request->input('title');

    $errors = [];

    if ( ! $title ) {
        $errors[] = 'No title specified.';
    }

    if ( \App\Title::validateIsbn($isbn) === FALSE ) {
        $errors[] = 'No valid ISBN-10 or ISBN-13 specified.';
    }

    if ( $errors ) {
        return response()->json($errors, 400);
    }

    return \App\Title::create($isbn, $title);
});

// #2 delete a copy
Route::delete('/copy/{sn}', function($sn) {
    \App\Copy::where('sn',$sn)->delete();
    return response()->json(['message'=>'success'], 200);
});

// patron endpoints

// #4 checkout a copy
Route::get('/checkout/{sn}', function($sn, Request $request) {

    $user_id = $request->input('user_id');

    // don't allow the librarian to checkout books
    if ( ! is_numeric($user_id) || $user_id == 1 ) {
        return response()->json(['error'=>'invalid user id.'],400);
    }

    return \App\Copy::checkout($user_id, $request->input('sn'));
});

// checkout a copy
Route::get('/checkout/{$sn}', function($sn) {
    return response()->json($request);
    return \App\Copy::checkout($sn);
});

// #5 return a copy
Route::delete('/return/{$sn}', function($sn) {
    return \App\Copy::return($sn);
});

// #6 show user's checkouts
Route::get('/checkouts/', function(Request $request) {
    $user_id = $request->input('user_id');

    // don't allow the librarian to checkout books
    if ( ! is_numeric($user_id) || $user_id == 1 ) {
        return response()->json(['error'=>'invalid user id.'],400);
    }

    return \App\Copy::checkouts($user_id);
});
