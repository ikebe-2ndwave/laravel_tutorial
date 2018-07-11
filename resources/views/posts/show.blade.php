@extends('layouts.app')

@section('title','記事詳細')

@section('content')

@if(Session::has('message'))
<div class="alert alert-success">
  {{ session('message') }}
</div>
@endif

<!-- タイトルと本文 -->
<span>{{ $posts->created_at }}</span>
<h1>{{ $posts->title }}</h1><br>
<p>{{ $posts->content }}</p>

<hr>

<!-- コメント表示 -->
<p>○コメント一覧：</p>
@if(count($comments) >= 1)
@foreach($comments as $comment)
<p>
    [{{ $comment->comment_id }}]<br>名前：{{ $comment->name }}<br>
</p>
<p>
    {{ $comment->comment }}
</p>
<p>
    投稿時間：{{ $comment->created_at }}
</p>
@endforeach
@else
<p>
  コメントはありません。
</p>
@endif

<hr>

<!-- コメント入力 -->
<p>○コメントする：</p>
@if($user)
{{ Form::open(['url' => '/comment']) }}
{{ csrf_field() }}
<p>
    名前：{{ $user->name }}
</p>
<p>
  コメント：<br>
  {{ Form::textarea('comment') }}
  @if($errors->has('comment'))
  <span class="text-danger">{{ $errors->first('comment') }}</span>
  @endif
</p>
{{ Form::hidden('name',$user->name) }}
{{ Form::hidden('post_id',$posts->id) }}
{{ Form::hidden('comment_id',$id) }}
{{ Form::submit('コメント',['class'=>'btn btn-primary btn-sm']) }}
{{ Form::close() }}<br>
@else
<p class="text-danger">
    ※コメントを行う場合はログインをする必要があります。
</p>
@endif

<a href="{{ $back_url }}">《   記事一覧へ戻る</a>

@endsection
