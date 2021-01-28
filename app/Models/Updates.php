<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Updates extends Model {

	protected $guarded = array();
	public $timestamps = false;

	public function user() {
        return $this->belongsTo('App\Models\User')->first();
    }

		public function likes(){
			return $this->hasMany('App\Models\Like')->where('status', '1');
		}

		 public function comments(){
			return $this->hasMany('App\Models\Comments');
		}

		public function bookmarks(){
			return $this->belongsToMany('App\Model\User', 'bookmarks','updates_id','user_id');
		}
}
