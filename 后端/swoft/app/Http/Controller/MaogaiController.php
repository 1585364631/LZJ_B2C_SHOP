<?php
namespace App\Http\Controller;

use App\libs\Mycat_short;
use Swoft\Context\Context;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;

/**
 * @Controller("maogai")
 */
class MaogaiController{
    /**
     * @RequestMapping(route="{title}",method={RequestMethod::GET, RequestMethod::POST})
     */
    public function get_title(string $title){
        $mycat = new Mycat_short();
        $sz = $mycat->selectall("select * from `maogai` where `text` like '%" . $title . "%'");
        return json_encode($sz);
    }

}
