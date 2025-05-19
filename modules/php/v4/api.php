<?php
header('Content-Type: application/json; charset=utf-8'); 

date_default_timezone_set("Asia/Kolkata");
$date = date("d-m-Y");
$time = date("h:i:s A");


$allowed = ["jpg", "png", "jpeg", "PNG", "JPG", "JPEG"];

if (isset($_REQUEST['GET_VERSION'])) {
    die($APP_VERSION);
}

if (isset($_REQUEST['isDemo'])) {
    echo "No";
}


function is_sql_injection($condition)
{ 
    if (preg_match("/''/", $condition)) {

        http_response_code(403);
        die(json_encode(["err" => true, "msg" => "Threads detected! Don't try to be smart, your device will be blocked", "code" => 403]));
 
    }

    $sqli_patterns = [
        '/\bOR\b/i',
        '/\bUNION\b/i',
        '/\bSELECT\b/i',
        '/\bINSERT\b/i',
        '/\bUPDATE\b/i',
        '/\bDELETE\b/i',
        '/\bDROP\b/i',
        '/--/',
        '/#/',
        '/\*/',
        '/sleep\s*\(/i',
        '/benchmark\s*\(/i',
        '/1\s*=\s*1/',
    ];

    foreach ($sqli_patterns as $pattern) {
        if (preg_match($pattern, $condition)) {
            http_response_code(403);
            die(json_encode(["err" => true, "msg" => "Threads detected! Don't try to be smart, your device will be blocked", "code" => 403]));
        }
    }

    return false;
}

if (isset($_REQUEST['where'])) {
    is_sql_injection($_REQUEST['where']);
}



if (isset($_REQUEST['method'])) {
    switch ($method) {
        case $method == "PUT":
            DevSecIt\INSERT_DB($table, "method", ["date" => $date, "time" => $time]);
            die(json_encode(["err" => false, "msg" => "Data inserted!", "code" => 200]));
        case $method == "GET":
            if (strlen($where) < 2) {
                die("condition is missing!");
            }
            $data = DevSecIt\GET_DATA_WHILE($table, $where);
            die(json_encode($data));
        case $method == "GET_SELECTED":

            if (strlen($where) < 2) {
                die("condition is missing!");
            }
            $data = DevSecIt\GET_DATA_WHILE_SELECTED($table, $string, $where);
            die(json_encode($data));
        case $method == "GET_JOIN":
            $data = DevSecIt\GET_DATA_WHILE_JOIN($query);
            die(json_encode($data));

        case $method == "UPDATE":
            if (strlen($where) < 2) {
                die("condition is missing!");
            }
            $data = DevSecIt\UPDATE_DB($table, "method", $where);
            if ($con->affected_rows > 0) {
                die(json_encode(["err" => false, "msg" => "Data updated!", "code" => 200]));
            } else {
                die(json_encode(["err" => true, "msg" => "Failed to update", "code" => 400, "raw" => $con->error]));
            }
        case $method == "DELETE":
            if (strlen($where) < 2) {
                die("condition is missing!");
            }
            $con->query("DELETE FROM `$table` WHERE $where ");
            if ($con->affected_rows > 0) {
                die(json_encode(["err" => false, "msg" => "Data deleted!", "code" => 200]));
            } else {
                die(json_encode(["err" => true, "msg" => "Failed to delete", "code" => 400, "raw" => $con->error]));
            }

        default:
            die(json_encode(["err" => true, "msg" => "no action found", "code" => 400]));
    }
}
