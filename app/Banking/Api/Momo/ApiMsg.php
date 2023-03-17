<?php

namespace App\Banking\Api\Momo;

use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\RSA;

trait ApiMsg
{
    protected function USER_LOGIN_MSG()
    {
        $microtime = $this->getMicrotime();
        $header=$this->getHeaders('USER_LOGIN_MSG');
        $data = array (
            'user' => $this->config->phone,
            'msgType' => 'USER_LOGIN_MSG',
            'pass' => $this->config->password,
            'cmdId' => (string) $microtime.'000000',
            'lang' => 'vi',
            'time' => $microtime,
            'channel' => 'APP',
            'appVer' => $this->app->appVer,
            'appCode' => $this->app->appCode,
            'deviceOS' => $this->config->device_os,
            'buildNumber' => 0,
            'appId' => 'vn.momo.platform',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' =>
                array (
                    '_class' => 'mservice.backend.entity.msg.LoginMsg',
                    'isSetup' => false,
                ),
            'extra' =>
                array (
                    'pHash' => $this->getPasswordHash(),
                    'AAID' => $this->config->aaid,
                    'IDFA' => '',
                    'TOKEN' => $this->config->token,
                    'SIMULATOR' => '',
                    'SECUREID' => $this->config->secure_id,
                    'MODELID' => $this->config->model_id,
                    'checkSum' => $this->generateChecksum('USER_LOGIN_MSG', $microtime),
                ),
        );
        return $this->CURL("USER_LOGIN_MSG",$header,$data);
    }

    protected function REFRESH_TOKEN_MSG()
    {
        $microtime = $this->getMicrotime();
        $header=$this->getHeaders('REFRESH_TOKEN_MSG');
        $header['authorization']="Bearer " . $this->config->refresh_token;
        $data = array(
            'user' => $this->config->phone,
            'msgType' => 'REFRESH_TOKEN_MSG',
            'cmdId' => (string) $microtime . '000000',
            'lang' => 'vi',
            'time' => $microtime,
            'channel' => 'APP',
            'appVer' => $this->app->appVer,
            'appCode' => $this->app->appCode,
            'deviceOS' => $this->config->device_os,
            'buildNumber' => 0,
            'appId' => 'vn.momo.platform',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' =>
                array(
                    '_class' => 'mservice.backend.entity.msg.RefreshAccessTokenMsg',
                    'accessToken' => $this->config->auth_token,
                ),
            'extra' =>
                array(
                    'SIMULATOR' => false,
                    'IDFA' => '',
                    'TOKEN' => $this->config->token,
                    'ONESIGNAL_TOKEN'=>'',
                    'SECUREID' => $this->config->secure_id,
                    'MODELID' => $this->config->model_id,
                    'DEVICE_TOKEN'=>$this->config->device_token,
                    'DEVICE_IMEI'=>$this->config->imei,
                    'checkSum' => $this->generateChecksum('REFRESH_TOKEN_MSG', $microtime),
                ),
        );
        return $this->CURL("REFRESH_TOKEN_MSG", $header, $data);
    }

