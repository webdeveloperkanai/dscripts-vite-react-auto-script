<?php

namespace DevSecIt\DB;

function CREATE_DATABASE($name)
{
    global $con;
    $create_db_query = "CREATE DATABASE `$name`";
    if (mysqli_query($con, $create_db_query)) {
        // echo "Database {project_name} created successfully.";
    } else {
        die(json_encode(["status" => "Failed to create database", "code" => 500]));
    }
    mysqli_select_db($con, "$name");
}


if (!function_exists("CREATE_UPDATE_TABLE")) {


    function CREATE_UPDATE_TABLE($page, $cols)
    {
        global $con, $date, $time;
        $table = strtolower($page);

        $resp = $con->query("DESCRIBE $table");
        $SQL = '';
        if ($resp == null) {
            // create table 
            $SQL = "CREATE TABLE IF NOT EXISTS $table (id int(255) NOT NULL AUTO_INCREMENT PRIMARY KEY , ";
            foreach ($cols as $col) {
                if ($col == "message" || $col == "description" || $col == "msg" || $col == "desc" || $col == "about") {
                    $SQL .= " `$col` TEXT DEFAULT NULL, ";
                } else {
                    $SQL .= " `$col` VARCHAR(255) DEFAULT NULL, ";
                }
            }
            $SQL = rtrim($SQL, ", ") . " )  ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        } else {
            // table update
            foreach ($resp as $r) {
                $updf[] = $r['Field'];
            }

            $SQL = "ALTER TABLE `$table`  ";

            foreach ($cols as $col) {
                if (!in_array($col, $updf)) {
                    if ($col == "message" || $col == "description" || $col == "msg" || $col == "desc" || $col == "about") {
                        $SQL .= " ADD `$col` TEXT DEFAULT NULL, ";
                    } else {
                        $SQL .= " ADD `$col` VARCHAR(255) DEFAULT NULL, ";
                    }
                }
            }

            $SQL = rtrim($SQL, ", ") . " ";
        }
        return $SQL;
    }
}
