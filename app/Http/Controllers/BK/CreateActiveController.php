<?php

namespace App\Http\Controllers\BK;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MD\MethodsControllerMD;
use App\Http\Controllers\MD\MondayController;
use App\Models\BK\BukEmployee;
use App\Models\BK\BukMdActive;
use App\Models\MD\Column;
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

    public function addColumn($boardId,$itemId,$columnId,$type,$value,$monday) 
    {
      switch ($type) {
        case 'numbers':
          $value=str_replace('.','',$value);
          break;
        case 'text':
          $value='\"'.$value.'\"';
          break;
        default:
          break;
      }
      switch ($type) {
        case "checkbox":
          $query='mutation {
            change_multiple_column_values(
              board_id:'.$boardId.',
              item_id:'.$itemId.',  
              column_values: "{\"'.$columnId.'\" : {\"checked\" : \"'.$value.'\"}}"
              ) 
              {
                id
              } 
            }';
          break;
        case 'date':
        case 'phone':
        case 'email':
          $query='mutation{
            change_simple_column_value(
              board_id:'.$boardId.',
              item_id:'.$itemId.',
              column_id: "'.$columnId.'",
              value: "'.$value.'"
            ) 
            {
              id
            }
          }';          
          break;
        case 'dropdown':
          $query='mutation{
            change_simple_column_value(
              board_id:'.$boardId.',
              item_id:'.$itemId.',
              column_id: "'.$columnId.'",
              value: "'.$value.'",
              create_labels_if_missing: true
            ) 
            {
              id
            }
          }';
          break;
        case 'file':
          $query='mutation($file:File!){
            add_file_to_column(
             item_id: "'.$itemId.'",
             column_id: "'.$columnId.'",
             file: "'.$value.'"
            )
            {
              id
            }
           }';          
          break;
        case 'status':
          if (is_numeric($value))
            $query='mutation {
              change_multiple_column_values(
                board_id:'.$boardId.', 
                item_id:'.$itemId.',
                column_values: "{\"'.$columnId.'\" : {\"index\" : \"'.$value.'\"}}",
                create_labels_if_missing: true) {
                id
              }
            }';
          else
            $query='mutation {
              change_simple_column_value (
                board_id:'.$boardId.',
                item_id:'.$itemId.',  
                column_id:"'.$columnId.'", 
                value: "'.$value.'",
                create_labels_if_missing: true) {
                id
              }
            }';
          break;
        default:
          $query='mutation{
           change_column_value(
            board_id: '.$boardId.',
            item_id: "'.$itemId.'",
            column_id: "'.$columnId.'",
            value: "'.$value.'"
           )
           {
             id
           }
          }';
          break;
      }
