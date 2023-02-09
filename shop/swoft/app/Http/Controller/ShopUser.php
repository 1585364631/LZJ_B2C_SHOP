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
 * @Controller(prefix="shopuser")
 */
class ShopUser
{
    /**
     * @RequestMapping(route="registry",method={RequestMethod::POST})
     */
    public function register(){
        $request = Context::get()->getRequest();
        $name = $request->post("name","");
        $username = $request->post("username","");
        $password = $request->post("password","");
        $token = $request->post("token","");
        if ($name==""||$username==""||$password==""){
            return json_encode(["registry"=>false,"text"=>"注册格式不规范"]);
        }
        if ($token==""){
            return json_encode(["registry"=>false,"text"=>"您的浏览器不支持cookie"]);
        }
        $mycat = Mycat::get_mycat();
        $msg =  $mycat->query('select * from shop_user where username="' . $username . '"');
        if ($msg->fetch()){
            return json_encode(["registry"=>false,"text"=>"用户名已存在"]);
        }
        if ($mycat->insert('insert into `shop_user` (`username`,`password`,`name`,`headimg`) values ("' . $username .'","' . $password . '","' . $name . '","http://static.000081.xyz/img/sc_14_1.jpg")')->rowCount()==1){
            Redis::setex($token,7200,$username);
            return json_encode(["registry"=>true,"text"=>"注册成功"]);
        }
        return json_encode(["registry"=>false,"text"=>"注册失败"]);
    }

    /**
     * @RequestMapping(route="login",method={RequestMethod::POST})
     */
    public function login(){
        $request = Context::get()->getRequest();
        $username = $request->post("username","");
        $password = $request->post("password","");
        $token = $request->post("token","");
        if ($username==""||$password==""){
            return json_encode(["login"=>false,"text"=>"注册格式不规范"]);
        }
        if ($token==""){
            return json_encode(["login"=>false,"text"=>"您的浏览器不支持cookie"]);
        }
        $mycat = Mycat::get_mycat();
        $msg =  $mycat->query('select * from shop_user where `username`="' . $username . '" and `password`="' . $password . '"');
        if ($ls = $msg->fetch()){
            if ($ls['admin']==1){
                return json_encode(["login"=>false,"text"=>"该账号为后台客服账号"]);
            }
            Redis::setex($token,7200,$username);
            return json_encode(["login"=>true,"text"=>"登录成功"]);
        }
        return json_encode(["login"=>false,"text"=>"账号密码错误"]);
    }

    /**
     * @RequestMapping(route="info",method={RequestMethod::POST})
     */
    public function getinfo(){
        $request = Context::get()->getRequest();
        $token=$request->input("token","");
        if ($token==""){
            return json_encode(["token"=>false]);
        }
        $info = Redis::get($token);
        if ($info==""){
            return json_encode(["token"=>false]);
        }
        $mycat = Mycat::get_mycat();
        $msg =  $mycat->selectone("select name,headimg from shop_user where username='$info'");
        Redis::setex($token,7200,$info);
        $data = [
            "name"=>$msg['name'],
            "img"=>$msg['headimg']
        ];
        return json_encode(["token"=>true,"data"=>$data]);

    }

    /**
     * @RequestMapping(route="token",method={RequestMethod::GET, RequestMethod::POST})
     */
    public function token_status(){
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
        return json_encode(["token"=>true]);
    }



}
