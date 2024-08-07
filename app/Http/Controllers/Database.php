<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Database extends Controller
{
    // Insert record (array)$data into database table $table
    public static function insert($table,$data)
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
}
