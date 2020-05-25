<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Copy extends Model
{
    protected $fillable = ['sn','checkout_date'];

    public static function checkout($user_id, $sn) {

        // see if they have any overdue books,
        // or if they have any overdue books

        $checkouts = self::checkouts($user_id);

        $errors = [];

        if ( $checkouts->count() >= 3 ) {
            $errors[] = 'User has three books checked out. User must return at least one book to check out another.';
        }

        foreach ( $checkouts as $checkout ) {
            if ( $checkouts->overdue ) {
                $errors[] = 'User has books overdue. User must return overdue books to checkout.';
                break;
            }
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

    public static function remove($sn) {
        Copy::where('sn',$sn)->delete();
        return $response()->json(['message'=>'Copy has been deleted from the system.'],204);
    }

    public static function return($sn) {
        $copy = Copy::find($sn);
        $copy->checkout_user_id = NULL;
        $copy->checkout_date = NULL;
        $copy->save();
        return $response()->json(['message'=>'User has returned book.'],204);
    }

    public static function overdue() {
        return response()->json(self::books($user=NULL, $overdue=TRUE));
    }

    public static function checkouts($user_id) {
        return response()->json(self::books($user_id));
    }

    /**
    * Abstract class for overdue, all copies, user checkouts
    */
    public static function books($user_id=NULL, $overdue=NULL) {
        $result = self::select('checkout_date', 'title', 'name')
            ->join('users', 'copies.checkout_user_id', '=', 'users.id')
            ->join('titles', 'copies.title_id', '=', 'titles.id')
            ->orderBy('checkout_date')
        ;

        if ( $user_id ) {
            $result->where('copies.checkout_user_id', '=', $user_id);
        }

        if ( $overdue ) {
            $result->where('checkout_date', '<', \Carbon\Carbon::today()->subDays(13)->toDateString());
        }

        return $result->get();
    }
}
