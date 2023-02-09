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
 * @Controller(prefix="shopchat")
 */
class ShopChat{

    /**
     * @RequestMapping(route="chat",method={RequestMethod::POST})
     */
    public function getchat(){
        $request = Context::get()->getRequest();
        $token=$request->post("token","");
        $people=$request->post("people","1");
        if ($token==""){
            return json_encode(["token"=>false]);
        }
        $info = Redis::get($token);
        if ($info==""){
            return json_encode(["token"=>false]);
        }
        Redis::setex($token,7200,$info);
        $mycat = Mycat::get_mycat();
        $userid = $mycat->selectone("select `id` from shop_user where `username`='$info'")['id'];
        $sql="select * from shop_chat where (`fromid`='$userid' and `acceptid`='$people') or (`fromid`='$people' and `acceptid`='$userid') order by `time`";
        $sz = $mycat->selectall($sql);
        $data = [];
        foreach ($sz as $item){
            if ($item['fromid']==$userid){
                $data[] = ["from"=>true,"text"=>str_replace("\\n","<br>",$item['text'])];
            }else{
                $data[] = ["from"=>false,"text"=>str_replace("\\n","<br>",$item['text'])];
            }
        }
        return json_encode(["token"=>true,"data"=>$data]);
    }

    /**
     * @RequestMapping(route="send",method={RequestMethod::POST})
     */
    public function sendchat(){
        $request = Context::get()->getRequest();
        $token=$request->post("token","");
        $people=$request->post("people","1");
        $text = $request->post("text","");
        if ($token==""||$text==""){
            return json_encode(["token"=>false,"text"=>"必须字段未填"]);
        }
        $info = Redis::get($token);
        if ($info==""){
            return json_encode(["token"=>false]);
        }
        Redis::setex($token,7200,$info);
        $mycat = Mycat::get_mycat();
        $userid = $mycat->selectone("select `id` from shop_user where `username`='$info'")['id'];
        $time = get_time();
        $sql = "insert into shop_chat values (null,'$userid','$people','$text','$time')";
        $mycat->query($sql);
        return json_encode(["token"=>true,"text"=>"发送成功","sql"=>$sql]);
    }
}
