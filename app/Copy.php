<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Copy extends Model
{
    protected $fillable = ['sn','checkout_date'];

    public static function add($isbn) {

        $faker = \Faker\Factory::create();
        $sn = $faker->ean8();

        try {
            \DB::insert(
                'INSERT INTO copies ( sn, title_id )
                SELECT ? as sn, id FROM titles WHERE isbn = ?',
                [$sn,$isbn]
            );
            return response()->json(['message'=>"Added copy of ISBN $isbn with serial number $sn."]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

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

        $copy = Copy::where('sn', $sn)->first();

        if ( is_null($copy) ) {
            $errors[] = 'Invalid serial number.';
        } elseif ( is_numeric($copy->checkout_user_id) ) {
            $errors[] = 'Copy is already checked out. It must be returned first.';
        }

        if ( $errors ) {
            return response()->json($errors, 400);
        }

        // checkout the book
        try {
            $update = self::checkoutsReturns($sn, $user_id, \Carbon\Carbon::today()->toDateString());
            if ( $update ) {
                return response()->json(['message'=>'User has checked out book. It is due in 14 days.']);
            } else {
                return response()->json(['error' => 'Copy could not be checked out.'], 400);
            }
        } catch (Exception $e) {
            return response()->json(['error'=>'An unknown error occured.'], 400);
        }

    }

    public static function remove($sn) {
        Copy::where('sn',$sn)->delete();
        return $response()->json(['message'=>'Copy has been removed from the system.'],204);
    }

    public static function return($sn) {
        try {
            $update = self::checkoutsReturns($sn, NULL, NULL);
            if ( $update ) {
                return response()->json(['message'=>'User has returned book.'],204);
            } else {
                return response()->json(['error' => 'Copy could not be returned.'], 500);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Copy could not be returned.'], 500);
        }
    }

    /**
     * Generic method for checkout and returns
     */
    private static function checkoutsReturns($sn, $user_id, $date) {

        return \DB::table('copies')
            ->where('sn', $sn)
            ->update([
                'checkout_user_id' => $user_id,
                'checkout_date' => $date
            ], ['touch' => FALSE]);
    }

    public static function overdue() {
        return response()->json(self::copies($user=NULL, $overdue=TRUE));
    }

    public static function checkouts($user_id) {
        return response()->json(self::copies($user_id));
    }

    /**
     * Generic method for overdue, all copies, user checkouts
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
