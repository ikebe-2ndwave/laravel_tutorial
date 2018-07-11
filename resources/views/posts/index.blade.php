@extends('layouts.app')

@section('title','記事一覧')

@section('script')
<script>
function delPostConfirm() {
    if(confirm('削除しますか？')) {
    } else {
        return false;
    }
}
</script>
@endsection

@section('content')

<!-- フラッシュメッセージ -->
@if(Session::has('message'))
<div class="alert alert-success">
  {{ session('message') }}
</div>
@endif

<!-- グリッドシステム -->
<div class="row">
  <div class="col-sm-9">
  <!-- コンテンツ -->
  <table class="table table-hover">
  <tr>
    <th scope="col">タイトル</th>
    <th scope="col">本文</th>
    <th scope="col">投稿時間</th>
    <th scope="col"></th>
    <th scope="col"></th>
  </tr>
  @foreach($posts as $post)
  <tr>
    <th scode="row">{{ link_to_route('posts.show',$post->title,[$post->id]) }}</th>
    <td>{{ $post->content }}</td>
    <td>{{ $post->created_at }}</td>
    <td>{{ link_to_route('posts.edit','編集',[$post->id],['class'=>'btn btn-primary btn-sm']) }}</td>
    <td>
      {{ Form::open(['route'=>['posts.destroy',$post->id],'onSubmit'=>'return delPostConfirm();','method'=>'delete']) }}
      {{ Form::hidden('keyword',$params['keyword']) }}
      {{ Form::hidden('from_year',$params['from_year']) }}
      {{ Form::hidden('from_month',$params['from_month']) }}
      {{ Form::hidden('from_day',$params['from_day']) }}
      {{ Form::hidden('to_year',$params['to_year']) }}
      {{ Form::hidden('to_month',$params['to_month']) }}
      {{ Form::hidden('to_day',$params['to_day']) }}
      {{ Form::hidden('page',$params['page']) }}
      {{ Form::submit('削除',['class'=>'btn btn-danger btn-sm']) }}
      {{ Form::close() }}
    </td>
  </tr>
  @endforeach
  </table>
  <div style="text-align:center;">
  {{ $posts->appends($params)->links() }}
  </div>
  </div><!-- col-sm-9 end -->

  <div class="col-sm-3">
    <!-- 検索 -->
    <span>○検索</span><br>
    {{ Form::open(['route'=>'posts.index','method'=>'get']) }}
    {{ csrf_field() }}
    {{ Form::text('keyword','',['type'=>'search','placeholder'=>'タイトルまたは本文から検索','style'=>'width: 200px; margin-bottom:3px;']) }}<br>
    {{ Form::submit('検索',['class'=>'btn btn-primary btn-sm']) }}
    {{ Form::close() }} <br>

    <!-- (おまけ)コメント検索 -->
    <span>○コメント検索</span><br>
    {{ Form::open(['route'=>'posts.index','method'=>'get']) }}
    {{ csrf_field() }}
    {{ Form::text('comment_keyword','',['type'=>'search','placeholder'=>'コメントから検索','style'=>'width: 200px; margin-bottom:3px;']) }}<br>
    {{ Form::submit('検索',['class'=>'btn btn-primary btn-sm']) }}
    {{ Form::close() }} <br>

    <!-- 日付検索 -->
    <span>○日付検索</span>
    {{ Form::open(['route'=>'posts.index','method'=>'get']) }}
    {{ Form::selectRange('from_year', 2000, 2018, '', ['placeholder' => '年']) }}年
    {{ Form::selectRange('from_month', 1, 12, '', ['placeholder' => '月']) }}月
    {{ Form::selectRange('from_day', 1, 31, '', ['placeholder' => '日']) }}日
    <span>〜</span><br>
    {{ Form::selectRange('to_year', 2000, 2018, '', ['placeholder' => '年']) }}年
    {{ Form::selectRange('to_month', 1, 12, '', ['placeholder' => '月']) }}月
    {{ Form::selectRange('to_day', 1, 31, '', ['placeholder' => '日']) }}日<br>
    {{ Form::submit('絞り込み',['class'=>'btn btn-primary btn-sm','style'=>'margin-top:3px;']) }}
    {{ Form::close() }}<br>

    <!-- 記事作成ボタン -->
    <span>○記事作成</span><br>
    {{ link_to_route('posts.create','作成','',['class'=>'btn btn-success btn-sm']) }}
  </div><!-- col-sm-3 end -->
</div>

@endsection
