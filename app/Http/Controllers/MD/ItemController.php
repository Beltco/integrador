<?php

namespace App\Http\Controllers\MD;

use App\Http\Controllers\Controller;
use App\Models\MD\BoardValue;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a single item.
     */
    public function item($id)
    {
        try {
            $recordId=(BoardValue::get()->where('column_id','n_meros__1')->where('value',$id))->first()->record_id;
        } catch (\Exception $e) {
            return(view('MT.index',array('error'=>true)));
        }

        $cols=BoardValue::join('columns','column_id','=','columns.id')->select('columns.order','columns.title','board_values.value','columns.id as col_id')->where('record_id',$recordId)->orderBy('columns.order')->get();

        foreach ($cols as $col){
            if (strcmp($col->col_id,'archivo9')==0){
                $imgs=json_decode($col->value);
                if (count($imgs->assetIds)>0)
                    foreach ($imgs->assetIds as $img) {
                        $file=FunctionsController::getImageUrl($img);
                        if ($file['type'])
                            $pics[]=$file['url'];
                        else
                            $pdfs[]=$file['url'];
                    }
                else
                    $urls[]=asset('/images/noimage.jpg');
                $data[$col->col_id]=array('title'=>$col->title,'value'=>$pics);  
                if (!isset($pdfs))
                  $pdfs=[];
                $data['pdf_files']=array('title'=>'FICHA TÉCNICA','value'=>$pdfs);
            }
            else
                $data[$col->col_id]=array('order'=>$col->order,'title'=>$col->title,'value'=>$col->value);             
        }

        return (view('MT.item',compact('data')));
    }

    /**
     * Ask for a item code.
     */
    function refreshMaterials()
    {
        $monday=New FunctionsController();

        $records=$monday->writeMaterials(true);

        echo "Registros:$records";
    }


    /**
     * Ask for a item code.
     */
    public function index()
    {
        return (view('MT.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(BoardValue $boardValue)
    {
    }


}
