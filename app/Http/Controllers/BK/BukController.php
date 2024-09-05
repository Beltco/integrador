<?php

namespace App\Http\Controllers\BK;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BK\MethodsController;
use App\Http\Controllers\MD\MondayController;
use App\Http\Controllers\Database;
use App\Models\BK\BukEmployee;
use App\Models\BK\BukMdActive;
use Illuminate\Http\Request;
use Mockery\Generator\Method;

class BukController extends Controller
{
    public static $boardActives=1116943145;
    public static $activesNuevos='grupo_nuevo34781';
    public static $boardInactives=1117417426;

    function getEmployees($id=false)
    {
        $bk=New MethodsControllerBK();

        ini_set('max_execution_time', 500); //3 minutes
        set_time_limit(500);
        
        BukEmployee::query()->delete();

        if ($id){
            $employees=$bk->apiCallBK("employees/$id");}
        else{
            $params=array();
            $pag=1;
            do{
                $buk=$bk->apiCallBK("employees",$params);
                foreach($buk['data'] as $employee)
                    $employees[]=$employee;
                $pag++;
                $params['page']=$pag;
          }while ($buk['pagination']['next']);

          foreach ($employees as $employee){
            $data['id']=$employee['id'];
            $data['full_name']=$employee['full_name'];
            $data['document_number']=str_replace(".","",$employee['document_number']);
            $data['city']=$employee['district'];
            $data['mobile_number']=$employee['phone'];
            $data['eps']=$employee['health_company'];
            $data['afp']=$employee['pension_regime'];
            $data['status']=$employee['status'];
            $data['marital_status']=$employee['civil_status'];
            $data['address']=$employee['address'];

            if (BukEmployee::where('id', $employee['id'])->count()==0){
                Database::insert(New BukEmployee,$data);
                //echo $data['document_number']."|".$data['full_name']." creado<br>\n";

            }
          }         
        }
    }

    function getActivesMD($reset=true)
    {
        $md=New MondayController();

        ini_set('max_execution_time', 500); //5 minutes
        set_time_limit(500);
        
        if ($reset) BukMdActive::query()->delete();

        $m=New MondayController(); 
        $groups=$m->getGroups(self::$boardActives);  

        foreach ($groups as $group)
        {
            $actives=$md->getBoardAllInfo(self::$boardActives,$group->id);
            $group=$actives['groups'][$group->id];

            foreach ($group['items'] as $id=>$item) 
            {
                $data['document_number']=$item['c_dula']['value'];
                if (strlen(trim($data['document_number']))>0)
                {
                    $data['id']=$id;
                    $data['full_name']=$item['elemento']['value'];
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
                }
                //echo $data['document_number']."|".$data['full_name']." creado<br>\n";
            }
        }

echo "Fin";
          
    }
}
