<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Support\Facades\DB;
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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get active contests for this user for mini layout
        $contests = DB::table('contestUsers')
            ->join('contests', 'contestUsers.contestId', '=', 'contests.id')
            ->select('contests.*')
            ->where('contestUsers.userId', '=', Auth::id())
            ->where('contests.endDt', '>=', date("Y-m-d H:i:s"))
            ->get();

        //get active invites for this user for messages layout
        $invites = DB::table('userInvites')
            ->join('contests', 'userInvites.contestId', 'contests.id')
            ->select('userInvites.inviteUUID', 'contests.name')
            ->where('userInvites.email', '=', Auth::user()->email)
            ->where('userInvites.status', '=', 2)
            ->get();

        return view('home')
            ->with('contests', $contests)
            ->with('invites', $invites);
    }
}
