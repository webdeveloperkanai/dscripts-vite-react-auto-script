import os

config_content = """<?php
session_start();
error_reporting(1);

header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$con = mysqli_connect("localhost", "myuser", "mypassword", "mydatabase");
$con->set_charset("utf8mb4");

if (!$con) {
    die(json_encode(["status" => "Database is not connected", "code" => 400]));
}

function sanitize($con, $data) {
    return mysqli_real_escape_string($con, $data);
}

require_once __DIR__ . "/devsecit/index.php";
"""

output_path = os.path.join(os.getcwd(), "config.php")
with open(output_path, "w", encoding="utf-8") as f:
    f.write(config_content)

print(f"✅ config.php created at {output_path}")
