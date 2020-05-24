<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Copy extends Model
{

    private $checkouts = [];
    private $overdue = [];

    public function checkout($user_id, $sn) {

        // see if they have any overdue books,
        // or if they have any overdue books

        $checkouts = \App\User::checkouts($user_id);

        if ( $checkouts['checkouts'] >= 3 ) {

        }

        if ( $checkouts['overdue'] ) {

        }
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
