<?php

namespace App;

use Illuminate\Database\Query\Builder;
use Laravel\Scout\Searchable;

//表 => posts
class Post extends Model
{
    use Searchable;
    protected $guarded = [];

    // 定义索引里面的type
    public function searchableAs()
    {
        return "post";
    }

    //定义有哪些字段需要搜索
    public function toSearchableArray()
    {
        return [
            'title'   => $this->title,
            'content' => $this->content,
        ];
    }

    //关联用户
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    //评论模型
    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at');
    }

    //和用户进行关联
    public function zan($user_id)
    {
        return $this->hasOne(\App\Zan::class)->where('user_id', $user_id);
    }

    //文章所有赞
    public function zans()
    {
        return $this->hasMany(\App\Zan::class);
    }

    // 属于某个作者的文章
    public function scopeAuthorBy($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    public function postTopics()
    {
        return $this->hasMany(\App\PostTopic::class, 'post_id', 'id');
    }

    // 不属于某个专题的文章
    public function scopeTopicNotBy($query, $topic_id)
    {
        return $query->doesntHave('postTopics', 'and', function($q) use($topic_id)
        {
            $q->where('topic_id', $topic_id);
        });
    }

    // 全局 scope 的方式
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope("available", function($builder)
        {
            $builder->whereIn('status', [0,1]);
        });

    }
}
