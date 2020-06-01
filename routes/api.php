<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// librarian endpoints

// list all titles
Route::get('/titles/', function () {
    return \App\Title::titles();
});

// #1 add a new title
Route::post('/title/add/{isbn}', function($isbn, Request $request) {

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

    return \App\Title::add($isbn, $title);
});

// list all copies
Route::get('/copies/', function () {
    return \App\Copy::all();
});

// #3 list all overdue copies
Route::get('/copies/overdue', function () {
    return \App\Copy::overdue();
});

// add a copy
Route::post('/copy/add/{isbn}', function($isbn, Request $request) {

    $errors = [];

    if ( \App\Title::validateIsbn($isbn) === FALSE ) {
        $errors[] = 'No valid ISBN-10 or ISBN-13 specified.';
    }

    if ( $errors ) {
        return response()->json($errors, 400);
    }

    return \App\Copy::add($isbn);
});

// #2 delete a copy
Route::delete('/copy/{sn}', function($sn) {
    \App\Copy::where('sn',$sn)->delete();
    return response()->json(['message'=>'Deleted copy.'], 200);
});

// #2 delete a title
Route::delete('/title/{id}', function($id) {
    return \App\Title::remove($id);
});

// patron endpoints

// #4 checkout a copy
Route::put('/checkout/{sn}', function($sn, Request $request) {

    $user_id = $request->input('user_id');

    // don't allow the librarian to checkout books
    if ( ! is_numeric($user_id) || $user_id == 1 ) {
        return response()->json(['error'=>'invalid user id.'],400);
    }

    return \App\Copy::checkout($user_id, $sn);
});

// #5 return a copy
Route::delete('/return/{sn}', function($sn) {
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
