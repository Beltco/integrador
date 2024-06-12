<?php

namespace App\Http\Controllers\MD;

use App\Models\MD\Column;
use App\Models\MD\Board;
use App\Models\MD\BoardValue;
use App\Models\MD\BoardColumn;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MD\MethodsController;

class FunctionsController extends Controller
{
    function getBoardColumns($id)
    {
        $monday=New MethodsController();

        $query="{boards (ids: $id){name columns{id title type}}}";
        $json=json_decode($monday->apiCallMD($query));
      
        $name=$json->data->boards[0]->name;
        $columns=$json->data->boards[0]->columns;
        
        return(compact('name','columns'));     
    }

    function getImageUrl($assetId)
    {
        $monday=New MethodsController();

        $query="{assets (ids:$assetId){public_url}}";
        return (json_decode($monday->apiCallMD($query))->data->assets[0]->public_url);
    }

    function insert($table,$data)
    {

        foreach ($data as $key => $value) {
            $table->$key=$value;
        }
        try{
            $table->save();
        } catch (\Exception $exception) {
            $message = $exception->getMessage();
       
            die("$message\n<br>* DATA *\n<br>$key => $value");
        }

        unset($table);

    }

    function jsonFiles($value)
    {
        $assetIds="[]";

        if (strlen($value)>0){
            foreach (json_decode($value)->files as $file){
                $json[]=$file->assetId;
            }
            $assetIds=json_encode($json);
        }

        return ('{"assetIds":'.$assetIds.'}');
    }

    function insertBoardItems($items,$boardId,$reset=false)
    {
        
        $methods = new MethodsController;

        if ($reset){
            BoardValue::query()->delete();
            BoardColumn::query()->delete();
            Column::query()->delete();
            Board::query()->delete();

            $info=$this->getBoardColumns($boardId);
            $this->insert(New Board(),array('id'=>$boardId,'name'=>$info['name']));

            $order=1;
            foreach ($info['columns'] as $column){
                $this->insert(New Column(),array_merge(get_object_vars($column),array('board_id'=>$boardId,'order'=>$order)));
                $order++;
            } 
        }

        foreach ($items as $item) 
        {
            $this->insert(New BoardValue,array('board_id'=>$boardId,'column_id'=>'name','record_id'=>$item->id,'value'=>$item->name));
            foreach ($item->column_values as $value) {
                if (strcmp($value->type,"file")==0)
                    $data=$this->jsonFiles($value->value);
                else
                    $data=$value->value;
                $this->insert(New BoardValue,array('board_id'=>$boardId,'column_id'=>$value->id,'record_id'=>$item->id,'value'=>$data));
            }
        }

    }

    function getBoardItems($boardId,$refresh)
    {
        $monday=New MethodsController();
        $limit=200;

        $query="{boards (ids: $boardId){items_page (limit:$limit){cursor items{id name column_values {id value type} }}}}";
        $json=json_decode($monday->apiCallMD($query))->data->boards[0]->items_page;
        $cursor=$json->cursor;
        $this->insertBoardItems($json->items,$boardId,$refresh);

        while ($cursor)
        {
            $query="{next_items_page (limit:$limit,cursor:".'"'.$cursor.'"'."){cursor items{id name column_values {id value type} }}}";

            $json=json_decode($monday->apiCallMD($query))->data->next_items_page;
            $cursor=$json->cursor;
            $this->insertBoardItems($json->items,$boardId);
        }
    }

    function getMaterials($refresh=false)
    {
        $monday=New MethodsController();
        if ($refresh)
            $this->getBoardItems($monday->boardMateriales(),$refresh);
    }
}
