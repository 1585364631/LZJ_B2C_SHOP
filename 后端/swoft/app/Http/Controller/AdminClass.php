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
 * @Controller(prefix="adminclass")
 */
class AdminClass
{
    /**
     * @RequestMapping(route="getoneclass",method={RequestMethod::POST})
     */
    public function get_one_class(){
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
        $page = $request->post("page",0);
        Redis::setex($token,7200,$info);
        $mycat = Mycat::get_mycat();
        $sql = "select * from shop_one_class order by `id`";
        $sz = $mycat->selectall($sql);
        $data = [];
        foreach ($sz as $item){
            $data[] = ["id"=>$item['id'],"name"=>$item['name']];
        }
        return json_encode(["token"=>true,"data"=>$data]);
    }


    /**
     * @RequestMapping(route="editoneclass",method={RequestMethod::POST})
     */
    public function edit_one_class(){
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
        $name = $request->post("name","");
        if ($id==""||$name==""){
            return json_encode(["token"=>false]);
        }
        Redis::setex($token,7200,$info);
        $mycat = Mycat::get_mycat();
        if ($id==0){
            $sql = "insert into `shop_one_class` values (null,'$name');";
            $mycat->query($sql);
            return json_encode(["token"=>true,"text"=>"添加成功"]);
        }
        $sql = "update `shop_one_class` set `name`= '$name' where `id`='$id'";
        $mycat->query($sql);
        return json_encode(["token"=>true,"text"=>"修改成功"]);
    }

    /**
     * @RequestMapping(route="deleteoneclass",method={RequestMethod::POST})
     */
    public function delete_one_class(){
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
        $sql = "select shop_one_class.id as oneid,shop_one_class.name as onename,shop_two_class.parent from `shop_one_class`,`shop_two_class` where shop_two_class.parent = shop_one_class.name and shop_one_class.id='$id'";
        $msg = $mycat->query($sql);
        if ($msg->fetch()){
            return json_encode(["token"=>true,"text"=>'删除失败，该分类下还存在子类']);
        }
        $sql = "delete from `shop_one_class` where `id`='$id'";
        $mycat->query($sql);
        return json_encode(["token"=>true,"text"=>'删除成功']);
    }

    /**
     * @RequestMapping(route="gettwoclass",method={RequestMethod::POST})
     */
    public function get_two_class(){
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
        $sql = "select shop_two_class.id as twoid,shop_two_class.name as twoname,shop_one_class.id as oneid,shop_one_class.name as onenames,shop_two_class.parent as onename from shop_two_class,shop_one_class where shop_one_class.name=shop_two_class.parent order by twoid;";
        $sz = $mycat->selectall($sql);
        $data = [];
        foreach ($sz as $item){
            $data[] = ["id"=>$item['twoid'],"name"=>$item['twoname'],"oneid"=>$item['oneid'],"onename"=>$item['onename']];
        }
        return json_encode(["token"=>true,"data"=>$data]);
    }

    /**
     * @RequestMapping(route="edittwoclass",method={RequestMethod::POST})
     */
    public function edit_two_class(){
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
        $name = $request->post("name","");
        $parent = $request->post("parent","");
        if ($id==""||$name==""||$parent==""){
            return json_encode(["token"=>false]);
        }
        Redis::setex($token,7200,$info);
        $mycat = Mycat::get_mycat();
        if ($id==0){
            $sql = "insert into `shop_two_class` values (null,'$name','$parent')";
            $mycat->query($sql);
            return json_encode(["token"=>true,"text"=>"添加成功"]);
        }
        $sql = "update `shop_two_class` set `name`= '$name' , `parent` = '$parent' where `id`='$id'";
        $mycat->query($sql);
        return json_encode(["token"=>true,"text"=>"修改成功"]);
    }

    /**
     * @RequestMapping(route="deletetwoclass",method={RequestMethod::POST})
     */
    public function delete_two_class(){
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
        $mycat = Mycat::get_mycat();
        Redis::setex($token,7200,$info);
        $sql = "delete from `shop_two_class` where `id`='$id'";
        $mycat->query($sql);
        return json_encode(["token"=>true,"text"=>'删除成功']);
    }


}
