## Tải về

- Tải plugin về và giải nén vào thư mục gốc của Wordpress (cùng cấp với wp-content, wp-admin)
- Tên thư mục sau khi giải nén phải là ws-momo
- Tạo file wp-content/plugins/ws-momo-plugin.php với nội dung
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




## Cài đặt


Hãy di chuyển vào thư mục `ws-momo` và chạy các lệnh sau để cài đặt

```shell
composer install
php -r "file_exists('.env') || copy('.env.example', '.env');"
php artisan key:generate --ansi
php artisan migrate
```

Kết nối momo:
```
php artisan momo connect
```

Kích hoạt plugin và bật phương thức thanh toán ở `woocommerce`

## Cài cronjob

Để hệ thống tự động update order ngay cả khi người dùng tắt trình duyệt thì cần cài cronjob như sau

```
* * * * * cd /path-to-ws-momo && php artisan schedule:run >> /dev/null 2>&1
```
## Cấu hình
Bạn có thể thay đổi cấu hình ở file `config/banking.php`

```
'rate'=>20000,
'prefix'=>[
    'invoice'=>'Momo'
],
```

Bạn có thể đặt tỉ giá nếu loại tiền tệ thanh toán trên website không phải VNĐ

Bạn có thể thay đổi mã hóa đơn `Momo` thành 1 chuỗi khác, khi đó nội dung chuyển tiền sẽ có dạng {prefix}+orderid

## Hỗ trợ
Cần hỗ trợ thêm thông tin vui lòng đặt câu hỏi tại group
https://www.facebook.com/groups/wpstarter