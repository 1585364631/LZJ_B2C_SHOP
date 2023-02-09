<?php

namespace App\Http\Controller;
use App\libs\Mycat;
use App\libs\Mycat_short;
use PHPUnit\Exception;
use PHPUnit\Util\Json;
use Swoft\Context\Context;
use Swoft\Db\DB;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;
use Swoft\Redis\Redis;



/**
 *  @Controller(prefix="shopfrom")
 */
class ShopFrom{
    /**
     * @RequestMapping(route="list",method={RequestMethod::POST})
     */
    public function fromlist(){
        $request = Context::get()->getRequest();
        $token=$request->post("token","");
        if ($token==""){
            return json_encode(["token"=>false,"text"=>"必须字段为空"]);
        }
        $info = Redis::get($token);
        if ($info==""){
            return json_encode(["token"=>false]);
        }
        Redis::setex($token,7200,$info);
        $mycat = Mycat::get_mycat();
        $userid = $mycat->selectone("select `id` from shop_user where `username`='$info'")['id'];
        $sql = "select shop_from.fromid,shop_from.status,shop_from.price,shop_from.time,shop_from.shopid,shop_from.userid,shop_list.shopinfoimg,shop_list.id as shopid1 from shop_from,shop_list where shop_from.shopid=shop_list.id and shop_from.userid='$userid' order by `time` desc";
        $sz = $mycat->selectall($sql);
        $data = [];
        foreach ($sz as $item){
            $data[] = ["id"=>$item['fromid'],"status"=>$item['status'],"price"=>$item['price'],"time"=>$item['time'],"img"=>explode(",",$item['shopinfoimg'])[0]];
        }
        return  json_encode(["token"=>true,"data"=>$data]);
    }

    /**
     * @RequestMapping(route="shop",method={RequestMethod::POST})
     */
    public function shopinfo(){
        $request = Context::get()->getRequest();
        $token=$request->post("token","");
        $id = $request->post("id","");
        if ($token==""||$id==""){
            return json_encode(["token"=>false,"text"=>"必须字段为空"]);
        }
        $info = Redis::get($token);
        if ($info==""){
            return json_encode(["token"=>false]);
        }
        Redis::setex($token,7200,$info);
        $mycat = Mycat::get_mycat();
        $sql="select id,shopinfoimg,shopinfotitle,fare,nowprice from shop_list where id='$id'";
        $data = $mycat->selectone($sql);
        $fromid = getfromid();
        Redis::setex($fromid,1800,$info);
        $newdata = ["id"=>$fromid,"shopid"=>$data['id'],"img"=>explode(",",$data['shopinfoimg'])[0],"title"=>$data['shopinfotitle'],"postage"=>$data['fare'],"price"=>$data['nowprice'],"sum"=>1];
        return json_encode(["token"=>true,"data"=>$newdata]);
    }

    /**
     * @RequestMapping(route="add",method={RequestMethod::POST})
     */
    public function addfrom(){
        $request = Context::get()->getRequest();
        $token=$request->post("token","");
        $data = $request->post("data","");
        if ($token==""||$data==""){
            return json_encode(["token"=>false,"text"=>"必须字段为空"]);
        }
        $info = Redis::get($token);
        if ($info==""){
            return json_encode(["token"=>false]);
        }
        Redis::setex($token,7200,$info);
        $mycat = Mycat::get_mycat();
        $data = json_decode($data,true);
        $fromid = Redis::get($data['fromid']);
        if (($fromid=="")||($info!=$fromid)){
            return json_encode(["token"=>true,"text"=>"表单信息错误"]);
        }
        $userid = $mycat->selectone("select `id` from shop_user where `username`='$info'")['id'];
        $time = get_time();
        $shopinfo = "收件人：" . $data['name'] . ";;;手机号码：" . $data["phone"] . ";;;地区：" . $data['region'] . ";;;详细地址：" . $data['address'] . ";;;邮政编码：" . $data["code"] . ";;;收货时间" . $data["sendtime"];
        ;
        $cacheprice = $mycat->selectone("select `nowprice`,`fare` from shop_list where `id`='{$data['shopid']}'");
        $price = $cacheprice['nowprice'];
        $fare = $cacheprice['fare'];
        if ($fare==""){
            $fare=0;
        }
        $price = floor(($price*$data['num']+$fare)*100)/100;
        $sql = "insert into `shop_from` values (null,'{$data['fromid']}','未付款','$price','$time','{$data['shopid']}','$userid','$shopinfo')";
        $mycat->query($sql);
        $key = get_token();
        Redis::setex($data['fromid'],108000,$key);
        return json_encode(["token"=>true,"text"=>"订单创建成功","id"=>$data['fromid'],"key"=>$key]);
    }

    /**
     *  @RequestMapping(route="update",method={RequestMethod::POST})
     */

    public function updatefrom(){
        $request = Context::get()->getRequest();
        $token=$request->post("token","");
        $id = $request->post("id","");
        $key = $request->post("key","");
        if ($token==""||$id==""){
            return json_encode(["token"=>false,"text"=>"必须字段为空"]);
        }
        $info = Redis::get($token);
        $key = Redis::get($key);
        if ($info==""){
            return json_encode(["token"=>false]);
        }
        Redis::setex($token,7200,$info);
        $mycat = Mycat::get_mycat();
        $userid = $mycat->selectone("select `id` from shop_user where `username`='$info'")['id'];
        $sql = "select * from shop_from where `fromid`='$id' and `userid`='$userid'";
        if ($ls = $mycat->query($sql)->fetch()){
            if($ls['userid']!=$userid){
                if($key==""){
                    return json_encode(["token"=>false,"text"=>"错误的授权码"]);
                }
            }
            $sql = "select `shopinfotitle`,`fare` from `shop_list` where `id`='{$ls['shopid']}'";
            $data = $mycat->selectone($sql);
            $time = get_time();
            $shopinfo[] = ["title"=>"商品信息","text"=>$data['shopinfotitle']];
            $shopinfo[] = ["title"=>"商品总价","text"=>floor(($ls['price']-$data['fare'])*100)/100,"price"=>true];
            $shopinfo[] = ["title"=>"邮费","text"=>floor(($data['fare'])*100)/100,"price"=>true];
            $shopinfo[] = ["title"=>"应付总价","text"=>floor(($ls['price'])*100)/100,"price"=>true];
            $shopinfo[] = ["title"=>"实际付款","text"=>floor(($ls['price'])*100)/100,"price"=>true];
            $shopinfo[] = ["title"=>"交易时间","text"=>$time];
            $mycat->query("update shop_from set `status`='订单生成' where `id`='{$ls['id']}'");



            return json_encode(["token"=>true,"data"=>$shopinfo]);
        }
        return json_encode(["token"=>false,"text"=>"订单不存在"]);
    }




}

