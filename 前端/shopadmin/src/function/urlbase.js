import { reactive } from "vue";
var base_url = "http://47.106.68.150:81/"
const url = reactive({
    "token": base_url + "adminuser/token",
    "login": base_url + "adminuser/login",
    "getoneclass": base_url + "adminclass/getoneclass",
    "editoneclass": base_url + "adminclass/editoneclass",
    "deleteoneclass": base_url + "adminclass/deleteoneclass",
    "gettwoclass": base_url + "adminclass/gettwoclass",
    "edittwoclass": base_url + "adminclass/edittwoclass",
    "deletetwoclass": base_url + "adminclass/deletetwoclass",
    "getshoplist": base_url + "adminshop/getshoplist",
    "setshoplist": base_url + "adminshop/setshoplist",
    "deleteshoplist": base_url + "adminshop/deleteshoplist",
    "getcollect": base_url + "adminshop/getcollect",
    "deletecollect": base_url + "adminshop/deletecollect",
    "getfrom": base_url + "adminshop/getfrom",
    "deletefrom": base_url + "adminshop/deletefrom",
    "getcart": base_url + "adminshop/getcart",
    "deletecart": base_url + "adminshop/deletecart",
    "getlist": base_url + "adminuser/getlist",
    "deletelist": base_url + "adminuser/deletelist",
    "editlist": base_url + "adminuser/editlist",
    "getaddress": base_url + "adminuser/getaddress",
    "deleteaddress": base_url + "adminuser/deleteaddress",
    "editaddress": base_url + "adminuser/editaddress",
    "getmessage": base_url + "adminmessage/getmessage",
    "sendmessage": base_url + "adminmessage/sendmessage",
})

export default url