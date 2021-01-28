<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminSettings;
use App\Models\Notifications;
use App\Models\Comments;
use App\Models\Like;
use App\Models\Updates;
use App\Models\Reports;
use App\Models\Messages;
use App\Helper;
use Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use League\Glide\Responses\LaravelResponseFactory;
use League\Glide\ServerFactory;
use Image;
use Form;


class UpdatesController extends Controller
{

  public function __construct( AdminSettings $settings, Request $request)
  {
		$this->settings = $settings::first();
		$this->request = $request;
	}

  /**
	 * Create Update / Post
	 *
	 * @return Response
	 */
  public function create()
  {
    // PATHS
    $path      = config('path.images');
    $pathVideo = config('path.videos');
    $pathMusic = config('path.music');
    $pathFiles = config('path.files');
    $image  = '';
    $video  = '';
    $music  = '';

    $sizeAllowed = $this->settings->file_size_allowed * 1024;
    $dimensions = explode('x', $this->settings->min_width_height_image);

    $messages = array (
    'description.required' => trans('general.please_write_something'),
    '_description.required_if' => trans('general.please_write_something_2'),
    'description.min' => trans('validation.update_min_length'),
    'description.max' => trans('validation.update_max_length'),
    'photo.dimensions' => trans('general.validate_dimensions'),
    'photo.mimetypes' => trans('general.formats_available'),
    );

    if (Auth::user()->verified_id != 'yes') {
      return response()->json([
          'success' => false,
          'errors' => ['error' => trans('general.error_post_not_verified')],
      ]);
    }

    $input = $this->request->all();

    if (! $this->request->hasFile('photo') && ! $this->request->hasFile('zip')) {
      $urlVideo = Helper::getFirstUrl($input['description']);
      $videoUrl = Helper::videoUrl($urlVideo) ? true : false;
      $input['_description'] = $videoUrl ? str_replace($urlVideo, '', $input['description']) : $input['description'];
      $input['_isVideoEmbed'] = $videoUrl;
    }


    if ($this->request->hasFile('photo')) {

      $originalExtension = strtolower($this->request->file('photo')->getClientOriginalExtension());
      $getMimeType = $this->request->file('photo')->getMimeType();

      if ($originalExtension == 'mp3' && $getMimeType == 'application/octet-stream') {
        $audio = ',application/octet-stream';
      } else {
        $audio = null;
      }

      if ($originalExtension == 'mp4'
      || $originalExtension == 'mov'
      || $originalExtension == 'mp3'
      ) {
        $isImage = null;
    	} else {
        $isImage = '|dimensions:min_width='.$dimensions[0].'';
    	}
    } else {
      $isImage = null;
      $audio = null;
      $originalExtension = null;
    }

    $validator = Validator::make($input, [
      'photo'       => 'mimetypes:image/jpeg,image/gif,image/png,video/mp4,video/quicktime,audio/mpeg'.$audio.'|max:'.$this->settings->file_size_allowed.','.$isImage.'',
      'zip'         => 'mimes:zip|max:'.$this->settings->file_size_allowed.'',
      'description' => 'required|min:1|max:'.$this->settings->update_length.'',
      '_description' => 'required_if:_isVideoEmbed,==,1|min:1|max:'.$this->settings->update_length.'',
    ], $messages);

     if ($validator->fails()) {
          return response()->json([
              'success' => false,
              'errors' => $validator->getMessageBag()->toArray(),
          ]);
      } //<-- Validator

      // Upload File Zip
      if ($this->request->hasFile('zip')) {

        $fileZip         = $this->request->file('zip');
        $extension       = $fileZip->getClientOriginalExtension();
        $fileSizeZip     = Helper::formatBytes($fileZip->getSize(), 1);
        $originalNameZip = Helper::fileNameOriginal($fileZip->getClientOriginalName());
        $file            = strtolower(Auth::user()->id.time().Str::random(20).'.'.$extension);

        $fileZip->storePubliclyAs($pathFiles, $file);
        $zipFile = $file;

      }

      if ($this->request->hasFile('photo') && $isImage != null) {

        $photo       = $this->request->file('photo');
        $extension   = $photo->getClientOriginalExtension();
        $mimeType    = $photo->getMimeType();
        $widthHeight = getimagesize($photo);
        $file        = strtolower(Auth::user()->id.time().Str::random(20).'.'.$extension);
        $url         = ucfirst(Helper::urlToDomain(url('/')));

        set_time_limit(0);
        ini_set('memory_limit', '512M');

        if ($extension == 'gif' && $mimeType == 'image/gif') {
          $photo->storePubliclyAs($path, $file);

          $imgType = 'gif';
          $image = $file;
        } else {
          //=============== Image Large =================//
          $img = Image::make($photo);

          $width     = $img->width();
          $height    = $img->height();
          $max_width = $width < $height ? 800 : 1400;

         if ($width > $max_width) {
            $scale = $max_width;
          } else {
            $scale = $width;
          }

          // Calculate font size
          if ($width >= 400 && $width < 900) {
            $fontSize = 16;
          } elseif ($width >= 800 && $width < 1200) {
            $fontSize = 20;
          } elseif ($width >= 1200 && $width < 2000) {
            $fontSize = 24;
          } elseif ($width >= 2000) {
            $fontSize = 32;
          } else {
            $fontSize = 0;
          }

          if ($this->settings->watermark == 'on') {
            $imageResize = $img->orientate()->resize($scale, null, function ($constraint) {
              $constraint->aspectRatio();
              $constraint->upsize();
            })->text($url.'/'.auth()->user()->username, $img->width() - 20, $img->height() - 10, function($font)
                use ($fontSize) {
                $font->file(public_path('webfonts/arial.TTF'));
                $font->size($fontSize);
                $font->color('#eaeaea');
                $font->align('right');
                $font->valign('bottom');
            })->encode($extension);
          } else {
            $imageResize = $img->orientate()->resize($scale, null, function ($constraint) {
              $constraint->aspectRatio();
              $constraint->upsize();
            })->encode($extension);
          }

            // Storage Image
            Storage::put($path.$file, $imageResize, 'public');
            $image = $file;
          }

      }//<====== End Upload Image

      //<----------- UPLOAD VIDEO
      if ($this->request->hasFile('photo')
          && $isImage == null
          && $originalExtension == 'mp4'
          || $originalExtension == 'mov'
    ) {

        $extension = $this->request->file('photo')->getClientOriginalExtension();
        $file      = strtolower(Auth::user()->id.time().Str::random(20).'.'.$extension);
        set_time_limit(0);

        //======= Storage Video
        $this->request->file('photo')->storePubliclyAs($pathVideo, $file);
        $video = $file;

      }//<====== End UPLOAD VIDEO

      //<----------- UPLOAD MUSIC
      if ($this->request->hasFile('photo')
      && $isImage == null
      && $originalExtension == 'mp3'
    ) {

        $extension = $this->request->file('photo')->getClientOriginalExtension();
        $file      = strtolower(Auth::user()->id.time().Str::random(20).'.'.$extension);
        set_time_limit(0);

        //======= Storage Video
        $this->request->file('photo')->storePubliclyAs($pathMusic, $file);
        $music = $file;

      }//<====== End UPLOAD MUSIC

      //<===== Locked Content
      if ($this->request->locked) {
        $this->request->locked = 'yes';
      } else {
        $this->request->locked = 'no';
      }

      $sql               = new Updates;
      $sql->image        = $image;
      $sql->video        = $video;
      $sql->music        = $music;
      $sql->description  = trim(Helper::checkTextDb($this->request->description));
      $sql->user_id      = Auth::user()->id;
      $sql->date         = Carbon::now();
      $sql->token_id     = Str::random(150);
      $sql->locked       = $this->request->locked;
      $sql->img_type     = $imgType ?? '';
      $sql->file          = $zipFile ?? '';
      $sql->file_size     = $fileSizeZip ?? '';
      $sql->file_name     = $originalNameZip ?? '';
      $sql->video_embed   = $urlVideo ?? '';
      $sql->save();

      if ($sql->image != '') {

        if (isset($imgType) && $imgType == 'gif') {
          $urlImg =  Storage::url(config('path.images').$sql->image);
        } else {
          $urlImg =  url("files/storage", $sql->id).'/'.$sql->image;
        }

        $media = '<a href="'.$urlImg.'" data-group="gallery'.$sql->id.'" class="js-smartPhoto">
        <img style="display: inline-block; width: 100%" src="'.$urlImg.'?w=100&h=100" data-src="'.$urlImg.'?w=650&h=650" class="img-fluid lazyload"></a>';
      } elseif ($sql->video != '') {
        $media = '<video id="video-'.$sql->id.'" class="js-player" controls>
          <source src="'.Storage::url(config('path.videos').$sql->video).'" type="video/mp4" />
        </video>';
      } elseif ($sql->music != '') {
        $media = '<div class="mx-3 border rounded"><audio id="music-'.$sql->id.'" class="js-player" controls>
          <source src="'.Storage::url(config('path.music').$sql->music).'" type="audio/mp3">
          Your browser does not support the audio tag.
        </audio></div>';
      } elseif ($sql->file != '') {
        $media = '<a href="'.url('download/file', $sql->id).'" class="d-block text-decoration-none">
    			<div class="card mb-3 mx-3">
    			  <div class="row no-gutters">
    			    <div class="col-md-2 text-center bg-primary">
    			      <i class="far fa-file-archive m-4 text-white" style="font-size: 48px;"></i>
    			    </div>
    			    <div class="col-md-10">
    			      <div class="card-body">
    			        <h5 class="card-title text-primary text-truncate mb-0">
    								'.$sql->file_name.'.zip
    							</h5>
    			        <p class="card-text">
    								<small class="text-muted">'.$sql->file_size.'</small>
    							</p>
    			      </div>
    			    </div>
    			  </div>
    			</div>
    			</a>';
      }
      else {
        $media = '';
      }

      $videoEmbed = '';

      if ($sql->video_embed != '' && in_array(Helper::videoUrl($sql->video_embed), array('youtube.com','www.youtube.com','youtu.be','www.youtu.be'))) {
        $videoEmbed = '<div class="embed-responsive embed-responsive-16by9 mb-2">
    			<iframe class="embed-responsive-item" height="360" src="https://www.youtube.com/embed/'.Helper::getYoutubeId($sql->video_embed).'" allowfullscreen></iframe>
    		</div>';
      }

      if ($sql->video_embed != '' && in_array(Helper::videoUrl($sql->video_embed), array('vimeo.com','player.vimeo.com'))) {
        $videoEmbed = '<div class="embed-responsive embed-responsive-16by9">
    			<iframe class="embed-responsive-item" src="https://player.vimeo.com/video/'.Helper::getVimeoId($sql->video_embed).'" allowfullscreen></iframe>
    		</div>';
      }

      if (Auth::user()->verified_id == 'yes') {
        $verify = '<small class="verified" title="'.trans('general.verified_account').'"data-toggle="tooltip" data-placement="top">
          <i class="feather icon-check-circle"></i>
        </small>';
      } else {
        $verify = '';
      }

      if ($sql->locked == 'yes')
				$locked = '<small class="text-muted" title="'.trans('users.content_locked').'"><i class="feather icon-lock"></i></small>';
			else {
        $locked = null;
      }

      $data = '<div class="card mb-3 card-updates" data="'.$sql->id.'">
      	<div class="card-body">
        <div class="pinned_post text-muted small w-100 mb-2 display-none">
    			<i class="fa fa-thumbtack mr-2"></i> '.trans('general.pinned_post').'
    		</div>
      	<div class="media">
      		<span class="rounded-circle mr-3">
          <a href="'.url(Auth::user()->username).'">
      				<img src="'.Storage::url(config('path.avatar').auth()->user()->avatar).'" class="rounded-circle avatarUser" width="60" height="60">
              </a>
      		</span>
      		<div class="media-body">
      				<h5 class="mb-0 font-montserrat">
              <a href="'.url(Auth::user()->username).'">
              '.Auth::user()->name.'
              </a>
              '.$verify.'
              <small class="text-muted">@'.Auth::user()->username.'</small>
              <a href="javascript:void(0);" class="text-muted float-right" id="dropdown_options" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        				<i class="fa fa-ellipsis-h"></i>
        			</a>
              <!-- Target -->
      				<button class="d-none copy-url" id="url'.$sql->id.'" data-clipboard-text="'.url(Auth::user()->username.'/post', $sql->id).'">'.trans('general.copy_link').'</button>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown_options">
              <a class="dropdown-item" href="'.url(Auth::user()->username.'/post', $sql->id).'">'.trans('general.go_to_post').'</a>
              <a class="dropdown-item pin-post" href="javascript:void(0);" data-id="'.$sql->id.'">'.trans('general.pin_to_your_profile').'</a>
              <button class="dropdown-item" onclick="$(\'#url'.$sql->id.'\').trigger(\'click\')">'.trans('general.copy_link').'</button>
                <a class="dropdown-item" href="'.url('update/edit',$sql->id).'">'.trans('general.edit_post').'</a>
                <form method="POST" action="'.url('update/delete',$sql->id).'" accept-charset="UTF-8" class="d-inline">
                  <input name="_token" type="hidden" value="'.$this->request->_token.'">
                  <button class="dropdown-item actionDelete" type="button">'.trans('general.delete_post').'</button>
                  </form>
              </div>
              </h5>
      				<small class="timeAgo text-muted" data="'.date('c', time()).'"></small> '.$locked.'
      		</div><!-- media body -->
      	</div><!-- media -->
      </div><!-- card body -->
      <div class="card-body pt-0">
        <p class="mb-0 update-text position-relative">'.Helper::linkText(Helper::checkText(str_replace($sql->video_embed, '', $sql->description))).'</p>
      </div>
      <div class="btn-block">'.$media.$videoEmbed.'</div>
      <div class="card-footer bg-white border-top-0">
      <h4>
  			<a href="javascript:void(0);" class="btnLike likeButton text-muted mr-2" data-id="'.$sql->id.'">
  				<i class="far fa-heart"></i> <small><strong class="countLikes">0</strong></small>
  			</a>
  			<span class="text-muted mr-2">
  				<i class="far fa-comment"></i> <small class="font-weight-bold totalComments">0</small>
  			</span>
        <a href="javascript:void(0);" class="text-muted float-right btnBookmark" data-id="'.$sql->id.'">
  				<i class="far fa-bookmark"></i>
  			</a>
  		</h4>
      <div class="container-media"></div>
      <hr />
      <div class="alert alert-danger alert-small dangerAlertComments" style="display:none;">
  			<ul class="list-unstyled m-0 showErrorsComments"></ul>
  		</div><!-- Alert -->
  		<div class="media">
  			<span href="#" class="float-left">
  				<img alt="" src="'.Storage::url(config('path.avatar').auth()->user()->avatar).'" class="rounded-circle mr-1 avatarUser" width="40">
  			</span>
        <div class="media-body">
        <form action="'. url('comment/store').'" method="post" class="comments-form">
        '.csrf_field().'
        <input type="hidden" name="update_id" value="'.$sql->id.'" />
  				<input type="text" name="comment" autocomplete="off" class="form-control comments border-0" placeholder="'.trans('general.write_comment').'"></div>
          </form>
  			</div>
    </div><!-- card footer -->
  </div><!-- card -->';

    $user = Auth::user();
    $user->post_locked = $this->request->locked;
    $user->save();

      return response()->json([
              'success' => true,
              'data' => $data,
              'total' => Auth::user()->updates()->count(),
            ]);

  }//<---- End Method

