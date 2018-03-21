<?php

namespace App;

class Comment extends Model
{
    public function post()
    {
        return $this->belongsTo('Post');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}