echo ("<pre>$query\n");//die("value:*$value*");  /////////////////////////////////////////////
      return json_decode($monday->apiCallMD($query));      
    }

    public function sincro(Request $request)
    {
        $monday=New MethodsControllerMD();
        $mc=New MondayController();
        $documents=$request->input('opciones');

        $boardCols=$mc->getBoardColumns(BukController::$boardActives)['columns'];

        foreach ($boardCols as $column) 
          $type[$column->id]=$column->type;

        $tribus=array(22=>3);
        $escuadrones=array(24=>108);
        $meses=array(0,8,14,2,7,19,17,0,1,6,3,4,12);
        $ubicaciones=array('Colombia'=>0,'USA'=>1);
        $generos=array('M'=>0,'F'=>1);
        $civiles=array('Solter'=>1,'Casad'=>0,'Divorciad'=>3,'Viud'=>4,'Unión Libr'=>6);
        $fondos=array(
          'no_aplica'=>'N/A',
          'nueva_eps'=>'NUEVA EPS',
          'eps_sura'=>'EPS SURA',
          'sanitas'=>'SANITAS',
          'sos_servicio_occidental_de_salud_sa'=>'SOS',
          'salud_total'=>'SALUD TOTAL',
          'aliansalud_eps'=>'ALIANSALUD',
          'asmet_salud'=>'ASMET SALUD',
          'famisanar'=>'FAMISANAR',
          'adres_regimen_excepcion'=>'FOSYGA RÉGIMEN EXCEPCIÓN',
          'coosalud'=>'COOSALUD',
          'compensar'=>'Compensar',
          'proteccion'=>'PROTECCIÓN',
          'colpensiones'=>'COLPENSIONES',
          'porvenir'=>'PORVENIR',
          'colfondos'=>'COLFONDOS',
          'porvenir_fondo_pensiones_voluntarias'=>'PORVENIR VOLUNTARIAS',
);

        foreach ($documents as $document_number)
        {
            $buk=New MethodsControllerBK();
            $employee=$buk->apiCallBK("employees/$document_number")['data'];
            $id=$employee['id'];
            $planes=$buk->apiCallBK("employees/$id/plans")['data'];
            foreach ($planes as $plan){
              $eps=$plan['health_company'];
              $afp=$plan['pension_fund'];
            }
//echo "<pre>";print_r($planes);die("eps:$eps pension:$afp");    //////////////        
            $areaId=$employee['current_job']['area_id'];
            $area=$buk->apiCallBK("/organization/areas/$areaId")['data'];
            $escuadronId=$area['id'];
            $tribuId=$area['parent_area']['id'];
            $roleName=trim($employee['current_job']['role']['name']);
            $ini=strpos($roleName,'(')+1;
            $roleName=substr($roleName,$ini,strlen($roleName)-$ini-1);
            $ubicacion=trim($employee['custom_attributes']['24. Ubicación']);
            if (strlen($ubicacion)==0)
              $ubicación=5;
            else
              $ubicacion=$ubicaciones[$ubicacion];            
            $genero=trim($employee['gender']);
            if (strlen($ubicacion)==0)
              $genero=5;
            else
              $genero=$generos[$genero];
            $civil=substr(trim($employee['civil_status']),0,-1);
            if (strlen($civil)==0)
              $civil=5;
            else
              $civil=$civiles[$civil];

/*
            $query='mutation{
              create_item(
                board_id: '.BukController::$boardActives.',
                group_id: "'.BukController::$activesNuevos.'",
                item_name: "'.$employee['full_name'].'"){ id }}';
            $itemId=json_decode($monday->apiCallMD($query))->data->create_item->id;
*/          
$itemId=7300280050; ////////////////////////////////////////////////////   

            $query="mutation { clear_item_updates (item_id: $itemId) {id} }";
            $j=json_decode($monday->apiCallMD($query));
            $query='mutation {
              create_update (item_id: '.$itemId.', body: "'.$employee['custom_attributes']['32. ¡Preséntate al equipo! Cuéntanos un poco sobre ti: ¿Qué estudiaste? ¿Qué experiencia tienes? ¿Cuál es tu comida favorita? ¿Cuáles son tus hobbies? ¿Qué esperas lograr en BELT? ¿Cómo te gusta que te digan?'].'") 
              { id }
            }';
            $j=json_decode($monday->apiCallMD($query));
//echo "<pre>"; print_r($j); die("query:$query"); ///////////////////////////////////////////            

            $columns=array(
/*              'c_dula'=>'document_number',
              'texto4'=>$employee['custom_attributes']['21. Ciudad de expedición de tu cédula'],
              'archivo'=>'picture_url',
              'tribu'=>$tribus[$tribuId], 
              'squad'=>$escuadrones[$escuadronId], 
              'dup__of_cargo3'=>$roleName, 
              'fecha_de_ingreso'=>$employee['current_job']['start_date'],  
              'dup__of_mes_nacimiento'=>$meses[(int)date("m",strtotime($employee['current_job']['start_date']))], 
              'fecha'=>$employee['current_job']['start_date'],  
              'contrataci_n'=>$employee['current_job']['type_of_contract'],
              'ubicaci_n'=>$ubicacion, 
              'g_nero'=>$genero, 
              'fecha_nacimiento'=>'birthday', 
              'month_1'=>$meses[(int)date("m",strtotime($employee['birthday']))], 
              'texto0'=>trim($employee['custom_attributes']['25. Por favor escribir la carrera (técnico, tecnología, profesional) que estudiaste']), 
              'verificar'=>(trim($employee['custom_attributes']['26. ¿Tienes hijos?'])=="Si"?"true":"false"), 
              'dup__of_g_nero'=>$civil, 
              'texto05'=>'address', 
              'estado_1'=>'district',  
              'tel_fono9'=>$employee['phone']." ".$employee['country_code'], 
              'correo_electr_nico'=>$employee['personal_email']." ".$employee['full_name'], 
              'texto40'=>$employee['custom_attributes']['27. RH - Tipo de sangre'], 
              'texto'=>(isset($fondos[$eps])?$fondos[$eps]:$eps), 
              'texto6'=>(isset($fondos[$afp])?$fondos[$afp]:$afp), 
              'texto2'=>$employee['custom_attributes']['28. Contacto de emergencia (Nombre, parentesco)'], 
              'tel_fono'=>($employee['country_code']=='CO'?'+57':'').str_replace('+57','',$employee['custom_attributes']['28.1 Contacto de emergencia (número de teléfono)'])." ".$employee['country_code'], 
              'salario9'=>$employee['current_job']['wage'], 
              'men__desplegable'=>$employee['custom_attributes']['29. Talla de camisa / polo / camiseta'], */
              'dup__of_talla_polo_camisa'=>$employee['custom_attributes']['30. Talla de pantalón (jean)'], 

            );

            foreach ($columns as $column=>$label){
              if (isset($employee[$label]))
                $valor=$employee[$label];
              else
                $valor=$label;
              $data=$this->addColumn(BukController::$boardActives,$itemId,$column,$type[$column],$valor,$monday);
echo "<pre>";print_r($data);die("columnId:$column");  ///////////////////
            }
            unset($columns);


        }


 //       return view('BK.sincro',['employees'=>$employees]);
          
    }

    public function index()
    {
        return (view('BK.index'));
    }
}
