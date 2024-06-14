<?php

namespace App\Http\Controllers\MD;

use App\Http\Controllers\Controller;
use App\Models\MD\BoardValue;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function item($id)
    {
        $recordId=(BoardValue::get()->where('column_id','n_meros__1')->where('value',$id))->first()->record_id;

        $cols=BoardValue::join('columns','column_id','=','columns.id')->select('columns.order','columns.title','board_values.value')->where('record_id',$recordId)->orderBy('columns.order')->get();

        foreach ($cols as $col)
          $data[$col->title]=$col->value; 
        return (view('MT.item',compact('data')));
    }

    /**
     * Display a listing of the resource.
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
