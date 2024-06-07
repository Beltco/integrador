<?php

namespace App\Http\Controllers\MD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\MD\MethodsController;

class FunctionsController extends Controller
{
    function getBoardColumns($id)
    {
        $monday=New MethodsController();

        $query="{boards (ids: $id){columns{id title type}}}";
        $json=json_decode($monday->apiCallMD($query));
       
        $columns=$json->data->boards[0]->columns;
    
        return $columns; //id, title and type of each column into an array
    }

    function getImageUrl($assetId)
    {
        $monday=New MethodsController();

        $query="{assets (ids:$assetId){public_url}}";
        return (json_decode($monday->apiCallMD($query))->data->assets[0]->public_url);
    }

    function insertBoardItems($items,$boardId)
    {
        $columns=$this->getBoardColumns($boardId);
        foreach ($columns as $column) {
          $field[$column->id]=array('title'=>$column->title,'type'=>$column->type);
        }
        foreach ($items as $item) 
        {
            $record[$item->id]['name']=array('value'=>$item->name,'type'=>'text');
            foreach ($item->column_values as $value) {
                $record[$item->id][$value->id]=array('value'=>$value->value,'type'=>$value->type);
            }
        }
echo "<pre>";print_r($field);print_r($record);die("id:$boardId");

    }

    function getBoardItems($boardId)
    {
        $monday=New MethodsController();
        $limit=200;

        $query="{boards (ids: $boardId){items_page (limit:$limit){cursor items{id name column_values {id value type} }}}}";
        $json=json_decode($monday->apiCallMD($query))->data->boards[0]->items_page;
        $cursor=$json->cursor;
        $this->insertBoardItems($json->items,$boardId);

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
            $this->getBoardItems($monday->boardMateriales());
    }
}
