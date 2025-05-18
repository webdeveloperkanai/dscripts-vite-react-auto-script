<?php 

// require_once  __DIR__."/config.php";
 
if(isset($_REQUEST['GET_DEMO'])) {
    die($is_demo);
}


if (isset($_POST['CREATE_NEW_EMPLOYEE'])) {
    extract($_POST);

    $ext = explode('.', $_FILES['image']['name']);

    $ext = end($ext);

    $img = str_replace(' ', '-', $department . time() . $name . "." . $ext);

    $host = "https://php-v2.dsillc.cloud";
    $img_uri = $host . "/docs/user/" . $img;

    if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {

        move_uploaded_file($_FILES['image']['tmp_name'], '../../docs/user/' . $img);

        $password=  urlencode(substr($name,0,3)."#". rand(11111,999999)."@");

        $curl = curl_init();
        $url = "$APP_API/employee/add-new?" . http_build_query([
            'name' => $name,
            'phone' => $phone,
            'password' => $password,
            'designation' => $designation,
            'department' => $department,
            'photo' => $img_uri,
            'employeeId' => $employeeId,
            'officeId' => $officeId,
            'zone' => $zone,
            'district' => $district,
            'state' => $state,
            'active' => 'true'
        ]);

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ]);

        $response = curl_exec($curl);
        $curl_error = curl_error($curl);
        curl_close($curl);
        
        $resp = file_get_contents("$ACTIN_URL?api_key=$API_KEY&sender=$SENDER_KEY&number=91$phone&message=Dear+$name,+your+attendance+account+has+been+created!+To+login+please+follow+the+link+given+bellow+%0ahttps%3A%2F%2Fplay.google.com%2Fstore%2Fapps%2Fdetails%3Fid%3Dcom.uttardinajpur.ams%0a%0a*USERNAME:$phone*%0a*PASSWORD:$password*%0a%0a_Do+not+share+this+with+anyone._%0a%0aThanks,%0a*UD%20AMS*%0aSUPPORT:https%3A%2F%2Fams.dmud.in%2Fsupport%2F%0aEMAIL:$SUPPORT_EMAIL%0aWhatsApp:$SUPPORT_PHONE");


        if ($curl_error) {
            echo "cURL Error: " . $curl_error;
        } else {
            // echo $response;
?>
            <center>
                <img src="./check.png" alt="" style="height: 200px;">
                <br><br>
                <a href="entry.php">ADD NEW</a>
            </center>
<?php
        }
    }
}



if(isset($_POST['INSERT_SELF_USER'])) {
    
    $ch = $con->query("SELECT * FROM `employees` WHERE `phone`='$phone' or `device_id`='$device_id' "); 
    if($ch->num_rows>0) {
        die("failed");
    }
    
    
    
    $img = time()."-user-".str_replace(" ", "-", $name).".jpg";
    file_put_contents("../../docs/user/$img", base64_decode($thumbnail) );
    
    
    $host = "https://php-v2.dsillc.cloud";
    $img_uri = $host . "/docs/user/" . $img;

    
    
    $password = rand(1111,999999);
    
    $o= $con->query("SELECT * FROM `offices` WHERE `name`='$office_name' ");
    $office = $o->fetch_assoc(); 
    
    $office_address = $office['address'];
    $office_lat = $office['lat'];
    $office_lon = $office['lon'];
    $office_zone = $office['zone'];
    $office_id = $office['id'];
    $circle = $office['circle'];
    
    $ins = $con->query("INSERT INTO `employees` SET `name`='$name', `phone`='$phone', `email`='$email', `employee_id`='$employee_id', `department`='$department', `office_name`='$office_name',`is_active`=1, `status`=1, `state`='WEST BENGAL', `district`='UTTAR DINAJPUR', `device_id`='$device_id', `office_address`='$office_address', `office_lat`='$office_lat', `office_lon`='$office_lon',`office_id`='$office_id', `password`='$password',`photo`='$img_uri', `zone`='$office_zone', `date`='$date',`designation`='$designation', `time`='$time', `circle`='$circle'  ");
    $id = $con->insert_id;
    if($con->affected_rows>0) {
        
        $resp = file_get_contents("$ACTIN_URL?api_key=$API_KEY&sender=$SENDER_KEY&number=91$phone&message=Dear+$name,+your+attendance+account+has+been+created!+To+login+please+follow+the+link+given+bellow+%0ahttps%3A%2F%2Fplay.google.com%2Fstore%2Fapps%2Fdetails%3Fid%3Dcom.uttardinajpur.ams%0a%0a*USERNAME:$phone*%0a*PASSWORD:$password*%0a%0a_Do+not+share+this+with+anyone._%0a%0aThanks,%0a*UD%20AMS*%0aSUPPORT:https%3A%2F%2Fams.dmud.in%2Fsupport%2F%0aEMAIL:$SUPPORT_EMAIL%0aWhatsApp:$SUPPORT_PHONE");


        $d = $con->query("SELECT * FROM `employees` WHERE `id`='$id' ");
        $data = $d->fetch_assoc(); 
        // $json[0]=$data;
        die(json_encode($data));
    } else {
         die("failed");
    }
    
}



