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
 * @Controller(prefix="adminmessage")
 */
class AdminMessage
{

    /**
     * @RequestMapping(route="sendmessage",method={RequestMethod::POST})
     */
    public function sendmessage(){
        $request = Context::get()->getRequest();
        $token=$request->post("token","");

        if ($token==""){
            return json_encode(["token"=>false]);
        }
        $info = Redis::get($token);
        if ($info==""){
            return json_encode(["token"=>false]);
        }
        $admin = explode(";",$info);
        if (count($admin)==2){
            if ($admin[1]!="admin"){
                return json_encode(["token"=>false]);
            }
        }else{
            return json_encode(["token"=>false]);
        }
        Redis::setex($token,7200,$info);
        $id = $admin[0];
        $text = $request->post("text","");
        $userid = $request->post("id","");
        if ($text==""||$userid==""){
            return json_encode(["token"=>true,"text"=>"缺少必备字段"]);
        }
        $time = get_time();
        $sql = "insert into shop_chat values (null,'$id','$userid','$text','$time')";
        $mycat = Mycat::get_mycat();
        $mycat->query(($sql));
        return json_encode(["token"=>true,"text"=>"发送成功"]);
    }


    /**
     * @RequestMapping(route="getmessage",method={RequestMethod::POST})
     */
    public function getmessage(){
        $request = Context::get()->getRequest();
        $token=$request->post("token","");
        if ($token==""){
            return json_encode(["token"=>false]);
        }
        $info = Redis::get($token);
        if ($info==""){
            return json_encode(["token"=>false]);
        }
        $admin = explode(";",$info);
        if (count($admin)==2){
            if ($admin[1]!="admin"){
                return json_encode(["token"=>false]);
            }
        }else{
            return json_encode(["token"=>false]);
        }
        Redis::setex($token,7200,$info);
        $mycat = Mycat::get_mycat();
        $sql="select * from `shop_chat` where `fromid`='{$admin[0]}' or `acceptid`='{$admin[0]}' order by `time`";
        $messagelist = datainjson($mycat->selectall($sql));
        $user = datainjson($mycat->selectall("select * from `shop_user`"));
        $myid = $admin[0];
        $myimg = "";
        foreach ($user as $i){
            if ($i['id']==$myid){
                $myimg = $i['headimg'];
                break;
            }
        }
        if ($myimg==""){
            $myimg="https://cube.elemecdn.com/9/c2/f0ee8a3c7c9638a54940382568c9dpng.png";
        }
        $userlist = [];
        $data = [];
        $data1 = [];
        $data1['myid'] = $myid;
        $data1['myimg'] = $myimg;
        $timelist = [];
        foreach ($messagelist as $i=>$item){
            $fromid = $item['fromid'];
            $acceptid = $item['acceptid'];
            if ($fromid!=$myid){
                if(!in_array($fromid,$userlist)){
                    $userlist["$fromid"] = ["id"=>$fromid];
                }
                $data["$fromid"]['data'][] = ['from'=>false,"text"=>$item['text']];
                $timelist["$fromid"] = $item['time'];

            }
            if ($acceptid!=$myid && !array_search($acceptid,$userlist)){
                if(!in_array($acceptid,$userlist)){
                    $userlist["$acceptid"] = ["id"=>$acceptid];
                }
                $data["$acceptid"]['data'][] = ['from'=>true,"text"=>$item['text']];
                $timelist["$acceptid"] = $item['time'];
            }
        }
        foreach ($userlist as $item) {
            foreach ($user as $i){
                if ($i['id']==$item['id']){
                    if ($i['headimg']==""){
                        $i['headimg']="https://cube.elemecdn.com/9/c2/f0ee8a3c7c9638a54940382568c9dpng.png";
                    }
                    $data[$item['id']]['id'] = "id_" . $item['id'];
                    $data[$item['id']]['username'] = $i['username'];
                    $data[$item['id']]['img'] = $i['headimg'];
                    $data[$item['id']]['time'] = $timelist[$item['id']];
                    break;
                }
            }
        }
        $data1['data'] = $data;
        return json_encode(["token"=>true,"data"=>$data1]);
    }
}