  public function ajaxUpdates()
  {
    $id = $this->request->input('id');
    $skip = $this->request->input('skip');
    $total = $this->request->input('total');
    $media = $this->request->input('media');
    $mediaArray = ['photos', 'videos', 'audio', 'files'];

    $user = User::findOrFail($id);

    if (isset($media) && ! in_array($media, $mediaArray)) {
      abort(500);
    }

    $page = $this->request->input('page');

    if (isset($media)) {
      $query = $user->updates();
    } else {
      $query = $user->updates()->whereFixedPost('0');
    }

    //=== Photos
    $query->when($this->request->input('media') == 'photos', function($q) {
      $q->where('image', '<>', '');
    });

    //=== Videos
    $query->when($this->request->input('media') == 'videos', function($q) use($user) {
      $q->where('video', '<>', '')->orWhere('video_embed', '<>', '')->whereUserId($user->id);
    });

    //=== Audio
    $query->when($this->request->input('media') == 'audio', function($q) {
      $q->where('music', '<>', '');
    });

    //=== Files
    $query->when($this->request->input('media') == 'files', function($q) {
      $q->where('file', '<>', '');
    });

    $data = $query->orderBy('id','desc')->skip($skip)->take($this->settings->number_posts_show)->get();

    $counterPosts = ($total - $this->settings->number_posts_show - $skip);

    return view('includes.updates',
        ['updates' => $data,
        'ajaxRequest' => true,
        'counterPosts' => $counterPosts,
        'total' => $total
        ])->render();

  }//<--- End Method

