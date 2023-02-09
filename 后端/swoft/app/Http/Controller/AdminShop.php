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
 * @Controller(prefix="adminshop")
 */
class AdminShop
{
    /**
     * @RequestMapping(route="getshoplist",method={RequestMethod::POST})
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
        $sql = "select * from shop_list order by `id`";
        $sz = datainjson($mycat->selectall($sql));
        $sql = "select * from shop_two_class order by `id`";
        $data = datainjson($mycat->selectall($sql));
        return json_encode(["token"=>true,"data"=>$sz,"class"=>$data]);
    }

    /**
     * @RequestMapping(route="setshoplist",method={RequestMethod::POST})
     */
    public function setlist(){
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
        if ($data==""){
            return json_encode(["token"=>false]);
        }
        $data = json_decode($data,true);
        Redis::setex($token,7200,$info);
        $mycat = Mycat::get_mycat();
        $inventory = $data['inventory'];
        if ($inventory==""||$inventory=='无限'){
            $inventory = 'NULL';
        }
        if ($data['id']==0){
            $time = get_time();
            $sql="INSERT INTO `shop_list` VALUES (null, '{$data['name']}', '{$data['class']}', {$data['nowprice']}, {$data['lastprice']},$inventory, '{$data['indeximg']}', '{$data['homeimg']}', '{$data['shophomeimg']}', '{$data['shoplistimg']}', '{$data['shopinfoimg']}', '{$data['shopinfotitle']}', {$data['fare']}, '{$data['introduce']}', NULL, {$data['recommend']}, '$time', '$time')";
            $mycat->query($sql);
            return json_encode(["token"=>true,"text"=>"添加成功"]);
        }
        $sql="update shop_list set `name`='{$data['name']}',`class`='{$data['class']}',`nowprice`='{$data['nowprice']}',`lastprice`='{$data['lastprice']}',`inventory`=$inventory,`indeximg`='{$data['indeximg']}',`homeimg`='{$data['homeimg']}',`shophomeimg`='{$data['shophomeimg']}',`shoplistimg`='{$data['shoplistimg']}',`shopinfoimg`='{$data['shopinfoimg']}',`shopinfotitle`='{$data['shopinfotitle']}',`fare`='{$data['fare']}',`introduce`='{$data['introduce']}',`recommend`='{$data['recommend']}' where `id`='{$data['id']}'";
        $mycat->query($sql);
        return json_encode(["token"=>true,"text"=>"修改成功","sql"=>$sql]);
    }

    /**
     * @RequestMapping(route="deleteshoplist",method={RequestMethod::POST})
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
        Redis::setex($token,7200,$info);
        $id = $request->post("id","");
        if ($id==""){
            return json_encode(["token"=>false]);
        }
        $mycat = Mycat::get_mycat();
        $sql = "delete from `shop_list` where `id`='$id'";
        $mycat->query($sql);
        return json_encode(["token"=>true,"text"=>'删除成功']);
    }

    /**
     * @RequestMapping(route="getcollect",method={RequestMethod::POST})
     */
    public function getcollect(){
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
        $sql = "select * from shop_collect order by `collectdate` desc";
        $data = datainjson($mycat->selectall($sql));
        return json_encode(["token"=>true,"data"=>$data]);
    }

    /**
     * @RequestMapping(route="deletecollect",method={RequestMethod::POST})
     */
    public function deletecollect(){
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
        $sql = "delete from `shop_collect` where `id`='$id'";
        $mycat->query($sql);
        return json_encode(["token"=>true,"text"=>"删除成功"]);
    }

    /**
     * @RequestMapping(route="getfrom",method={RequestMethod::POST})
     */
    public function getfrom(){
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
        $sql = "select * from shop_from order by `time` desc";
        $data = datainjson($mycat->selectall($sql));
        return json_encode(["token"=>true,"data"=>$data]);
    }

    /**
     * @RequestMapping(route="deletefrom",method={RequestMethod::POST})
     */
    public function deletefrom(){
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
        $sql = "delete from `shop_from` where `id`='$id'";
        $mycat->query($sql);
        return json_encode(["token"=>true,"text"=>"删除成功"]);
    }

    /**
     * @RequestMapping(route="getcart",method={RequestMethod::POST})
     */
    public function getcart(){
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
        $sql = "select * from shop_cart order by `addtime` desc";
        $data = datainjson($mycat->selectall($sql));
        return json_encode(["token"=>true,"data"=>$data]);
    }

    /**
     * @RequestMapping(route="deletecart",method={RequestMethod::POST})
     */
    public function deletecart(){
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
        $sql = "delete from `shop_cart` where `id`='$id'";
        $mycat->query($sql);
        return json_encode(["token"=>true,"text"=>"删除成功"]);
    }
}
