<?php

namespace App\Http\Controllers\MD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\MD\MondayController;


class BoardController extends Controller
{



    function getBoards()
    {
      $monday=New MondayController;

      print_r($monday->getBoardsInfo([1013753818]));
    }

}



