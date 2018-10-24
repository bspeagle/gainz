<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Auth;

class mineController extends Controller
{
    /**
     * Show a list of all of the application's users.
     *
     * @return Response
     */
    public function index()
    {
        //$users = DB::select('select * from users', [1]);

        $authUser = Auth::user();
        $authUser = json_decode($authUser);

        return view('mine')->with('authUser',$authUser);
    }
}