<?php

namespace App\Http\Controllers\MD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\MD\MondayController;
use App\Models\MD\MdBoardDetail;
use Carbon\Carbon;

class BoardController extends Controller
{

    function getBoards()
    {
      $monday=New MondayController;
      /*
      MdBoardDetail::whereNull('json')->update([
        'procesada' => null,  
      ]);
      */
      do{
        $ids = MdBoardDetail::whereNull('json')
        ->take(100)
        ->pluck('id')
        ->toArray();
        
        foreach ($ids as $id) {
          $board=$monday->getBoardsInfo([$id]);
          MdBoardDetail::where('id', $id)->update([
            'json' => json_encode($board),                // Cadena de texto $json
            'procesada' => Carbon::now(),  // Fecha y hora actual
          ]);
        }
        
      }while (count($ids)>0);
      echo config('app.md_token');
    }

}



