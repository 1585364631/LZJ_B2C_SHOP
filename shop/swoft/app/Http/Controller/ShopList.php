<?php

namespace App\Http\Controller;

use App\libs\Mycat;
use App\libs\Mycat_short;
use PHPUnit\Util\Json;
use Swoft\Context\Context;
use Swoft\Db\DB;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;
use Swoft\Redis\Redis;

/**
 * @Controller(prefix="shoplist")
 */
class ShopList{
    /**
     * @RequestMapping(route="mycat",method={RequestMethod::GET, RequestMethod::POST})
     */
    public function text_mycat(){
        $mycat = Mycat::get_mycat();
        if ($mycat->pdo_ping()){
            return "数据库正在连接" . $mycat->get_date();
        }
        return "数据库连接失败" . $mycat->get_date();
    }

    /**
     * @RequestMapping(route="index",method={RequestMethod::GET, RequestMethod::POST})
     */
    public function shop_index(){
        $mycat = Mycat::get_mycat();
        $sz = $mycat->selectall("select `id`,`nowprice`,`shopinfotitle`,`indeximg` from shop_list where `recommend` = '1' order by `updatetime` desc limit 0,5");
        $data = [];
        foreach ($sz as $i=>$ii){
            $data[] = ["id"=>$ii['id'],'price'=>$ii['nowprice'],'title'=>$ii['shopinfotitle'],'img_url'=>$ii['indeximg'],'show_title'=>true,'show_price'=>true];
        }
        return json_encode($data);
    }


    /**
     * @RequestMapping(route="home",method={RequestMethod::GET, RequestMethod::POST})
     */
    public function shop_home(){
        $mycat = Mycat::get_mycat();
        $sz = $mycat->selectall("select shop_list.id,shop_list.nowprice,shop_list.shopinfotitle,shop_list.shophomeimg,shop_list.name as shopname,shop_two_class.parent as oneclass ,shop_two_class.name as twoclass from shop_list,shop_two_class where shop_list.class = shop_two_class.name order by `updatetime` desc");
        $data = [];
        foreach ($sz as $i=>$ii){
            $data[$ii['twoclass']][] = ["id"=>$ii['id'],'price'=>$ii['nowprice'],'title'=>$ii['shopinfotitle'],'img_url'=>$ii['shophomeimg']];
        }
        $data1 = [];
        foreach ($data as $i=>$ii){
            $data1[] = ["class"=>$i,"data"=>$ii];
        }
        return json_encode($data1);
    }

    /**
     * @RequestMapping(route="homeimg",method={RequestMethod::GET, RequestMethod::POST})
     */
    public function shop_home_img(){
        $mycat = Mycat::get_mycat();
        $sz = $mycat->selectall("select `id`,`nowprice`,`shopinfotitle`,`homeimg` from shop_list where `recommend` = '2' order by `updatetime` desc limit 0,5");
        $data = [];
        foreach ($sz as $i=>$ii){
            $data[] = ["id"=>$ii['id'],'price'=>$ii['nowprice'],'title'=>$ii['shopinfotitle'],'img_url'=>$ii['homeimg'],'show_title'=>false,'show_price'=>false];
        }
        return json_encode($data);
    }

    /**
     * @RequestMapping(route="class",method={RequestMethod::GET, RequestMethod::POST})
     */
    public function shop_class(){
        $mycat = Mycat::get_mycat();
        $sz = $mycat->selectall("select shop_one_class.id as oneid,shop_one_class.name as onename,shop_two_class.name as twoname from shop_one_class,shop_two_class where shop_one_class.name=shop_two_class.parent");
        $classid = [];
        foreach ($sz as $i){
            $classid[$i['onename']] = ['id'=>$i['oneid']];
        }
        $sz = $mycat->selectall("select shop_list.id,shop_list.nowprice,shop_list.shopinfotitle,shop_list.shoplistimg,shop_list.name as shopname,shop_two_class.parent as oneclass,shop_two_class.name as twoclass,shop_one_class.id as oneid from shop_list,shop_two_class,shop_one_class where shop_list.class=shop_two_class.name and shop_two_class.parent=shop_one_class.name order by `updatetime` desc");
        $data = [];
        foreach ($sz as $i=>$ii){
            $data[$ii['oneclass']][$ii['twoclass']][] = ["id"=>$ii['id'],'price'=>$ii['nowprice'],'title'=>$ii['shopinfotitle'],'img_url'=>$ii['shoplistimg'],'oneid'=>$ii['oneid']];
        }
        $data1 = [];
        foreach ($data as $i=>$ii){
            $data1[$i] = ["class"=>$i,"id"=>$classid[$i]['id'],"show"=>false,"data"=>[]];
            foreach ($ii as $j=>$k){
                $data1[$i]["data"][] = ["class"=>$j,"data"=>$k];
            }
        }
        return json_encode(array_values($data1));
    }

    /**
     * @RequestMapping(route="info/{id}",method={RequestMethod::GET, RequestMethod::POST})
     */
    public function shop_info(int $id){
        $mycat = Mycat::get_mycat();
        $sz = $mycat->selectone("select id,name,nowprice,lastprice,fare,shopinfoimg,introduce from shop_list where `id`= $id  order by updatetime;");
        $data = [];
        $data["shop_info"]['id'] = $sz['id'];
        $data["shop_info"]['title'] = $sz['name'];
        $data["shop_info"]['now_price'] =  floatval($sz['nowprice']);
        $data["shop_info"]['last_price'] = floatval($sz['lastprice']);
        $data["shop_info"]['fare'] = floatval($sz['fare']);
        $data["shop_info"]['text'] = $sz['introduce'];
        foreach (explode(",",$sz['shopinfoimg']) as $item) {
            $data['lunbo_data'][]['img_url']=$item;
        }
        return json_encode($data);
    }

    /**
     * @RequestMapping(route="collect",method={ RequestMethod::POST})
     */
    public function shop_collect(){
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
        $sql = "select shopid,username from shop_collect where `shopid`='$id' and `username`='$info'";
        $msg =  $mycat->query($sql);
        if ($msg->fetch()){
            return json_encode(["token"=>true,"collect"=>true]);
        }else{
            return json_encode(["token"=>true,"collect"=>false]);
        }

    }

    /**
     * @RequestMapping(route="addcollect",method={ RequestMethod::POST})
     */
    public function shop_change_collect(){
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
        $msg = $mycat->query("select id,shopid,username from shop_collect where `shopid`='$id' and `username`='$info'");
        if ($id1=$msg->fetch()){
            $id1 = $id1['id'];
            $mycat->query("delete from shop_collect where `id`='$id1'");
            return json_encode(["token"=>true,"collect"=>false]);
        }
        $sql = "insert into `shop_collect` (`shopid`,`username`) values ('$id','$info')";
        $mycat->query($sql);
        return json_encode(["token"=>true,"collect"=>true]);
    }

}
