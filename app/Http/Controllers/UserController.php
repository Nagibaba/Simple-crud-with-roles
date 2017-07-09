<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct(){
        $this->middleware('CanEffectUsers');
    }
    public function index()
    {
        $users = User::latest()->paginate(30);
        return view('users.index', compact('users'));
    }
    public function store(Request $r)
    {

        $data = User::validate($r);
        if(!$data['response']) return redirect('users/create')
                               ->withErrors($data['validator'])
                               ->withInput();
        $user = User::create([
                'name' => $r->name,
                'email' => $r->email,
                'role' => $r->role,
                'password' => $r->password
                
            ]);
        return redirect('users/'.$user->id);
    }
    public function create()
    {
        return view('users.create');
    }
    public function show($id)
    {
        $user = User::findOrFail($id);
        $posts = $user->posts()->select(['id','thumb','title','user_id'])->latest()->paginate(30);
        
        if(\Request::ajax()) return $user;
        return view('posts.index',compact('user','posts'));
    }
    public function update(Request $r)
    {
        // return $r->file('img');
        $data = User::validate($r,false);
        if(!$data['response']) return redirect('users')
                               ->withErrors($data['validator'])
                               ->withInput();
        $array1 =[
            'name'=>$r->name,
            'email'=>$r->email,
            'role'=>$r->role,
        ];
        $array2 = [
            'password'=>$r->password
        ];
        if($r->password) $array1 = array_merge($array1,$array2);
        $user = User::find($r->userId)->update($array1);
        return redirect('users/'.$r->userId)->with('message','Changed Succesfully');

    }
    public function destroy(Request $r,$id)
    {
        $user = User::findOrFail($id);
        $user->sil();

        return view('home')->with('message','user deleted');
    }
    public function edit()
    {
        return 'edit';
    }
}
