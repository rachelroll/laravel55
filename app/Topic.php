<?php

namespace App;

class Topic extends Model
{
    // 属于这个专题的所有文章
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_topic', 'topic_id', 'post_id');
    }

    // 专题的文章数, 用于 withCount
    public function postTopic()
    {
        return $this->hasMany(PostTopic::class, 'topic_id','id');
    }

}
