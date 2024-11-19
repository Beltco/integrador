<?php

namespace App\Http\Controllers\MD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\MD\MondayController;


class BoardController extends Controller
{

    function getBoardsInfo($ids)
    {
      $boards="";
        foreach ($ids as $id) {
          $boards.="$id,";
        }
        $monday=New MethodsControllerMD();
        $query="query {
          boards (ids:[$boards]) {
            id
            name
            description
            board_kind 
            creator{
              account{
                id
                name
              }
              email
            }
            owners {
              account{
                id
                name
              }
              email
            }
            subscribers {
              account{
                id
                name
              }
              email
            }
            state
            permissions
            type
            updated_at
            url
          }
        }";
         return json_decode($monday->apiCallMD($query))->data->boards;      
    }

    function getBoards()
    {
      print_r($this->getBoardsInfo([1013753818]));
    }

}



