<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Promotions;
use App\Services\FirebaseService;
use Exception;
use FirebaseNotificationService;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Exception\AuthException;
use Illuminate\Http\Request;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Messaging\RegistrationToken;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\MulticastMessage;
use Illuminate\Support\Facades\Http;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Contract\Messaging;
use Google\Client;

class DeviceTokenController extends Controller
{
    protected $firebaseService;
    protected $messaging;
    private $projectId;
    private $serviceAccountFile;

    public function __construct(FirebaseService $firebaseService, Messaging $messaging)
    {
        // FirebaseService $firebaseService
        $this->firebaseService = $firebaseService;
        $this->serviceAccountFile = storage_path('app/medonrider-e328c-firebase-adminsdk-zf60y-67e038ed77.json');
        $this->projectId = 'medonrider-e328c';
        $this->messaging = $messaging;
    }

    public function sendNotification(Request $request){
        $title = $request->title ?? env('APP_NAME','Medon');
        $body = $request->message;

        $deviceTokens = User::whereNotNull('device_token')->where('user_type','customer')->where('banned', 0)->pluck('device_token')->toArray();
       
        // $factory = (new Factory)->withServiceAccount(base_path(env('FIREBASE_SERVICE_ACCOUNT_PATH'))); // Path to the Firebase credentials JSON

        // // Initialize Firebase Messaging instance (not using create(), access it directly)
        // $messaging = $factory->createMessaging(); 

        // Create the notification object with the title and body
        $notification = Notification::create($title, $body);
      echo '<pre>';
        if(!empty($deviceTokens)){
            Promotions::create([
                'title' => $title,
                'message' => $body,
            ]);
            $chunkedTokens = array_chunk($deviceTokens, 490);

            $allFailedTokens = [];
            $message = CloudMessage::new()
                            ->withNotification($notification)
                            ->withData(['key' => 'value']);
            foreach ($chunkedTokens as $tokensChunk) {
                try {
                    // $response = $messaging->sendMulticast($message, $tokensChunk);
                    $report = $this->messaging->sendMulticast($message, $deviceTokens);
                } catch (\Exception $e) {
                    // continue;
                    print_r($e->getMessage());
                } catch (\Kreait\Firebase\Exception\MessagingException $e) {
                    // continue;
                    print_r($e->getMessage());
                } catch (\Kreait\Firebase\Exception\FirebaseException $e) {
                    // continue;
                    print_r($e->getMessage());
                } catch (\Exception $e) {
                    // continue;
                    print_r($e->getMessage());
                }
            }
            die;
            flash('Notification sent successfully')->success();
        }else{
            flash('Device tokens not found')->error();
        }
          
        return redirect()->route('notifications.list');
      
    }

    public function notifications(){
        $notifications = Promotions::orderBy('created_at','desc')->paginate(20);
        return view('backend.notifications', compact('notifications'));
    }

}
