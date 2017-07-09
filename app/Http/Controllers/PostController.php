<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Intervention\Image\Facades\Image as Image;
// use Validator;


class PostController extends Controller
{
    function __construct(){
        $this->middleware('CanEffectPosts', ['except' => ['index','show']]);
    }
    public function index()
    {
    	$posts = Post::select(['id','thumb','title','user_id'])->latest()->paginate(30);
    	return view('posts.index', compact('posts'));
    }
    public function store(Request $r)
    {
        $user_id = auth()->user()->id;
        $data = Post::validate($r);
        if(!$data['response']) return redirect('posts/create')
                               ->withErrors($data['validator'])
                               ->withInput();
        $imgName = '';
        $thumbName = '';   
           
        if($r->file('img')):
            $images = Post::createImages($r->file('img'));
            extract($images);
        endif;
        $post = Post::create([
                'user_id' => $user_id,
                'title' => $r->title,
                'content' => $r->content,
                'img_src' => $imgName,
                'thumb' => $thumbName,
            ]);
        return redirect('posts/'.$post->id);
    }
    public function create()
    {
    	return view('posts.create');
    }
    public function show($id)
    {
    	$post = Post::findOrFail($id);
        if(\Request::ajax()) return $post;
        return view('posts.show',compact('post'));
    }
    public function update(Request $r)
    {
        // return $r->file('img');
        $post = Post::findOrFail($r->postId);
    	$data = Post::validate($r);
        if(!$data['response']) return redirect('posts')
                               ->withErrors($data['validator'])
                               ->withInput();
        
        $array1 = [
            'title' => $r->title,
            'content' => $r->content,
        ];
        if($r->file('img')):
            $post->unlinkImages();
            $images = Post::createImages($r->file('img'));
            extract($images);
            $array2 = [
                'img_src' => $imgName,
                'thumb' => $thumbName
            ];
            $array1 = array_merge($array1,$array2);
        endif;        
        $post = Post::find($r->postId)->update($array1);
        return redirect('posts/'.$r->postId)->with('message','Changed Succesfully');

    }
    public function destroy(Request $r,$id)
    {

    	$post = Post::findOrFail($id);
        $post->sil();

        return view('home')->with('message','Post deleted');
    }
    public function edit()
    {
    	return 'edit';
    }
}
