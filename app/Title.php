<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Title extends Model
{

    protected $fillable = ['title','isbn'];

    public function add($isbn, $title) {

        $title = Title::create([
            'isbn' => $isbn,
            'title' => $title
        ]);

        return $title->save();
    }

    public function remove($isbn) {
        title::where('isbn', $isbn)->delete();
    }

}
