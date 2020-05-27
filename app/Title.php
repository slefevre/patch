<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Title extends Model
{

    protected $fillable = ['title','isbn'];

    public function add($isbn, $title) {

    }

    public function remove($isbn) {
    }

}