  public function edit($id)
  {
    $data = Auth::user()->updates()->findOrFail($id);

    return view('users.edit-update')->withData($data);
  }

  public function postEdit()
  {
    $id  = $this->request->input('id');
    $sql = Auth::user()->updates()->findOrFail($id);
    $image = $sql->image;
    $video  = $sql->video;
    $music  = $sql->music;
    $zipFile = $sql->file;

    // PATHS
    $path      = config('path.images');
    $pathVideo = config('path.videos');
    $pathMusic = config('path.music');
    $pathFile = config('path.files');


    $sizeAllowed = $this->settings->file_size_allowed * 1024;
    $dimensions = explode('x',$this->settings->min_width_height_image);

    $messages = array(
    'description.required' => trans('general.please_write_something'),
    '_description.required_if' => trans('general.please_write_something_2'),
    'description.min' => trans('validation.update_min_length'),
    'description.max' => trans('validation.update_max_length'),
    'photo.dimensions' => trans('general.validate_dimensions'),
    );

    $input = $this->request->all();

    if (! $this->request->hasFile('photo') && ! $this->request->hasFile('zip')) {
      $urlVideo = Helper::getFirstUrl($input['description']);
      $videoUrl = Helper::videoUrl($urlVideo) ? true : false;
      $input['_description'] = $videoUrl ? str_replace($urlVideo, '', $input['description']) : $input['description'];
      $input['_isVideoEmbed'] = $videoUrl;
    }

    // return $videoUrl;

    if ($this->request->hasFile('photo')) {

      $originalExtension = strtolower($this->request->file('photo')->getClientOriginalExtension());
      $getMimeType = $this->request->file('photo')->getMimeType();

      if ($originalExtension == 'mp3' && $getMimeType == 'application/octet-stream') {
        $audio = ',application/octet-stream';
      } else {
        $audio = null;
      }

      if ($originalExtension == 'mp4'
      || $originalExtension == 'mov'
      || $originalExtension == 'mp3'
      ) {
        $isImage = null;
    	} else {
        $isImage = '|dimensions:min_width='.$dimensions[0].'';
    	}
    } else {
      $isImage = '';
      $audio = null;
      $originalExtension = null;
    }

    $validator = Validator::make($input, [
      'photo'        => 'mimetypes:image/jpeg,image/gif,image/png,video/mp4,video/quicktime,audio/mpeg'.$audio.'|max:'.$this->settings->file_size_allowed.','.$isImage.'',
      'description'  => 'required|min:1|max:'.$this->settings->update_length.'',
      '_description' => 'required_if:_isVideoEmbed,==,1|min:1|max:'.$this->settings->update_length.'',

    ],$messages);

    if ($validator->fails()) {
         return response()->json([
             'success' => false,
             'errors' => $validator->getMessageBag()->toArray(),
         ]);
     } //<-- Validator

             // Upload File Zip
       if ($this->request->hasFile('zip')) {

         $fileZip         = $this->request->file('zip');
         $extension       = $fileZip->getClientOriginalExtension();
         $fileSizeZip     = Helper::formatBytes($fileZip->getSize(), 1);
         $originalNameZip = Helper::fileNameOriginal($fileZip->getClientOriginalName());
         $file            = strtolower(Auth::user()->id.time().Str::random(20).'.'.$extension);

         $fileZip->storePubliclyAs($pathFiles, $file);

         //======== Delete Old Image if exists
         Storage::delete($path.$image);
         //======== Delete Old Music if exists
         Storage::delete($pathMusic.$music);
         //======== Delete Old Video if exists
         Storage::delete($pathVideo.$video);

         $image = '';
         $video = '';
         $music = '';
         $zipFile = $file;

       }

      if ($this->request->hasFile('photo') && $isImage != null) {

        $photo       = $this->request->file('photo');
        $extension   = $photo->getClientOriginalExtension();
        $mimeType    = $photo->getMimeType();
        $widthHeight = getimagesize($photo);
        $file        = strtolower(Auth::user()->id.time().Str::random(20).'.'.$extension);
        $url         = ucfirst(Helper::urlToDomain(url('/')));

        set_time_limit(0);
        ini_set('memory_limit', '512M');

        if ($extension == 'gif' && $mimeType == 'image/gif') {
          $photo->storePubliclyAs($path, $file);

          $imgType = 'gif';
          $image = $file;
        } else {
          //=============== Image Large =================//
          $img = Image::make($photo);

          $width     = $img->width();
          $height    = $img->height();
          $max_width = $width < $height ? 800 : 1400;

            if ($width > $max_width) {
            $scale = $max_width;
          } else {
            $scale = $width;
          }

          // Calculate font size
          if ($width >= 400 && $width < 900) {
            $fontSize = 16;
          } elseif ($width >= 800 && $width < 1200) {
            $fontSize = 20;
          } elseif ($width >= 1200 && $width < 2000) {
            $fontSize = 24;
          } elseif ($width >= 2000) {
            $fontSize = 32;
          } else {
            $fontSize = 0;
          }

          if ($this->settings->watermark == 'on') {
            $imageResize = $img->orientate()->resize($scale, null, function ($constraint) {
              $constraint->aspectRatio();
              $constraint->upsize();
            })->text($url.'/'.auth()->user()->username, $img->width() - 20, $img->height() - 10, function($font)
                use ($fontSize) {
                $font->file(public_path('webfonts/arial.TTF'));
                $font->size($fontSize);
                $font->color('#eaeaea');
                $font->align('right');
                $font->valign('bottom');
            })->encode($extension);
          } else {
            $imageResize = $img->orientate()->resize($scale, null, function ($constraint) {
              $constraint->aspectRatio();
              $constraint->upsize();
            })->encode($extension);
          }

            // Storage Image
            Storage::put($path.$file, $imageResize, 'public');
            //======== Delete Old Image if exists
            Storage::delete($path.$image);
            //======== Delete Old Music if exists
            Storage::delete($pathMusic.$music);
            //======== Delete Old Video if exists
            Storage::delete($pathVideo.$video);
            //======== Delete Old File if exists
            Storage::delete($pathFile.$zipFile);

            $video = '';
            $music = '';
            $zipFile = '';
            $image = $file;
          }

      }//<====== End UploadImage

      //<---------- UPLOAD NEW VIDEO
      if($this->request->hasFile('photo')
      && $isImage == null
      && $originalExtension == 'mp4'
      || $originalExtension == 'mov'
    ) {

      $extension = $this->request->file('photo')->getClientOriginalExtension();
      $file      = strtolower(Auth::user()->id.time().Str::random(20).'.'.$extension);
      set_time_limit(0);

          //======= Storage Video
          $this->request->file('photo')->storePubliclyAs($pathVideo, $file);

          //======== Delete Old Image if exists
          Storage::delete($path.$image);
          //======== Delete Old Music if exists
          Storage::delete($pathMusic.$music);
          //======== Delete Old Video if exists
          Storage::delete($pathVideo.$video);
          //======== Delete Old File if exists
          Storage::delete($pathFile.$zipFile);

          $image = '';
          $music = '';
          $zipFile = '';
          $video = $file;

      }//<====== End UPLOAD NEW VIDEO

      //<---------- UPLOAD NEW MUSIC
      if ($this->request->hasFile('photo')
      && $isImage == null
      && $originalExtension == 'mp3'
    ) {

      $extension = $this->request->file('photo')->getClientOriginalExtension();
      $file      = strtolower(Auth::user()->id.time().Str::random(20).'.'.$extension);
      set_time_limit(0);

          //======= Storage Video
          $this->request->file('photo')->storePubliclyAs($pathMusic, $file);

          //======== Delete Old Image if exists
          Storage::delete($path.$image);
          //======== Delete Old Music if exists
          Storage::delete($pathMusic.$music);
          //======== Delete Old Video if exists
          Storage::delete($pathVideo.$video);
          //======== Delete Old File if exists
          Storage::delete($pathFile.$zipFile);

          $image = '';
          $video = '';
          $zipFile = '';
          $music = $file;

      }//<====== End UPLOAD NEW MUSIC

      //<===== Locked Content
      if($this->request->locked){
        $this->request->locked = 'yes';
      } else{
        $this->request->locked = 'no';
      }

      $sql->image        = $image;
      $sql->video        = $video;
      $sql->music        = $music;
      $sql->description  = trim(Helper::checkTextDb($this->request->description));
      $sql->user_id      = Auth::user()->id;
      $sql->token_id     = Str::random(150);
      $sql->locked       = $this->request->locked;
      $sql->img_type     = $imgType ?? '';
      $sql->file          = $zipFile ?? '';
      $sql->file_size     = $fileSizeZip ?? '';
      $sql->file_name     = $originalNameZip ?? '';
      $sql->video_embed   = $urlVideo ?? '';
      $sql->save();

      return response()->json([
              'success' => true,
            ]);

  }//<---- End Method

