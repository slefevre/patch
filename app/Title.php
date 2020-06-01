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
        $affected = title::where('isbn', $isbn)->delete();

        if ( $affected ) {
            return response()->json(['error'=>'Title deleted.'], 200);
        } else {
            return response()->json(['error'=>'Invalid title ID.'], 400);
        }
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


    /**
    *
    */
    public static function titles() {
        $result = self::select('title', 'title_id', 'isbn',
                \DB::raw('COUNT(title_id) AS copies')
            )
            ->leftjoin('copies', 'copies.title_id', '=', 'titles.id')
            ->groupBy('title_id')
            ->orderBy('title')
        ;

        return $result->get();
    }
}
