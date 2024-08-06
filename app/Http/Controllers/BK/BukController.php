<?php

namespace App\Http\Controllers\BK;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BK\MethodsController;
use Illuminate\Http\Request;
use Mockery\Generator\Method;

class BukController extends Controller
{
    public static $boardActivos=1116943145;
    public static $boardInactivos=1117417426;

    function getEmployees($id=null)
    {
        $bk=New MethodsController();

        if ($id)
            $employees=$bk->apiCallBK("employees/$id");
        else{
            $params=array();
            $pag=1;
            do{
                $data=$bk->apiCallBK("employees",$params);
                foreach($data['data'] as $employee)
                    $employees[]=$employee;
                $pag++;
                $param['page']=$pag;
          }while ($data['pagination']['next']&&$pag<3);
        }
    }
}
