<div class="col-md-6 col-lg-3 mb-3">

<button type="button" class="btn btn-primary btn-block mb-2 d-lg-none" type="button" data-toggle="collapse" data-target="#navbarUserHome" aria-controls="navbarCollapse" aria-expanded="false">
		<i class="fa fa-bars myicon-right"></i> {{trans('general.menu')}}
	</button>

	<div class="navbar-collapse collapse d-lg-block" id="navbarUserHome">
	<div class="card shadow-sm">
			<div class="list-group list-group-sm list-group-flush">

					<a href="{{url(Auth::user()->username)}}" class="list-group-item list-group-item-action d-flex justify-content-between">
							<div>
									<i class="far fa-user-circle mr-2"></i>
									<span>{{trans('general.my_page')}}</span>
							</div>
							<div>
									<i class="fas fa-angle-right"></i>
							</div>
					</a>
					<a href="{{url('dashboard')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if(request()->is('dashboard')) active @endif">
							<div>
									<i class="fa fa-tachometer-alt mr-2"></i>
									<span>{{trans('admin.dashboard')}}</span>
							</div>
							<div>
									<i class="fas fa-angle-right"></i>
							</div>
					</a>
					<a href="{{url('settings/page')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if(request()->is('settings/page')) active @endif">
							<div>
									<i class="fa fa-pencil-alt mr-2"></i>
									<span>{{trans('general.edit_my_page')}}</span>
							</div>
							<div>
									<i class="fas fa-angle-right"></i>
							</div>
					</a>
					<a href="{{url('messages')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if(request()->is('messages') || request()->is('messages/*')) active @endif">
							<div>
									<i class="far fa-envelope mr-2"></i>
									<span>{{trans('general.messages')}}</span>
							</div>
							<div>
									<i class="fas fa-angle-right"></i>
							</div>
					</a>
					<a href="{{url('settings/verify/account')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if(request()->is('settings/verify/account')) active @endif">
							<div>
									<i class="far fa-check-circle mr-2"></i>
									<span>{{trans('general.verify_account')}}</span>
							</div>
							<div>
									<i class="fas fa-angle-right"></i>
							</div>
					</a>
					<a href="{{url('notifications')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if(request()->is('notifications')) active @endif">
							<div>
									<i class="far fa-bell mr-2"></i>
									<span>{{trans('users.notifications')}}</span>
							</div>
							<div>
									<i class="fas fa-angle-right"></i>
							</div>
					</a>
					<a href="{{url('settings/password')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if(request()->is('settings/password')) active @endif">
							<div>
									<i class="fa fa-key mr-2"></i>
									<span>{{trans('auth.password')}}</span>
							</div>
							<div>
									<i class="fas fa-angle-right"></i>
							</div>
					</a>

					<a href="{{url('my/subscribers')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if(request()->is('my/subscribers')) active @endif">
							<div>
									<i class="fas fa-users mr-2"></i>
									<span>{{trans('users.my_subscribers')}}</span>
							</div>
							<div>
									<i class="fas fa-angle-right"></i>
							</div>
					</a>

					<a href="{{url('my/subscriptions')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if(request()->is('my/subscriptions')) active @endif">
							<div>
									<i class="fas fa-user-friends mr-2"></i>
									<span>{{trans('users.my_subscriptions')}}</span>
							</div>
							<div>
									<i class="fas fa-angle-right"></i>
							</div>
					</a>

					<a href="{{url('my/payments')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if(request()->is('my/payments') || request()->is('my/payments/received')) active @endif">
							<div>
									<i class="fas fa-file-invoice-dollar mr-2"></i>
									<span>{{trans('general.payments')}}</span>
							</div>
							<div>
									<i class="fas fa-angle-right"></i>
							</div>
					</a>

					<a href="{{url('settings/payout/method')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if(request()->is('settings/payout/method')) active @endif">
							<div>
									<i class="far fa-credit-card mr-2"></i>
									<span>{{trans('users.payout_method')}}</span>
							</div>
							<div>
									<i class="fas fa-angle-right"></i>
							</div>
					</a>

					<a href="{{url('settings/withdrawals')}}" class="list-group-item list-group-item-action d-flex justify-content-between @if(request()->is('settings/withdrawals')) active @endif">
							<div>
									<i class="fa fa-university mr-2"></i>
									<span>{{trans('general.withdrawals')}}</span>
							</div>
							<div>
									<i class="fas fa-angle-right"></i>
							</div>
					</a>
			</div>
	</div>
</div><!-- End Card -->
</div><!-- navbarUserHome -->
