<?php

namespace App\Console\Commands;

use App\Banking\Api\Momo\Config;
use App\Banking\Api\Momo\MomoApi;
use WpStarter\Console\Command;

class MomoCommand extends Command
{
    protected $signature='momo {action=history} {value=0}';

    function handle(){
        $action=$this->argument('action');
        $value=$this->argument('value');
        $momo=new MomoApi();
        if($action=='clear'){
            $momo->clearConfig();
            $this->info('Đã xóa thông tin Momo');
        }
        if($action=='show'){
            $this->info('Thông tin momo như sau');
            dd($momo->config()->toArray());
        }
        if($action=='history'){
            $this->info('Lịch sử giao dịch gần đây');
            dd($momo->getNotifications());
        }
        if($action==='export'){
            $this->info('Hãy copy chuỗi sau để nhập vào 1 trang wordpress khác nha');
            $data=maybe_serialize($momo->config()->getAttributes());
            $data=base64_encode($data);
            $this->line($data);
            return ;
        }
        if($action==='login' || $action==='connect') {
            if(!$value){
                $value=$this->ask('Nhập số điện thoại');
            }
            $momo=new MomoApi($value);
            $result=$momo->sendOTP();
            if($result['errorCode']==0) {
                $this->line('Hãy kiểm tra điện thoại để lấy mã OTP');
            }else{
                $this->error($result['errorDesc']);
                return ;
            }
            $otp=$this->ask("Nhập mã OTP");
            if(!$otp){
                $this->error('Bạn chưa nhập mã OTP');
                return;
            }
            $result=$momo->setOTP($otp);
            if($result['errorCode']==0) {
                $this->line('Đã nhập OTP: '.$otp);
            }else{
                $this->error($result['errorDesc']);
                return ;
            }
            $pass=$this->ask("Nhập mật khẩu momo của bạn");
            $this->line('Đang đăng nhập');
            $result=$momo->loginUser($pass);
            if($result instanceof Config){
                $this->info('Đăng nhập thành công');
                $this->info('Name: '.$result->name);
                $this->info('Balance: '.$result->balance);
            }else{
                $this->error('Đăng nhập thất bại');
            }
        }

    }
}