  public function delete($id)
  {
    if (! $this->request->expectsJson()) {
        abort(404);
    }

	  $update = Auth::user()->updates()->findOrFail($id);
    $path   = config('path.images');
    $file   = $update->image;
    $pathVideo   = config('path.videos');
    $fileVideo   = $update->video;
    $pathMusic   = config('path.music');
    $fileMusic   = $update->music;
    $pathFile   = config('path.files');
    $fileZip    = $update->file;

    // Image
    Storage::delete($path.$file);
    // Video
    Storage::delete($pathVideo.$fileVideo);
    // Music
    Storage::delete($pathMusic.$fileMusic);
    // File
    Storage::delete($pathFile.$fileZip);

      // Delete Reports
  		$reports = Reports::where('report_id', $id)->where('type','update')->get();

  		if (isset($reports)) {
  			foreach($reports as $report) {
  				$report->delete();
  			}
  		}

      // Delete Notifications
      Notifications::where('target', $id)
  			->where('type', '2')
  			->orWhere('target', $id)
  			->where('type', '3')
  			->delete();

        // Delete Comments
        $update->comments()->delete();

        // Delete likes
        Like::where('updates_id', $id)->delete();

        // Delete Update
        $update->delete();

        if ($this->request->inPostDetail && $this->request->inPostDetail == 'true') {
          return response()->json([
                  'success' => true,
                  'inPostDetail' => true,
                  'url_return' => url(Auth::user()->username)
                ]);
        } else {
          return response()->json([
                  'success' => true
                ]);
        }

	}//<--- End Method

