function getfull0(i) {
    i = i < 10 ? "0" + i : i;
    return i;
}

export var lzj_get_from_id = () => {
    var date = new Date();
    var y = date.getFullYear();
    var m = getfull0(date.getMonth() + 1);
    var d = getfull0(date.getDate());
    var h = getfull0(date.getHours());
    var min = getfull0(date.getMinutes());
    var s = date.getMilliseconds() + "";
    switch (s.length) {
        case 0:
            s = "000";
            break;
        case 1:
            s = "00" + s;
            break;
        case 2:
            s = "0" + s;
            break;
        default:
            break;
    }
    for (var i = 0; i < 6; i++) {
        s = s + "" + Math.floor(Math.random() * 10);
    }

    return y + m + d + h + min + s;
} 