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
    // Get ELEMENTS columns ONLY. Don't get sub-elements columns
    function getBoardColumns($id)
    {
        $monday=New MethodsController();

        $query="{boards (ids: $id){name columns{id title type}}}";
        $json=json_decode($monday->apiCallMD($query));

        $name=$json->data->boards[0]->name;
        $columns=$json->data->boards[0]->columns;

        return(compact('name','columns'));
    }

    // Get temporary image URL from assetID set
    public static function getImageUrl($assetId)
    {
        $monday=New MethodsController();

        $query="{assets (ids:$assetId){name public_url}}";
        $img=(json_decode($monday->apiCallMD($query)))->data->assets[0];
        $ar=explode(".",$img->name);
        $ext=strtolower($ar[count($ar)-1]);
        $type=(strcmp(strtolower($ext),'png')==0||strcmp(strtolower($ext),'jpg')==0);

        return array('type'=>$type,'url'=>$img->public_url);
    }

    // Insert record (array)$data into database table $table
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

    function status($value,$settings)
    {
        if (strlen(trim($value))==0)
          return "";

        $index=json_decode($value)->index;
        return json_decode($settings)->labels->$index;

    }

}
