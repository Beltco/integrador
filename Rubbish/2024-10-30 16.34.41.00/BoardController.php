<?php

namespace App\Http\Controllers\MD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\MD\MondayController;


class BoardController extends Controller
{
    function getBoards()
    {
        $monday=New MethodsControllerMD();
        $query="query {
          boards (ids:[1]) {
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
        $boards=json_decode($monday->apiCallMD($query))->data->boards;      
print_r($boards);

    }
}