    protected function CHECK_USER_BE_MSG()
    {
        $microtime = $this->getMicrotime();

        $header = $this->getHeaders('CHECK_USER_BE_MSG');
        $data = array (
            'user' => $this->config->phone,
            'msgType' => 'CHECK_USER_BE_MSG',
            'cmdId' => (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => $microtime,
            'channel' => 'APP',
            'appVer' => $this->app->appVer,
            'appCode' => $this->app->appCode,
            'deviceOS' => $this->config->device_os,
            'buildNumber' => 0,
            'appId' => 'vn.momo.platform',
            'result' => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg' =>
                array (
                    '_class' => 'mservice.backend.entity.msg.RegDeviceMsg',
                    'number' => $this->config->phone,
                    'imei' => $this->config->imei,
                    'cname' => 'Vietnam',
                    'ccode' => '084',
                    'device' => $this->config->device,
                    'firmware' => '14.7.1',
                    'hardware' => $this->config->hardware,
                    'manufacture' => $this->config->manufacture,
                    'csp' => 'Viettel',
                    'icc' => '',
                    'mcc' => '0',
                    'mnc' => '0',
                    'device_os' => 'ios',
                    'secure_id' => '',
                ),

        );
        return $this->CURL("CHECK_USER_BE_MSG",$header,$data);

    }

    protected function SEND_OTP_MSG()
    {
        $header = $this->getHeaders('SEND_OTP_MSG');
        $microtime = $this->getMicrotime();
        $data = array (
            'user' => $this->config->phone,
            'msgType' => 'SEND_OTP_MSG',
            'cmdId' => (string) $microtime. '000000',
            'lang' => 'vi',
            'time' => $microtime,
            'channel' => 'APP',
            'appVer' => $this->app->appVer,
            'appCode' => $this->app->appCode,
            'deviceOS' => $this->config->device_os,
            'buildNumber' => 0,
            'appId' => 'vn.momo.platform',
            'momoMsg' =>
                array (
                    '_class' => 'mservice.backend.entity.msg.RegDeviceMsg',
                    'number' => $this->config->phone,
                    'imei' => $this->config->imei,
                    'cname' => 'Vietnam',
                    'ccode' => '084',
                    'device' => $this->config->device,
                    'firmware' => '14.7.1',
                    'hardware' => $this->config->hardware,
                    'manufacture' => $this->config->manufacture,
                    'csp' => 'Viettel',
                    'icc' => '',
                    'mcc' => '0',
                    'mnc' => '0',
                    'device_os' => 'ios',
                    'secure_id' => '',
                ),
            'extra' =>
                array (
                    'action' => 'SEND',
                    'rkey' => $this->config->rkey,
                    'IDFA' => '',
                    'TOKEN' => $this->config->token,
                    'ONESIGNAL_TOKEN' => $this->config->token,
                    'SIMULATOR' => false,
                    'SECUREID' => '',
                    'MODELID' => $this->config->model_id,
                    'DEVICE_TOKEN' =>  $this->config->device_token,
                    'isVoice' => true,
                    'REQUIRE_HASH_STRING_OTP' => true,

                ),
        );

        return $this->CURL("SEND_OTP_MSG",$header,$data);

    }



    protected function REG_DEVICE_MSG()
    {
        $microtime = $this->getMicrotime();

        $header = $this->getHeaders('REG_DEVICE_MSG');
        $data = array (
            'user' => $this->config->phone,
            'msgType'   => 'REG_DEVICE_MSG',
            'cmdId'     => $microtime. '000000',
            'lang'      => 'vi',
            'time'      => $microtime,
            'channel'   => 'APP',
            'appVer'    => $this->app->appVer,
            'appCode'   => $this->app->appCode,
            'deviceOS'  => $this->config->device_os,
            // 'buildNumber' => 0,
            // 'appId'     => 'vn.momo.platform',
            'result'    => true,
            'errorCode' => 0,
            'errorDesc' => '',
            'momoMsg'   =>
                array (
                    '_class' => 'mservice.backend.entity.msg.RegDeviceMsg',
                    'number' => $this->config->phone,
                    'imei'   => $this->config->imei,
                    'cname'  => 'Vietnam',
                    'ccode'  => '084',
                    'device' => $this->config->device,
                    'firmware'    => '23',
                    'hardware'    => $this->config->hardware,
                    'manufacture' => $this->config->manufacture,
                    'csp' => '',
                    'icc' => '',
                    'mcc' => '',
                    'device_os' => 'Android',
                    'secure_id' => $this->config->secure_id,
                ),
            'extra' =>
                array (
                    'ohash'     => $this->config->ohash,
                    'AAID'      => '',
                    'IDFA'      => '',
                    'TOKEN'     => '',
                    'SIMULATOR' => 'false',
                    'SECUREID'  => $this->config->secure_id,
                ),
        );
        return $this->CURL("REG_DEVICE_MSG",$header,$data);

    }
    protected function CLOUD_USER_NOTI($from,$to,$cursor='',$limit=200){
        $header = $this->getHeaders('');
        $data = array(
            'userId'=>$this->config->phone,
            'fromTime'=> (int) $from * 1000,
            'toTime'  => (int) $to * 1000,
            'limit'   => $limit,
            'cursor'  => $cursor,
        );

        return  $this->CURL("CLOUD_USER_NOTI",$header,$data);
    }
    private function CURL($Action,$header,$data)
    {
        $Data = is_array($data) ? json_encode($data) : $data;

        $curl = curl_init();
        $opt = array(
            CURLOPT_URL =>$this->URLAction[$Action],
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_POST => empty($data) ? FALSE : TRUE,
            CURLOPT_POSTFIELDS => $Data,
            CURLOPT_CUSTOMREQUEST => empty($data) ? 'GET' : 'POST',
            CURLOPT_HTTPHEADER => $this->formatHeader($header),
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            // CURLOPT_PROXY   => $this->proxy
        );
        curl_setopt_array($curl,$opt);

        $body = curl_exec($curl);
        if(curl_errno($curl) != 0){
            return null;
        }
        if(is_object(json_decode($body))){
            return json_decode($body,true);
        }
        if($body){
            return json_decode($this->encryptDecrypt($body,$this->app->token,'DECRYPT'),true);
        }
        return null;

    }
    protected function formatHeader($headers){
        if(isset($headers[0])){
            return $headers;
        }
        $formatted=[];
        foreach ($headers as $k=>$v){
            $formatted[]=sprintf('%s: %s',$k,$v);
        }
        return $formatted;
    }



    private function generateToken()
    {
        return  $this->generateRandom(22).':'.$this->generateRandom(9).'-'.$this->generateRandom(20).'-'.$this->generateRandom(12).'-'.$this->generateRandom(7).'-'.$this->generateRandom(7).'-'.$this->generateRandom(53).'-'.$this->generateRandom(9).'_'.$this->generateRandom(11).'-'.$this->generateRandom(4);
    }


    public function generateChecksum($type, $microtime)
    {
        $Encrypt =   $this->config->phone.$microtime.'000000'.$type. ($microtime / 1000000000000.0) . 'E12';
        $iv = pack('C*', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        return base64_encode(openssl_encrypt($Encrypt, 'AES-256-CBC',$this->config->setupKeyDecrypt, OPENSSL_RAW_DATA, $iv));
    }

    private function getPasswordHash()
    {
        $data = $this->config->imei."|".$this->config->password;
        $iv = pack('C*', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        return base64_encode(openssl_encrypt($data, 'AES-256-CBC',$this->config->setupKeyDecrypt, OPENSSL_RAW_DATA, $iv));
    }

    public function decryptSetupKey($setUpKey)
    {
        $iv = pack('C*', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        return openssl_decrypt(base64_decode($setUpKey), 'AES-256-CBC',$this->config->ohash, OPENSSL_RAW_DATA, $iv);
    }

    private function generateRandom($length = 20)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    public function getMicrotime() {
        return round(microtime(true) * 1000);
    }
    function encryptDecrypt($data, $key, $mode = 'ENCRYPT') {
        if (strlen($key) < 32) {
            $key = str_pad($key, 32, 'x');
        }
        $key = substr($key, 0, 32);
        $iv = pack('C*', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        if ($mode === 'ENCRYPT') {
            return base64_encode(openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv));
        } else {
            return openssl_decrypt(base64_decode($data), 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        }
    }

    function encryptAppKeyUsingPublicKey($public_key) {
        $rsa=PublicKeyLoader::load($public_key);
        $rsa=$rsa->withPadding(RSA::ENCRYPTION_PKCS1)->withHash('sha1')->withMGFHash('sha1');
        return base64_encode($rsa->encrypt($this->app->token));
    }
}