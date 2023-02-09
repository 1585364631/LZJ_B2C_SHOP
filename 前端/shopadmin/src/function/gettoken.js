export var gettoken = (len) => {
    len = len == undefined ? 32 : len
    let chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz23456789'
    let token = ''
    for (let i = 0; i < len; ++i) {
        token += chars.charAt(Math.floor(Math.random() * chars.length))
    }
    return token
}