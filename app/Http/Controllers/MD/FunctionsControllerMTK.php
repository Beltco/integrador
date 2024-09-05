<?php

namespace App\Http\Controllers\MD;

use App\Models\MD\Column;
use App\Models\MD\Board;
use App\Models\MD\BoardValue;
use App\Http\Controllers\Database;
use App\Models\MD\BoardColumn;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MD\MethodsController;
use App\Http\Controllers\MD\FunctionsController;


class FunctionsControllerMTK extends Controller
{

    function insertBoardItems($items,$boardId,$reset=false)
    {
        
        $methods = new MethodsControllerMD();
        $fn=new MondayController();

        if ($reset){
            BoardValue::query()->delete();
            Column::query()->delete();
            Board::query()->delete();

            $info=$fn->getBoardColumns($boardId);
            Database::insert(New Board(),array('id'=>$boardId,'name'=>$info['name']));

            $order=1;
            foreach ($info['columns'] as $column){
                Database::insert(New Column(),array_merge(get_object_vars($column),array('board_id'=>$boardId,'order'=>$order)));
                $order++;
            } 
        }
        $records=0;
        foreach ($items as $item) 
        {
            $records++;
            Database::insert(New BoardValue,array('board_id'=>$boardId,'column_id'=>'name','record_id'=>$item->id,'value'=>$item->name));
            foreach ($item->column_values as $value) {
                if (strcmp($value->type,"file")==0)
                    $data=$fn->jsonFiles($value->value);
                elseif (strcmp($value->type,"status")==0)
                    $data=$fn->status($value->value,$value->column->settings_str);
                else
                    $data=trim($value->value,'"');
                Database::insert(New BoardValue,array('board_id'=>$boardId,'column_id'=>$value->id,'record_id'=>$item->id,'value'=>$data));
            }
        }

        return $records;
    }

    function getBoardItems($boardId,$refresh)
    {
        $monday=New MethodsControllerMD();

        $limit=200;
        $query="{boards (ids: $boardId){items_page (limit:$limit){cursor items{id name column_values {id value type column {settings_str}}}}}}";
        $json=json_decode($monday->apiCallMD($query))->data->boards[0]->items_page;
        $cursor=$json->cursor;
        $records=$this->insertBoardItems($json->items,$boardId,$refresh);

        while ($cursor)
        {
            $query="{next_items_page (limit:$limit,cursor:".'"'.$cursor.'"'."){cursor items{id name column_values {id value type column {settings_str}}}}}";

            $json=json_decode($monday->apiCallMD($query))->data->next_items_page;
            $cursor=$json->cursor;
            $records+=$this->insertBoardItems($json->items,$boardId);
        }

        return $records;
    }

    public function writeMaterials($refresh=false)
    {
        $md=New MethodsControllerMD();

        return $this->getBoardItems($md->boardMateriales(),$refresh);
    }
}
