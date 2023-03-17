<?php
/**
 * Plugin name: WpStarter Momo Payment
 * Plugin URI: https://github.com/wpstarter/ws-momo
 * Description: Phương thức thanh toán momo
 * Author: WpStarter
 * Author URI: https://wpstarter.dev
 */

if(file_exists(ABSPATH.'/ws-momo/main.php')){
    require_once ABSPATH.'/ws-momo/main.php';
}else{
    echo "Không tìm thấy file plugin ".ABSPATH.'/ws-momo/main.php';
}