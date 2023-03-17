<script>
    window.addEventListener('load', function() {
        (function ($){
            var order_id={{$order_id}};
            var status='{{$status}}';
            var view_url='{{$view_url}}';
            var order_key='{{$order_key}}';
            var checkInterval=2000;
            var getStatus=function (){
                return $.get(woocommerce_params.ajax_url,{
                    order_key:order_key,
                    order_id:order_id,
                    action:'ws_order_status'
                });
            }
            var checkStatus=function(){
                getStatus().then(function(rp){
                    if(rp.data && rp.data.status && (rp.data.status==='completed' || rp.data.status==='processing')){
                        if(view_url) {
                            window.location.href = view_url;
                        }
                    }else{
                        setTimeout(function(){
                            checkStatus();
                        },checkInterval);
                    }
                },function(){
                    setTimeout(function(){
                        checkStatus();
                    },checkInterval);
                });
            }
            if(status==='completed'){
                if(view_url) {
                    window.location.href = view_url;
                }
            }else{
                checkStatus();
            }
        })(jQuery);
    });

</script>