<?php  
    $version = "2.0.0";
  
    if(isset($_REQUEST['version'])) { 
        die($version);
    }
    
    function getUserInfo($con, $uid) {
        $q = $con->query("SELECT * FROM `users` WHERE `id`='$uid' "); 
        $data = mysqli_fetch_array($q);
        return $data;
    }
    
    function getWorkerInfo($con, $uid) {
        $q = $con->query("SELECT * FROM `workers` WHERE `id`='$uid' "); 
        $data = mysqli_fetch_array($q);
        return $data;
    }
    
    
    if(isset($_POST['upload_image'])) {
        if(file_put_contents("img/".time().".png", base64_decode($_POST['thumbnail']))) {
            echo "Success"; 
        } 
    }
    
    if(isset($_POST['register'])) {
        $name = sanitize($con,$_POST['name']); 
        $email = sanitize($con,$_POST['email']); 
        $phone = sanitize($con,$_POST['phone']);
        $address = sanitize($con,$_POST['address']); 
        $pincode = sanitize($con,$_POST['pincode']); 
        $gender = sanitize($con,$_POST['gender']);
        $age = sanitize($con,$_POST['age']);
        $password = sanitize($con,$_POST['password']); 
        
        recheck:
            $q=  mysqli_query($con,"SELECT * FROM `users` WHERE `phone`='$phone' "); 
            $res = mysqli_fetch_array($q); 
            if($res!=null) {
                $jsonData1[] = $res;
                echo json_encode($jsonData1); 
            } else {
                $ins = mysqli_query($con,"INSERT INTO `users` SET `name`='$name',`email`='$email',`address`='$address',`pincode`='$pincode',`gender`='$gender',`age`='$age',`phone`='$phone',`password`='$password',`status`='Active' "); 
                if($ins) {
                    goto recheck; 
                } else {
                    
                    echo "failed"; 
                }
            }
    }
    
    // if(isset($_POST['registerfamily'])) {
    //     $name = sanitize($con,$_POST['name']); 
    //     $email = sanitize($con,$_POST['email']); 
    //     $phone = sanitize($con,$_POST['phone']);
    //     $address = sanitize($con,$_POST['address']); 
    //     $pincode = sanitize($con,$_POST['pincode']); 
    //     $gender = sanitize($con,$_POST['gender']);
    //     $age = sanitize($con,$_POST['age']);
    //     $password = sanitize($con,$_POST['password']); 
        
    //     recheck:
    //         $q=  mysqli_query($con,"SELECT * FROM `familymembers` WHERE `email`='$email' "); 
    //         $res = mysqli_fetch_array($q); 
    //         if($res!=null) {
    //             $jsonData1[] = $res;
    //             echo json_encode($jsonData1); 
    //         } else {
    //             $ins = mysqli_query($con,"INSERT INTO `familymembers` SET `name`='$name',`email`='$email',`address`='$address',`pincode`='$pincode',`gender`='$gender',`age`='$age',`phone`='$phone',`password`='$password',`status`='Active' "); 
    //             if($ins) {
    //                 goto recheck; 
    //             } else {
                    
    //                 echo "failed"; 
    //             }
    //         }
    // }
    
    if(isset($_POST['registerWorker'])) {
        
        $name = sanitize($con,$_POST['name']); 
        $email = sanitize($con,$_POST['email']); 
        // $phone = sanitize($con,$_POST['phone']); 
        
        recheckx:
            $q=  mysqli_query($con,"SELECT * FROM `workers` WHERE `email`='$email' "); 
            $res = mysqli_fetch_array($q); 
            if($res!=null) {
                $jsonData1[] = $res;
                echo json_encode($jsonData1); 
            } else {
                $ins = mysqli_query($con,"INSERT INTO `workers` SET `name`='$name',`email`='$email' "); 
                if($ins) {
                    goto recheckx; 
                } else {
                    $jsonData1[] = $res['0']="Failed";
                    echo json_encode($jsonData1); 
                } 
            } 
    }
    
    //insert contact Us
    if(isset($_POST['Contactus'])) {
        $name = sanitize($con,$_POST['name']); 
        $email = sanitize($con,$_POST['email']); 
        $phone = sanitize($con,$_POST['phone']); 
        $msg = sanitize($con,$_POST['msg']);
         
            $res = mysqli_fetch_array($q); 
            if($res!=null) {
                $jsonData1[] = $res;
                echo json_encode($jsonData1); 
            } else {
                $ins = mysqli_query($con,"INSERT INTO `contact_us` SET `name`='$name',`email`='$email',`phone`='$phone',`msg`='$msg' "); 
                if($ins) {
                    echo "Success";  
                } else {
                    
                    echo "failed"; 
                }
            }
    }
    
    //insert Feedback
    if(isset($_POST['Feedbackform'])) {
         $uid = $_POST['uid'];  
        // get user 
        $u = $con->query("SELECT  * FROM `users` WHERE `id`='$uid' "); 
        $user = $u->fetch_array();
        $name = $user['name']; 
        $email = $user['email']; 
        $phone = $user['phone']; 
        $msg = sanitize($con,$_POST['msg']);
         
            // insert into orders 
                 $ins = $con->query("INSERT INTO `contact_us` SET `uid`='$uid',`name`='$name',`phone`='$phone',`email`='$email',`msg`='$msg' ");
        if($ins) {
            $cid = $data['id'];
            $con->query("DELETE FROM `cart` WHERE `id`='$cid' and `uid`='$uid' ");
        }
    
    $json[0]['status']="Success";
    echo json_encode($json);
}
   
   // my details 
    if(isset($_POST['myDetails'])) {
        $uid = sanitize($con,$_POST['uid']);  
         
            $q=  mysqli_query($con,"SELECT * FROM `users` WHERE `id`='$uid' "); 
            $res = mysqli_fetch_array($q); 
            if($res!=null) {
                $jsonData1[] = $res;
                echo json_encode($jsonData1); 
            } else {
                 echo 'Failed';
            }
    }
   // my details 
    if(isset($_POST['update_phone'])) {
        $uid = sanitize($con,$_POST['uid']);  
        $phone = sanitize($con,$_POST['phone']);  
         
            $q=  mysqli_query($con,"UPDATE `users` SET `phone`='$phone' WHERE `id`='$uid' "); 
             
            if($q) {
                echo "Success"; 
            } else {
                 echo 'Failed';
            }
    }


