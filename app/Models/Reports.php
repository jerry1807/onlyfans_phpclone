<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reports extends Model
{

  protected $guarded = array();
	public $timestamps = false;

  public function user(){
    return $this->belongsTo('App\Models\User')->first();
  }

  public function userReported(){
    return $this->belongsTo('App\Models\User', 'report_id')->first();
  }

   public function updates(){
    return $this->belongsTo('App\Models\Updates', 'report_id')->first();
  }
}
