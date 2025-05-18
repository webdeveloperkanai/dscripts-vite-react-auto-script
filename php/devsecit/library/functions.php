<?php

namespace DevSecIt;

$debug = false;
$auto_create = true;

## Hardly Coded by @webdeveloperkanai
# for more info visit https://devsecit.com
if (!function_exists('ext_validate')) {
    function ext_validate($file)
    {
        $valid = ['jpg', 'JPG', 'png', 'PNG', 'JPEG', 'jpeg'];
        $ext = explode('.', $file);
        $ext = end($ext);
        if (in_array($ext, $valid)) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('inDB')) {
    function inDB($field, $table)
    {
        global $con, $auto_create;
        $cols = [];
        try {
            $ch = $con->query("DESCRIBE `$table`");
            // if ($ch->num_rows > 0) {
            foreach ($ch as $r) {
                $cols[] = $r['Field'];
            }
            if (in_array($field, $cols) !== false) {
                return true;
            } else {
                if ($auto_create == true) {
                    $con->query("ALTER TABLE `$table` ADD `$field` TEXT DEFAULT NULL");
                    return true;
                } else {
                    return false;
                }
            }
            // }  
        } catch (\Thread $e) {
            if ($auto_create == true) {
                CHECK_DATABASE_TABLE($table);
                return true;
            }
            return false;
        }
    }
}


if (!function_exists('CHECK_DATABASE_TABLE')) {
    function CHECK_DATABASE_TABLE($table)
    {
        global $con, $auto_create, $debug;
        $table_exists = $con->query("SHOW TABLES LIKE '$table'");

        if ($table_exists->num_rows == 0) {
            if ($auto_create == true) {
                $query  = "CREATE TABLE `$table` (`id` int(156) NOT NULL AUTO_INCREMENT, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
                if ($debug) {
                    echo $query;
                }
                $con->query($query);
                return true;
            }
            return false;
        } else {
            return true;
        }
    }
}




if (!function_exists('inREQUEST')) {
    function inREQUEST($field)
    {
        $reqs = [];
        if (count($_REQUEST) > 0) {
            foreach ($_REQUEST as $r => $v) {
                $reqs[] = $r;
            }
            if (in_array($field, $reqs) !== false) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

if (!function_exists('INSERT_DB')) {
    function INSERT_DB($table, $submit_btn, $ext)
    {
        global $auto_create;
        // $data = file_get_contents("php://input");
        CHECK_DATABASE_TABLE($table);
        $solid = $_REQUEST;

        if (isset($solid[$submit_btn])) {
            global $con, $debug;

            $solid = array_merge($solid, $ext);
            extract($solid);
            $qu = "INSERT INTO `$table` SET ";

            foreach ($solid as $param_name => $param_value) {
                if (
                    $param_name != "$submit_btn" &&
                    strlen($param_value) > 0 &&
                    inDB($param_name, $table)
                ) {
                    $param = mysqli_real_escape_string($con, $param_name);
                    $para_value = mysqli_real_escape_string($con, $param_value);
                    $qu = $qu . "`$param`='$para_value',";
                }
            }

            $params = UPLOAD_DOCUMENTS($table, $auto_create);
            $qu = $qu . ' ' . $params;
            $qu = rtrim($qu, ', ');
            if ($debug) {
                echo $qu;
            }
            $q = $con->query($qu);
            return $q;
        }
    }
}



// if (!function_exists('UPLOAD_DOCUMENTS')) {
//     function UPLOAD_DOCUMENTS($table, $auto_create)
//     {
//         global $upload_dir;
//         if (is_dir($upload_dir) === false) {
//             mkdir($upload_dir, 0777, true);
//         }
//         $parameters = '';
//         foreach ($_FILES as $file_name => $file_value) {
//             $ext = ext_validate($file_value['name']);
//             if ($ext) {
//                 $target_file = $upload_dir . basename(time() . $file_value['name']);
//                 move_uploaded_file($file_value['tmp_name'], $target_file);
//                 $parameters = $parameters  . "`" . $file_name . "`='" . $target_file . "',";
//                 inDB($file_name, $table, $auto_create);
//             }
//         }

//         return $parameters;
//     }
// }


if (!function_exists('UPLOAD_DOCUMENTS')) {
    function UPLOAD_DOCUMENTS($table, $auto_create)
    {
        global $upload_dir;

        // Ensure the upload directory exists
        if (is_dir($upload_dir) === false) {
            mkdir($upload_dir, 0777, true);
        }

        $parameters = '';

        // Loop through the uploaded files
        foreach ($_FILES as $file_name => $file_value) {
            $file_data = [];

            // Check if the field is an array of files
            if (is_array($file_value['name'])) {
                // Multiple files
                foreach ($file_value['name'] as $key => $name) {
                    $ext = ext_validate($name);
                    if ($ext) {
                        $target_file = $upload_dir . basename(time() . '_' . $name);
                        move_uploaded_file($file_value['tmp_name'][$key], $target_file);
                        $file_data[] = ['src' => $target_file];
                    }
                }
            } else {
                // Single file
                $ext = ext_validate($file_value['name']);
                if ($ext) {
                    $target_file = $upload_dir . basename(time() . $file_value['name']);
                    move_uploaded_file($file_value['tmp_name'], $target_file);
                    $file_data[] = ['src' => $target_file];
                }
            }

            // Convert file data to JSON format
            if (!empty($file_data)) {
                $json_data = json_encode($file_data, JSON_UNESCAPED_SLASHES);
                $parameters .= "`$file_name`='" . $json_data . "',";

                // Add the field to the database if required
                inDB($file_name, $table, $auto_create);
            }
        }

        return rtrim($parameters, ','); // Remove the trailing comma
    }
}



if (!function_exists('DELETE_DB')) {
    function DELETE_DB($table, $where)
    {
        // $data = file_get_contents("php://input");
        // $solid = $_REQUEST;
        extract($_REQUEST);
        global $con;
        $q = $con->query("DELETE FROM `$table` WHERE $where ");
        if ($con->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('INSERT_DB_ARRAY')) {
    function INSERT_DB_ARRAY($table, $array)
    {
        // $data = file_get_contents("php://input");
        $solid = $array;

        global $con, $debug;

        extract($solid);
        $qu = "INSERT INTO `$table` SET ";

        foreach ($solid as $param_name => $param_value) {
            if (strlen($param_value) > 0 && inDB($param_name, $table)) {
                $param = mysqli_real_escape_string($con, $param_name);
                $para_value = mysqli_real_escape_string($con, $param_value);
                $qu = $qu . "`$param`='$para_value',";
            }
        }
        $qu = rtrim($qu, ',');
        if ($debug) {
            echo $qu;
        }
        $q = $con->query($qu);
        return $q;
    }
}

if (!function_exists('INSERT_DB_ARRAY_EX')) {
    function INSERT_DB_ARRAY_EX($table, $array, $exclude = [])
    {
        // $data = file_get_contents("php://input");
        $solid = $array;

        global $con, $debug;

        extract($solid);
        $qu = "INSERT INTO `$table` SET ";

        foreach ($solid as $param_name => $param_value) {
            if (strlen($param_value) > 0 && inDB($param_name, $table) &&   !in_array($param_name, $exclude)) {
                $param = mysqli_real_escape_string($con, $param_name);
                $para_value = mysqli_real_escape_string($con, $param_value);
                $qu = $qu . "`$param`='$para_value',";
            }
        }
        $qu = rtrim($qu, ',');
        if ($debug) {
            echo $qu;
        }
        $q = $con->query($qu);
        return $q;
    }
}

if (!function_exists('INSERT_DB_EX')) {
    function INSERT_DB_EX($table, $submit_btn, $ext, $exclude)
    {
        // $data = file_get_contents('php://input');
        $solid = $_REQUEST;

        if (isset($solid[$submit_btn])) {
            global $con;
            global $debug;

            extract($solid);
            $qu = "INSERT INTO `$table` SET ";

            foreach ($solid as $param_name => $param_value) {
                if (
                    $param_name != "$submit_btn" &&
                    strlen($param_value) > 0 &&
                    inDB($param_name, $table) &&
                    !in_array($param_name, $exclude)
                ) {
                    $param = mysqli_real_escape_string($con, $param_name);
                    $para_value = mysqli_real_escape_string($con, $param_value);
                    $qu = $qu . "`$param`='$para_value',";
                }
            }
            // $qu = $qu. " `date`='$date',`time`='$time'";

            if ($ext != null) {
                foreach ($ext as $xf => $xv) {
                    $qu = $qu . " $xf='$xv',";
                }
            }
            $qu = rtrim($qu, ',');
            if ($debug) {
                echo $qu;
            }
            $q = $con->query($qu);
            return $q;
        }
    }
}

if (!function_exists('UPDATE_DB_EX')) {
    function UPDATE_DB_EX($table, $submit_btn, $ext, $exclude, $condition)
    {
        // $data = file_get_contents('php://input');
        // $solid = json_decode($data, 1);

        global $con;

        global $debug;

        extract($_REQUEST);
        $qu = "UPDATE `$table` SET ";

        foreach ($_REQUEST as $param_name => $param_value) {
            if (
                $param_name != "$submit_btn" &&
                strlen($param_value) > 0 &&
                inDB($param_name, $table) &&
                !in_array($param_name, $exclude)
            ) {
                $param = mysqli_real_escape_string($con, $param_name);
                $para_value = mysqli_real_escape_string($con, $param_value);
                $qu = $qu . "`$param`='$para_value',";
            }
        }

        if ($ext != null) {
            foreach ($ext as $xf => $xv) {
                $qu = $qu . " $xf='$xv',";
            }
        }
        $qu = rtrim($qu, ',');

        $qu = $qu . " WHERE $condition";

        if ($debug) {
            echo $qu;
        }
        $q = $con->query($qu);
        return $q;
    }
}

if (!function_exists('UPDATE_DB')) {
    function UPDATE_DB($table, $submit_btn, $condition)
    {
        // if (isset($_POST[$submit_btn])) {
        global $con, $debug;
        extract($_REQUEST);
        $qu = "UPDATE `$table` SET ";

        foreach ($_REQUEST as $param_name => $param_value) {
            if (
                $param_name != "$submit_btn" &&
                strlen($param_value) > 0 &&
                inDB($param_name, $table)
            ) {
                $param = mysqli_real_escape_string($con, $param_name);
                $para_value = mysqli_real_escape_string($con, $param_value);
                $qu = $qu . "`$param`='$para_value',";
            }
        }

        foreach ($_FILES as $file_name => $file_value) {
            if (strlen($file_value['name']) > 3) {
                if (ext_validate($file_value['name'])) {
                    $img = time() . '-' . $file_value['name'];
                    move_uploaded_file($file_value['tmp_name'], "$img");
                    $qu = $qu . " `$file_name`='$img',";
                } else {
                }
            }
        }
        $qu = rtrim($qu, ',');

        if (strlen($condition) > 0) {
            $qu = $qu . ' WHERE ' . $condition;
        } else {
            $qu = $qu . ' ' . $where;
        }

        if ($debug) {
            echo $qu;
        }
        $q = $con->query($qu);
        return $q;
    }
}

if (!function_exists('UPDATE_DB_ARRAY')) {
    function UPDATE_DB_ARRAY($table, $fields, $condition)
    {
        global $con, $debug;
        extract($fields);
        $solid = $fields;
        $qu = "UPDATE `$table` SET ";

        foreach ($solid as $param_name => $param_value) {
            if (strlen($param_value) > 0 && inDB($param_name, $table)) {
                $param = mysqli_real_escape_string($con, $param_name);
                $para_value = mysqli_real_escape_string($con, $param_value);
                $qu = $qu . "`$param`='$para_value',";
            }
        }

        $qu = rtrim($qu, ',');

        if (strlen($condition) > 0) {
            $qu = $qu . ' WHERE ' . $condition;
        } else {
            $qu = $qu . ' ' . $where;
        }
        if ($debug) {
            echo $qu;
        }
        $q = $con->query($qu);
        return $q;
    }
}
if (!function_exists('UPDATE_DB_ARRAY_EX')) {
    function UPDATE_DB_ARRAY_EX($table, $fields, $condition, $ext = [])
    {
        global $con, $debug;
        extract($fields);
        $solid = $fields;
        $qu = "UPDATE `$table` SET ";

        foreach ($solid as $param_name => $param_value) {
            if (strlen($param_value) > 0 && inDB($param_name, $table) && !in_array($param_name, $ext)) {
                $param = mysqli_real_escape_string($con, $param_name);
                $para_value = mysqli_real_escape_string($con, $param_value);
                $qu = $qu . "`$param`='$para_value',";
            }
        }

        $qu = rtrim($qu, ',');

        if (strlen($condition) > 0) {
            $qu = $qu . ' WHERE ' . $condition;
        } else {
            $qu = $qu . ' ' . $where;
        }
        if ($debug) {
            echo $qu;
        }
        $q = $con->query($qu);
        return $q;
    }
}

if (!function_exists('GET_DATA')) {
    function GET_DATA($table, $condition)
    {
        global $con, $debug;
        $qu = "SELECT * FROM `$table` WHERE $condition";

        if ($debug) {
            echo $qu;
        }
        $q = $con->query($qu);
        $data = $q->fetch_assoc();
        return $q->num_rows > 0 ? $data : [];
    }
}

if (!function_exists('GET_DATA_SELECTED')) {
    function GET_DATA_SELECTED($table, $stringList, $condition)
    {
        global $con, $debug;
        $qu = "SELECT $stringList FROM `$table` WHERE $condition";

        if ($debug) {
            echo $qu;
        }
        $q = $con->query($qu);
        $data = $q->fetch_assoc();
        return $q->num_rows > 0 ? $data : [];
    }
}

if (!function_exists('GET_DATA_WHILE')) {
    function GET_DATA_WHILE($table, $condition)
    {
        global $con, $debug;
        $qu = "SELECT * FROM `$table` WHERE $condition";

        if ($debug) {
            echo $qu;
        }
        $fd = [];
        $q = $con->query($qu);
        if ($q->num_rows > 0) {
            while ($data = $q->fetch_assoc()) {
                $fd[] = $data;
            }
            return $fd;
        } else {
            return [];
        }
    }
}


if (!function_exists('GET_DATA_WHILE_SELECTED')) {
    function GET_DATA_WHILE_SELECTED($table, $stringList, $condition)
    {
        global $con, $debug;
        $qu = "SELECT $stringList FROM `$table` WHERE $condition";

        if ($debug) {
            echo $qu;
        }
        $fd = [];
        $q = $con->query($qu);
        if ($q->num_rows > 0) {
            while ($data = $q->fetch_assoc()) {
                $fd[] = $data;
            }
            return $fd;
        } else {
            return [];
        }
    }
}


if (!function_exists('GET_DATA_WHILE_JOIN')) {
    function GET_DATA_WHILE_JOIN($query)
    {
        global $con, $debug;
        $qu = "$query";

        if ($debug) {
            echo $qu;
        }
        $fd = [];
        $q = $con->query($qu);
        if ($q->num_rows > 0) {
            while ($data = $q->fetch_assoc()) {
                $fd[] = $data;
            }
            return $fd;
        } else {
            return [];
        }
    }
}



if (!function_exists('SQL_QUERY')) {
    function SQL_QUERY($query)
    {
        global $con;
        $q = $con->query($query);
        return $q;
    }
}

if (!function_exists('LOGOUT')) {
    function AUTO_LOGOUT()
    {
?>
        <script>
            setTimeout(() => {
                window.location.href = '?dsilogout';
            }, 1000 * 60 * 5);
        </script>
<?php
    }
}
if (isset($_REQUEST['dsilogout'])) {
    session_destroy();
    session_unset();
}

if (!function_exists('CHECK_SESSION')) {
    function CHECK_SESSION($key)
    {
        if (!isset($_SESSION[$key])) {
            return false;
        } else {
            return true;
        }
    }
}

if (!function_exists('LOGOUT')) {
    function LOGOUT()
    {
        session_destroy();
        session_unset();
        echo "<script>location.href='logout.php'</script>";
    }
}