if (isset($_REQUEST['GET_APP_VERSION'])) {

    echo $APP_VERSION;
    die;
}

if (isset($_POST['UPDATE_PASSWORD'])) {
    $newPassword = sanitize($_POST['newPassword']);
    $oldPassword = sanitize($_POST['oldPassword']);
    $uid = sanitize($_POST['uid']);

    $ch = $con->query("SELECT * FROM `employees` WHERE `id`='$uid' and `password`='$oldPassword' ");
    if ($ch->num_rows > 0) {
        $user  = $ch->fetch_assoc(); 
        
        $phone = $user['phone'];
        $name = $user['name'];
        
        $resp = file_get_contents("$ACTIN_URL?api_key=$API_KEY&sender=$SENDER_KEY&number=91$phone&message=Dear+$name,+your+password+has+been+changed!%0a%0aThanks,%0a*UD%20AMS*%0aSUPPORT:https%3A%2F%2Fams.dmud.in%2Fsupport%2F%0aEMAIL:$SUPPORT_EMAIL%0aWhatsApp:$SUPPORT_PHONE");
        
        $con->query("UPDATE `employees` SET `password` = '$newPassword' WHERE `employees`.`id` = $uid");
        die("true");
    } else {
        die("false");
    }
}



if(isset($_POST['GET_ANALYTICS'])) {
    
    // print_r($_REQUEST);
    if($rank=="ADMIN") {    
        $office = $con->query("SELECT count(id) FROM `offices` "); 
        $offices = $office->fetch_array()[0]; 
        
        $emp = $con->query("SELECT count(id) FROM `employees` "); 
        $employees = $emp->fetch_array()[0]; 
    
        $attd = $con->query("SELECT DISTINCT(uid) FROM `attendance` WHERE `date`='$date' "); 
        $attendances = $attd->num_rows; 
    
    } else if($rank=="SUB-ADMIN") {
        $office = $con->query("SELECT count(id) FROM `offices` WHERE `department`='$department' "); 
        $offices = $office->fetch_array()[0]; 
         
        $emp = $con->query("SELECT count(id) FROM `employees` WHERE `department`='$department' "); 
        $employees = $emp->fetch_array()[0]; 
         
        $attd = $con->query("SELECT DISTINCT(uid) FROM `attendance` WHERE `date`='$date' and `department`='$department' "); 
        $attendances = $attd->num_rows; 
    } else {
        
        $office = $con->query("SELECT count(id) FROM `offices` WHERE `department`='$department' "); 
        $offices = $office->fetch_array()[0]; 
         
        $emp = $con->query("SELECT count(id) FROM `employees` WHERE `department`='$department' and `office_name`='$office_name' "); 
        $employees = $emp->fetch_array()[0]; 
         
        $attd = $con->query("SELECT DISTINCT(uid) FROM `attendance` WHERE `date`='$date' and `department`='$department' and `office_name`='$office_name' "); 
        $attendances = $attd->num_rows; 
        
    }
    
    $school = $con->query("SELECT count(id) FROM `offices` WHERE `department`='EDUCATION' "); 
    $schools = $school->fetch_array()[0]; 
     
    
    $bl = $con->query("SELECT count(id) FROM `zones`  "); 
    $blocks = $bl->fetch_array()[0]; 
    
    
   
    
    $absents = $employees - $attendances;
    
    $json[0]['offices'] = $offices;
    $json[0]['schools'] = $schools;
    $json[0]['employees'] = $employees;
    $json[0]['attendances'] = $attendances;
    $json[0]['absents'] = $absents;
    $json[0]['blocks'] = $blocks;
    
    die(json_encode($json)); 
}
  
