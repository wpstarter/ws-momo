<?php

namespace App\Admin\Controllers;

use App\Admin\ListTable\TransactionsListTable;
use WpStarter\Http\Request;
use WpStarter\Wordpress\Admin\Facades\Notice;

class TransactionsController
{
    function index(){
        $table=new TransactionsListTable();
        $table->prepare_items();
        return ws_view('admin::table',['table'=>$table]);
    }
    function getAdd(){

    }
    function postAdd(){

    }
    function getDelete(Request $request){
        Notice::success('Deleted');
        return ws_redirect()->back();
    }
}