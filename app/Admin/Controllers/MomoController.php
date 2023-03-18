<?php

namespace App\Admin\Controllers;

use App\Banking\Api\Momo;
use App\Banking\Api\Momo\MomoApi;
use WpStarter\Http\Request;
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
    function postExport(){
        $data=maybe_serialize(MomoApi::make()->config()->getAttributes());
        $data=base64_encode($data);
        return ws_view('admin::momo-export',['export_data'=>$data]);
    }
    function postImport(Request $request){
        $request->merge(['import_data'=> preg_replace('/\s+/', '', $request->input('import_data',''))]);
        $this->validate($request,[
            'import_data'=>['required',function($name,$value,$fail) use(&$decoded){
                $decode=base64_decode($value);
                if($decode){
                    $decode=maybe_unserialize($decode);
                }
                if(!is_array($decode)){
                    $fail('Dữ liệu nhập vào chưa chính xác!');
                }else{
                    $decoded=$decode;
                }

            }],
        ]);
        foreach ($decoded as $key=>$value){
            MomoApi::make()->config()[$key]=$value;
        }
        Notice::success('Nhập dữ liệu thành công');
        return ws_redirect()->back();
    }
    function postUpdate(){
        Notice::success('Cập nhật thành công');
        MomoApi::make()->reloadUserData(true);
        return ws_redirect()->back();
    }
    function postDisconnect(){
        MomoApi::make()->config()->clear();
        Notice::success('Ngắt kết nối momo thành công');
        return ws_redirect()->back();
    }
}