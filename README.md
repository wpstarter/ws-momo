## Tải về

- Tải plugin về và giải nén vào thư mục gốc của Wordpress (cùng cấp với wp-content, wp-admin)
- Tên thư mục sau khi giải nén phải là ws-momo
- Tạo file wp-content/plugins/ws-momo-plugin.php với nội dung
- Cấu trúc thư mục trông sẽ như sau
```
.                                          
├── wp-admin
├── wp-content
│   ├── plugins
│   │   ├── ws-momo-plugin.php
│   └── ...                                             
├── wp-includes
├── ws-momo
│   ├── app
│   ├── bootstrap                   
│   └── ...
└── ...
```


```php
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
```

## Cài đặt


Hãy di chuyển vào thư mục `ws-momo` và chạy các lệnh sau để cài đặt

```shell
composer install
php artisan migrate
```

Kết nối momo:
```
php artisan momo connect
```
