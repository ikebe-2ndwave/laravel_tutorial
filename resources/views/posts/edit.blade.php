@extends('layouts.app')

@section('title','記事編集')

@section('content')

{{ Form::open(['route'=>['posts.update',$post->id], 'method'=>'put']) }}
{{ csrf_field() }}
<p>
  ○タイトル：<br>
  {{ Form::text('title',$post->title) }}
  @if($errors->has('title'))
    <span class="text-danger">{{ $errors->first('title') }}</span>
  @endif
</p>
<p>
  ○本文：<br>
  {{ Form::textarea('content',$post->content) }}
  @if($errors->has('content'))
    <span class="text-danger">{{ $errors->first('content') }}</span>
  @endif
</p>
{{ Form::submit('更新',['class'=>'btn btn-primary btn-sm']) }}
{{ Form::close() }}<br>

<a href="{{ url()->previous('/posts') }}">《   記事一覧へ戻る</a>

@endsection