if(isset($_POST['CHANGE_PASSWORD'])) {
    $user = DevSecIt\GET_DATA("admin", " `id`='$uid' and `password`='$current_password' ");
    if($user!=null) {
        $upd = $con->query("UPDATE `admin` SET `password`='$new_password' WHERE `id`='$uid' and `password`='$current_password' ");
        if($con->affected_rows>0) {
            
             sendWA($user[0]['phone'], "Your password has been changed! If you did not this, try to recover your account quickly.");
             
            die("success");
        } else {
            die("failed");
        }
    } else {
        die("failed");
    }
}    
    

// if(isset($_POST['UPLOAD_PHOTO'])) {
//      $img= time()."-photo.jpg";
//     file_put_contents("../../docs/user/$img", base64_decode($_POST['thumbnail'])); 
//     $photo = "https://php-v2.dsillc.cloud/docs/user/$img";
//     $upd = $con->query("UPDATE `employees` SET `photo`='$photo' WHERE `id`='$uid' "); 
//     if($con->affected_rows>0) {
//         die("success");
//     } else {
//         die("failed");
//     }
// }


if(isset($_POST['AUTH_APP_LOGIN'])) { 
    $user = DevSecIt\GET_DATA_WHILE("users", " `phone`='$phone'  and `password`='$password'  ");
    if($user!=null) { 
        // sendWA($user[0]['phone'], "New device login detected! If you did not this, try to recover your account quickly.");
        die(json_encode($user[0]));
    } else {
        die("failed"); 
    }
   
}

if(isset($_POST['AUTH_LOGIN_DASHBOARD'])) {
    $user = DevSecIt\GET_DATA_WHILE("admin", " `phone`='$phone' and `department`='$department' and `password`='$password' ");
    sendWA($user[0]['phone'], "New device login detected! If you did not this, try to recover your account quickly.");
    die(json_encode($user));
}


if(isset($_REQUEST['GET_TTAENDANCE_BY_UID'])) {
    $data = DevSecIt\GET_DATA_WHILE("attendance", " `uid`='$uid' order by id desc");
    die(json_encode($data));
}

if(isset($_REQUEST['GET_SUBJECTS_BY_CLASS'])) {
    $data = DevSecIt\GET_DATA_WHILE("subjects", " `class`='$class' order by title asc");
    die(json_encode($data));
}
if(isset($_REQUEST['GET_CHAPTERS_BY_CLASS_SUBJECT'])) {
    $data = DevSecIt\GET_DATA_WHILE("chapters", " `class`='$class' and `subject`='$subject' order by title asc");
    die(json_encode($data));
}

if(isset($_REQUEST['GET_OFFICE_BY_DEPARTMENT_BLOCK'])) {
    $user = DevSecIt\GET_DATA_WHILE("offices", " `department`='$department' and `zone`='$block' order by name asc");
    die(json_encode($user));
}

if(isset($_REQUEST['GET_EMPLOYEE_BY_DEPARTMENT_BLOCK_OFFICE'])) {
    $user = DevSecIt\GET_DATA_SELECTED_WHILE("employees", ["id","photo","name","office_name", "designation", "zone", "phone", "employee_id"], " `department`='$department'  and `office_name`='$office' order by name asc");
    // $user = DevSecIt\GET_DATA_SELECTED_WHILE("employees", ["id","photo","name","office_name", "designation", "zone", "phone", "employee_id"], " `department`='$department' and `zone`='$block' and `office_name`='$office' order by name asc");
    die(json_encode($user));
}
if(isset($_REQUEST['GET_CIRCLE_COUNT'])) {
    $q = $con->query("SELECT DISTINCT(circle) FROM `offices` WHERE `department`='EDUCATION' and `department` is not NULL"); 
    echo $q->num_rows;
    exit;
}
if(isset($_REQUEST['GET_TEACHER_COUNT'])) {
    $q = $con->query("SELECT count(id) FROM `employees` WHERE `department`='EDUCATION' "); 
    echo $q->fetch_array()[0];
    exit;
}



if(isset($_REQUEST['GET_TODAY_PRESENTS'])) {
    $q = $con->query("SELECT count(id) FROM `attendance` WHERE `department`='$dept' and `date`='$date' "); 
    echo $q->fetch_array()[0];
    exit;
}


