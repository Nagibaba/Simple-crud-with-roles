<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Rule;

class User extends Authenticatable
{
    use Notifiable;

    // static $authIsAdmin = auth()->check() && auth()->user()->isAdmin()?true:false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    function setPasswordAttribute($value){
        $this->attributes['password'] = bcrypt($value);
    }
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function isUser()
    {
        return $this->role=='user';
    }
    public function isEditor()
    {
        return $this->role=='editor';
    }
    public function isAdmin()
    {
        return $this->role=='admin';
    }
    function canEffectPosts(){
        return $this->isEditor() || $this->isAdmin();
    }
    function canEffectUsers(){
        return $this->isAdmin();
    }
    static function validate($r,$creatingUser=true){
        $rules = [
        'name' => 'required|max:70',
        'email' => 'required|email|max:100|unique:users,email,'.$r->userId,
        'role' => 'required|in:user,editor,admin',
        ];
        $validator = Validator::make($r->all(), $rules);
        $validator->sometimes('password', 'required|confirmed|min:6|max:100', function()  use($creatingUser) {
            return $creatingUser;
        });
        if ($validator->fails()) {
            return ['response'=>false,'validator'=>$validator];
        }
        return ['response'=>true];
    }
    function sil(){
        if($this->has('posts')):
            foreach ($this->posts() as $post) {
                $post->sil();
            }
        endif;
        $this->delete();
    }
}
