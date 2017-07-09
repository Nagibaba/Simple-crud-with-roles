<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image as Image;
use Validator;

class Post extends Model
{
    protected $guarded = [];

    function user(){
    	return $this->belongsTo(User::class);
    }
    function sil(){
    	if($this->img_src):
    		static::unlinkImages();
    	endif;
    	$this->delete();
    }
    static function validate($r, $redirect = '/'){
    	$rules = [
        'title' => 'required|max:70',
        'img' => 'nullable|image',
        'content' => 'required|min:70|max:10000',
        ];

        $validator = Validator::make($r->all(), $rules);
        if ($validator->fails()) {
            return ['response'=>false,'validator'=>$validator];
        }
        return ['response'=>true];
    }
    static function createImages($image){
    	$ext = $image->getClientOriginalExtension();
        $imgName =  md5(microtime()).".".$ext;
        $thumbName =  't_'.md5(microtime()).".".$ext;
        $img = Image::make($image)->resize(400, 300)->save(str_replace('\\', '/', public_path()).'/images/'.$imgName);
        $thumb = Image::make($image)->resize(40, 30)->save(str_replace('\\', '/', public_path()).'/thumbs/'.$thumbName);
        // if(!$img or !$thumb) throw new Exception("Error Processing Request image", 1);
        return ['imgName'=>$imgName, 'thumbName'=>$thumbName];
    }
    function unlinkImages(){
    	if($this->img_src):
	    	@unlink(public_path().'/images/'.$this->img_src);
			@unlink(public_path().'/thumbs/'.$this->thumb);
			
    	endif;
    }
}
