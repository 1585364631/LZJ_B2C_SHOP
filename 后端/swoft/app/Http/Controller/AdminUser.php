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
 * @Controller(prefix="adminuser")
 */
class AdminUser
{
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
                Redis::setex($token,7200,$ls['id'] . ";admin");
                return json_encode(["login"=>true,"text"=>"登录成功"]);

            }
            return json_encode(["login"=>false,"text"=>"该账号为前台用户账号"]);
        }
        return json_encode(["login"=>false,"text"=>"账号密码错误"]);
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
        $admin = explode(";",$info);
        if (count($admin)==2){
            if ($admin[1]=="admin"){
                Redis::setex($token,7200,$info);
                return json_encode(["token"=>true]);
            }
        }
        return json_encode(["token"=>false]);
    }

    /**
     * @RequestMapping(route="getlist",method={RequestMethod::POST})
     */
    public function getlist(){
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
        $sql = "select * from shop_user order by `id`";
        $data = datainjson($mycat->selectall($sql));
        return json_encode(["token"=>true,"data"=>$data]);
    }

    /**
     * @RequestMapping(route="deletelist",method={RequestMethod::POST})
     */
    public function deletelist(){
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
        $id = $request->post("id","");
        if ($id==""){
            return json_encode(["token"=>false]);
        }
        Redis::setex($token,7200,$info);
        $mycat = Mycat::get_mycat();
        $sql = "delete from `shop_user` where `id`='$id'";
        $mycat->query($sql);
        return json_encode(["token"=>true,"text"=>"删除成功"]);
    }

    /**
     * @RequestMapping(route="editlist",method={RequestMethod::POST})
     */
    public function editlist(){
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
        $data = $request->post("data","");
        Redis::setex($token,7200,$info);
        $data = json_decode($data,true);
        $mycat = Mycat::get_mycat();
        foreach ($data as $i=>$j){
            if ($j==null){
                $data[$i]="null";
                if ($i=="email"||$i=="headimg"){
                    $data[$i]="";
                }
            }
        }
        $id = $data['id'];

        if ($id==0){
            $sql = "select * from `shop_user` where `username`='{$data['username']}'";
            if ($mycat->query($sql)->fetch()){
                return json_encode(["token"=>true,"text"=>"操作失败，用户名已存在"]);
            }
            $sql = "insert into shop_user values (null,'{$data['username']}','{$data['password']}','{$data['name']}','{$data['email']}','{$data['headimg']}',{$data['phone']},{$data['address']},{$data['admin']})";
            $mycat->query($sql);
            return json_encode(["token"=>true,"text"=>"添加成功","sql"=>$sql]);
        }
        $sql = "update `shop_user` set `username`='{$data['username']}',`password`='{$data['password']}',`name`='{$data['name']}',`email`='{$data['email']}',`headimg`='{$data['headimg']}',phone={$data['phone']},address={$data['address']},admin='{$data['admin']}' where `id`='$id'";
        $mycat->query($sql);
        return json_encode(["token"=>true,"text"=>"修改成功","data"=>$data]);
    }

    /**
     * @RequestMapping(route="getaddress",method={RequestMethod::POST})
     */
    public function getaddress(){
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
        $sql = "select * from shop_address order by `id`";
        $data = datainjson($mycat->selectall($sql));
        return json_encode(["token"=>true,"data"=>$data]);
    }

    /**
     * @RequestMapping(route="deleteaddress",method={RequestMethod::POST})
     */
    public function deleteaddress(){
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
        $id = $request->post("id","");
        if ($id==""){
            return json_encode(["token"=>false]);
        }
        Redis::setex($token,7200,$info);
        $mycat = Mycat::get_mycat();
        $sql = "delete from `shop_address` where `id`='$id'";
        $mycat->query($sql);
        return json_encode(["token"=>true,"text"=>"删除成功"]);
    }

    /**
     * @RequestMapping(route="editaddress",method={RequestMethod::POST})
     */
    public function editaddress(){
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
        $data = $request->post("data","");
        Redis::setex($token,7200,$info);
        $data = json_decode($data,true);
        $mycat = Mycat::get_mycat();
        foreach ($data as $i=>$j){
            if ($j==null){
                $data[$i]="null";
                if ($i=="code"){
                    $data[$i]="";
                }
            }
        }
        $id = $data['id'];
        $sql = "select * from `shop_user` where `id`='{$data['user']}'";
        if (!($mycat->query($sql)->fetch())){
            return json_encode(["token"=>true,"text"=>"操作失败，用户id不存在"]);
        }
        if ($id==0){
            $sql = "insert into shop_address values (null,'{$data['user']}','{$data['name']}','{$data['phone']}','{$data['region']}','{$data['address']}',{$data['code']})";
            $mycat->query($sql);
            return json_encode(["token"=>true,"text"=>"添加成功","sql"=>$sql]);
        }
        $sql = "update `shop_address` set `user`='{$data['user']}',`name`='{$data['name']}',`phone`='{$data['phone']}',`region`='{$data['region']}',`address`='{$data['address']}',code={$data['code']} where `id`='$id'";
        $mycat->query($sql);
        return json_encode(["token"=>true,"text"=>"修改成功","data"=>$data]);
    }

}
