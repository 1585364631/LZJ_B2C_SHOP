<?php

namespace App\Http\Controller;

use App\libs\Mycat;
use Swoft\Db\DB;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;

use Swoft\Validator\Annotation\Mapping\Validate;

/**
 * @Controller(prefix="help")
 */
class HelpController
{
    /**
     * @RequestMapping(route="create/{name}",method={RequestMethod::GET, RequestMethod::POST})
     */
    public function create(string $name){
        $mycat = Mycat::get_mycat();
        return $mycat->query("insert into `test` values (null,'" . $name . "')");
    }

    /**
     * @RequestMapping(route="test",method={RequestMethod::GET, RequestMethod::POST})
     */
    public function test(){
        $mycat = Mycat::get_mycat();
        return $mycat->selectall("select * from `shop_list`");
    }

}
