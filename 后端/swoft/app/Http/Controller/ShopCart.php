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
 * @Controller(prefix="shopcart")
 */
class ShopCart
{
    /**
     * @RequestMapping(route="list",method={RequestMethod::POST})
     */
    public function cart(){
        $request = Context::get()->getRequest();
        $token=$request->input("token","");
        if ($token==""){
            return json_encode(["token"=>false]);
        }
        $info = Redis::get($token);
        if ($info==""){
            return json_encode(["token"=>false]);
        }
        Redis::setex($token,7200,$info);
        $mycat = Mycat::get_mycat();
        $sql = "select shop_cart.id,shop_cart.addtime,shop_cart.shopid,shop_cart.userid,shop_cart.number,shop_cart.check,shop_list.name,shop_list.nowprice,shop_list.id as shopid1,shop_user.id as userid1,shop_user.username as username,shop_list.shopinfoimg from shop_cart,shop_list,shop_user where shopid=shop_list.id and shop_user.id=userid and shop_user.username='$info' order by addtime desc";
        $msg =  $mycat->selectall($sql);
        $sz = [];
        foreach ($msg as $item){
            if($item['check']==0){
                $check=false;
            }else{
                $check=true;
            }
            $sz[]=["id"=>$item["id"],"title"=>$item["name"],"price"=>$item["nowprice"],"number"=>$item["number"],"sum"=>($item["nowprice"]*$item["number"]),"check"=>$check,"img"=>explode(",",$item["shopinfoimg"])[0]];
        }
        return json_encode(["token"=>true,"data"=>$sz]);
    }

    /**
     * @RequestMapping(route="change",method={RequestMethod::POST})
     */
    public function changecart(){
        $request = Context::get()->getRequest();
        $token=$request->post("token","");
        $id=$request->post("id","");
        $num=$request->post("number","");
        $check=$request->post("check",false);
        if ($token==""||$id==""||$num==""){
            return json_encode(["token"=>false,"text"=>"必须字段为空"]);
        }
        if ($check=='true'){
            $check=1;
        }else{
            $check=0;
        }
        $info = Redis::get($token);
        if ($info==""){
            return json_encode(["token"=>false]);
        }
        Redis::setex($token,7200,$info);
        $mycat = Mycat::get_mycat();
        if(!($mycat->query("select shop_cart.id as cartid,shop_cart.userid,shop_user.id as userid1,shop_user.username from shop_cart,shop_user where shop_cart.userid=shop_user.id and shop_user.username='$info' and shop_cart.id='$id'")->fetch())){
            return json_encode(["token"=>false,"text"=>"id不在允许列表内"]);
        }
        if ($mycat->insert("update shop_cart set `number`='$num',`check`='$check' where `id`=$id")->rowCount()==1){
            return json_encode(["token"=>true,"text"=>"修改成功"]);
        }
        return json_encode(["token"=>false,"text"=>"修改失败"]);
    }

    /**
     * @RequestMapping(route="add",method={RequestMethod::POST})
     */
    public function addcart(){
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
        $addtime= get_time();
        if(!($mycat->query("select shop_cart.id as cartid,shop_cart.userid,shop_user.id as userid1,shop_user.username from shop_cart,shop_user where shop_cart.userid=shop_user.id and shop_user.username='$info' and shop_cart.id='$id'")->fetch())){
            $userid = $mycat->selectone("select id from shop_user where `username`='$info'")['id'];
            $sql = "insert into `shop_cart` (`shopid`,`userid`,`number`,`check`,`addtime`) values ('$id','$userid','1','1','$addtime')";
            $mycat->query($sql);
            return json_encode(["token"=>true,"add"=>true,"time"=>$addtime]);
        }
        return json_encode(["token"=>true,"add"=>true,"time"=>$addtime]);
    }

    /**
     * @RequestMapping(route="del",method={RequestMethod::POST})
     */
    public function delcart(){
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
        $id = explode(";",$id);
        $userid = $mycat->selectone("select `id` from shop_user where `username`='$info'")['id'];
        foreach ($id as $item){
            if ($mycat->query("select id from shop_cart where `id`='$item' and `userid`='$userid'")->fetch()){
                $mycat->query("delete from shop_cart where `id`='$item'");
            }
        }
        return json_encode(["token"=>true]);
    }

    /**
     * @RequestMapping(route="in",method={RequestMethod::POST})
     */
    public function incart(){
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
        if(!($mycat->query("select shop_cart.id as cartid,shop_cart.userid,shop_user.id as userid1,shop_user.username from shop_cart,shop_user where shop_cart.userid=shop_user.id and shop_user.username='$info' and shop_cart.id='$id'")->fetch())){
            return json_encode(["token"=>true,"in"=>false]);
        }
        return json_encode(["token"=>true,"in"=>true]);

    }

}
