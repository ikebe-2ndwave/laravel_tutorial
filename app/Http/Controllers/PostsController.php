<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use App\Http\Requests\ValidationCheck;
use App\Comment;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $post = new Post();

        // 日付絞り込み
         $select_from = $request->input('from_year') .'-'.$request->input('from_month').'-'.$request->input('from_day');
         $select_to = $request->input('to_year') .'-'.$request->input('to_month').'-'.$request->input('to_day');

        if($request->has('keyword')) { // あいまい検索
            $keyword = $request->input('keyword');
            $keyword = str_replace("　"," ",$keyword);
            $arr = explode(" ",$keyword);

            $posts = $post->where(function($post) use($arr) {
                foreach($arr as $val) {
                    $post->where('title','like','%'.$val.'%')->orWhere('content','like','%'.$val.'%');
                }
            })->paginate(20);
        } else if(strptime($select_from,'%Y-%m-%d') && strptime($select_to,'%Y-%m-%d')) { // 日付絞り込み(From&To)
            $posts = $post->whereDate('created_at','>=',$select_from)->whereDate('created_at','<=',$select_to)->paginate(20);
        } else if(strptime($select_from,'%Y-%m-%d') && !strptime($select_to,'%Y-%m-%d')) { // 日付絞り込み(From)
            $posts = $post->whereDate('created_at','>=',$select_from)->paginate(20);
        } else if(!strptime($select_from,'%Y-%m-%d') && strptime($select_to,'%Y-%m-%d')) { // 日付絞り込み(To)
            $posts = $post->whereDate('created_at','<=',$select_to)->paginate(20);
        } else if($request->has('comment_keyword')) { // コメント検索
            $comment_keyword = $request->input('comment_keyword');
            $comment_keyword = str_replace("　"," ",$comment_keyword);
            $arr = explode(" ",$comment_keyword);

            $posts = $post->select('posts.id','posts.title','posts.content','posts.created_at')
              ->join('comments', 'posts.id', '=', 'comments.post_id')
              ->where(function($post) use($arr) {
                foreach($arr as $val) {
                    $post->orWhere('comments.comment','like','%'.$val.'%');
                }
            })->groupBy('posts.id')
            ->paginate(20);
         } else { // 通常時
            $posts = Post::paginate(20);
        }

        // ページング時のパラメータ引き回し
        $params = array(
            'keyword' => $request->input('keyword'),
            'from_year' => $request->input('from_year'),
            'from_month' => $request->input('from_month'),
            'from_day' => $request->input('from_day'),
            'to_year' => $request->input('to_year'),
            'to_month' => $request->input('to_month'),
            'to_day' => $request->input('to_day'),
            'page' => $request->input('page')
        );

        return view('posts.index',[
            'posts' => $posts,
            'params' => $params
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $post = new Post();
        return view('posts.create',compact('post'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ValidationCheck $request)
    {
        $post = Post::create($request->all());
        $post->save();

        $request->session()->flash('message','記事の登録が完了しました。');

        return redirect()->route('posts.show',[$post->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $comment = new Comment();
        $comments = $comment->where('post_id',$post->id)->get();
        $id = $comments->max('comment_id') + 1;
        $user = \Auth::user();

        // 戻り先の調整
        $back_url = url()->previous();
        if(preg_match('/posts\/[0-9]/',$back_url) || preg_match('/edit/',$back_url)) {
            $back_url = '/posts';
        }

        return view('posts.show',[
            'posts' => $post,
            'comments' => $comments,
            'user' => $user,
            'id' => $id,
            'back_url' => $back_url
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('posts.edit',compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(ValidationCheck $request, Post $post)
    {
        $post->update($request->all());

        $request->session()->flash('message','記事の編集が完了しました。');

        return redirect()->route('posts.show',[$post->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Post $post)
    {
        $post->delete();
        $comment = new Comment();
        $comment->where('post_id',$post->id)->delete();

        return redirect()->route('posts.index',[
            'keyword'=>$request->input('keyword'),
            'from_year'=>$request->input('from_year'),
            'from_month'=>$request->input('from_month'),
            'from_day'=>$request->input('from_day'),
            'to_year'=>$request->input('to_year'),
            'to_month'=>$request->input('to_month'),
            'to_day'=>$request->input('to_day'),
            'page'=>$request->input('page')
        ])->with('message','記事および関連コメントの削除が完了しました。');
    }

    /**
     * Comment add.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function comment(Request $request)
    {
        $comment = Comment::create($request->all());
        $comment->save();

        $request->session()->flash('message','コメントの登録が完了しました。');

        return redirect()->route('posts.show',[$comment->post_id]);
    }
}
