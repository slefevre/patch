<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Copy extends Model
{
    protected $fillable = ['sn','checkout_date'];

    public static function checkout($user_id, $sn) {

        // see if they have any overdue copies,
        // or if they have any overdue copies

        $checkouts = self::copies($user_id);

        $errors = [];

        if ( $checkouts->count() >= 3 ) {
            $errors[] = 'User has three copies checked out. User must return at least one book to check out another.';
        }

        foreach ( $checkouts as $checkout ) {
            if ( $checkout->days_overdue ) {
                $errors[] = 'User has copies overdue. User must return overdue books to checkout.';
                break;
            }
        }

        if ( $errors ) {
            return response()->json($errors, 400);
        }

        // checkout the book
        try {
            $update = \Db::table('copies')
            ->where('sn', $sn)
            ->update([
                'checkout_user_id' => $user_id,
                'checkout_date' => \Carbon\Carbon::today()->toDateString()
            ], ['touch' => FALSE]);

            if ( $update ) {
                return response()->json(['message'=>'User has checked out book. It is due in 14 days.']);
            } else {
                return response()->json(['error' => 'Copy could not be checked out.'], 400);
            }
        } catch (Exception $e) {
            return response()->json(['errors'], 400);
        }

    }

    public static function remove($sn) {
        Copy::where('sn',$sn)->delete();
        return $response()->json(['message'=>'Copy has been deleted from the system.'],204);
    }

    public static function return($sn) {

        try {
            $copy = Copy::where('sn', $sn)->firstOrFail();
            $copy->checkout_user_id = NULL;
            $copy->checkout_date = NULL;
            $copy->save();
            return response()->json(['message'=>'User has returned book.'],204);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    public static function overdue() {
        return response()->json(self::copies($user=NULL, $overdue=TRUE));
    }

    public static function checkouts($user_id) {
        return response()->json(self::copies($user_id));
    }

    /**
    * Abstract class for overdue, all copies, user checkouts
    */
    public static function copies($user_id=NULL, $overdue=NULL) {
        $result = self::select('checkout_date', 'title', 'name', 'sn AS serial_number',
                \DB::raw('GREATEST(DATEDIFF(NOW(),checkout_date) - 14,0) AS days_overdue')
            )
            ->join('users', 'copies.checkout_user_id', '=', 'users.id')
            ->join('titles', 'copies.title_id', '=', 'titles.id')
            ->orderBy('checkout_date')
        ;

        if ( $user_id ) {
            $result->where('copies.checkout_user_id', '=', $user_id);
        }

        if ( $overdue ) {
            $result->where('checkout_date', '<', \Carbon\Carbon::today()->subDays(14)->toDateString());
        }

        return $result->get();
    }
}
