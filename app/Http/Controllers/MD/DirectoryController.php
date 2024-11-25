<?php

namespace App\Http\Controllers\MD;

use App\Http\Controllers\Controller;
use App\Models\MD\MdAdminUser;
use Illuminate\Http\Request;

class DirectoryController extends Controller
{

    public function index()
    {
        //
    }

    function getBoardsInfo($ids,$token)
    {
      $boards="";
      foreach ($ids as $id) {
         $boards.=$id.",";
      }
      $boards=rtrim($boards,",");
      $monday=New MethodsControllerMD($token);

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
              id
              name
              email
              last_activity 
              photo_small 
            }
            owners {
              id
              name
              email
              last_activity 
              photo_small 
            }
            subscribers {
              id
              name
              email
              last_activity 
              photo_small 
            }
            state
            permissions
            type
            updated_at
            url
          }
      }";

      $json=json_decode($monday->apiCallMD($query));
      if (isset($json->data->boards))
        return $json->data->boards;     
      else
        return false; 
    }

    function drawView($data=false,$new=null)
    {
      if ($data)
        print_r($data);
    }

    function boardProperties($boardId){
      $tokens=MondayController::getTokens($boardId);
      $new=(count($tokens)==0);
      if ($new)
        $tokens=MdAdminUser::select('token as key')->whereRaw('length(token)>0')->get();

      foreach ($tokens as $token){
        $json=$this->getBoardsInfo([$boardId],$token->key);
        if ($json)
          break;
      }
      if (isset($json[0]))
        $this->drawView($json[0],$new);
      else
        $this->drawView();
    }



}
