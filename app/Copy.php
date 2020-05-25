<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Copy extends Model
{
    protected $fillable = ['sn','checkout_date'];

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
            return $response()->json($errors, 400);
        }

        // checkout the book
        $copy = Copy::find($sn);
        $copy->checkout_user_id = $id;
        $copy->checkout_date = Carbon\Carbon::now();
        $copy->save();

        return $response()->json(['message'=>'User has checked out book. It is due in 14 days.']);
    }

    public function remove($sn) {
        Copy::where('sn',$sn)->delete();
        return $response()->json(['message'=>'Copy has been deleted from the system.'],204);
    }

    public function return($sn) {
        $copy = Copy::find($sn);
        $copy->checkout_user_id = NULL;
        $copy->checkout_date = NULL;
        $copy->save();
        return $response()->json(['message'=>'User has returned book.'],204);
    }

    public static function overdue() {
        $results = self::where('checkout_date', '<' , \Carbon\Carbon::today()->subDays(13)->toDateString());
        return response()->json($results->get());
    }

}
