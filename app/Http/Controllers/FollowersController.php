<?php

namespace App\Http\Controllers;

use App\Notifications\NewUserFollowNotification;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Auth;

class FollowersController extends Controller
{
    protected $user;

    /**
     * FollowersController constructor.
     * @param $user
     */
    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    public function index($user)
    {
        $followers = Auth::guard('api')->user()->followers()
            ->where('followed_id', $user)
            ->count();
        return response()->json(['followed' => !! $followers]);
    }

    public function follow(Request $request)
    {
        $userToFollow = $this->user->byId($request->get('user'));
        $followed = Auth::guard('api')->user()->followThisUser($userToFollow);
        if(count($followed['attached']) > 0){
            $userToFollow->notify(new NewUserFollowNotification());
            $userToFollow->increment('followers_count');
            return response()->json(['followed' => true]);
        }
        $userToFollow->decrement('followers_count');
        return response()->json(['followed' => false]);
    }
}
