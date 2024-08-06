<?php

namespace App\Http\Controllers\MD;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MD\MethodsController;
use stdClass;
use Carbon\Carbon;


class CalendarController extends Controller
{
    private $gooClientID;
    private $gooClientSecret;
    private $boardReservas;


    function __construct()
    {
        $this->boardReservas = 4371273842;
        $this->gooClientID = env('GOOGLE_CLIENT_ID');
        $this->gooClientSecret = env('GOOGLE_CLIENT_SECRET');
    }

    public function boardMateriales(){
        return $this->boardReservas;
    }

    public function gooClientID(){
        return $this->gooClientID();
    }

    public function gooClientSecret(){
        return $this->gooClientSecret();
    }

    public function calendar($date = null)
    {
        $date = empty($date) ? Carbon::now() : Carbon::createFromDate($date);
        
        $startOfCalendar = $date->copy()->firstOfMonth()->startOfWeek(Carbon::SUNDAY);
        $endOfCalendar = $date->copy()->lastOfMonth()->endOfWeek(Carbon::SATURDAY);

        $year=$date->format('Y');
        $month=$date->format('M');
        $day=$date->format('j');
        $dayLabels = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

        $dow=ucfirst($date->shortEnglishDayOfWeek);

        while($startOfCalendar <= $endOfCalendar)
        {
            $extraClass = $startOfCalendar->format('m') != $date->format('m') ? 'dull' : '';
            $extraClass .= $startOfCalendar->isToday() ? ' today' : '';
            $days[]=array('class'=>$extraClass,'day'=>$startOfCalendar->format('j'));
            $startOfCalendar->addDay();
        }
        return compact('year','month','dow','day','dayLabels','days');
    }

    public function index()
    {
        return (view('CD.index')->with('calendar',$this->calendar()));
    }

}
