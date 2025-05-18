import Cookies from "universal-cookie"
import { APP_CONFIG } from "../config"
import axios from "axios";

function generateBase64LikeString(length) {
    const base64Chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz+/';
    let result = '';
    for (let i = 0; i < length; i++) {
        result += base64Chars.charAt(Math.floor(Math.random() * base64Chars.length));
    }
    return result;
}

const httpdService = (data) => {

    const cookie = new Cookies();
    var uid = `` + cookie.get('uid');  
    data.append('timestmp', Date.now());
    data.append('token', APP_CONFIG.API_TOKEN);

    const plainData = {};
    for (let [key, value] of data.entries()) {
        plainData[key] = value;
    }

    const jsonString = JSON.stringify(plainData);
    var base64String = btoa(unescape(encodeURIComponent(jsonString)));

    var part1 = base64String.slice(0, 10)
    var part2 = base64String.slice(10)


    var uidLen = uid.length;

    var extra = generateBase64LikeString(uidLen);
    var extra2 = generateBase64LikeString(uidLen);
    var extra3 = btoa(unescape(encodeURIComponent(generateBase64LikeString(uidLen * 5))));
    var extra4 = generateBase64LikeString(uidLen * 3);
    var extra3Len = extra3.length;

    base64String = `salted=${uidLen}${extra}${uid}${extra3Len}${extra2}${part1}${extra3}${part2}${extra4}=`

    return axios.post(APP_CONFIG.API, { httpd_data: base64String });
}

export default httpdService