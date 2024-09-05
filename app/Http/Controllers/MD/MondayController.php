<?php

namespace App\Http\Controllers\MD;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MD\MethodsController;
use App\Models\MD\Column;
use Exception;
use stdClass;

class MondayController extends Controller
{
    // Get ELEMENTS columns ONLY. Don't get sub-elements columns
    function getBoardColumns($id)
    {
        $monday=New MethodsControllerMD();

        $query="{boards (ids: $id){name columns{id title type}}}";
        $json=json_decode($monday->apiCallMD($query));
        $name=$json->data->boards[0]->name;
        $columns=$json->data->boards[0]->columns;

        return(compact('name','columns'));
    }

    // Get temporary image URL from assetID set
    public static function getImageUrl($assetId)
    {
        $monday=New MethodsControllerMD();

        $query="{assets (ids:$assetId){name public_url}}";
        try{
        $img=(json_decode($monday->apiCallMD($query)))->data->assets[0];
        }catch(\Exception $e){
          return array('type'=>'jpg','url'=>'https://integrador.beltforge.com/images/noimage.jpg');
        }
        $ar=explode(".",$img->name);
        $ext=strtolower($ar[count($ar)-1]);
        $type=(strcmp(strtolower($ext),'png')==0||strcmp(strtolower($ext),'jpg')==0);

        return array('type'=>$type,'url'=>$img->public_url);
    }

    function jsonFiles($value)
    {
        $assetIds="[]";
        $json=array();

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

    function splitUpdate($updates)
    {
        return $updates;
    }

    function splitItem($fields)
    {
        switch ($fields['type']) {
            case 'link':
                $detail=json_decode($fields['detail']['value'],true);
                $fields['detail']=$detail;
                break;
            case 'people':
                $fields['detail']=explode(',',$fields['value']);
                break;     
            case 'status':
                $value=json_decode($fields['detail']['value'],true);
                if (!isset($value['index']))
                  $value['index']=5;
                $detail=json_decode($fields['detail']['settings_str'],true);
                try{
                  $labels=$detail['labels_positions_v2'];  
                }catch (\Exception $e) {
                  $orden=1;
                  foreach ($detail['labels'] as $idx=>$lbl){
                    $labels[$idx]=$orden;
                    $orden++;
                  }
                }                
                foreach($labels as $index=>$i)
                  try {
                    $options[$i]=array('label'=>$detail['labels'][$index],'color'=>$detail['labels_colors'][$index]['color'],'indice'=>$index);
                  } catch (\Exception $e) {
                    $options[$i]=array('label'=>'','color'=>'#225091');
                  }
                
                ksort($options);
                $fields['detail']=array('selected'=>$value['index'],'options'=>$options);
            break;
            case 'formula';
                $detail=json_decode($fields['detail']['settings_str'],true);
                $fields['detail']=$detail['formula'];

            break;
        } 

        return $fields;
    }

    function subItems($itemId)
    {
        $md=New MethodsControllerMD();

        $subItems=array();

        $query="query{
          items (ids: $itemId) {
            subitems {
                          id 
                          name 
                          column_values 
                          {
                            id 
                            text
                            value 
                            type 
                            column 
                            {
                              title
                              settings_str
                            }
                          }
                          updates{body id creator {name id}}  
            }
          }
        }";
        $items=json_decode($md->apiCallMD($query))->data->items[0]->subitems;
        foreach ($items as $item) {
                $fields=$this->splitItem(['name'=>'Elemento','value'=>$item->name,'detail'=>$item->name,'type'=>'text']);
                $subItems[$item->id]['elemento']=$fields;
                foreach ($item->column_values as $column) {
                    $fields=$this->splitItem(['name'=>$column->column->title,'value'=>$column->text,'detail'=>array('value'=>$column->value,'settings_str'=>$column->column->settings_str),'type'=>$column->type]);
                    $subItems[$item->id][$column->id]=$fields;
                } // foreach $item->column_values
                foreach($item->updates as $update)
                {
                    $updates=$this->splitUpdate(['body'=>$update->body,'creator'=>$update->creator->name]);
                    $subItems[$item->id]['updates'][$update->id]=$updates;
                } // foreach $item->updates
            } // foreach $items
        return $subItems;
    }

    function getGroups($boardId,$groupId=null){
      $monday=New MethodsControllerMD();

      $groups="";
      if ($groupId)
        $groups="(ids: ".'"'.$groupId.'"'.")";

      $query="
      {
        boards(ids: $boardId) 
        {
          name
          groups $groups
          {
            id
            title
          }
        }
      }";
      $json=json_decode($monday->apiCallMD($query));
      $board['name']=$json->data->boards[0]->name;

      return $json->data->boards[0]->groups;
    }

    function getBoardAllInfo($boardId,$groupId=false) 
    {
        $monday=New MethodsControllerMD();
        $limit=200;
        $groups=$this->getGroups($boardId,$groupId);

        foreach ($groups as $group) {
            $board['groups'][$group->id]['title']=$group->title;

            $cursor=false;
            do{
                if ($cursor){
                    $query="
                    {
                      next_items_page (limit:$limit,cursor:".'"'.$cursor.'"'.")
                      {
                        cursor 
                        items
                        {
                          id 
                          name 
                          column_values 
                          {
                            id 
                            text
                            value 
                            type 
                            column 
                            {
                              title
                              settings_str
                            }
                          }
                          updates{body id creator {name id}}  
                        }
                      }
                    }";
                    $items_page=json_decode($monday->apiCallMD($query))->data->next_items_page ;
                }
                else{
                    $query="
                    {
                      boards (ids: $boardId)
                      {
                        groups (ids:".'"'.$group->id.'"'.")
                        {
                          id 
                          title 
                          items_page (limit:$limit)
                          {
                            cursor 
                            items
                            {
                              id 
                              name 
                              column_values 
                              {
                                id 
                                text
                                value 
                                type 
                                column 
                                {
                                  title
                                  settings_str
                                }
                              }
                              updates{body id creator {name id}}  
                            }
                          }
                        }
                      }
                      complexity { before after}
                    }";  
                    $items_page=json_decode($monday->apiCallMD($query))->data->boards[0]->groups[0]->items_page;
                    foreach ($items_page->items as $item) {
                        $fields=$this->splitItem(['name'=>'Elemento','value'=>$item->name,'detail'=>$item->name,'type'=>'text']);
                        $board['groups'][$group->id]['items'][$item->id]['elemento']=$fields;
                        foreach ($item->column_values as $column) {
                            if (strcmp($column->type,"subtasks")!=0)
                            {
                                $fields=$this->splitItem(['name'=>$column->column->title,'value'=>$column->text,'detail'=>array('value'=>$column->value,'settings_str'=>$column->column->settings_str),'type'=>$column->type]);
                                $board['groups'][$group->id]['items'][$item->id][$column->id]=$fields;
                            }
                            else
                            {
                              $board['groups'][$group->id]['items'][$item->id]['subitems']=$this->subItems($item->id);
                            }
                        } // foreach $item->column_values
                        foreach($item->updates as $update)
                        {
                            $updates=$this->splitUpdate(['body'=>$update->body,'creator'=>$update->creator->name]);
                            $board['groups'][$group->id]['items'][$item->id]['updates'][$update->id]=$updates;
                        } // foreach $item->updates
                    } // foreach $items_page->items
                }
                $cursor=$items_page->cursor;
            }while ($cursor);
          } //foreach $groups
        return $board;
    } // Function getBoardAllInfo
}
