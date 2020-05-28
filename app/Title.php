<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Title extends Model
{

    protected $fillable = ['title','isbn'];

    public static function add($isbn, $title) {

        $title = Title::create([
            'isbn' => $isbn,
            'title' => $title
        ]);

        return $title->save();
    }

    public static function remove($isbn) {
        title::where('isbn', $isbn)->delete();
    }

    /**
     * returns 1 on ISBN-10, 2 on ISBN-13, and FALSE on no valid isbn.
     */
    public static function validateIsbn($isbn) {

        $regex = '/\b(?:ISBN(?:: ?| ))?((?:97[89])?\d{9}[\dx])\b/i';

        if ( preg_match($regex, str_replace('-', '', $isbn), $matches)) {
            return (10 === strlen($matches[1])) ? 1 : 2;
        }
        return false;
    }
}
