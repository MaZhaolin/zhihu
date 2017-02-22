<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class notificationsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('notifications.index', compact('user'));
    }
}
