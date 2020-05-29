<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Title extends Model
{

    protected $fillable = ['title','isbn'];

    public static function add($isbn, $title) {

            $new_title = new Title;
            $new_title->title = $title;
            $new_title->isbn = $isbn;

        try {
            $new_title->save();
            return response()->json('Added title', 201);
        } catch (\Illuminate\Database\QueryException $e) {
            $message = $e->getMessage();

            if ( stristr($message, "for key 'titles_isbn_unique") ) {
            // the title already exists.
                return response()->json(['error'=>"The ISBN $isbn already exists."], 400);
            }

            // unexpected error
            return response()->json(['error'=>$message], 400);
        }
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
