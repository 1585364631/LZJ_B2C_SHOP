<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

function user_func(): string
{
    return 'hello';
}

function get_time(){
    $datatime = new \DateTime();
    return $datatime->format('Y-m-d H:i:s');
}

function getfromid(){
    $order_id_main = date('YmdHis') . rand(10000,99999);
    $order_id_len = strlen($order_id_main);
    $order_id_sum = 0;
    for($i=0; $i<$order_id_len; $i++){
        $order_id_sum += (int)(substr($order_id_main,$i,1));
    }
    $osn = $order_id_main . str_pad(strval((100 - $order_id_sum % 100) % 100),2,'0',STR_PAD_LEFT);
    return $osn;
}

function get_token(){
    return rand(1000000000,9999999999) . rand(1000000000,9999999999);
}

function datainjson($sz){
    $data=[];
    $in = 0;
    foreach ($sz as $i=>$ii){
        foreach ($ii as $j=>$k){
            if (!is_numeric($j)){
                $data[$in][$j] = $k;
            }
        }
        $in++;
    }
    return $data;
}
