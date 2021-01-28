@extends('layouts.app')

@section('title') {{trans('general.edit_my_page')}} -@endsection

@section('content')
<section class="section section-sm">
    <div class="container">
      <div class="row justify-content-center text-center mb-sm">
        <div class="col-lg-8 py-5">
          <h2 class="mb-0 font-montserrat"><i class="fa fa-pencil-alt mr-2"></i> {{trans('general.edit_my_page')}}</h2>
          <p class="lead text-muted mt-0">{{trans('users.settings_page_desc')}}</p>
        </div>
      </div>
      <div class="row">

        @include('includes.cards-settings')

        <div class="col-md-6 col-lg-9 mb-5 mb-lg-0">

          @if (session('status'))
                  <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                			<span aria-hidden="true">×</span>
                			</button>

                    {{ session('status') }}
                  </div>
                @endif

          @include('errors.errors-forms')

          @if (Auth::user()->verified_id == 'no')
          <div class="alert alert-danger mb-3">
             <ul class="list-unstyled m-0">
               <li><i class="fa fa-exclamation-triangle"></i> {{trans('general.verified_account_info')}} <a href="{{url('settings/verify/account')}}" class="text-white link-border">{{trans('general.verify_account')}}</a></li>
             </ul>
           </div>
           @endif

          <form method="POST" action="{{ url('settings/page') }}" id="formEditPage" accept-charset="UTF-8" enctype="multipart/form-data">

            @csrf

            <input type="hidden" id="featured_content" name="featured_content" value="{{Auth::user()->featured_content}}">

          <div class="form-group">
            <label>{{trans('auth.full_name')}}</label>
            <div class="input-group mb-4">
            <div class="input-group-prepend">
              <span class="input-group-text"><i class="far fa-user"></i></span>
            </div>
                <input class="form-control" name="full_name" required placeholder="{{trans('auth.full_name')}}" value="{{Auth::user()->name}}"  type="text">
            </div>
          </div><!-- End form-group -->

          <div class="form-group">
            <label>{{trans('auth.username')}}</label>
            <div class="input-group mb-4">
            <div class="input-group-prepend">
              <span class="input-group-text pr-0">{{url('/')}}/</span>
            </div>
                <input class="form-control" name="username" required placeholder="{{trans('auth.username')}}" value="{{Auth::user()->username}}"  type="text">
            </div>
          </div><!-- End form-group -->

          <div class="form-group">
                <input class="form-control" disabled value="{{Auth::user()->email}}" type="text">
            </div><!-- End form-group -->

            <div class="form-group">
                <div class="input-group mb-4">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user-tie"></i></span>
                  </div>
                  <input class="form-control" name="profession" placeholder="{{trans('users.profession_ocupation')}}" value="{{Auth::user()->profession}}" type="text">
                </div>
              </div><!-- End form-group -->

          <div class="form-group">
            <label>{{trans('users.subscription_price')}}</label>
            <div class="input-group mb-2">
            <div class="input-group-prepend">
              <span class="input-group-text">{{$settings->currency_symbol}}</span>
            </div>
                <input class="form-control form-control-lg" @if (Auth::user()->verified_id == 'no') disabled @endif name="price" placeholder="{{trans('users.subscription_price')}}" value="{{$settings->currency_code == 'JPY' ? round(Auth::user()->price) : Auth::user()->price}}"  type="text">
            </div>
            <small class="text-muted btn-block mb-4 d-none">{{trans('users.subscription_price_info')}}</small>
          </div><!-- End form-group -->

        <div class="row form-group mb-0">
            <div class="col-md-6">
                <div class="input-group mb-4">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-link"></i></span>
                  </div>
                  <input class="form-control" name="website" placeholder="{{trans('users.website')}}"  value="{{Auth::user()->website}}" type="text">
                </div>
              </div><!-- ./col-md-6 -->

              <div class="col-md-6" id="billing">
                <div class="input-group mb-4">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-lightbulb"></i></span>
                </div>
                <select required name="categories_id" class="form-control custom-select" >
                      @foreach (Categories::where('mode','on')->orderBy('name')->get() as $category)
                        <option @if (Auth::user()->categories_id == $category->id ) selected="selected" @endif value="{{$category->id}}">{{ $category->name }}</option>
                        @endforeach
                      </select>
                      </div>
                </div><!-- ./col-md-6 -->
              </div><!-- End Row Form Group -->

              <div class="row form-group mb-0">

                <div class="col-lg-12 py-2">
                  <small class="text-muted">-- {{trans('general.billing_information')}}</small>
                </div>

                <div class="col-lg-12">
                    <div class="input-group mb-4">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fa fa-building"></i></span>
                      </div>
                      <input class="form-control" name="company" placeholder="{{trans('general.company')}}"  value="{{Auth::user()->company}}" type="text">
                    </div>
                  </div><!-- ./col-md-6 -->

                <div class="col-md-6">
                  <div class="input-group mb-4">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-globe"></i></span>
                  </div>
                  <select name="countries_id" class="form-control custom-select">
                    <option value="">{{trans('general.select_your_country')}}</option>
                        @foreach(  Countries::orderBy('country_name')->get() as $country )
                          <option @if( Auth::user()->countries_id == $country->id ) selected="selected" @endif value="{{$country->id}}">{{ $country->country_name }}</option>
                          @endforeach
                        </select>
                        </div>
                  </div><!-- ./col-md-6 -->

                  <div class="col-md-6">
                      <div class="input-group mb-4">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fa fa-map-pin"></i></span>
                        </div>
                        <input class="form-control" name="city" placeholder="{{trans('general.city')}}"  value="{{Auth::user()->city}}" type="text">
                      </div>
                    </div><!-- ./col-md-6 -->

                    <div class="col-md-6">
                        <div class="input-group mb-4">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-map-marked-alt"></i></span>
                          </div>
                          <input class="form-control" name="address" placeholder="{{trans('general.address')}}"  value="{{Auth::user()->address}}" type="text">
                        </div>
                      </div><!-- ./col-md-6 -->

                      <div class="col-md-6">
                          <div class="input-group mb-4">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fa fa-map-marker-alt"></i></span>
                            </div>
                            <input class="form-control" name="zip" placeholder="{{trans('general.zip')}}"  value="{{Auth::user()->zip}}" type="text">
                          </div>
                        </div><!-- ./col-md-6 -->

              </div><!-- End Row Form Group -->

              <div class="row form-group mb-0">
                <div class="col-lg-12 py-2">
                  <small class="text-muted">-- {{trans('admin.profiles_social')}}</small>
                </div>

                  <div class="col-md-6">
                      <div class="input-group mb-4">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fab fa-facebook-f"></i></span>
                        </div>
                        <input class="form-control" name="facebook" placeholder="Facebook"  value="{{Auth::user()->facebook}}" type="text">
                      </div>
                    </div><!-- ./col-md-6 -->

                    <div class="col-md-6">
                        <div class="input-group mb-4">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                          </div>
                          <input class="form-control" name="twitter" placeholder="Twitter"  value="{{Auth::user()->twitter}}" type="text">
                        </div>
                      </div><!-- ./col-md-6 -->
                    </div><!-- End Row Form Group -->

                    <div class="row form-group mb-0">
                        <div class="col-md-6">
                            <div class="input-group mb-4">
                              <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                              </div>
                              <input class="form-control" name="instagram" placeholder="Instagram"  value="{{Auth::user()->instagram}}" type="text">
                            </div>
                          </div><!-- ./col-md-6 -->

                          <div class="col-md-6">
                              <div class="input-group mb-4">
                                <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                                </div>
                                <input class="form-control" name="youtube" placeholder="Youtube"  value="{{Auth::user()->youtube}}" type="text">
                              </div>
                            </div><!-- ./col-md-6 -->
                          </div><!-- End Row Form Group -->

                          <div class="row form-group mb-0">
                              <div class="col-md-6">
                                  <div class="input-group mb-4">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text"><i class="fab fa-pinterest-p"></i></span>
                                    </div>
                                    <input class="form-control" name="pinterest" placeholder="Pinterest"  value="{{Auth::user()->pinterest}}" type="text">
                                  </div>
                                </div><!-- ./col-md-6 -->

                                <div class="col-md-6">
                                    <div class="input-group mb-4">
                                      <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fab fa-github"></i></span>
                                      </div>
                                      <input class="form-control" name="github" placeholder="Github"  value="{{Auth::user()->github}}" type="text">
                                    </div>
                                  </div><!-- ./col-md-6 -->
                                </div><!-- End Row Form Group -->

                          <div class="form-group">
                            <label><i class="fa fa-bullhorn text-muted"></i> {{trans('users.your_story')}}</label>
                            <textarea name="story" id="story" required rows="5" cols="40" class="form-control">{{Auth::user()->story ? Auth::user()->story : old('story') }}</textarea>
                          <div id="the-count" class="float-right my-2">
                            <span id="current"></span>
                            <span id="maximum">/ {{$settings->story_length}}</span>
                          </div>
                          </div><!-- End Form Group -->

                          <button class="btn btn-1 btn-success btn-block" id="saveChanges" type="submit">{{trans('general.save_changes')}}</button>

                      @if (Auth::user()->id != 1)
                      <div class="text-center mt-3">
                        <a href="{{ url('account/delete') }}">{{ trans('general.delete_account') }}</a>
                      </div>
                    @endif
                  </form>
                </div><!-- end col-md-6 -->
              </div>
            </div>
  </section>
@endsection

@section('javascript')
<script type="text/javascript">
$('#current').html($('#story').val().length);
</script>
@endsection