if(isset($_REQUEST['CHECK_REMOTE_ATTENDANCE'])) {
    // $debug=true;
    
    $user = DevSecIt\GET_DATA("users", " `id`='$uid' " );
    $name = $user['name'];
    $phone = $user['phone'];
    $branch = $user['branch'];
    
    $ch= DevSecIt\GET_DATA("remote_attendance", " `uid`='$uid' and `branch`='$branch' and `date`='$date' " );
    
    
    if($ch==null) {
        $admin = DevSecIt\GET_DATA("users", " `branch`='$branch' and `rank`='ADMIN' and `status`='Active' "); 
        $aphone = $admin['phone']; 
        $ins= $con->query("INSERT INTO `remote_attendance` SET `uid`='$uid', `branch`='$branch', `date`='$date', `time`='$time', `name`='$name', `phone`='$phone' ");
        sendWA($aphone, "$name has been requested for remote attendance! Please update request status quickly. Otherwise request will automatically deleted! ");  
        die("Pending"); 
    } else {
        $status = $ch['status'];
        die($status);
    }
 }
if(isset($_REQUEST['UPDATE_ATTENDANCE_REQUEST'])) {
    $ch= DevSecIt\GET_DATA("remote_attendance", " `id`='$id' and `branch`='$branch' and `date`='$date' " );
    if($ch==null) {
        die("failed"); 
    }
    $uid = $ch['uid']; 
    $user = DevSecIt\GET_DATA("users", " `id`='$uid' " );
    $name = $user['name'];
    $phone = $user['phone'];
    $branch = $user['branch'];
    
    $ins= $con->query("UPDATE `remote_attendance` SET `status`='$status', `modified_by`='$modified_by' WHERE `id`='$id' ");
    if($status=="Yes"){
        sendWA($phone, "Dear $name your remote attendance request has been approved! Please give your attendance shortly, Otherwise request will automatically deleted! ");  
    } else {
        sendWA($phone, "Dear $name your remote attendance request has been rejected!");  
    }
        die("success"); 
 }


if(isset($_REQUEST['GET_ALL_ATTENDANCES'])) {
    if($rank=="SUPER ADMIN") {
        $data = DevSecIt\GET_DATA_WHILE("attendance", " 1 order by id asc ");
    } else {
         $data = DevSecIt\GET_DATA_WHILE("attendance", " `branch`='$branch' order by id asc ");
    }
    die(json_encode($data)); 
    exit;
}
if(isset($_REQUEST['GET_ALL_ATTENDANCE_REUQEST'])) {
    if($rank=="SUPER ADMIN") {
        $data = DevSecIt\GET_DATA_WHILE("remote_attendance", " 1 order by id desc ");
    } else {
         $data = DevSecIt\GET_DATA_WHILE("remote_attendance", " `branch`='$branch' order by id desc ");
    }
    die(json_encode($data)); 
    exit;
}

if(isset($_REQUEST['GET_ALL_ABSENT_ATTENDANCE'])) {
    $q = $con->query("SELECT branch,name, phone, address, remote_attendance FROM `users` WHERE `rank`!='USER' and `rank`!='VENDOR' and `status`='Active' ");
    $dat = [];
    while($data = $q->fetch_assoc()) {
        $uid= $data['id']; 
        $qq = $con->query("SELECT * FROM `attendance` WHERE `date`='$date' and `uid`='$uid' ");
        if($qq->num_rows <1 ) {
            $dat[]=$data;
        }
    }
    
    die(json_encode($dat)); 
    exit;
}


if(isset($_REQUEST['GET_TODAY_PERIODS'])) {
    $q = $con->query("SELECT count(id) FROM `schedules` WHERE  `date`='$date' "); 
    echo $q->fetch_array()[0];
    exit;
}




