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
 *  @Controller(prefix="shopaddress")
 */
class ShopAddress
{
    /**
     * @RequestMapping(route="list",method={RequestMethod::POST})
     */
    public function addresslist(){
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
        $sz = $mycat->selectall("select * from `shop_address` where `user`='$userid'");
        $deafultid = $mycat->selectone("select address from shop_user where `id`='$userid'")['address'];

        $data = [];
        $lock = true;
        foreach ($sz as $item){
            if ($deafultid==""){
                $deafultid=$item["id"];
                $lock=false;
                $mycat->query("update shop_user set address='$deafultid' where `id`='$userid'");
            }
            if ($deafultid==$item["id"]){
                $lock = false;
            }
            $data[] = ["id"=>$item['id'],"data"=>[[
                'tip'=> "收货人",
              'value'=> $item["name"]
            ],
            [
                'tip'=> "手机号码",
              'value'=> $item["phone"]
            ],
            [
                'tip'=> "地区信息",
              'value'=> $item['region']
            ],
            [
                'tip'=> "详细地址",
              'value'=> $item['address']
            ],
            [
                'tip'=> "邮政编码",
              'value'=> $item['code']
            ]]];
        }
        if ($lock){
            $deafultid = $sz[0]['id'];
            $mycat->query("update shop_user set address='$deafultid' where `id`='$userid'");
        }

        return json_encode(["token"=>true,"data"=>$data,"defaultid"=>$deafultid]);
    }


    /**
     * @RequestMapping(route="add",method={RequestMethod::POST})
     */
    public function addaddress(){
        $request = Context::get()->getRequest();
        $token=$request->post("token","");
        $data = $request->post("data","");
        if ($token==""||$data==""){
            return json_encode(["token"=>false,"text"=>"必须字段为空"]);
        }
        $data = json_decode($data,true)['data'];
        $info = Redis::get($token);
        if ($info==""){
            return json_encode(["token"=>false]);
        }
        Redis::setex($token,7200,$info);
        $mycat = Mycat::get_mycat();
        $userid = $mycat->selectone("select `id` from shop_user where `username`='$info'")['id'];

        $sql = "insert into shop_address (`user`,`name`,`phone`,`region`,`address`,`code`) values ('$userid','{$data[0]['value']}','{$data[1]['value']}','{$data[2]['value']}','{$data[3]['value']}','{$data[4]['value']}')";
        $mycat->query($sql);
        $sql = "select `id` from `shop_address` where `user`='$userid' and `name`='{$data[0]['value']}' and `phone`='{$data[1]['value']}' and `region`='{$data[2]['value']}' and `address`='{$data[3]['value']}' and `code`='{$data[4]['value']}'";
        $localid = $mycat->selectone($sql)['id'];
        return json_encode(["token"=>true,"id"=>$localid]);
    }


    /**
     *  @RequestMapping(route="update",method={RequestMethod::POST})
     */
    public function updateaddress(){
        $request = Context::get()->getRequest();
        $token=$request->post("token","");
        $data = $request->post("data","");
        if ($token==""||$data==""){
            return json_encode(["token"=>false,"text"=>"必须字段为空"]);
        }
        $data = json_decode($data,true);
        $id = $data['id'];
        $data = $data['data'];
        $info = Redis::get($token);
        if ($info==""){
            return json_encode(["token"=>false]);
        }
        Redis::setex($token,7200,$info);
        $mycat = Mycat::get_mycat();
        $userid = $mycat->selectone("select `id` from shop_user where `username`='$info'")['id'];
        if ($mycat->query("select `id` from shop_address where `user`='$userid' and `id`='$id'")->fetch()){
            $sql="update shop_address set name='{$data[0]['value']}',phone='{$data[1]['value']}',region='{$data[2]['value']}',address='{$data[3]['value']}',code='{$data[4]['value']}' where id='$id'";
            $mycat->query($sql);
            return json_encode(["token"=>true,"id"=>$id,"text"=>"修改成功"]);
        }
         return json_encode(["token"=>true,"id"=>$id,"text"=>"修改失败"]);
    }

    /**
     * @RequestMapping(route="updatedefault",method={RequestMethod::POST})
     */
    public function updatedefault(){
        $request = Context::get()->getRequest();
        $token=$request->post("token","");
        $id=$request->post("id","");
        if ($token==""||$id==""){
            return json_encode(["token"=>false,"text"=>"必须字段为空"]);
        }
        $info = Redis::get($token);
        if ($info==""){
            return json_encode(["token"=>false]);
        }
        Redis::setex($token,7200,$info);
        $mycat = Mycat::get_mycat();
        $userid = $mycat->selectone("select `id` from shop_user where `username`='$info'")['id'];
        $sz = $mycat->selectall("select * from `shop_address` where `user`='$userid'");
        foreach ($sz as $item){
            if ($id==$item['id']){
                $sql="update `shop_user` set `address`='$id' where `id`='$userid'";
                $mycat->query($sql);
                return json_encode(["token"=>true,"text"=>"修改成功"]);
            }
        }
        return json_encode(["token"=>true,"text"=>"id不在列表内"]);
    }

    /**
     * @RequestMapping(route="delete",method={RequestMethod::POST})
     */
    public function deleteaddress(){
        $request = Context::get()->getRequest();
        $token=$request->post("token","");
        $id=$request->post("id","");
        if ($token==""||$id==""){
            return json_encode(["token"=>false,"text"=>"必须字段为空"]);
        }
        $info = Redis::get($token);
        if ($info==""){
            return json_encode(["token"=>false]);
        }
        Redis::setex($token,7200,$info);
        $mycat = Mycat::get_mycat();
        $userid = $mycat->selectone("select `id` from shop_user where `username`='$info'")['id'];
        $sz = $mycat->selectall("select * from `shop_address` where `user`='$userid'");
        $lock = false;
        foreach ($sz as $item){
            if ($id==$item['id']){
                $lock=true;
                break;
            }
        }
        if ($lock){
            $sql="delete from `shop_address` where `id`='$id'";
            $mycat->query($sql);
            return json_encode(["token"=>true,"text"=>"修改成功"]);
        }
        return json_encode(["token"=>true,"text"=>"id不在列表内"]);
    }

}
