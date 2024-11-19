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
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Promise\Utils;

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
        

        if ($id){
            $employees[]=$bk->apiCallBK("employees/$id")['data'];
        }
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
//          BukEmployee::query()->delete();
        }
//echo "<pre>";print_r($employees);die("id:$id");  ///////////////////////////////
        foreach ($employees as $employee)
        try{
            $field['id']=$employee['id'];
            $field['full_name']=$employee['full_name'];
            $field['document_number']=str_replace(".","",$employee['document_number']);
            $field['city']=$employee['district'];
            $field['mobile_number']=$employee['phone'];
            $field['eps']=data_get($employee,'health_company','');
            $field['afp']=data_get($employee,'pension_regime','');
            $field['status']=$employee['status'];
            $field['marital_status']=$employee['civil_status'];
            $field['address']=$employee['address'];
            if (BukEmployee::where('id', $employee['id'])->count()==0){
                Database::insert(New BukEmployee,$field);

            }
          }
          catch (\Exception $e) {
            echo "ERROR getEmployees(): ".$e->getMessage();
            die("\nReportar a Soporte IT");
//            echo("<pre>");print_r($employee);die(); //////////////////////////////////
        }         
          return redirect()->route('sincro');          

    }

    function insertGroupMD($groupId)
    {
        $md=New MondayController();

        $actives=$md->getBoardAllInfo(self::$boardActives,$groupId);
        $group=$actives['groups'][$groupId];

        foreach ($group['items'] as $id=>$item) 
        {
            $field['document_number']=$item['c_dula']['value'];
            if (strlen(trim($field['document_number']))>0)
            {
              $field['id']=$id;
              $field['full_name']=$item['elemento']['value'];
              $field['city']=$item['estado_1']['value'];
              $field['mobile_number']=$item['tel_fono9']['value'];
              $field['eps']=$item['texto']['value'];
              $field['afp']=$item['texto6']['value'];
              $st=$item['estado']['value'];
              $field['status']=(strlen(trim($st))==0?"Activo":$st);
              $field['marital_status']=$item['dup__of_g_nero']['value'];
              $field['address']=$item['texto05']['value'];
              $field['neigborhood']=$item['texto49']['value'];
              $field['tribu']=$group['title'];
              Database::insert(New BukMdActive,$field);
            }
            //echo $field['document_number']."|".$field['full_name']." creado<br>\n";
        }


    }

    function getActivesMD($reset=true)
    {
        if ($reset) BukMdActive::query()->delete();

        $m=New MondayController(); 
        $groups=$m->getGroups(self::$boardActives);  

        foreach ($groups as $group)
        {
            $url = route('insertGroup', ['id' => $group->id]);
            //echo "<pre>Procesando $group->title\n";
            $promises[] = Http::async()->get($url);            

        }
        $responses = Utils::settle($promises)->wait();

        $msg="La carga de Monday>Activos a la base de datos ha sido exitosa...";
        return view('BK.land')->with('url', route('sincro'))->with('msg',$msg);          
    }

}