if (isset($_REQUEST['ADD_UPDATE_ATTENDANCE'])) {
    $uid = sanitize($con, $_REQUEST['uid']);
    $lat = sanitize($con, $_REQUEST['lat']);
    $lon = sanitize($con, $_REQUEST['lon']);

    $ud = $con->query("SELECT * FROM `users` WHERE `id`='$uid'  ");
    $user = $ud->fetch_assoc();

    $designation = $user['rank']; 
    $name = $user['name'];
 
    // file_put_contents("resp.txt", json_encode($_REQUEST));
    $office = $user['office_name']; 
    
   
    $officeId = $user['office_id'];
    $ch = $con->query("SELECT * FROM `attendance` WHERE `uid` = '$uid' and `date` = '$date' ");
    if ($ch->num_rows > 0) {
        // update out time 
        $dt = $ch->fetch_assoc();
        $id = $dt['id'];
        $con->query("UPDATE `attendance` SET `out_time` = '$time' WHERE `uid` = '$uid' and `date` = '$date' and `id`='$id' ");
        die("true");
    } else {

        $con->query("INSERT INTO `attendance` SET `uid` = '$uid', `lat` = '$lat', `lon` = '$lon', `date`='$date',`in_time`='$time', `zone` = '$zone', `office_name` = '$office', `office_id` = '$officeId', `designation` = '$designation', `name` = '$name', `time`='$time' ");
        echo "true";
        die;
    }
}


if (isset($_POST['RESET_PASSWORD'])) {
    $phone = sanitize($_POST['phone']);
    $ch = $con->query("SELECT id FROM `employees` WHERE `phone`='$phone' ");
    if ($ch->num_rows > 0) {
        $otp = rand("111111", "999999");

        $update = $con->query("UPDATE `employees` SET `password`='$otp' WHERE  `phone` = '$phone' ");
        $resp = file_get_contents("$ACTIN_URL?api_key=$API_KEY&sender=$SENDER_KEY&number=91$phone&message=Dear+user+your+one+time+password+is+*$otp*.%0a%0aThanks,%0a*UD%20AMS*%0aSUPPORT:https%3A%2F%2Fams.dmud.in%2Fsupport%2F%0aEMAIL:$SUPPORT_EMAIL%0aWhatsApp:$SUPPORT_PHONE");
        die($resp);
    } else {
        die("false");
    }
}
  

 // Function to calculate the geodesic distance using the Haversine formula
    function calculateGEODistance($startLat, $startLon, $endLat, $endLon) {
        $R = 6371; // Radius of the earth in km
        
        // Convert degrees to radians
        $latDistance = deg2rad($endLat - $startLat);
        $lonDistance = deg2rad($endLon - $startLon);
    
        // Haversine formula
        $a = sin($latDistance / 2) * sin($latDistance / 2) +
             cos(deg2rad($startLat)) * cos(deg2rad($endLat)) *
             sin($lonDistance / 2) * sin($lonDistance / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
        // Calculate the distance
        $distance = $R * $c;
    
        return $distance; // Distance in km
    }
    
    
    
if (isset($_REQUEST['GET_LAT_LON_ALL_BY_LAT_LON'])) { 
   extract($_REQUEST); 
    $nearbyOffices = [];
    
    try { 
        $stmt = $con->query("SELECT name, lat, lon, department FROM `offices` ORDER BY id ASC");
        
        if ($stmt) {
            while ($data = $stmt->fetch_assoc()) {
                // Fetch office data
                $name = $data['name']; 
                $department = $data['department'] ?? 'Unknown'; // Fallback if 'department' is null
                
                $endLat = $data['lat']; 
                $endLon = $data['lon']; 
                
                // Calculate the distance from the start location
                $dist = calculateGEODistance($startLat, $startLon, $endLat, $endLon);
                
                // If the distance is within 50 km, add to the array
                if ($dist <= 0.299) {
                    $nearbyOffices[] = [
                        'name' => $name,
                        'lat' => $endLat,
                        'lon' => $endLon,
                        'distance' => $dist,
                        'type' => $department,
                    ];
                }
            }
    
            // Sort the nearby offices by distance in ascending order
            usort($nearbyOffices, function($a, $b) {
                return $a['distance'] <=> $b['distance'];
            });
    
            // Output the result as JSON
            header('Content-Type: application/json');
            echo json_encode($nearbyOffices);
    
        } else {
            throw new Exception("Query execution failed.");
        }
    
    } catch (Exception $e) {
        // Handle errors (e.g., query failures, connection issues)
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

  

if(isset($_POST['UPDATE_OFFICE_DATA'])) {
    extract($_REQUEST);
      
    $upd = $con->query("UPDATE `offices` SET name='$name', `address`='$address', `lat`='$lat', `lon`='$lon', `reg_no`='$reg_no' WHERE `id`='$id' ");
    if($con->affected_rows>0) {
        die("success");
    } else {
        die("failed");
    }
}