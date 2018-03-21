<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Comment;
use App\Zan;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    //列表
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->withCount(['comments', 'zans'])->paginate(6);
        return view("post/index", compact('posts'));
    }

    //详情页面
    public function show(Post $post)
    {
        $post->load('comments');
        return view("post/show", compact('post'));
    }


    //创建页面
    public function create()
    {
         return view("post/create");
    }

    //创建逻辑
    public function store()
    {
        //验证
        $this->validate(request(), [
            'title' => 'required|string|max:100|min:5',
            'content' => 'required|string|min:10',
        ]);

        //逻辑
        $user_id = \Auth::id();
        $params = array_merge(request(['title', 'content']), compact('user_id'));
        Post::create($params);
        //页面渲染
        return redirect('/posts');
    }

    //编辑页面
    public function edit(Post $post)
    {
        return view("post/edit", compact('post'));
    }

    //编辑逻辑
    public function update(Post $post)
    {
        //验证
        $this->validate(request(), [
            'title' => 'required|string|max:100|min:5',
            'content' => 'required|string|min:10',
        ]);
        $this->authorize('update', $post);

        //逻辑
        $post->title = request('title');
        $post->content = request('content');
        $post->save();
        //渲染
        return redirect("/posts/{$post->id}");
    }

    //删除逻辑
    public function delete(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
        return redirect("/posts");
    }

    public function upload()
    {
       $file = request()->file('yourFileName');
        if ($file->isValid()) {
            $storage = Storage::disk('local');
            $url = $storage->url($storage->put('public',$file));
            return response()->json([
                'errno'=>0,
                'data'=>[
                    $url,
                ]
            ]);
        }
    }

    public function comment(Post $post)
    {
        //验证
        $this->validate(request(), [
            'content' => 'required|min:3',
        ]);

        //逻辑
        $comment = new Comment();
        $comment->user_id = \Auth::id();
        //$comment->post_id = $post->id;
        $comment->content = request('content');
        $post->comments()->save($comment);

        //渲染
        return back();
    }

    public function zan(Post $post)
    {
        $param = [
            'user_id' => \Auth::id(),
            'post_id' => $post->id,
        ];
        // 先查找 zans 数据表, 如果没有这些数据再创建
        Zan::firstOrCreate($param);
        return back();
    }
    public function unzan(Post $post)
    {
        $post->zan(\Auth::id())->delete();
        return back();
    }

    public function search()
    {

        //验证
        $this->validate(request(),[
            'query' => 'required'
        ]);
        //逻辑
        $query = request('query');
        $posts = \App\Post::search($query)->paginate(2);

        //渲染
        return view('post.search', compact('posts', 'query'));
    }

}

