<?php

class Vote extends Eloquent {
    protected $guarded = array();

    public static $rules = array(
		'fbid' => 'required',
		'item_id' => 'required'
	);
}