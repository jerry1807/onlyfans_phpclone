<?php

namespace App\Http\Controllers\Traits;

use DB;
use App\Helper;
use App\Models\User;
use App\Models\AdminSettings;
use App\Models\Subscriptions;
use App\Models\Notifications;
use App\Models\Comments;
use App\Models\Like;
use App\Models\Updates;
use App\Models\Reports;
use App\Models\VerificationRequests;
use App\Models\PaymentGateways;
use App\Models\Conversations;
use App\Models\Messages;
use App\Models\Bookmarks;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


trait UserDelete {

	// START
	public function deleteUser($id)
	{
		$user     = User::findOrFail($id);
		$settings = AdminSettings::first();

		// Comments Delete
		$comments = Comments::where('user_id', $id)->get();

		if (isset($comments)) {
			foreach ($comments as $comment){
				$comment->delete();
			}
		}

		// Conversations Delete
		$conversations = Conversations::where('user_1',  $id)
				->orWhere('user_2', $id)
				->get();

		if (isset($conversations)) {
			foreach ($conversations as $conversation){
				$conversation->delete();
			}
		}

		// Likes
		$likes = Like::where('user_id', $id)->get();

		if (isset($likes)) {
			foreach ($likes as $like) {
				$like->delete();
			}
		}

		// Bookmarks
		$bookmarks = Bookmarks::where('user_id', $id)->get();

		if (isset($bookmarks)) {
			foreach ($bookmarks as $bookmark) {
				$bookmark->delete();
			}
		}

		// Messages Delete
		$path = config('path.messages');

		$messages = Messages::where('from_user_id', $id)
				->orWhere('to_user_id', $id)
				->get();

		if (isset($messages)) {
			foreach ($messages as $message) {
				Storage::delete($path.$message->file);
				$message->delete();
			}
		}

		// Delete Notification
		$notifications = Notifications::where('author', $id)
				->orWhere('destination', $id)
					->get();

		if (isset($notifications)) {
			foreach ($notifications as $notification) {
				$notification->delete();
			}
		}

		// Reports
		$reports = Reports::where('user_id', $id)
				->orWhere('type', 'user')
				->where('report_id', $id)
					->get();

		if (isset($reports)) {
			foreach ($reports as $report) {
				$report->delete();
			}
		}

		// Subscriptions User
		$subscriptions = Subscriptions::whereUserId($id)->get();
		$payment       = PaymentGateways::whereId(2)->whereName('Stripe')->whereEnabled(1)->first();


		if (isset($subscriptions) && isset($payment->key_secret)) {
			$stripe  = new \Stripe\StripeClient($payment->key_secret);

			foreach ($subscriptions as $subscription) {
				 if ($subscription->stripe_id == '') {
				 		$subscription->delete();
				 } else {
					 try {
						 $stripe->subscriptions->cancel($subscription->stripe_id, []);
					 } catch (\Exception $e) {

					 }

					 DB::table('subscription_items')->where('subscription_id', '=', $subscription->id)->delete();
					 $subscription->delete();
				 }
			}
		} // Isset Stripe

		// Subscriptions Creator
		$subscriptions = Subscriptions::whereStripePlan($user->plan)->get();
		if (isset($subscriptions)) {
			foreach ($subscriptions as $subscription) {
				 if ($subscription->stripe_id != '') {
					 $stripe->subscriptions->cancel($subscription->stripe_id, []);
					 DB::table('subscription_items')->where('subscription_id', '=', $subscription->id)->delete();
					 $subscription->delete();
				 }
			}
		}

		// Delete All Updates (Posts)
		$this->deleteUserUpdates($id);

		// VerificationRequests Delete
		$pathImage = config('path.verification');
		$verificationRequests = VerificationRequests::where('user_id', $id)->first();

		if (isset($verificationRequests)) {
			// Delete Image
			Storage::delete($pathImage.$verificationRequests->image);
			$verificationRequests->delete();
		}

		//<<<-- Delete Avatar -->>>/
		if ($user->avatar != $settings->avatar) {
			Storage::delete(config('path.avatar').$user->avatar);
		}

		//<<<-- Delete Cover -->>>/
		if ($user->cover != '') {
			Storage::delete(config('path.cover').$user->cover);
		}

		// User Delete
		$user->delete();

	}//<--- END METHOD

	protected function deleteUserUpdates($idUser)
	{
		$path      = config('path.images');
    $pathVideo = config('path.videos');
    $pathMusic = config('path.music');

		// Delete Updates
		$updates = Updates::where('user_id', $idUser)->get();

		if (isset($updates)) {
			foreach($updates as $update){

				// Delete Image
					Storage::delete($path.$update->image);

				// Delete VIDEO
					Storage::delete($pathVideo.$update->video);

				// Delete Music
					Storage::delete($pathMusic.$update->music);

				$update->delete();
			}
		}
	}// End Method

}// End Class
