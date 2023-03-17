<?php

namespace App\Admin\Controllers;

use App\Banking\Api\Momo;
use App\Banking\Api\Momo\MomoApi;
use WpStarter\Support\Carbon;
use WpStarter\Wordpress\Admin\Facades\Notice;

class MomoController extends Controller
{
    function getIndex(){
        $momo=MomoApi::make();
        $config=$momo->config();
        return ws_view('admin::momo',[
            'phone'=>$config->phone,
            'name'=>$config->name,
            'balance'=>wc_price($config->balance,['currency'=>'VND']),
            'updated_at'=>$config->logged_in_at?Carbon::createFromTimestamp($config->logged_in_at)->tz(wp_timezone()):''
        ]);
    }
    function postUpdate(){
        Notice::success('Cập nhật thành công');
        MomoApi::make()->reloadUserData(true);
        return ws_redirect()->back();
    }
}