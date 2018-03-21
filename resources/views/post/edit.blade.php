@extends("layout.main")

@section("content")

        <div class="col-sm-8 blog-main">
            <form action="/posts/{{ $post->id }}" method="POST">
                <input type="hidden" name="_method" value="PUT">
                {{ csrf_field() }}
                <div class="form-group">
                    <label>标题</label>
                    <input name="title" type="text" class="form-control" placeholder="这里是标题" value= "{{ $post->title }}">
                </div>
                <div class="form-group">
                    <label>内容</label>

                    <div id="editor">
                        {!! $post->content !!}
                    </div>
                    <textarea id="content" name="content" style="display:none;">
                        </textarea>
                </div>

                @include("layout.errors")

                <button type="submit" class="btn btn-default">提交</button>
            </form>
            <br>
        </div><!-- /.blog-main -->

    @endsection("content")


