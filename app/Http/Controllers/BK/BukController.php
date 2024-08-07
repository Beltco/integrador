<?php

namespace App\Http\Controllers\BK;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BK\MethodsController;
use App\Http\Controllers\MD\MondayController;
use App\Http\Controllers\Database;
use App\Models\BK\BukMdActive;
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

    function getActivesMD()
    {
        $md=New MondayController();

        ini_set('max_execution_time', 300); //3 minutes
        set_time_limit(300);
        
        BukMdActive::query()->delete();
        $actives=$md->getBoardAllInfo(self::$boardActivos);

        foreach ($actives['groups'] as $group) {
            foreach ($group['items'] as $id=>$item) 
            {
                $data['id']=$id;
                $data['full_name']=$item['elemento']['value'];
                $data['document_number']=$item['c_dula']['value'];
                $data['city']=$item['estado_1']['value'];
                $data['mobile_number']=$item['tel_fono9']['value'];
                $data['eps']=$item['texto']['value'];
                $data['afp']=$item['texto6']['value'];
                $data['status']=$item['estado']['value'];
                $data['marital_status']=$item['dup__of_g_nero']['value'];
                $data['address']=$item['texto05']['value'];
                $data['neigborhood']=$item['texto49']['value'];
                $data['tribu']=$group['title'];

                Database::insert(New BukMdActive,$data);

                echo $data['document_number']."|".$data['full_name']." creado<br>\n";
            }
        }

echo "Fin";
          
    }
}
