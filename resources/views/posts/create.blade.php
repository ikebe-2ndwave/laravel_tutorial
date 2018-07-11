@extends('layouts.app')

@section('title','記事作成')

@section('content')

{{ Form::open(['route'=>'posts.store']) }}
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
{{ Form::submit('作成',['class'=>'btn btn-primary btn-sm']) }}
{{ Form::close() }}<br>

{{ link_to_route('posts.index','《   記事一覧へ戻る') }}

@endsection
