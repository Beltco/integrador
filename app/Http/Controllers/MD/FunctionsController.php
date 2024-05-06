<?php

namespace App\Http\Controllers\MD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\MD\MethodsController;

class FunctionsController extends Controller
{
    public function getBoards()
    {
        $monday=New MethodsController();

        print_r(json_decode($monday->apiCallMD('{ boards { id name } }')));
    }
}
