<?php

namespace App\Http\Controllers\MD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\MD\MethodsController;

class FunctionsController extends Controller
{
    public function getBoardColumns($id=false)
    {
        $monday=New MethodsController();
        if (!$id)
            $boardCode=$monday->boardMateriales();

        $query="{boards (ids: $boardCode){columns{id title type}}}";
        $json=json_decode($monday->apiCallMD($query));
        $columns=$json->data->boards[0];
        
        return $columns;
    }
}
