<?php

$drxxx = json_decode(file_get_contents("php://input"), 1);

if (isset($drxxx['httpd_data'])) {

    $_REQUEST = json_decode(file_get_contents("php://input"), 1);

    $data = str_replace("salted=", "", $_REQUEST['httpd_data']);
    $uidLen = substr($data, 0, 1);

    $uid = substr($data, $uidLen + 1, $uidLen);
    $extra3Len = substr($data, $uidLen + 1 + $uidLen, 2);
    $extra3LenMain = substr($data, $uidLen + 1 + $uidLen, 2);

    $part1_distance = $uidLen + 1 + $uidLen + $extra3Len;
    // $part1 = substr($data, $part1_distance, 10); 
    $xx = $uidLen + 1 + $uidLen + $extra3Len;

    // after uid  
    $data = substr(substr($data, 1), ($uidLen * 2) + 2 + $uidLen);

    $part1 = substr($data, 0, 10);

    $part2 = substr($data, 10 + $extra3Len, strlen($data));


    $datax = $data;
    $extra3Len = ($uidLen * 3) + 1;

    $data = substr($data, 10);
    $data = substr($data, 0, -$extra3Len);

    //  die($extra3LenMain); 
    $ext = substr($data, 0, $extra3LenMain);
    $data = str_replace($ext, "", $data);


    $final = $part1 . $data;

    $dt = json_decode(base64_decode($final), 1);

    $_REQUEST = $dt;
    extract($_REQUEST);

    if ($token != "Y2V5c2luZ3RyYWRpbmc=") {
        die(json_encode(["err" => "Token Error!", "code" => 403]));
    }
}

require_once __DIR__ . "/v4/index.php";
