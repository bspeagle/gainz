<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Support\Facades\DB;
use Mail;
use Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class contestUsersController extends Controller
{
    public function sendInvite($toEmail, $sender, $link) {
        Mail::send('emails.contestInvite', ['toEmail' => $toEmail, 'sender' => $sender, 'link' => $link], function ($m) use ($toEmail) {
            $m->from('me@bspeagle.com');

            $m->to($toEmail)->subject("Gainz. You're invited to compete!");
        });
    }

    public function inviteUser() {
        $inviteId = DB::table('userInvites')->insertGetId(
            ['email' => $_POST['email'],
            'status' => 2,
            'contestId' => $_POST['contestId'],
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")]
        );

        $cUserId = DB::table('contestUsers')->insertGetId(
            ['status' => 2,
            'contestId' => $_POST['contestId'],
            'userId' => 0,
            'inviteId' => $inviteId,
            'owner' => 0,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")]
        );

        $iRecord = DB::table('userInvites')
            ->where('userInvites.id', '=', $inviteId)
            ->get();

        //$jLink = 'http://gainz.us-east-1.elasticbeanstalk.com/invite/' . $iRecord[0]->inviteUUID;
        $jLink = 'http://localhost:8000/invite/' . $iRecord[0]->inviteUUID;

        $sender = Auth::user();
        $this->sendInvite($_POST['email'], $sender, $jLink);

        return redirect()->route('manageUsers', ['id' => $_POST['contestId']]);;
    }

    public function getUsers($contestId) {
        $uORecord = DB::table('contestUsers')
            ->where('contestId', '=', $contestId)
            ->where('owner', '=', true)
            ->get();

        if ($uORecord[0]->userId == Auth::id()) {
            $cUsers = DB::table('contestUsers')
                ->join('users', 'contestUsers.userId', '=', 'users.id')
                ->select('contestUsers.id', 'users.name', 'users.email', 'contestUsers.owner', 'contestUsers.status', 'contestUsers.inviteId')
                ->where('contestUsers.contestId', '=', $contestId);
            
            $iUsers = DB::table('contestUsers')
                ->join('userInvites', 'contestUsers.inviteId', 'userInvites.id')
                ->select('contestUsers.id', 'contestUsers.userId AS name', 'userInvites.email', 'contestUsers.owner', 'contestUsers.status', 'contestUsers.inviteId')
                ->where('contestUsers.contestId', '=', $contestId)
                ->where('contestUsers.status', '=', 2)
                ->union($cUsers)
                ->get();
    
            return view('manageUsers')
                ->with('cUsers', $iUsers)
                ->with('contestId', $contestId);
        }
        else {
            return view('errors.noAuth');
        }
    }

    public function removeUser() {
        $userId = $_POST['dUserId'];
        $contestId = $_POST['contestId'];

        DB::table('contestUsers')
            ->where('contestId', '=', $contestId)
            ->where('id', '=', $userId)
            ->delete();

        return redirect()->route('manageUsers', ['id' => $_POST['contestId']]);
    }

    public function updateWeight() {
        DB::table('contestUsers')
        ->where('userId', $_POST['userId'])
        ->where('contestId', $_POST['contestId'])
        ->update(['startLbs' => $_POST['weight'] . '.00', 'updated_at' => date("Y-m-d H:i:s")]);

        return "true";
    }
}