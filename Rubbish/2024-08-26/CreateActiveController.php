<?php

namespace App\Http\Controllers\BK;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MD\MethodsControllerMD;
use App\Models\BK\BukEmployee;
use App\Models\BK\BukMdActive;
use Illuminate\Http\Request;

class CreateActiveController extends Controller
{
    public function listUnmatch()
    {
        $employees=array();
        $actives=BukEmployee::select('document_number','full_name')->where('status','=','activo')->whereNotIn('document_number',BukMdActive::pluck('document_number')->toArray())->pluck('full_name','document_number');
        foreach ($actives as $id=>$name)
          $employees[$id]=$name;

        return view('BK.sincro',['employees'=>$employees]);
          
    }

    public function sincro(Request $request)
    {
        $monday=New MethodsControllerMD();
        $documents=$request->input('opciones');

        $tribus=array(22=>1);
        $escuadrones=array(24=>13);

        foreach ($documents as $document_number)
        {
            $buk=New MethodsControllerBK();
            $employee=$buk->apiCallBK("employees/$document_number")['data'];
//            $id=$employee['id'];
            $areaId=$employee['current_job']['area_id'];
            $area=$buk->apiCallBK("/organization/areas/$areaId")['data'];
            $escuadronId=$area['id'];
            $tribuId=$area['parent_area']['id'];
            $query='mutation{
              create_item(
                board_id: '.BukController::$boardActives.',
                group_id: "'.BukController::$activesNuevos.'",
                item_name: "'.$employee['full_name'].'"){ id }}';
            $itemId=json_decode($monday->apiCallMD($query))->data->create_item->id;
$itemId=7290728700; ////////////////////////////////////////////////////            
            $query='mutation{
              change_column_value(
                board_id: '.BukController::$boardActives.',
                item_id: "'.$itemId.'",
                column_id: "c_dula",
                value: "'.str_replace(".","",$employee['document_number']).'"
              ){
                 id
               }
            }';

            $data=json_decode($monday->apiCallMD($query));



echo "<pre>itemId:";print_r($data);die("itemId:$itemId");  ///////////////////

        }


 //       return view('BK.sincro',['employees'=>$employees]);
          
    }

    public function index()
    {
        return (view('BK.index'));
    }
}
