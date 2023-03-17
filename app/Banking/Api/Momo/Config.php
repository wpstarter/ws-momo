<?php

namespace App\Banking\Api\Momo;

use WpStarter\Support\Fluent;

/**
 * @property $phone
 * @property $password
 * @property $name
 * @property $gender
 * @property $email
 * @property $imei
 * @property $aaid
 * @property $token
 * @property $refresh_token
 * @property $ohash
 * @property $secure_id
 * @property $rkey
 * @property $bank_code
 * @property $auth_token
 * @property $auth_token_lifetime
 * @property $agent_id
 * @property $setupKeyDecrypt
 * @property $setupKey
 * @property $session_key
 * @property $public_key
 * @property $balance
 * @property $device
 * @property $hardware
 * @property $manufacture
 * @property $model_id
 * @property $logged_in_at
 * @property $DataJson
 * @property $bank_verify
 * @property $wallet_status
 * @property $success
 * @property $messages_sent
 * @property $identify
 * @property $request_encrypt_key
 * @property $device_token
 * @property $device_os
 */
class Config extends Fluent
{
    const OPTION_KEY='momo';
    public function __destruct()
    {
        $this->save();
    }

    public static function load(){
        $configs=get_option(static::OPTION_KEY);
        if(!is_array($configs)){
            $configs=[];
        }
        return new static($configs);
    }
    public function save(){
        update_option('momo',$this->attributes,false);
    }
    public function clear(){
        $this->attributes=[];
        return $this;
    }
}