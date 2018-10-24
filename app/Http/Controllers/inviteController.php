<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Support\Facades\DB;
use Log;
use Illuminate\Http\Request;

class inviteController extends Controller
{
    public function startProcess($inviteUUID) {
        $iRecord = DB::table('userInvites')
        ->join('contests', 'userInvites.contestId', 'contests.id')
        ->where('userInvites.inviteUUID', '=', $inviteUUID)
        ->select('userInvites.*', 'contests.name')
        ->get();
            
        if((Auth::user()->email == $iRecord[0]->email) && $iRecord[0]->status == 2) {
            $view = 'invite.landing';
        }
        elseif ((Auth::user()->email == $iRecord[0]->email) && $iRecord[0]->status == 3) {
            $view = 'invite.alreadyAccepted';
        }
        elseif ((Auth::user()->email == $iRecord[0]->email) && $iRecord[0]->status == 1) {
            $view = 'invite.alreadyDeclined';
        }
        else {
            $view = 'errors.noAuth';
        }
            
        return view($view)->with('iRecord', $iRecord);
    }

    public function respondInvite() {
        if ($_POST['submitBtn'] == 'Accept') {
            $iRecord = DB::table('userInvites')
                ->where('inviteUUID', '=', $_POST['inviteUUID'])
                ->get();

            DB::table('contestUsers')
                ->join('userInvites', 'contestUsers.inviteId', 'userInvites.id')
                ->where('userInvites.email', '=', $iRecord[0]->email)
                ->update(['contestUsers.userId' => Auth::id(),
                    'contestUsers.updated_at' => date("Y-m-d H:i:s"),
                    'contestUsers.status' => 3]);

            DB::table('userInvites')
                ->where('inviteUUID', $_POST['inviteUUID'])
                ->update(['status' => 3, 'updated_at' => date("Y-m-d H:i:s")]);

            return redirect()->route('getContest', ['id' => $iRecord[0]->contestId]);
        }
        else if ($_POST['submitBtn'] == 'Decline') {
            DB::table('userInvites')
                ->where('inviteUUID', $_POST['inviteUUID'])
                ->update(['status' => 1, 'updated_at' => date("Y-m-d H:i:s")]);

            DB::table('contestUsers')
                ->join('userInvites', 'contestUsers.inviteId', 'userInvites.id')
                ->where('userInvites.email', '=', $iRecord[0]->email)
                ->update(['contestUsers.updated_at' => date("Y-m-d H:i:s"),
                    'contestUsers.status' => 1]);

            return view('invite.decline');
        }
        else {
            //do shady things...
        }
    }

    public function revokeInvite() {
        $userId = $_POST['rUserId'];
        $contestId = $_POST['contestId'];
        $inviteId = $_POST['rInviteId'];

        DB::table('userInvites')
            ->where('userInvites.Id', '=', $inviteId)
            ->delete();

        DB::table('contestUsers')
            ->where('contestUsers.contestId', '=', $contestId)
            ->where('contestUsers.id', '=', $userId)
            ->delete();

        return redirect()->route('manageUsers', ['id' => $_POST['contestId']]);
    }
}
