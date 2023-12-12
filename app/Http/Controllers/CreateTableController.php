<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CreateTableController extends Controller
{
    public function create_table(){
        Schema::connection('mysql2')->create('go', function($table)
        {
            $table->increments('id');
        });

        return 'success';
    }
}
