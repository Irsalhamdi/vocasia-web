<?php


function pusher_notification($action = null, $order_id = null)
{
    $options = array(
        'cluster' => 'ap1',
        'useTLS' => true
    );
    $pusher = new Pusher\Pusher(
        '133d68f1076d4f3fa367',
        '8114086e5e5c20cc7ff9',
        '1314175',
        $options
    );
    $data['message'] = event_list_notification($action, $order_id);
    $pusher->trigger('payment-channel', 'paid-event', $data);
}

function event_list_notification($event, $order_id = null)
{
    if ($event == 'before-paid') {
        $data = 'Pesanan #' . $order_id .  ' Berhasil Dibuat Silahkan Lakukan Pembayaranmu !';
        return $data;
    }
    if ($event == 'after-paid') {
        $data = 'Pembayaran Pesanan #' . $order_id .  ' Berhasil !';
        return $data;
    }
    if ($event == 'fail-paid') {
        $data = 'Pembayaran Pesanan Ditolak !';
        return $data;
    }
}