// getting bookings
if(isset($_POST['getMyBookings'])) {
    $uid = sanitize($con,$_POST['uid']);  

    $q = mysqli_query($con,"SELECT * FROM `orders` WHERE `status`!='Cancel' and `uid`='$uid' order by `id` desc "); 
    // $res = mysqli_fetch_array($q); 
    
    if(mysqli_num_rows($q)>0) {
        //  $jsonData1 = [];
        while($re = mysqli_fetch_array($q)) {
            $pid = $re['pid']; 
            
            if($re['type']=="Medicine") {
                $qx = $con->query("SELECT * FROM `medicines` WHERE `id`='$pid' "); 
            } else if($re['type']=="labtest") {
                $qx = $con->query("SELECT * FROM `lab_tests` WHERE `id`='$pid' "); 
            } else {
                $qx = $con->query("SELECT * FROM `hospital_doctor_booking` WHERE `id`='$pid' "); 
            }
            // $s= mysqli_query($con,"SELECT * FROM `services` WHERE `id`='$pid' ");
            $ser = mysqli_fetch_array($qx);
            $re['ext'][0]= $ser;
            // $re['thumbnail'].= $ser['thumbnail'];
            
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}
 

//get pharmacy category
if(isset($_POST['getallcategory'])) { 
    $limit = $_POST['limit']; 
    $q = mysqli_query($con,"SELECT * FROM `medicine-category` order by rand() limit  $limit "); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

//get limited time deals for pharmacy
if(isset($_POST['getAlllimitedtimedeals'])) { 
    $limit = $_POST['limit']; 
    $q = mysqli_query($con,"SELECT * FROM `medicines` order by rand() limit  $limit "); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

//get limited time deals
if(isset($_POST['getalllimiteddeals'])) { 
    $limit = $_POST['limit']; 
    $q = mysqli_query($con,"SELECT * FROM `limited_time_deals` order by rand() limit  $limit "); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

//get Disease wise care
if(isset($_POST['getDiseasewisecare'])) { 
    $limit = $_POST['limit']; 
    $q = mysqli_query($con,"SELECT * FROM `medicines` order by rand() limit  $limit "); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

//get all adult care
if(isset($_POST['getAlladultcare'])) { 
    $limit = $_POST['limit']; 
    $q = mysqli_query($con,"SELECT * FROM `medicines` order by rand() limit  $limit "); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

//get all Medicines
if(isset($_POST['getAllMedicines'])) { 
    $limit = $_POST['limit']; 
    $q = mysqli_query($con,"SELECT * FROM `medicines` order by rand() limit  $limit "); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

/// order place 
if(isset($_POST['placeOrder'])){
    date_default_timezone_set("Asia/Kolkata"); 
    
   
    $uid = sanitize($con,$_POST['uid']); 
    $name = sanitize($con,$_POST['name']); 
    $phone = sanitize($con,$_POST['phone']);  
    $address = sanitize($con,$_POST['address']);  
    $date = sanitize($con,$_POST['date']);  
    $time = sanitize($con,$_POST['time']);  
    
    $email = sanitize($con,$_POST['email']);  
    $sid = sanitize($con,$_POST['sid']);  
    $price = sanitize($con,$_POST['price']);  
    $category = sanitize($con,$_POST['category']);  
    $service = sanitize($con,$_POST['service']);  
    
    $otp = rand(1111,9999);
    
  
    $upd = mysqli_query($con,"INSERT INTO `bookings` SET `uid`='$uid',`tenant`='$name',`tenant_phone`='$phone',`tenant_email`='$email',`sid`='$sid',
    `status`='Pending',`address`='$address',`price`='$price',`category`='$category',`date`='$date',`time`='$time',`otp`='$otp',`booking_id`=$booking_id "); 
    
    $update = $con->query("UPDATE `users` SET `address`='$address' WHERE `id`='$uid' ");
    
    sendmail($email, "New order confirmation for $service - Roy Sercice", "Hello dear $name , your booking order has been placed. <br> Your booking confirmation id is <b> $otp </b> . Please share this otp with only our agent for verification purpose not another person. Stay connected with us. ");
    
    // file_get_contents($sms_uri."?sendSMS=true&phone=$phone&message=New booking order placed by $name . Contact $phone"); 
    
    sendSMS("8670731523", "New booking order placed for $service by $name .Contact $phone");
    
    if($upd) {
        echo "Success";
    } else{
        echo "Failed"; 
    }
}

/// cancel order 
if(isset($_POST['cancelOrder'])){
    date_default_timezone_set("Asia/Kolkata"); 
    
   
    $uid = sanitize($con,$_POST['uid']); 
    $oid = sanitize($con,$_POST['oid']); 
    $phone = sanitize($con,$_POST['phone']);  
    $name = sanitize($con,$_POST['name']);  
    
    $email = sanitize($con,$_POST['email']);  
    $service = sanitize($con,$_POST['service']);  
     
    $upd = mysqli_query($con,"UPDATE `bookings` SET `status`='Cancelled by user' WHERE `id`='$oid'  "); 
    
   
    sendmail($email, "Order cancellation for $service - Roy Sercice", "Hello dear $name , your booking order has been cancelled. <br>
        Visit our application and place another order <a href='https://royservice.com/download'> here </a>. ");
    
    // file_get_contents($sms_uri."?sendSMS=true&phone=$phone&message=New booking order placed by $name . Contact $phone"); 
    
    // sendSMS("8670731523", "#$oid ($service) - Order cancelled by $name .Contact $phone");
    sendSMS("8670731523", "$oid $service - Order cancelled by $name .Contact $phone");
    
    if($upd) {
        echo "Success";
    } else{
        echo "Failed"; 
    }
}

  

/// add service 
if(isset($_POST['addService'])){
    date_default_timezone_set("Asia/Kolkata"); 
    $img = date("dmYHis").".png"; 
    $title = sanitize($con,$_POST['title']); 
    $charge = sanitize($con,$_POST['charge']); 
    $category = sanitize($con,$_POST['category']);  
    
    file_put_contents("images/$img", base64_decode($_POST['thumbnail'])); 
    
    $upd = mysqli_query($con,"INSERT INTO `services` SET `thumbnail`='$img',`status`='Live',`title`='$title',`charge`='$charge',`category`='$category' "); 
    if($upd) {
        echo "Success";
    } else{
        echo "Failed"; 
    }
}

// add category 
if(isset($_POST['addCategory'])){
    date_default_timezone_set("Asia/Kolkata"); 
    $img = date("dmYHis").".png"; 
    $title = sanitize($con,$_POST['title']); 
    file_put_contents("images/$img", base64_decode($_POST['thumbnail'])); 
    
    $upd = mysqli_query($con,"INSERT INTO `categories` SET `thumbnail`='$img',`status`='Live',`title`='$title' "); 
    if($upd) {
        echo "Success";
    } else{
        echo "Failed"; 
    }
}

if(isset($_POST['AddSlider'])){
    date_default_timezone_set("Asia/Kolkata"); 
    $img = date("dmYHis").".png"; 
     
    file_put_contents("images/$img", base64_decode($_POST['thumbnail'])); 
    
    $upd = mysqli_query($con,"INSERT INTO `banners` SET `thumbnail`='$img' "); 
    if($upd) {
        echo "Success";
    } else{
        echo "Failed"; 
    }
}

//get slider banner
if(isset($_POST['getsliders'])) { 
    $limit = $_POST['limit']; 
    $q = mysqli_query($con,"SELECT * FROM `sliders` order by `for`='$for' limit  $limit "); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

//get all notification
if(isset($_POST['getallNotification'])) { 
    $limit = $_POST['limit']; 
    $q = mysqli_query($con,"SELECT * FROM `notification` order by id desc  limit  $limit "); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

//get getUserdetails
if(isset($_POST['getallUserdetails'])) { 
    
    $uid = sanitize($con,$_POST['uid']);  
         
            $q=  mysqli_query($con,"SELECT * FROM `users` WHERE `id`='$uid' "); 
            $res = mysqli_fetch_array($q); 
            if($res!=null) {
                $jsonData1[] = $res;
                echo json_encode($jsonData1); 
            } else {
                 echo 'failed';
            }
}

//get medical Product advartisement
if(isset($_POST['getMedicalproduct'])) { 
    $limit = $_POST['limit']; 
    $q = mysqli_query($con,"SELECT * FROM `medical_product` order by rand() limit  $limit "); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

//get medical Product advartisement for view all
if(isset($_POST['getMedicalproducts'])) { 
    $limit = $_POST['limit']; 
    $q = mysqli_query($con,"SELECT * FROM `medical_product` order by rand()"); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

//get offers
if(isset($_POST['getOffers'])) { 
    $limit = $_POST['limit']; 
    $q = mysqli_query($con,"SELECT * FROM `offers` order by rand() limit  $limit "); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

//get all hospitals
if(isset($_POST['getallhospital'])) { 
    $limit = $_POST['limit']; 
    $q = mysqli_query($con,"SELECT * FROM `hospitals` order by rand() limit  $limit "); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

//get all Top Reated hospitals
if(isset($_POST['getalltopreatedhospital'])) { 
    $limit = $_POST['limit']; 
    $q = mysqli_query($con,"SELECT * FROM `hospitals` order by `rating` desc limit  $limit "); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

//get all doctors hospital wise
if(isset($_POST['getAlldoctorshospital'])) { 
    $limit = $_POST['limit']; 
    $h_id = $_POST['h_id']; 
    
    $q = mysqli_query($con,"SELECT * FROM `hospital_doctors` WHERE `h_id`='$h_id'");
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    }
    // if(mysqli_num_rows($q)>0) {
    //     while($hosp = mysqli_fetch_array($q)) {
    //         $did= $hosp['d_id'];
    //         $dq = $con->query("SELECT * FROM `hospital_doctors` WHERE `id`='$did' "); 
    //         $doct = $dq->fetch_array();
            
    //         $jsonData1[]=$doct;
    //     }
         
    //     echo json_encode($jsonData1); 
    // }
    else { 
        echo "Failed" ; 
    } 
}

//get all doctors hospital wise
if(isset($_POST['getAlldoctorshospitalwise'])) { 
    $limit = $_POST['limit']; 
    $h_id = $_POST['h_id']; 
    
    $q = mysqli_query($con,"SELECT * FROM `hospital_doctors` WHERE `status`='Active' and `h_id`='$h_id' ");
    if(mysqli_num_rows($q)>0) {
        while($hosp = mysqli_fetch_array($q)) {
            $did= $hosp['d_id'];
            $dq = $con->query("SELECT * FROM `doctors` WHERE `id`='$did' "); 
            $doct = $dq->fetch_array();
            
            $jsonData1[]=$doct;
        }
         
        echo json_encode($jsonData1); 
    }
    else { 
        echo "Failed" ; 
    } 
}

//get all doctors
if(isset($_POST['getAlldoctors'])) { 
    $limit = $_POST['limit']; 
    $q = mysqli_query($con,"SELECT * FROM `doctors` order by rand() "); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

//get all doctors specialists wise
if(isset($_REQUEST['getAllspecialistsWithCategory'])) { 
    $limit = $_POST['limit']; 
    $category = $_REQUEST['category']; 
    
    $q = mysqli_query($con,"SELECT a.*, b.thumbnail, b.about, b.name, b.speciality, b.rating, b.experience, b.hospital, c.address, c.city, c.state, c.map FROM `chambers` a join doctors b on a.did=b.id JOIN hospitals c on a.oid=c.id WHERE a.specialist LIKE '%$category%'  order by name asc ");
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_assoc($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

if(isset($_POST['GET_ALL_DOCTORS'])) {   
    $q = mysqli_query($con,"SELECT * FROM `doctors` WHERE `status`='Active'  order by name asc ");
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[]= $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}
if(isset($_POST['GET_ALL_CLINICS'])) {   
    $q = mysqli_query($con,"SELECT * FROM `hospitals` WHERE `status`='Active'  order by name asc ");
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[]= $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

//get all doctors specialists wise
if(isset($_POST['getAllspecialists'])) { 
    $limit = $_POST['limit'];  
    $json=[]; 
    $q = mysqli_query($con,"SELECT DISTINCT(`specialist`) FROM `chambers` order by specialist asc "); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $name = $re['specialist']; 
            $dt = DevSecIt\GET_DATA("specialist", " `name`='$name' ");
            if($dt!=null) {
                $json[]=$dt;
            }
        }
        echo json_encode($json); 
    } else { 
        echo "Failed" ; 
    } 
}

//get all doctors details wise
if(isset($_POST['getDoctorsdetails'])) { 
    $limit = $_POST['limit']; 
    $q = mysqli_query($con,"SELECT * FROM `doctors` WHERE `id`='$uid' "); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

//get all doctors
if(isset($_POST['getAlldoctors'])) { 
    $limit = $_POST['limit']; 
    $q = mysqli_query($con,"SELECT * FROM `doctors` order by rand() "); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

//get all nurses
if(isset($_POST['getAllNurse'])) { 
    $limit = $_POST['limit']; 
    $q = mysqli_query($con,"SELECT * FROM `nurses` order by rand() "); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

//get all hospital doctors
if(isset($_POST['getAllamount'])) { 
    $limit = $_POST['limit']; 
    $q = mysqli_query($con,"SELECT * FROM `hospital_doctors` order by rand() "); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

//get all dr. orderbooking details
if(isset($_POST['getDrbookingdetails'])) { 
    // $limit = $_POST['limit']; 
    $uid = sanitize($con,$_POST['uid']);  

    $q = mysqli_query($con,"SELECT * FROM `appointments` WHERE `status`!='Cancel' and `uid`='$uid' order by `id` desc ");
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

//get all dr. orderbooking details
if(isset($_POST['getLabbookingdetails'])) { 
    // $limit = $_POST['limit']; 
    $uid = sanitize($con,$_POST['uid']);  

    $q = mysqli_query($con,"SELECT * FROM `orders` WHERE `status`!='Cancel' and `uid`='$uid' order by `id` desc ");
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

//// filtered 
//get all doctors
if(isset($_POST['getfilteredDoctors'])) { 
    $limit = $_POST['limit']; 
    $que = $_POST['query']; 
    
    $q = mysqli_query($con,"SELECT a.*, b.thumbnail, b.about, b.hospital, b.experience, b.rating, c.address, c.city, c.district FROM `chambers` a JOIN doctors b ON a.did=b.id JOIN hospitals c on a.oid=c.id WHERE (`oName` like '%$que%'  or `doctorName` like '%$que%' or
    `speciality` like '%$que%' or `oName` like '$que%'  or `doctorName` like '$que%' or `speciality` like '$que%') order by rand();"); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}
if(isset($_POST['getfilteredTests'])) { 
    $limit = $_POST['limit']; 
    $que = $_POST['query']; 
    
    $q = mysqli_query($con,"SELECT * FROM `lab_tests` WHERE (`title` like '%$que%' or `category` like '%$que%') and `status`='Active'  order by rand() "); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

if(isset($_POST['getfilteredMedicine'])) { 
    $limit = $_POST['limit']; 
    $que = $_POST['query']; 
    
    $q = mysqli_query($con,"SELECT * FROM `medicines` WHERE (`title` like '%$que%' or `medicine-category` like '%$que%') and `status`='Active'  order by rand() "); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $pid = $re['p_id'];
            $pd = $con->query("SELECT `name` FROM `pharmacy` where `id`='$pid'  "); 
            $dt = $pd->fetch_array(); 
            
            $re['pharmacy'].= $dt[0];
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

// insert booking details
if(isset($_POST['addBookings'])) {
    extract($_POST);
    $booking_id = time().rand(24,99);
    $ins = mysqli_query($con,"INSERT INTO `doctor_booking` SET `uid`='$uid',`doc_id`='$doc_id',`name`='$name',`email`='$email',`phone`='$phone',
                        `booking_date`='$booking_time',`doctor_name`='$doctor_name',`hospital_name`='$hospital_name',`specialists`='$specialists',
                        `price`='$price',`gst`='$gst',`net_price`='$net_price',`booking_id`='$booking_id',
                        `status`='$status',`thumbnail`='$thumbnail'");
    // $res = mysqli_fetch_array($ins);
     $id = $con->insert_id; 
    
    if($ins) {
              
               $qx=$con->query("SELECT * FROM `booking` WHERE `id`='$id'");
               $data=$qx->fetch_array();
               $json[0]=$data;
               echo json_encode($json);
            } else {
                 echo 'Failed';
                 
            }
}


//get all hospitals for view all page
if(isset($_POST['getHospitalByDoctor'])) { 
    $limit = $_POST['limit']; 
    $d_id = $_POST['d_id']; 
    
    
    $s = $con->query("SELECT * FROM `hospital_doctors` WHERE `d_id`='$d_id' and `status`='Active' "); 
    if($s->num_rows>0) {
        while($data = $s->fetch_array()){
            
            $hid = $data['h_id']; 
            
             $q = mysqli_query($con,"SELECT * FROM `hospitals` WHERE `id`='$hid'  order by rand() "); 
            if(mysqli_num_rows($q)>0) {
                $re = mysqli_fetch_array($q); 
                    $jsonData1[] = $re; 
            }  
        }
        echo json_encode($jsonData1); 
    } else {
        echo "failed";
    }
   
}

//get all hospitals
if(isset($_POST['getAllhospitals'])) { 
    $limit = $_POST['limit']; 
    $q = mysqli_query($con,"SELECT * FROM `hospitals` order by rand() "); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}


if(isset($_REQUEST['getSlot'])) {
    extract($_POST); 
    $da = strtolower(date("D", strtotime($date))); 
    
    if($da=="sun") {
        $da = "sunday";
    } else if($da== "mon") {
        $da = "monday";
    } else if($da == "tue") {
        $da = "tuesday";
    } else if($da== "wed") {
        $da = "wednesday";
    } else if($da=="thu") {
        $da= "thursday"; 
    } else if($da== "fri") {
        $da = "friday"; 
    } else {
        $da= "saturday"; 
    }
    
    
    $q = $con->query("SELECT `id`, `$da`,visiting_charge, `quota` FROM `chambers` WHERE `$da`!='No' and `did`='$doc_id' and `oid`='$hid'  ");
    if($q->num_rows >0) {
        $data = $q->fetch_array();
        $limit= $data['quota']; 
        $rs= json_decode($data[$da], 1); 
        
       
        if($rs['is_open']==false) {
            die("Doctor is not available on this! Please try with another else");
        }
        $da = substr($da,0,3);
         
        // die(explode(":", $rs['start_time'])[0]);
        
        if( $da == strtolower(date("D")) ) {
            // die( explode(":", $rs['start_time'])[0] . "::: ". date("H")+2 ); 
            
            if( explode(":", $rs['start_time'])[0] < date("H")+2 ) {
                die("Doctor booking is not possible right now. Please try with another date");
            }
        }
        
        
        $appts = DevSecIt\GET_DATA_WHILE("appointments", " `did`='$doc_id' and `oid`='$hid' and `booking_date`='$date' and `status`='Pending' and `uid`='$uid' " );
        
        
        // die("$da :: " .strtolower(date("D"))); 
        
        $rs['self'] = count($appts); 
        $rs['amount'] = $data['visiting_charge']; 
        $rs['id'] = $data['id'];  
        $rs['limit'] = $limit; 
        
        $bookings = $con->query("SELECT count(id) FROM `appointments` WHERE `did`='$doc_id' and `oid`='$hid' and `booking_date`='$date' and `status`!='Cancelled' ");
        $allBooks = $bookings->fetch_array()[0]; 
        
        if($allBooks >= $limit) {
            $rs['msg'] = "All Slots has been booked! Please try with another date";
            $rs['left'] = 0;
        } else {
            $rs['left'] = $limit - $allBooks;
        }
        
        $rs['booked'] = $allBooks; 
        $rs['quota'] = $limit; 
        
        
        $json[0]= $rs ; 
        echo json_encode($json);
    } else {
        echo "failed";
    }
    
}





if(isset($_POST['hospital_doctor_booking'])){
    extract($_POST); 
    
    $bookings = DevSecIt\GET_DATA_WHILE("appointments", " oid='$hid' and `did`='$doc_id' and `booking_date`='$booking_date' " );
    $ph = DevSecit\GET_DATA("chambers", " `oid`='$hid' and `did`='$doc_id' ");
    
    
    $doctor = DevSecit\GET_DATA("doctors", " `id`='$doc_id' ");
    
    $user = DevSecIt\GET_DATA("users", " id='$uid' ");
    $phone = $user['phone']; 
    
    $oid= time().$uid; 
    $h = $con->query("SELECT * FROM `hospitals` WHERE `id`='$hid' ");
    $hosp = $h->fetch_array();
    
    $hospital = $hosp['name']; 
    // $thumbnail = $hosp['thumbnail'];
    $booking_id = time().rand(24,99);
    
    $chk = $con->query("SELECT count(id) FROM `appointments` WHERE `booking_date`='$booking_date' and `oid`='$hid' and `did`='$doc_id'  ");
    $cnt = $chk->fetch_array()[0];
    if($cnt>0) {
        $slot_number = $cnt+1;
    } else {
        $slot_number = "1";
    }
    
    
    if($ph['quota'] < count($bookings)+1 ) {
         sendWA("7098656818", str_replace(" ", "%20","*Critical Alert!*%0aDate : $booking_date %0aAll booking slots has been reserved for *$doctor_name* at $hospital . %0a%0a$name wants to book appointment there. %0aPhone - $phone %0a %0a---------------- ----------------%0aWebsite: https://healthalign.in/ .%0aThanks%0a"));
         die("All slots has been booked!");
    }
    
    
    $hospx = DevSecIt\GET_DATA("chambers", " `oid`='$hid' and `did`='$doc_id' ");
    if($hospx==null) {
        die("Chamber details not found!"); 
    }
    
    $current_time = date("H");
    
    $today = strtolower(date("D", strtotime($date) ));
    
    $data =  explode(":",str_replace("start_time:","",str_replace("{", "",str_replace(" ","", str_replace("Am","",str_replace("Pm","",explode(",",$hospx[$today])[0]))))))[0];
    
    // die($data);
    if(strpos($hospx[$today], "Pm")!==false) {
        $data = $data+12;
    }
    
    $ch_time = $data;
    
    
    if($current_time > $ch_time || $current_time > 23 ) {
        
         
        if(date("D")==$today){
            sendWA($phone, str_replace(" ", "%20","Dear $name, we are sorry to say you that you can not take appointment to this doctor currently. Please try again later. %0aFor more details visit our app https://play.google.com/store/apps/details?id=com.udoctors.user .%0a---------------- ----------------%0aWebsite: https://healthalign.in/ %0aThanks%0a"));
            die("Today booking is not permitted!"); 
        }
    }
    
    
    $q = $con->query("INSERT INTO `appointments` SET `uid`='$uid',`did`='$doc_id',`oid`='$hid',`name`='$name', `phone`='$phone', `time_slot`='$time_slot', `pid`='$uid', `booking_type`='$booking_type', `booking_by`='$booking_by', `doctor`='$did', `user_id`='$uid', 
            `invoice_id`='$booking_id',`booking_date`='$booking_date',`booking_time`='$booking_time', `oName`='$hospital', `oType`='Hospital', `timestmp`='".time()."', `token`='$token', `date`='$date', `time`='$time',   `doctorName`='$doctor_name',`visiting_charge`='$price', 
            `status`='Pending',`address`='$u_address',  `slot_number`='$slot_number', `age`='$age',`gender`='$gender', `region`='$region', `district`='$district', `city`='$city', `state`='$state', `payment_status`='$payment_status', 
            `payment_method`='$payment_method', `paid_amount`='$amount', `due_amount`='$due_amount', `thumbnail`='$thumbnail'  ");
             // `net_price`='$net_price',`gst`='$gst',,
            
    $id = $con->insert_id;
    
    if($q) { 
        $dt = DevSecIt\GET_DATA_WHILE("appointments", " id='$id' " );
        $hospital = str_replace("&", " and ", $hospital);
        
        
        
        $inx = $con->query("INSERT INTO `notification` SET `type`='Doctor', `uid`='$doc_id', `title`='New appointment at $hospital', `sort_description`='$booking_date - $name booked appointment at $hospital',  `description`='$booking_date - $name booked appointment at $hospital' "); 
        $inc = $con->query("INSERT INTO `notification` SET `type`='Patient', `uid`='$uid', `title`='New appointment at $hospital', `sort_description`='$booking_date - $name booked appointment at $hospital',  `description`='$booking_date - $name booked appointment at $hospital' "); 
        
    //   $docbal = $doctor['balance'] + $amount;
        
    //     $credit_doc = [
    //             "name" => $doctor_name, 
    //             "phone" => $doctor['phone'], 
    //             "type" => "Credit", 
    //             "date"=> $date, 
    //             "time" => $time, 
    //             "uid" => $did, 
    //             "oid" => $hosp['id'], 
    //             "sender" => $user["id"], 
    //             "payment_type"=> "DR. BOOKING", 
    //             "txn_id" => $txn, 
    //             "region" => $region,
    //             "district" => $district, 
    //             "state" => $state, 
    //             "amount" => $amount, 
    //             "balance" => $docbal, 
    //             "payment_method" => $payment_method,
    //             "title" => "Appointment booking for $doctor_name as $hospital",
    //             "for"=> "DOCTOR", 
    //             "payment_date"=> $booking_date
    //         ];
    //     $upd = $con->query("UPDATE `doctors` SET `balance`='$docbal' WHERE `id`='".$doctor['id']."' ");
    //      DevSecIt\INSERT_DB_ARRAY("transactions", $credit_doc);
         
         
        $ubal = $user['balance'] + $amount;
        // add to wallet of customer first 
        $credit_PT = [
                "name" => $user['name'], 
                "phone" => $user['phone'], 
                "type" => "Credit", 
                "date"=> $date, 
                "time" => $time, 
                "uid" => $user['id'], 
                "sender" => $user["id"], 
                "payment_type"=> "WALLET TOPUP", 
                "txn_id" => "TP".$txn, 
                "region" => $region,
                "district" => $district, 
                "state" => $state, 
                "amount" => $amount, 
                "balance" => $ubal, 
                "payment_method" => $payment_method,
                "title" => "Wallet topup of Rs. $amount",
                "for"=> "PATIENT", 
                "payment_date"=> $booking_date
            ];
            
        DevSecIt\INSERT_DB_ARRAY("transactions", $credit_PT);
        
        // deduct money to book appointment
        
         $credit_PT2 = [
                "name" => $user['name'], 
                "phone" => $user['phone'], 
                "type" => "Debit", 
                "date"=> $date, 
                "time" => $time, 
                "uid" => $user['id'], 
                "sender" => $user["id"], 
                "payment_type"=> "WALLET TOPUP", 
                "txn_id" => "DB".$txn, 
                "region" => $region,
                "district" => $district, 
                "state" => $state, 
                "amount" => $amount, 
                "balance" => $user['balance'], 
                "payment_method" => $payment_method,
                "title" => "Appointment booking to $doctor_name at $hospital",
                "for"=> "PATIENT", 
                "payment_date"=> $booking_date
            ];
            
        DevSecIt\INSERT_DB_ARRAY("transactions", $credit_PT2);
         
         
        
        $bal = $hosp['balance'] + $amount;
        $credit = [
                "name" => $hospital, 
                "phone" => $hosp['phone'], 
                "type" => "Credit", 
                "date"=> $date, 
                "time" => $time, 
                "uid" => $hosp['id'], 
                "oid" => $hosp['id'], 
                "sender" => $user["id"], 
                "payment_type"=> "DR. BOOKING", 
                "txn_id" => $txn, 
                "region" => $region,
                "district" => $district, 
                "state" => $state, 
                "amount" => $amount, 
                "balance" => $bal, 
                "payment_method" => $payment_method,
                "title" => "$doctor_name",
                "for"=> "CHAMBER", 
                "payment_date"=> $booking_date
            ];
            
            DevSecIt\INSERT_DB_ARRAY("transactions", $credit);
            $upd = $con->query("UPDATE `hospitals` SET `balance`='$bal' WHERE `id`='".$hosp['id']."' "); 
        
          sendWA("7098656818", str_replace(" ", "%20","Date -$date %0aBooking id $booking_id %0aNew Order received for *$doctor_name* at $hospital . %0a %0a*SLOT NO- $slot_number* %0a---------------- ----------------%0aWebsite: https://healthalign.in/ .%0aThanks%0a"));
          
          sendWA("$phone", str_replace(" ", "%20","Date         : $date %0aBooking id : $booking_id %0aDoctor appointment has been successfully placed to *$doctor_name* at $hospital%0a---------------- ----------------%0a*SLOT NO- $slot_number* %0a---------------- ----------------%0aWebsite: https://healthalign.in/%0aThanks%0a"));
          
        
        
        echo json_encode($dt);
    } else {
        echo "Server Error!";
    }
}


//get all labs
if(isset($_POST['getLabs'])) { 
    $limit = $_POST['limit']; 
    $q = mysqli_query($con,"SELECT * FROM `laboratory` order by rand() limit  $limit "); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

//get all labs for view all
if(isset($_POST['getAllLabs'])) { 
    $limit = $_POST['limit']; 
    $q = mysqli_query($con,"SELECT * FROM `laboratory` order by rand()"); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

//get all labs for view all
if(isset($_POST['getAllfacilities'])) { 
    $limit = $_POST['limit']; 
    $q = mysqli_query($con,"SELECT * FROM `facilities` order by rand()"); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}


//get all test laborotary wise
if(isset($_POST['getAlltestslaborotarylwise'])) { 
    $limit = $_POST['limit']; 
    $lab_id = $_POST['lab_id']; 
    
    $q = mysqli_query($con,"SELECT * FROM `labwaise_labtest` WHERE `lab_id`='$lab_id' and `status`='Active'order by title asc "); 
    if(mysqli_num_rows($q)>0) {
        while($hosp = mysqli_fetch_array($q)) {
            
            $jsonData1[]=$hosp;
        }
         
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

//get all test facilitys wise
if(isset($_POST['getAlltestWithCategory'])) { 
    $limit = $_POST['limit']; 
    $category = $_POST['category'];  
    
    $q = mysqli_query($con,"SELECT * FROM `lab_tests` WHERE `category`='$category' and `status`='Active'  order by title asc "); 
    if(mysqli_num_rows($q)>0) {
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

 // add to cart
if(isset($_POST['addTocart'])) {
    extract($_POST);
    $ins = mysqli_query($con,"INSERT INTO `cart` SET `title`='$title',`labname`='$labname',`thumbnail`='$thumbnail',`rating`='$rating',`net_price`='$net_price',`price`='$price',`uid`='$uid',
                                `lid`='$lid',`type`='$type',`pid`='$pid' ");
    // $res = mysqli_fetch_array($ins); 
    
     if($ins) {
                echo "Success"; 
            } else {
                 echo 'Failed';
            }
}

if(isset($_POST['removeProperties'])) {
    extract($_POST);
    $ins = mysqli_query($con,"DELETE FROM `cart` WHERE `id`='$id' ");  
    
     if($ins) {
                echo "Success"; 
            } else {
                 echo 'Failed';
            }
}

// get all carts data    
if(isset($_POST['getAllcartsdata'])) {
    $uid=$_POST['uid'];
    $q = mysqli_query($con,"SELECT * FROM `cart` WHERE `uid`='$uid' "); 
    // $res = mysqli_fetch_array($q); 
    
    if(mysqli_num_rows($q)>0) {
        //  $jsonData1 = [];
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

if(isset($_POST['getTotalOfCartValue'])) {
    $uid = $_POST['uid']; 
    $q = $con->query("SELECT sum(net_price) FROM `cart` WHERE `status`='Pending' and `uid`='$uid' "); 
    $json[0]['amount'] =  $q->fetch_array()[0];
    
    echo json_encode($json) ;
}


if(isset($_POST['gettotallabname'])) {
    $uid = $_POST['uid']; 
    $q = $con->query("SELECT (title) FROM `cart` WHERE `status`='Pending' and `uid`='$uid' "); 
    $json[0]['ltitle'] =  $q->fetch_array()[0];
    
    echo json_encode($json) ;
}


if(isset($_POST['updateAdmin'])){
    
    date_default_timezone_set("Asia/Kolkata"); 
    $email = $_POST['email'];
    $password = $_POST['password'];
    $uid = $_POST['uid'];
    
    $upd = mysqli_query($con,"UPDATE `admins` SET `email`='$email',`password`='$password' WHERE `id`='$uid' "); 
    if($upd) {
        echo "Success";
    } else{
        echo "Failed"; 
    }
}


// get all services    
if(isset($_POST['getUsers'])) {
     
    $q = mysqli_query($con,"SELECT * FROM `users` order by `id` desc "); 
    // $res = mysqli_fetch_array($q); 
    
    if(mysqli_num_rows($q)>0) {
        //  $jsonData1 = [];
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

 

 


// get all services    
if(isset($_POST['getServicesAll'])) {
     
    $q = mysqli_query($con,"SELECT * FROM `services` order by `id` desc "); 
    // $res = mysqli_fetch_array($q); 
    
    if(mysqli_num_rows($q)>0) {
        //  $jsonData1 = [];
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

// get all categories    
if(isset($_POST['getAllCategories'])) {
     
    $q = mysqli_query($con,"SELECT * FROM `categories` order by `title` asc "); 
    // $res = mysqli_fetch_array($q); 
    
    if(mysqli_num_rows($q)>0) {
        //  $jsonData1 = [];
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

// get categorized services    
if(isset($_POST['getCatServicesAll'])) {
    $category = sanitize($con,$_POST['category']);
    $q = mysqli_query($con,"SELECT * FROM `services` WHERE `category`='$category' order by rand() "); 
    // $res = mysqli_fetch_array($q); 
    
    if(mysqli_num_rows($q)>0) {
        //  $jsonData1 = [];
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

// get all categories   
if(isset($_POST['getCategoriesAll'])) {
     
    $q = mysqli_query($con,"SELECT * FROM `categories` order by `id` desc "); 
    // $res = mysqli_fetch_array($q); 
    
    if(mysqli_num_rows($q)>0) {
        //  $jsonData1 = [];
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}
// get all categories   
if(isset($_POST['getCategoriesAllMain'])) {
     
    $q = mysqli_query($con,"SELECT * FROM `categories` order by title asc limit 30 "); 
    // $res = mysqli_fetch_array($q); 
    
    if(mysqli_num_rows($q)>0) {
        //  $jsonData1 = [];
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}



// get all categories    
if(isset($_POST['getAllCategoriesBySearch'])) {
     $search = $_POST['search']; 
     
    $q = mysqli_query($con,"SELECT * FROM `categories` WHERE `title` like '%$search%' or `title` like '%$search' or `title` like '$search%'  order by `title` asc "); 
    // $res = mysqli_fetch_array($q); 
    
    if(mysqli_num_rows($q)>0) {
        //  $jsonData1 = [];
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}



// get my bookings  
if(isset($_POST['getBanners'])) {
     
    $q = mysqli_query($con,"SELECT * FROM `banners` order by `id` desc "); 
    // $res = mysqli_fetch_array($q); 
    
    if(mysqli_num_rows($q)>0) {
        //  $jsonData1 = [];
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}
// delete services 
if(isset($_POST['deleteService'])) {
     $id = sanitize($con,$_POST['id']); 
    $q = mysqli_query($con,"DELETE FROM `services` WHERE `id`='$id' "); 
    // $res = mysqli_fetch_array($q); 
    if($q) {
        echo "Success";
    } else {
        echo "failed";
    }
    
}
// delete category  
if(isset($_POST['deleteCategory'])) {
     $id = sanitize($con,$_POST['id']); 
    $q = mysqli_query($con,"DELETE FROM `categories` WHERE `id`='$id' "); 
    // $res = mysqli_fetch_array($q); 
    if($q) {
        echo "Success";
    } else {
        echo "failed";
    }
    
}
// delete contacts  
if(isset($_POST['deleteContact'])) {
     $id = sanitize($con,$_POST['id']); 
    $q = mysqli_query($con,"DELETE FROM `contacts` WHERE `id`='$id' "); 
    // $res = mysqli_fetch_array($q); 
    if($q) {
        echo "Success";
    } else {
        echo "failed";
    }
    
}
// get my bookings  
if(isset($_POST['deleteBanner'])) {
     $id = sanitize($con,$_POST['id']); 
    $q = mysqli_query($con,"DELETE FROM `banners` WHERE `id`='$id' "); 
    // $res = mysqli_fetch_array($q); 
    if($q) {
        echo "Success";
    } else {
        echo "failed";
    }
    
}


// get my bookings  
if(isset($_POST['get_my_bookings'])) {
    $uid = sanitize($con,$_POST['uid']); 
    $q = mysqli_query($con,"SELECT * FROM `bookings` WHERE `uid`='$uid' order by `id` desc "); 
    // $res = mysqli_fetch_array($q); 
    
    if(mysqli_num_rows($q)>0) {
        //  $jsonData1 = [];
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}

// get pending bookings  
if(isset($_POST['get_pending_bookings'])) {
    // $uid = sanitize($con,$_POST['uid']); 
    $q = mysqli_query($con,"SELECT * FROM `bookings` WHERE `status`='Pending' order by `id` asc "); 
    // $res = mysqli_fetch_array($q); 
    
    if(mysqli_num_rows($q)>0) {
        //  $jsonData1 = [];
        while($re = mysqli_fetch_array($q)) {
            $pid= $re['sid'];
            $s = mysqli_query($con,"SELECT * FROM `services` WHERE `id`='$pid' ");
            $data = mysqli_fetch_array($s);
            $re['service'] = $data['title'];
            $re['thumbnail'] = $data['thumbnail'];
            $re['scategory'] = $data['category'];
            $re['scharge'] = $data['charge'];
            
            $jsonData1[] = $re; 
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}


// get my orders  
if(isset($_POST['check_orders'])) {
    $email = sanitize($con,$_POST['email']); 
    $q = mysqli_query($con,"SELECT * FROM `bookings` WHERE `owner_email`='$email' and `status`='Renting' order by `id` desc "); 
    // $res = mysqli_fetch_array($q); 
    
    if(mysqli_num_rows($q)>0) {
        echo "available";
    } else { 
        echo "Failed" ; 
    } 
}


// assign vendor  
if(isset($_POST['assignVendor'])) {
    
    $oid = sanitize($con,$_POST['oid']); 
    $vid = sanitize($con,$_POST['vid']); 
    $phone = sanitize($con,$_POST['phone']); 
    
    $q = mysqli_query($con,"UPDATE `bookings` SET `status`='Assigning', `wid`='$vid'  WHERE `id`='$oid' "); 
     
    sendSMS("$phone", "New order is waiting for your confirmation. Please confirm otherwise order will be cancelled. Roy Service");
    
    if($q) {
        echo "Success";
    } else { 
        echo "Failed" ; 
    } 
}


// get my bookings  
if(isset($_POST['get_my_tenants'])) {
    $uid = sanitize($con,$_POST['uid']); 
    $q = mysqli_query($con,"SELECT * FROM `bookings` WHERE `oid`='$uid' order by `id` desc "); 
    // $res = mysqli_fetch_array($q); 
    
    if(mysqli_num_rows($q)>0) {
        //  $jsonData1 = [];
        while($re = mysqli_fetch_array($q)) {
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}
    
 

if(isset($_POST['contact_us'])) {
    $uid = sanitize($con,$_POST['uid']); 
    $subject = sanitize($con,$_POST['subject']); 
    $message = sanitize($con,$_POST['message']); 
    $user = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `users` WHERE `id`='$uid' ")); 
    
    $name = $user['name']; 
    $email = $user['email']; 
    
    $con->query("INSERT INTO `contacts` SET `name`='$name',`email`='$email',`subject`='$subject',`description`='$message',`date`='".date('d-m-Y')."',`time`='".date('h:i:s A')."',`status`='Pending' ");
    
    if(mail("bsm1111419@gmail.com,vairrab@gmail.com","$subject - ".date('d-M-Y')."", $user['name']." sent a message for you as - ".$message . ". on ".date('d-M-Y'). ". Email id - ".$user['email']." & phone no is ".$user['phone'] ." . Thanks" )) {
       echo "Success";  
    } else {
        echo "Failed"; 
    }
    
}

// ------------------------------------------------- Admin -------------------------------------------------------
   if(isset($_POST['loginAdmin'])) {
        $password = sanitize($con,$_POST['password']); 
        $email = sanitize($con,$_POST['email']);   
        $ip = $_SERVER['REMOTE_ADDR']; 
        
            $q=  mysqli_query($con,"SELECT * FROM `admins` WHERE `email`='$email' and `password`='$password' "); 
             $res = mysqli_fetch_array($q); 
             
            if(mysqli_num_rows($q)>0) {
                 $upd = $con->query("UPDATE `admins` SET `ip`='$ip' WHERE `id`='".$res['id']."' "); 
            }
            
            $q=  mysqli_query($con,"SELECT * FROM `admins` WHERE `email`='$email' and `password`='$password' "); 
            $res = mysqli_fetch_array($q); 
            if($res!=null) {
               
                $jsonData1[] = $res;
                echo json_encode($jsonData1); 
            } else {
                echo "false";
            }
    }
    
if(isset($_POST['delete_contact'])) {
    $id = sanitize($con,$_POST['delete_contact']);  
    if($con->query("DELETE FROM `contacts` WHERE `id`='$id' ")) {
       echo "Success";  
    } else {
        echo "Failed"; 
    }
    
}
   
   if(isset($_POST['userCheck'])) {
          
        $uid = sanitize($con,$_POST['uid']);    
        $ip = sanitize($con,$_POST['ip']);    
        
            $q=  mysqli_query($con,"SELECT * FROM `admins` WHERE `id`='$uid' and `ip`='$ip' "); 
             $res = mysqli_fetch_array($q);  
            if($res!=null) {
               echo $res['ip']; 
            } else {
                echo "false";
            }
    }
    
    
    // ------------------------------------------------------------------------------
 
   
    // getting transactions 
if(isset($_POST['getTransactions'])) {
    $uid= $_POST['uid']; 
    $q = mysqli_query($con,"SELECT * FROM `transactions` WHERE `uid`='$uid' order by `id` desc "); 
      
    if(mysqli_num_rows($q)>0) {
        //  $jsonData1 = [];
        while($re = mysqli_fetch_array($q)) {
            $r = $con->query("SELECT * FROM `services` WHERE `id`='$uid' "); 
            $service = $r->fetch_array();
            $re['service'].= $service['title'];   
                $jsonData1[] = $re; 
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}
 ////////////////////////////////// workers /////////////////////////////
if(isset($_POST['getVendors'])) {
    $category =  sanitize($con,$_POST['category']);
    
    $q = mysqli_query($con,"SELECT * FROM `workers` WHERE `status`='Live' and `category`='$category' order by `id` desc "); 
      
    if(mysqli_num_rows($q)>0) {
        //  $jsonData1 = [];
        while($re = mysqli_fetch_array($q)) { 
                $jsonData1[] = $re; 
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "failed" ; 
    } 
}



// getting bookings
if(isset($_POST['getAssigningMyBookings'])) {
    $uid = sanitize($con,$_POST['uid']);  

    $q = mysqli_query($con,"SELECT * FROM `bookings` WHERE `status`='Assigning' and `wid`='$uid' order by `id` desc "); 
    // $res = mysqli_fetch_array($q); 
    
    if(mysqli_num_rows($q)>0) {
        //  $jsonData1 = [];
        while($re = mysqli_fetch_array($q)) {
            $pid = $re['sid']; 
            $s= mysqli_query($con,"SELECT * FROM `services` WHERE `id`='$pid' ");
            $ser = mysqli_fetch_array($s);
            $re['title'].= $ser['title'];
            $re['thumbnail'].= $ser['thumbnail'];
            
            $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}
 



 
    if(isset($_POST['workerDetails'])) {
        $uid = sanitize($con,$_POST['uid']);  
         
            $q=  mysqli_query($con,"SELECT * FROM `workers` WHERE `id`='$uid' "); 
            $res = mysqli_fetch_array($q); 
            if($res!=null) {
                $jsonData1[] = $res;
                echo json_encode($jsonData1); 
            } else {
                 echo 'Failed';
            }
    }
    if(isset($_POST['updateWorker'])) {
        $uid = sanitize($con,$_POST['uid']);  
        $name = sanitize($con,$_POST['name']);  
        $phone = sanitize($con,$_POST['phone']);  
        $address = sanitize($con,$_POST['address']);  
        $category = sanitize($con,$_POST['category']);  
         
            $q=  mysqli_query($con,"UPDATE `workers`SET `name`='$name',`phone`='$phone',`address`='$address',`category`='$category',`status`='Pending' WHERE `id`='$uid' "); 
            
            if($q) {
               echo "Success";
            } else {
                 echo 'Failed';
            }
    }


if(isset($_POST['getWorkerOrders'])) {
    $wid =  sanitize($con,$_POST['uid']);
    $status =  sanitize($con,$_POST['status']); 
    
    $q = mysqli_query($con,"SELECT * FROM `bookings` WHERE `status`='$status' and `wid`='$wid' order by `id` desc "); 
      
    if(mysqli_num_rows($q)>0) {
        //  $jsonData1 = [];
        while($re = mysqli_fetch_array($q)) { 
            $sid = $re['sid'];
            $n =mysqli_query($con,"SELECT * FROM `services` WHERE `id`='$sid' "); 
            $data = mysqli_fetch_array($n); 
            $re['service'] = $data['title'];
            
            $jsonData1[] = $re; 
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "failed" ; 
    } 
}




    if(isset($_POST['rejectOrderFromWorker'])) {
        $uid = sanitize($con,$_POST['uid']);  
        $oid = sanitize($con,$_POST['oid']);  
         
         $user = getWorkerInfo($con, $uid);
         $name = $user['name']; 
         $phone = $user['phone'];
         
         sendSMS("8670731523", "$name - has been rejected order. order id $oid .Contact $phone");
         
            $q=  mysqli_query($con,"UPDATE `bookings` SET `status`='Pending',`wid`='' WHERE `id`='$oid' "); 
            if($q) {
               echo "Success";
            } else {
                 echo 'Failed';
            }
    }
   
    if(isset($_POST['acceptOrderWorker'])) {
        $uid = sanitize($con,$_POST['uid']);  
        $oid = sanitize($con,$_POST['oid']);  
         
         $user = getWorkerInfo($con, $uid);
         $name = $user['name']; 
         $phone = $user['phone'];
         
         sendSMS("8670731523", "$name - has been accepted order. order id $oid .Contact $phone");
         
            $q=  mysqli_query($con,"UPDATE `bookings` SET `status`='Assigned',`wid`='$uid',`worker`='$name',`worker_phone`='$phone' WHERE `id`='$oid' "); 
            if($q) {
               echo "Success";
            } else {
                 echo 'Failed';
            }
    }

if(isset($_POST['getLiveWorkers'])) {
      
    $q = mysqli_query($con,"SELECT * FROM `workers` WHERE `status`='Live' order by `id` desc "); 
      
    if(mysqli_num_rows($q)>0) {
        //  $jsonData1 = [];
        while($re = mysqli_fetch_array($q)) { 
                $jsonData1[] = $re; 
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "failed" ; 
    } 
}
if(isset($_POST['getLiveUsers'])) {
      
    $q = mysqli_query($con,"SELECT * FROM `users` WHERE `status`='Live' order by `id` desc "); 
      
    if(mysqli_num_rows($q)>0) {
        //  $jsonData1 = [];
        while($re = mysqli_fetch_array($q)) { 
                $jsonData1[] = $re; 
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "failed" ; 
    } 
}




if(isset($_POST['getPendingWorkers'])) {
      
    $q = mysqli_query($con,"SELECT * FROM `workers` WHERE `status`!='Live' and `status`!='Deleted' order by `id` desc "); 
      
    if(mysqli_num_rows($q)>0) {
        //  $jsonData1 = [];
        while($re = mysqli_fetch_array($q)) { 
                $jsonData1[] = $re; 
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "failed" ; 
    } 
}
if(isset($_POST['getPendingUsers'])) {
      
    $q = mysqli_query($con,"SELECT * FROM `users` WHERE `status`!='Live' order by `id` desc "); 
      
    if(mysqli_num_rows($q)>0) {
        //  $jsonData1 = [];
        while($re = mysqli_fetch_array($q)) { 
                $jsonData1[] = $re; 
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "failed" ; 
    } 
}

if(isset($_POST['changeWorkerStatus'])) {
      $uid = sanitize($con,$_POST['uid']);  
      $status = sanitize($con,$_POST['changeWorkerStatus']);  
      
    $q = mysqli_query($con,"UPDATE `workers` SET `status`='$status' WHERE `id`='$uid' "); 
      
    if($q) { 
        echo "Success";
    } else { 
        echo "Failed" ; 
    } 
}



if(isset($_POST['changeUserStatus'])) {
      $uid = sanitize($con,$_POST['uid']);  
      $status = sanitize($con,$_POST['changeUserStatus']);  
      
    $q = mysqli_query($con,"UPDATE `users` SET `status`='$status' WHERE `id`='$uid' "); 
      
    if($q) { 
        echo "Success";
    } else { 
        echo "Failed" ; 
    } 
}


////////////////////////////////////////////////////////////////////////////////////////////////
 
    // getting contacts
if(isset($_POST['getContacts'])) {
    $q = mysqli_query($con,"SELECT * FROM `contacts` WHERE `status`!='Deleted' order by `id` desc "); 
      
    if(mysqli_num_rows($q)>0) {
        //  $jsonData1 = [];
        while($re = mysqli_fetch_array($q)) { 
                $jsonData1[] = $re;
        }
        echo json_encode($jsonData1); 
    } else { 
        echo "Failed" ; 
    } 
}
 
 
 

if(isset($_POST['getCount'])) {
    $p = $con->query("SELECT COUNT(id) FROM `services` WHERE `status`='Live' "); 
    $services = $p->fetch_array()[0];
    
    
    $l = $con->query("SELECT COUNT(id) FROM `bookings` WHERE `status`='Done' "); 
    $orders = $l->fetch_array()[0];
    
    $ppp = $con->query("SELECT COUNT(id) FROM `bookings` WHERE `status`='Pending' "); 
    $pending = $ppp->fetch_array()[0];
    
    
    $r = $con->query("SELECT COUNT(id) FROM `users` "); 
    $users = $r->fetch_array()[0];
    
    
    $rx = $con->query("SELECT COUNT(id) FROM `workers` "); 
    $workers = $rx->fetch_array()[0];
    
    
    // $u = $con->query("SELECT COUNT(id) FROM `users` WHERE `status`='Live' "); 
    // $users= $u->fetch_array()[0];
    
    
    // $co = mysqli_query($con,"SELECT DISTINCT(id) FROM `contacts` WHERE `status`='Pending' "); 
    // $contacts = mysqli_num_rows($co);
    $data = Array();
    $data['services'].=$services;
    $data['pending'].=$pending;
    $data['orders'].=$orders;
    $data['users'].=$users;
    $data['workers'].=$workers;
    
    echo json_encode($data);
} 
  
  
  

if(isset($_POST['MY_DOCTOR_REGISTER'])) {
     
        extract($_REQUEST);   
        
        //  echo "UPDATE  `users` SET `license_no`='$license', `experience`='$experience', `name`='$name', `specilist`='$specialist', `email`='$email', `about`='$about', `address`='$address',`doctor_status`='DocPending'  WHERE `phone`='$phone' and `id`='$uid' "; 
         
            $q=  mysqli_query($con,"UPDATE  `users` SET `license_no`='$license', `experience`='$experience', `name`='$name', `specilist`='$specialist', `email`='$email', `about`='$about', `address`='$address',`doctor_status`='DocPending'  WHERE `phone`='$phone' and `id`='$uid' ");  
            
            if($con->affected_rows>0) {
                
                sendWA($phone, str_replace(" ", "%20","Welcome to Health Align! Dear $name, your doctor account has been created on Health Align. Please complete document varification process. %0a%0aTo login your account please visit on %0aApp https://play.google.com/store/apps/details?id=com.udoctors.user .%0a---------------- ----------------%0aWebsite: https://healthalign.in/ %0aThanks%0a"));
             $q = $con->query("SELECT * FROM `users` WHERE `id`='$uid' "); 
             $json[0] = $q->fetch_assoc(); 
             
                die(json_encode($json));
            }  
            die("failed");
}


if(isset($_REQUEST['GET_DOCTOR_PATIENT_STATUS'])) {
    
//     ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
    
    $user = DevSecIt\GET_DATA("users", "id='$uid' ");
    $name = $user['name'];
    $phone = $user['phone'];
    
    $dateWithLeadingZeroes = $date;
    $parts = explode('-', $dateWithLeadingZeroes);
    $dateWithoutLeadingZeroes = ltrim($parts[0], '0') . '-' . ltrim($parts[1], '0') . '-' . $parts[2]; 
    $date = $dateWithoutLeadingZeroes;  
    
     
    $al = $con->query("SELECT COUNT(id) FROM `appointments` WHERE `did`='$doc_id' and `oid`='$h_id' and `booking_date`='$date' ");
    $all = $al->fetch_array()[0]; 
    
    
    $pen = $con->query("SELECT COUNT(id) FROM `appointments` WHERE  `booking_date`='$date' and `did`='$doc_id' and `oid`='$h_id' and `status`='Pending' ");
    $pending = $pen->fetch_array()[0]; 
    
    $vis = $con->query("SELECT COUNT(id) FROM `appointments` WHERE  `did`='$doc_id' and `oid`='$h_id' and `status`='Visited' and `booking_date`='$date' ");
    $visited = $vis->fetch_array()[0]; 
    
    $lastvis = $con->query("SELECT slot_number FROM `appointments` WHERE   `did`='$doc_id' and `oid`='$h_id' and `status`='Visited' and `booking_date`='$date' ");
    $last_visited = $lastvis->fetch_array()[0]; 
    
    $difference = $my_slot - $last_visited;
    
    if($difference>3 && $difference< 3 && $difference>0 ) {
        sendWA($phone, str_replace(" ", "%20","Dear $name, your doctor booking slot is near about last visted! Please stay alert.%0a%0aTo login your account please visit on %0aApp https://play.google.com/store/apps/details?id=com.udoctors.user .%0a---------------- ----------------%0aWebsite: https://healthalign.in/ %0aThanks%0a"));
    }
     
    $json[0]['doc_id'] = $doc_id;
    $json[0]['h_id'] = $h_id;
    $json[0]['all'] = $all;
    $json[0]['pending'] = $pending;
    $json[0]['visited'] = $visited;
    $json[0]['last_visited'] = $last_visited;
    $json[0]['difference'] = $difference;
    $json[0]['date'] = $date;
    
    die(json_encode($json)); 
    
}
 

if(isset($_POST['UPDATE_PROFILE'])) {
    $resp = DevSecIt\UPDATE_DB("users", "UPDATE_PROFILE", " id='$uid' ");
    $rs = DevSecIt\GET_DATA_WHILE("users", " id='$uid' "); 
    die(json_encode($rs)); 
}


if(isset($_POST['UPLOAD_PHOTO'])) {
    $img= time()."-photo.jpg";
    chdir("../public/img/profile/");
    file_put_contents("$img", base64_decode($_POST['thumbnail'])); 
    
    $upd = $con->query("UPDATE `users` SET `profile_img`='profile/$img' WHERE `id`='$uid' "); 
    if($con->affected_rows>0) {
        die("success");
    } else {
        die("failed");
    }
}

if(isset($_POST['UPLOAD_LICENSE'])) {
    $img= time()."-license.jpg";
    chdir("../../public/img/");
     
    file_put_contents("$img", base64_decode($_POST['thumbnail'])); 
    
    $upd = $con->query("UPDATE `users` SET `license_document`='$img' WHERE `id`='$uid' "); 
    if($con->affected_rows>0) {
        die("success");
    } else {
        die("failed");
    }
}

if(isset($_POST['UPLOAD_KYC'])) {
    $img= time()."-kyc.jpg";
    
    chdir("../../public/img/");
     
    file_put_contents("$img", base64_decode($_POST['thumbnail'])); 
    
    $upd = $con->query("UPDATE `users` SET `kyc_document`='$img', `kyc_type`='$kyc_type' WHERE `id`='$uid' "); 
    if($con->affected_rows>0) {
        die("success");
    } else {
        die("failed");
    }
}

if(isset($_POST['UPLOAD_EDUCATIION'])) {
    $img= time()."-kyc.jpg";
    
     chdir("../../public/img/");
    file_put_contents("$img", base64_decode($_POST['thumbnail'])); 
    
    $upd = $con->query("INSERT INTO `qualifications` SET `thumbnail`='$img', `title`='$qualification', `uid`='$uid', `date`='$date', `status`='Pending', `area`='$area' "); 
    if($con->affected_rows>0) {
        die("success");
    } else {
        die("failed");
    }
}
if(isset($_POST['UPDATE_DOCTOR_DOCUMENTS'])) { 
   $upd = $con->query("UPDATE `users` SET `doctor_status`='Waiting'  WHERE `id`='$uid' "); 
    if($con->affected_rows>0) {
        
        //  sendWA($phone, str_replace(" ", "%20","Welcome to Health Align! Dear $name, your doctor account is pending on Health Align. Please wait until we complete varification process. %0a%0aTo login your account please visit on %0aApp https://play.google.com/store/apps/details?id=com.udoctors.user .%0a---------------- ----------------%0aWebsite: https://healthalign.in/ %0aThanks%0a")); 
        die("success");
    } else {
        die("failed");
    }
}
 


if(isset($_POST['GET_ALL_CHAMBERS'])) { 
    $h = DevSecIt\GET_DATA_WHILE("hospitals", " status='Active' ");
     
    die(json_encode($h));
}

if(isset($_POST['GET_CHAMBER_DOC_DETAILS'])) { 
    $h = DevSecIt\GET_DATA_WHILE("hospital_doctors", " h_id='$hid' and `d_id`='$did'  ");
     
    die(json_encode($h));
}

if(isset($_POST['getNotificationForPatient'])) { 
    $h = DevSecIt\GET_DATA_WHILE("notification", " `type`='Patient' and `uid`='$uid' order by id desc limit 1 ");
     
    die(json_encode($h));
}



if(isset($_POST['getNotificationForDoctor'])) { 
    $h = DevSecIt\GET_DATA_WHILE("notification", " `type`='Doctor' and `uid`='$did' order by id desc limit 1 ");
     
    die(json_encode($h));
}


if(isset($_POST['GET_ALL_CHAMBERS_DOC'])) { 
    
    $h = DevSecIt\GET_DATA_WHILE("chambers", " status='Active' and `did`='$did' ");
    $json=[];
    foreach($h as $dt) {
        $hid = $dt['oid']; 
        $hosp= DevSecIt\GET_DATA("hospitals", " status='Active' and `id`='$hid' ");
        $json[]=$hosp;
    }
    die(json_encode($json));
}



if(isset($_POST['INSERT_CHAMBER_TXN'])) { 
    $us = DevSecIt\GET_DATA("doctors", " status='Active' and `id`='$did' ");
    $ch= DevSecIt\GET_DATA("hospitals", " status='Active' and `name`='$chamber' ");
    
    $ext=[
            "name" => $us['name'],
            "phone" => $us['phone'],
            "email" => $us['email'],
            "thumbnail" => $us['thumbnail'],
            "address" => $us['address'],
            "speciality" => $us['speciality'],
            "about" => $us['about'],
            "h_id" => $ch['id'],
            "patient_limit" => $patient_limit,
            "d_id" => $did, 
            "amount" => $amount,
            "start_time" => $from,
            "ending_time" => $to,
            "sun" => $sun,
            "mon" => $mon,
            "tue" => $tue,
            "wed" => $wed,
            "thu" => $thu,
            "fri" => $fri,
            "sat" => $sat,
            "date" => $date,
            "status" => "Active",
            
        ];
    $ins = DevSecIt\INSERT_DB_ARRAY("hospital_doctors", $ext);
    if($con->affected_rows>0) {
        die("success");
    } else {
        die("failed");
    }
    
}



if(isset($_POST['UPDATE_CHAMBER_TXN'])) { 
    $us = DevSecIt\GET_DATA("doctors", " status='Active' and `id`='$did' ");
    
    $ext=[ 
            "amount" => $amount,
            "start_time" => $from,
            "ending_time" => $to,
            "patient_limit" => $patient_limit,
            "sun" => $sun,
            "mon" => $mon,
            "tue" => $tue,
            "wed" => $wed,
            "thu" => $thu,
            "fri" => $fri,
            "sat" => $sat,
            
        ]; 
    $ins = DevSecIt\UPDATE_DB_ARRAY("hospital_doctors", $ext, " `h_id`='$hid' and `id`='$id' and `d_id`='$did' ");
    if($con->affected_rows>0) { 
        die("success");
    } else {
        die("failed");
    }
    
}
    
if(isset($_REQUEST['GET_ALL_BOOKINGS_DOC'])) { 
    
    // $date = substr($date,0,1)=="0" ? substr($date,-9) : $date; 
    // $date = substr($date,0,3)=="0" ? substr($date,-9) : $date; 
    
    $dateWithLeadingZeroes = $date;
    $parts = explode('-', $dateWithLeadingZeroes);
    $dateWithoutLeadingZeroes = ltrim($parts[0], '0') . '-' . ltrim($parts[1], '0') . '-' . $parts[2]; 
    $date = $dateWithoutLeadingZeroes;  
    $data = DevSecIt\GET_DATA_WHILE("appointments", " did='$did' and `oid`='$hid' and `booking_date`='$date' and `status`='Pending' ");
    
    die(json_encode($data));
}    

    
if(isset($_POST['GET_ALL_BOOKINGS_DOC_BY_DATE'])) {
    
    $date = substr($start_date,0,1) == "0" ? substr($start_date,-9): $start_date;
     
    $data = DevSecIt\GET_DATA_WHILE("hospital_doctor_booking", " doc_id='$did' and `h_id`='$hid' and `booking_date`='$date' ");
     
    
    die(json_encode($data));
}    

if(isset($_POST['GET_ALL_BOOKINGS_COUNTS_BY_DATE'])) {
    
     $date = substr($start_date,0,1) == "0" ? substr($start_date,-9): $start_date;
     
    $datax = DevSecIt\GET_DATA_WHILE("hospital_doctor_booking", " doc_id='$did' and `h_id`='$hid' and `booking_date`='$date' ");
     
    $pd = $con->query("SELECT COUNT(id) FROM `hospital_doctor_booking` WHERE doc_id='$did' and `h_id`='$hid' and `booking_date`='$date' and `status`='Pending'  ");
    // $vs = $con->query("SELECT COUNT(id) FROM `hospital_doctor_booking` WHERE doc_id='$did' and `h_id`='$hid' and `booking_date`='$date' and `status`!='Pending'  ");
    
   $data[0]['patients'] = count($datax);
   $data[0]['visited'] = count($datax) - $pd->fetch_array()[0];
   $data[0]['date'] = $date;
    die(json_encode($data));
}    



if(isset($_POST['UPDATE_VISIT_RECORD'])) {
    $upd = $con->query("UPDATE `hospital_doctor_booking` SET `status`='$status' WHERE `id`='$pid' and `doc_id`='$did' "); 
    if($upd->affected_rows>0) {
        die("success");
    } else {
        die("failed");
    }
}
 

if(isset($_POST['UPDATE_USER_LOCATION'])) {
    $last_seen= time(); 
    $upd = $con->query("UPDATE `users` SET `lat`='$lat', `lon`='$lon', `last_seen`='$last_seen' WHERE `id`='$uid' "); 
    if($upd->affected_rows>0) {
        die("success");
    } else {
        die("failed");
    }
}
 

if(isset($_POST['checkout'])) {
    $uid = $_POST['uid']; 
    
    $oid = time().$uid;
    $thumb = time().rand(1111,999999).".jpeg";
    
    $prescription = file_put_contents("images/$thumb", base64_decode($_POST['prescription']));
    
    // check cart 
    $ct = $con->query("SELECT * FROM `cart` WHERE `uid`='$uid' order by id asc "); 
    while($data = $ct->fetch_array()) {
        // get user 
        $u = $con->query("SELECT  * FROM `users` WHERE `id`='$uid' "); 
        $user = $u->fetch_array(); 
    //     $l = $con->query("SELECT * FROM `laboratory` WHERE `id`='$lid' ");
    // $lab = $l->fetch_array();
    
    
    $laboratory = $data['labname'];
        $name = $user['name']; 
        $email = $user['email']; 
        $phone = $user['phone']; 
        $title = $data['title'];
        $lid = $data['lid'];
        $thumbnail = $data['thumbnail'];
        $pid= $data['pid']; 
        $type = $data['type']; 
        $address =$user['address']; 
        $net = $data['net_price'];
        // insert into orders 
        $ins = $con->query("INSERT INTO `orders` SET `oid`='$oid',`uid`='$uid',`lid`='$lid',`name`='$name',`phone`='$phone',`email`='$email',`title`='$title',`hospital_name`='$laboratory',
                            `pid`='$pid',`type`='$type',`u_address`='$address',`status`='pending',`date`='$date',`time`='$time',`total`='$net',`net_amount`='$net',`prescription`='$thumb',`thumbnail`='$thumbnail',`slno_booking`='pending'  ");
        if($ins) {
            $cid = $data['id'];
            
          $subject = "Confirm Laboratory Booking - UDOCTORS ";
        
        $message = "
        <html>
        <head>
        <title>Confirm Laboratory Booking - UDOCTORS </title>
        </head>
        <body>
        <p>Dear $name , your Laboratory booking has been confirmed </p>
        <h2> $oid </h2>
         
         <br><br> 
         <b> Regards  <br> UDOCTORS </b>
         
        </body>
        </html>
        ";
        
        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        
        // More headers
        $headers .= 'From: UDOCTORS <support@udoctors.in>' . "\r\n";
        $headers .= 'Cc: support@udoctors.in' . "\r\n";
         mail($email,$subject,$message,$headers);
            $con->query("DELETE FROM `cart` WHERE `id`='$cid' and `uid`='$uid' ");
        }
    }
    
    $json[0]['status']="Success";
    echo json_encode($json);
}


if(isset($_POST['Prep_upload'])) {
    $uid = $_POST['uid']; 
     $oid = time().$uid;
    $thumb = time().rand(1111,999999).".jpeg";
    
    $prescription = file_put_contents("images/$thumb", base64_decode($_POST['prescription']));
    // get user 
        $u = $con->query("SELECT  * FROM `users` WHERE `id`='$uid' "); 
        $user = $u->fetch_array();
        $name = $user['name']; 
        $email = $user['email']; 
        $phone = $user['phone']; 
        $address =$user['address']; 
        // insert into orders 
        $ins = $con->query("INSERT INTO `direct_prescription` SET `uid`='$uid',`name`='$name',`phone`='$phone',`email`='$email',`u_address`='$address',`status`='pending',`date`='$date',`time`='$time',`prescription`='$thumb'  ");
        if($ins) {
            $cid = $data['id'];
            
             $subject = "Confirm Laboratory Booking - UDOCTORS ";
        
        $message = "
        <html>
        <head>
        <title>Confirm Laboratory Booking Prescription Upload  - UDOCTORS </title>
        </head>
        <body>
        <p>Dear $name , your Laboratory booking Prescription Upload has been confirmed </p>
        <h2> $oid </h2>
         
         <br><br> 
         <b> Regards  <br> UDOCTORS </b>
         
        </body>
        </html>
        ";
        
        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        
        // More headers
        $headers .= 'From: UDOCTORS <support@udoctors.in>' . "\r\n";
        $headers .= 'Cc: support@udoctors.in' . "\r\n";
         mail($email,$subject,$message,$headers);
            
            $con->query("DELETE FROM `cart` WHERE `id`='$cid' and `uid`='$uid' ");
        }
    
    $json[0]['status']="Success";
    echo json_encode($json);
} 
 
if(isset($_POST['GET_NOTIFICATIONS'])) {
    $notifications = DevSecIt\GET_DATA_WHILE("notification", " 1 order by id desc limit 50" );
    die(json_encode($notifications));
}



if(isset($_POST['reset_password'])) { 
    
    $phone = sanitize($con,$_POST['phone']);   
    $ip = $_SERVER['REMOTE_ADDR']; 
    
    $q=  mysqli_query($con,"SELECT * FROM `users` WHERE `phone`='$phone' "); 
    $res = mysqli_fetch_array($q); 
    
     if(mysqli_num_rows($q)>0) {
        
        $otp = rand(1111,999999); 
         
        $upd = $con->query("UPDATE `users` SET `ip`='$ip',`password`='$otp' WHERE `phone`='$phone' "); 
        $name = $res['name'];
        
         sendWA($phone, str_replace(" ", "%20","Dear $name, your new password is *$otp*! Please don't share with anyone. %0a%0aTo login your account please visit on %0aApp https://play.google.com/store/apps/details?id=com.udoctors.user .%0a---------------- ----------------%0aWebsite: https://healthalign.in/ .%0aThanks%0a"));
         
         die("Success");
    }else {
       echo "false";
       exit;
    }
       
    }
    
    
?>