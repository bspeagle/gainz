<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Auth;
use DateTime;

class contestController extends Controller
{
    /**
     * Show a list of all of the application's users.
     *
     * @return Response
     */
    public function getContest($id) {
        $uRecord = DB::table('contestUsers')
            ->where('contestId', '=', $id)
            ->get();

        $uORecord = DB::table('contestUsers')
            ->join('users', 'contestUsers.userId', 'users.id')
            ->where('contestId', '=', $id)
            ->where('owner', '=', true)
            ->select('contestUsers.*', 'users.name')
            ->get();

        $array = json_decode(json_encode($uRecord), true);

        if (in_array(Auth::id(), array_column($array, 'userId'))) {
            $contest = DB::table('contests')
                ->where('id', '=', $id)
                ->get();

            $contest[0]->startDt = date('m/d/Y', strtotime($contest[0]->startDt));
            $contest[0]->endDt = date('m/d/Y', strtotime($contest[0]->endDt));
            
            $cUsers = DB::table('contestUsers')
                ->join('users', 'contestUsers.userId', 'users.id')
                ->where('contestId', '=', $id)
                ->select('contestUsers.*', 'users.email', 'users.name')
                ->get();

            $cUser = DB::table('contestUsers')
                ->where('userId', '=', Auth::id())
                ->where('contestId', '=', $id)
                ->get();

            $wResult = $this->getWeek(date('W', strtotime(date('Y-m-d H:i:s'))), date('Y', strtotime(date('Y-m-d H:i:s'))));
            $startDt = date('Y-m-d H:i:s', strtotime($wResult['start']));
            $endDt = date('Y-m-d H:i:s', strtotime($wResult['end']));

            $cWeek = DB::table('contestWeeks')
                ->where('contestWeeks.contestId', '=', $id)
                ->where('contestWeeks.startDt', '=', $startDt)
                ->where('contestWeeks.endDt', '=', $endDt)
                ->select('contestWeeks.week', 'contestWeeks.startDt', 'contestWeeks.weighDt', 'contestWeeks.endDt')
                ->get();
            
            if (count($cWeek) == 0) {
                $o = (object) ['week' => '0', 'startDt' => '', 'weighDt' => '', 'endDt' => ''];
                $cWeek->push($o);
            }

            $cWeek[0]->startDt = date('m/d/Y', strtotime($cWeek[0]->startDt));
            $cWeek[0]->weighDt = date('m/d/Y', strtotime($cWeek[0]->weighDt));
            $cWeek[0]->endDt = date('m/d/Y', strtotime($cWeek[0]->endDt));

            return view('contest')
                ->with('contest', $contest)
                ->with('ownerId', $uORecord[0]->userId)
                ->with('ownerName', $uORecord[0]->name)
                ->with('userId', Auth::id())
                ->with('cUsers', $cUsers)
                ->with('userLBS', $cUser[0]->startLbs)
                ->with('cWeek', $cWeek);
        }
        else {
            return view('errors.noAuth');
        }
    }

    public function index() {
        return view('newContest');
    }

    public function createContest() {
        $cId = DB::table('contests')->insertGetId(
            ['name' => $_POST['name'],
            'startDt' => date("Y-m-d H:i:s", strtotime($_POST['startDt'])),
            'endDt' => date("Y-m-d H:i:s", strtotime($_POST['endDt'])),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")]
        );

        $cUId = DB::table('contestUsers')->insertGetId(
            ['userId' => Auth::id(),
            'contestId' => $cId,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
            'owner' => true,
            'status' => 3]
        );

        //Create week data for tracking//
        $startDateUnix = strtotime($_POST['startDt']);
        $endDateUnix = strtotime($_POST['endDt']);
        $currentDateUnix = $startDateUnix;
        $week = 1;

        while ($currentDateUnix < $endDateUnix) {
            $result = $this->getWeek(date('W', $currentDateUnix), date('Y', $currentDateUnix));

            $cWId = DB::table('contestWeeks')->insertGetId(
                ['contestId' => $cId,
                'week' => $week,
                'startDt' => $result['start'],
                'weighDt' => $result['weigh'],
                'endDt' => $result['end'],
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")]
            );
            
            $currentDateUnix = strtotime('+1 week', $currentDateUnix);
            $week++;
        }
        //////////////////////////////////

        return redirect()->route('getContest', array('id' => $cId));
    }

    private function getWeek($week, $year) {
        $dto = new DateTime();
        $result['start'] = $dto->setISODate($year, $week, 1)->format('Y-m-d');
        $result['weigh'] = $dto->setISODate($year, $week, 5)->format('Y-m-d');
        $result['end'] = $dto->setISODate($year, $week, 7)->format('Y-m-d');
        return $result;
      }
}