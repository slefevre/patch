<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Title extends Model
{

    protected $fillable = ['title','isbn','media'];

    public function add($title, $isbn, $media) {
    }

    public function remove($isbn) {
    }

    public function overdue() {
    }

    public function list() {
    }

}
