<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Billable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPassword as ResetPasswordNotification;
use App\Models\Notifications;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable, Billable;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'countries_id',
        'name',
        'email',
        'password',
        'avatar',
        'cover',
        'status',
        'role',
        'permission',
        'confirmation_code',
        'oauth_uid',
        'oauth_provider',
        'token',
        'story',
        'verified_id',
        'ip',
        'language'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function userSubscriptions()
    {
          return $this->hasMany('App\Models\Subscriptions');
      }

    public function mySubscriptions()
    {
          return $this->hasMany('App\Models\Subscriptions', 'stripe_plan', 'plan');
      }

    public function myPayments()
    {
          return $this->hasMany('App\Models\Transactions');
      }

    public function myPaymentsReceived()
    {
          return $this->hasMany('App\Models\Transactions', 'subscribed')->where('approved', '<>', '0');
      }

    public function updates()
    {
          return $this->hasMany('App\Models\Updates');
      }

      public function withdrawals()
      {
        return $this->hasMany('App\Models\Withdrawals');
    }

  	public function country()
    {
          return $this->belongsTo('App\Models\Countries', 'countries_id')->first();
      }

      public function notifications()
      {
            return $this->hasMany('App\Models\Notifications', 'destination');
        }

      public function messagesInbox()
      {
            return $this->hasMany('App\Models\Messages', 'to_user_id')->where('status','new')->count();
        }

      public function comments()
      {
            return $this->hasMany('App\Models\Comments');
        }

      public function likes()
      {
        return $this->hasMany('App\Models\Like');
      }

      public function category()
      {
        return $this->belongsTo('App\Models\Categories', 'categories_id');
      }

      public function verificationRequests()
      {
            return $this->hasMany('App\Models\VerificationRequests')->whereStatus('pending')->count();
        }

      public static function notificationsCount()
      {
        // Notifications Count
      	$notifications_count = auth()->user()->notifications()->where('status', '0')->count();
        // Messages
      	$messages_count = auth()->user()->messagesInbox();

        if( $messages_count != 0 &&  $notifications_count != 0 ) {
          $totalNotifications = ( $messages_count + $notifications_count );
        } else if( $messages_count == 0 &&  $notifications_count != 0  ) {
          $totalNotifications = $notifications_count;
        } else if ( $messages_count != 0 &&  $notifications_count == 0 ) {
          $totalNotifications = $messages_count;
        } else {
          $totalNotifications = null;
        }

       return $totalNotifications;
    }

      function getFirstNameAttribute()
      {
        $name = explode(' ', $this->name);
        return $name[0];
      }

      public function bookmarks()
      {
        return $this->belongsToMany('App\Models\Updates', 'bookmarks','user_id','updates_id');
      }
}
