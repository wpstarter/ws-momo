<?php

namespace App\Banking\Api\Momo;

use WpStarter\Support\Arr;
use WpStarter\Support\Carbon;

class MomoApi
{
    use ApiMsg;
    protected $config;
    protected $app;
    protected $session_lifetime=3600;

    protected $URLAction = array(
        "CHECK_USER_BE_MSG" => "https://api.momo.vn/backend/auth-app/public/CHECK_USER_BE_MSG",// Init user session
        "SEND_OTP_MSG"      => "https://api.momo.vn/backend/otp-app/public/",//Send OTP
        "REG_DEVICE_MSG"    => "https://api.momo.vn/backend/otp-app/public/REG_DEVICE_MSG",// Register device
        "USER_LOGIN_MSG"     => "https://owa.momo.vn/public/login",// Login
        "CLOUD_USER_NOTI" => "https://m.mservice.io/hydra/v2/user/noti",// Get notifications
        'REFRESH_TOKEN_MSG'    => 'https://api.momo.vn/auth/fast-login/refresh-token',//Refresh token
        'TRANSACTION_HISTORY_LIST'    => 'https://api.momo.vn/sync/transhis/browse',
        'TRANSACTION_HISTORY_DETAIL'    => 'https://api.momo.vn/sync/transhis/details',
    );
    public static function make($phone=null){
        return new static($phone);
    }
    public function __construct($phone=null)
    {
        $this->config=Config::load();
        $this->app=new App();
        $this->init($phone);
    }
    function config(){
        return $this->config;
    }
    function clearConfig(){
        $this->config->clear();
        return $this;
    }

    public function init($phone)
    {
        if($phone && $this->config->phone !== $phone){
            $this->config->clear();
            $this->loadUserData($phone);
        }
    }

    public function loadUserData($phone)
    {
        $device=Device::random();
        $this->config->phone=$phone;
        $this->config->imei=$device->imei;
        $this->config->secure_id=$device->getSecureId();
        $this->config->rkey=$this->generateRandom(20);
        $this->config->aaid=$device->generateImei();
        $this->config->token=$this->generateToken();
        $this->config->device=$device->name;
        $this->config->hardware=$device->hardware;
        $this->config->manufacture=$device->manufacture;
        $this->config->model_id=$device->model_id;
        $this->config->device_token=$device->token;
        $this->config->device_os=$device->os;

        return $this;
    }

    public function initUserSession()
    {
        $result = $this->CHECK_USER_BE_MSG();
        if(empty($result)){
            return array(
                'errorCode' => 500,
                'errorDesc'=> 'Đã xảy ra lỗi máy chủ xin vui lòng thử lại'
            );
        }
        return $result;
    }

    public function sendOTP()
    {
        $this->initUserSession();
        $result = $this->SEND_OTP_MSG();
        return $result;

    }

    public function setOTP($code)
    {
        $this->config->ohash = hash('sha256',$this->config->phone.$this->config->rkey.$code);
        $result = $this->REG_DEVICE_MSG();
        if(empty($result)){
            return array(
                "errorCode"   => 500,
                "errorDesc"=> "Đã xảy ra lỗi máy chủ xin vui lòng thử lại",
            );
        }
        $this->config->setupKey=$result["extra"]["setupKey"];
        $this->config->setupKeyDecrypt=$this->decryptSetupKey($result["extra"]["setupKey"]);

        return $result;
    }



    public function loginUser($password=null)
    {
        if(!empty($password)) {
            $this->config->password = $password;
        }
        $result = $this->USER_LOGIN_MSG();
        if(!empty($result["errorCode"])){
            return array(
                "status" => "error",
                "code"   => $result["errorCode"],
                "message"=> $result["errorDesc"]
            );
        }else if(is_null($result)){
            return array(
                "status"  => "error",
                "code"    => -5,
                "message" => "Hết thời gian truy cập vui lòng đăng nhập lại"
            );
        }
        $extra = $result["extra"];
        $BankVerify = empty($result['momoMsg']['bankVerifyPersonalid']) ? '1' : '2';
        $this->config->name=$result['momoMsg']['name'];
        $this->config->identify=$result['momoMsg']['identify'];
        $this->config->auth_token=$extra['AUTH_TOKEN'];
        $this->config->refresh_token=$extra['REFRESH_TOKEN'];
        $this->config->public_key=$extra["REQUEST_ENCRYPT_KEY"];
        $this->config->request_encrypt_key=trim($this->encryptAppKeyUsingPublicKey($extra['REQUEST_ENCRYPT_KEY']));
        $this->config->bank_verify=$BankVerify;
        $this->config->agent_id=$result["momoMsg"]["agentId"];
        $this->config->balance=$extra['BALANCE'];
        $this->config->bank_code=$result['momoMsg']['bankCode'];
        $this->config->wallet_status=$result['momoMsg']['walletStatus'];
        $this->config->session_key=$extra["SESSION_KEY"];
        $this->config->success='true';
        $this->config->logged_in_at=time();
        $this->config->auth_token_lifetime=$this->session_lifetime;
        return $this->config;
    }
    function reloadUserData($force=false){
        $tokenLifetime=time()-$this->config->logged_in_at;
        if($force || $tokenLifetime>$this->config->auth_token_lifetime) {
            $this->loginUser();
        }
        return false;
    }
    function refreshAccessToken($force=false){
        $tokenLifetime=time()-$this->config->logged_in_at;
        if($force || $tokenLifetime>$this->config->auth_token_lifetime) {
            $response = $this->REFRESH_TOKEN_MSG();
            if ($accessToken = ws_data_get($response, 'momoMsg.accessToken')) {
                $this->config->auth_token = $accessToken;
                return true;
            }
            $this->config->logged_in_at=time();
        }
        return false;
    }






    public function getNotifications($from=null, $to=null, $limit=200)
    {
        $this->reloadUserData();
        if(!$to){
            $to=Carbon::now();
        }
        if(!$to instanceof \DateTimeInterface){
            $to=Carbon::parse($to);
        }
        if(!$from){
            $from=Carbon::now()->subDays(1);
        }
        if(!$from instanceof \DateTimeInterface){
            $from=Carbon::parse($from);
        }
        $result=$this->CLOUD_USER_NOTI($from->timestamp,$to->timestamp,'',$limit);
        $notifications=Arr::get($result,'message.data.notifications',[]);
        return $notifications;

    }



    protected function getHeaders($msgType){
        if($msgType==='REG_DEVICE_MSG'){
            return [
                "authorization: Bearer",
                "msgtype: REG_DEVICE_MSG",
                "Accept: application/json",
                "Content-Type: application/json",
            ];
        }
        $headers=[
            'agent_id'=>$this->config->agent_id,
            "user_phone"=>$this->config->phone,
            "sessionkey"=>$this->config->session_key,
            "authorization"=>"Bearer {$this->config->auth_token}",
            "msgtype"=>$msgType,
            "user_id"=>$this->config->phone,
            "User-Agent"=>"okhttp/3.14.17",
            "app_version"=>$this->app->appVer,
            "app_code"=>$this->app->appCode,
            "device_os"=>$this->config->device_os,
        ];
        return $headers;
    }









}