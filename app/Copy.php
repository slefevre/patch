<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Copy extends Model
{

    public function checkout($user_id, $sn) {

        // see if they have any overdue books,
        // or if they have any overdue books

        $checkouts = \App\User::checkouts($user_id);

        $errors = [];

        if ( $checkouts['checkouts'] >= 3 ) {
            $errors[] = 'User has three books checked out. User must return at least one book to check out another.';
        }

        if ( $checkouts['overdue'] ) {
            $errors[] = 'User has three books overdue. User must return overdue books to checkout.';
        }

        if ( $errors ) {
            return $response()->json($errors);
        }

        // checkout the book
        $copy = Copy::find($sn);
        $copy->checkout_user_id = $id;
        $copy->checkout_date = Carbon\Carbon::now();
        $copy->save();

    }

    public function delete($sn) {
        Copy::where('sn',$sn)->delete();
    }

    public function return($sn) {
        $copy = Copy::find($sn);
        $copy->checkout_user_id = NULL;
        $copy->checkout_date = NULL;
        $copy->save();
    }

}
