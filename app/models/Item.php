<?php

class Item extends Eloquent {
    protected $guarded = array();
	
	 public function votes()
    {
        return $this->hasMany('Vote');
    }
	
    public static $rules = array(
		'name' => 'required',
		'body' => 'required',
		'image' => 'required',
		'author' => 'required',
		'captcha' => array('required', 'captcha'),
	);
}