<?php

namespace App\Http\Controllers\MD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\MD\MondayController;


class BoardController extends Controller
{



    function getBoards()
    {
      print_r($this->getBoardsInfo([1013753818]));
    }

}



