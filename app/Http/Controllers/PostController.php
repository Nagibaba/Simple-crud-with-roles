<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Intervention\Image\Facades\Image as Image;


class PostController extends Controller
{
    function __construct(){
        $this->middleware('auth', ['only' => ['store','create','update','destroy','edit']]);
    }
    public function index()
    {
    	$posts = Post::select(['id','thumb','title','user_id'])->latest()->paginate(30);
    	return view('posts.index', compact('posts'));
    }
    public function store(Request $r)
    {
        $user_id = auth()->user()->id;
        Post::validate($r, 'posts/create'); //validate and redirect to ... if fails
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
        $post = Post::findOrFail($r->postId);
        if(auth()->user()->id!=$post->user()->first()->id) abort(403);
    	Post::validate($r,'posts');
        $array1 = [
            'title' => $r->title,
            'content' => $r->content,
        ];
        if($r->file('img')):
            $post->unlinkImages();
            // $images = $this->createImages($r->file('img'));

            $ext = $r->img->getClientOriginalExtension();
            $imgName =  md5(microtime()).".".$ext;
            $thumbName =  't_'.md5(microtime()).".".$ext;
            $img = Image::make($r->img)->fit(400, 300)->save(str_replace('\\', '/', public_path()).'/images/'.$imgName);
            $thumb = Image::make($r->img)->fit(40, 30)->save(str_replace('\\', '/', public_path()).'/thumbs/'.$thumbName);
            if(!$img or !$thumb) throw new Exception("Error Processing Request image", 1);
            // $images = ['imgName'=>$imgName, 'thumbName'=>$thumbName];
            // // return $images;
            // extract($images);
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
        if(auth()->user()->id!=$post->user()->first()->id) abort(403);
        $post->sil();

        return view('home')->with('message','Post deleted');
    }
    public function edit()
    {
    	return Post::all();
    }
    protected function createImages($image){
        $ext = $image->getClientOriginalExtension();
        $imgName =  md5(microtime()).".".$ext;
        $thumbName =  't_'.md5(microtime()).".".$ext;
        $img = Image::make($image)->fit(400, 300)->save(str_replace('\\', '/', public_path()).'/images/'.$imgName);
        $thumb = Image::make($image)->fit(40, 30)->save(str_replace('\\', '/', public_path()).'/thumbs/'.$thumbName);
        if(!$img or !$thumb) throw new Exception("Error Processing Request image", 1);
        return ['imgName'=>$imgName, 'thumbName'=>$thumbName];
    }
}