  public function report(Request $request)
  {

		$data = Reports::firstOrNew(['user_id' => Auth::user()->id,'report_id' => $request->id]);

		if($data->exists){
			\Session::flash('noty_error','error');
			return redirect()->back();
		} else{
			$data->type = 'update';
      $data->reason = $request->reason;
			$data->save();

		  \Session::flash('noty_success','success');
			return redirect()->back();
		}
	}//<--- End Method

  public function image($id, $path)
	{
			try {

				$server = ServerFactory::create([
            'response' => new LaravelResponseFactory(app('request')),
            'source' => Storage::disk()->getDriver(),
            'cache' => Storage::disk()->getDriver(),
						'source_path_prefix' => '/uploads/updates/images/',
            'cache_path_prefix' => '.cache',
            'base_url' => '/uploads/updates/images/',
        ]);

        $response = Updates::findOrFail($id);

        if (Auth::check() && Auth::user()->id == $response->user()->id
    		|| Auth::check() && $response->locked == 'yes'
    		&& Auth::user()->userSubscriptions()
    			->where('stripe_id', '=', '')
    				->whereDate('ends_at', '>=', Carbon::today())
    					->where('stripe_plan', $response->user()->plan)

    						->orWhere('stripe_status', 'active')
    							->where('stripe_plan', $response->user()->plan)
    								->where('stripe_id', '<>', '')
    									->whereUserId(Auth::user()->id)

    									->orWhere('stripe_id', '=', '')
    										->where('stripe_plan', $response->user()->plan)
    										->where('free', '=', 'yes')
    											->whereUserId(Auth::user()->id)
    			->count() != 0
    		|| Auth::check() && Auth::user()->role == 'admin' && Auth::user()->permission == 'all'
    		|| $response->locked == 'no'
      ) {
        $server->outputImage($path, $this->request->all());
      } else {
        abort(404);
      }

				$server->deleteCache($path);

			} catch (\Exception $e) {

				abort(404);
				$server->deleteCache($path);
			}
    }//<--- End Method

