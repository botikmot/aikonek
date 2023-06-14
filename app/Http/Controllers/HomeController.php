<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user()->load('friend_requests_received.sender', 'friends', 'followers', 'friend_requests_sent.receiver');
        return view('home', compact('user'));
    }

    public function myprofile()
    {
        $user = auth()->user()->load('friend_requests_received.sender', 'friends', 'followers', 'friend_requests_sent.receiver');
       /*  dd($user); */
        return view('myprofile', compact('user'));
    }


    public function forms()
    {
        //$user = auth()->user()->load('friend_requests_received.sender', 'friends', 'followers', 'friend_requests_sent.receiver');
       /*  dd($user); */
        return view('forms');
    }

}
