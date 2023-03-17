<?php

namespace App\Banking\Api\Momo;

class Device
{
    public $imei;
    public $name;
    public $hardware;
    public $manufacture;
    public $model_id;
    public $token;
    public $os;
    public static function random(){
        $device=new static();
        $device->imei='A1625736-B67D-4921-B0AA-9A5C64706BCE';
        $device->name='iPhone 6s';
        $device->hardware='iPhone';
        $device->manufacture='Apple';
        $device->model_id='3579b72290f859c42a61fc473b59b6865e0a9533c07fed9cbb5f0df284fe008d';
        $device->token='4147F3BBDC0316F8B1E48F8954D0E01045BE89F2E9BF9A7BA0C215C9E7EB891C';
        $device->os='ios';
        return $device;
    }
    function getSecureId($length = 17)
    {
        $characters = '0123456789abcdef';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function generateImei()
    {
        return $this->generateRandomString(8) . '-' . $this->generateRandomString(4) . '-' . $this->generateRandomString(4) . '-' . $this->generateRandomString(4) . '-' . $this->generateRandomString(12);
    }

    private function generateRandomString($length = 20)
    {
        $characters = '0123456789abcdef';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}