    public function messagesImage($id, $path)
  	{
  			try {

  				$server = ServerFactory::create([
              'response' => new LaravelResponseFactory(app('request')),
              'source' => Storage::disk()->getDriver(),
              'cache' => Storage::disk()->getDriver(),
  						'source_path_prefix' => '/uploads/messages/',
              'cache_path_prefix' => '.cache',
              'base_url' => '/uploads/messages/',
          ]);

          $response = Messages::whereId($id)
              ->whereFromUserId(auth()->user()->id)
                ->orWhere('id', '=', $id)->where('to_user_id', '=', auth()->user()->id)
                 ->firstOrFail();

          $server->outputImage($path, $this->request->all());
  				$server->deleteCache($path);

  			} catch (\Exception $e) {

  				abort(404);
  				$server->deleteCache($path);
  			}
      }//<--- End Method

    public function pinPost(Request $request)
    {
      $findPost = Updates::whereId($request->id)->whereUserId(Auth::user()->id)->firstOrFail();
      $findCurrentPostPinned = Updates::whereUserId(Auth::user()->id)->whereFixedPost('1')->first();

      if ($findPost->fixed_post == '0') {
        $status = 'pin';
        $findPost->fixed_post = '1';
        $findPost->update();

        // Unpin old post
        if ($findCurrentPostPinned) {
          $findCurrentPostPinned->fixed_post = '0';
          $findCurrentPostPinned->update();
        }

      } else {
        $status = 'unpin';
        $findPost->fixed_post = '0';
        $findPost->update();
      }

      return response()->json([
              'success' => true,
              'status' => $status,
            ]);
    }

    // Bookmarks Ajax Pagination
    public function ajaxBookmarksUpdates()
    {
      $skip = $this->request->input('skip');
      $total = $this->request->input('total');

      $data = auth()->user()->bookmarks()->orderBy('bookmarks.id','desc')->skip($skip)->take($this->settings->number_posts_show)->get();
      $counterPosts = ($total - $this->settings->number_posts_show - $skip);

      return view('includes.updates', ['updates' => $data, 'ajaxRequest' => true, 'counterPosts' => $counterPosts, 'total' => $total])->render();
    }//<--- End Method

}
