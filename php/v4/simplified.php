<?php


if(isset($_REQUEST['GET_HOME_PAGE'])) {
    $region = $area; 
    
    
    $spp = [];
    $chmb = []; 
    $splst = []; 
    $fchambers = []; 
    
    // $chambers = DevSecIt\GET_DATA_WHILE("chambers", " `region`='$region' order by rand() limit 100");
    $chambers = DevSecIt\GET_DATA_WHILE_SELECTED("chambers", "id, oid, did, oName, oType, sunday, monday, tuesday, wednesday, thursday, friday, saturday, did, specialist, visiting_charge, doctorName, quota", " `status`='Active' order by rand() limit 100");
    for($x=0; $x < count($chambers); $x++) {
        
        $did = $chambers[$x]['did'];
        // $doc = DevSecIt\GET_DATA_SELECTED("doctors", " ",  " `id`='$did' ");
        
        $dc = $con->query("SELECT a.id, a.name, a.phone, a.email, a.thumbnail, a.address, a.about, a.degree, a.rating, a.region, a.experience, a.specialization as speciality, b.specialist FROM `doctors` a JOIN chambers b ON a.id=b.did  WHERE a.id=$did ");
        $doc=$dc->fetch_assoc(); 
        $chambers[$x]["doctor"]= json_encode($doc); 
        
        $spl = $chambers[$x]['specialist'];
        if( !in_array($spl, $spp) ) {
            $spp[]=$spl;
            $sdoc = DevSecIt\GET_DATA_SELECTED("specialist", "id, name, thumbnail",  "  `name` LIKE '$spl%' order by rand() ");
            // $chambers[$x]["specialists"]= json_encode($sdoc); 
            $splst[]=$sdoc;
        }
        
        
        
        if($chambers[$x]['oType']=="Hospital") {
            $hid = $chambers[$x]['oid'];
            if(!in_array($hid, $chmb) ) {
                
                $hoc = DevSecIt\GET_DATA_SELECTED("hospitals", " id, name, phone, address, gallery, thumbnail, map, city, district, state ",  " `id`='$hid' "); 
                $fchambers[]= $hoc; 
                $chmb[]=$hid; 
            }
            
        }
    }
    
    $sld= DevSecIt\GET_DATA_WHILE("sliders", " 1 order by id desc "); 
    $chambers[0]['sliders'] = json_encode($sld); 
    $chambers[0]['specialists'] = json_encode($splst); 
    $chambers[0]['hospital'] = json_encode($fchambers); 
    die(json_encode($chambers)); 
}


if(isset($_REQUEST["GET_CHAMBERS_BY_DID"])) { 
    $fch = []; 
    $chambers = DevSecIt\GET_DATA_WHILE_SELECTED("chambers", " id, oid, oName, oType, sunday, monday, tuesday, wednesday,thursday, friday, saturday, specialist, visiting_charge, quota  ", " did='$did' order by rand() "); 
    
    for($x=0; $x< count($chambers); $x++ ) {
        $oid = $chambers[$x]['oid'];
        $chm= DevSecit\GET_DATA_SELECTED("hospitals", "id, name,phone,email,thumbnail, address, map, rating, city, district,state,pincode", " id='$oid' order by rand() " ); 
        $fch[]=$chm; 
    }
    if(count($fch)>0) {
        die(json_encode($fch));
    } else {
        die("failed"); 
    }
}

if(isset($_REQUEST["GET_SINGLE_CHAMBER_BY_OID_DID"])) {
    $fch = []; 
    $chambers = DevSecIt\GET_DATA_WHILE_SELECTED("chambers", " id, oid, oName, oType, sunday, monday, tuesday, wednesday,thursday, friday, saturday, specialist, visiting_charge, quota  ", " did='$did' and `oid`='$oid' order by rand() "); 
    
    
    die(json_encode($chambers)); 
}


if(isset($_REQUEST["GET_LOCAL_CHAMBERS"])) {
    $fch = []; 
    $chambers = DevSecIt\GET_DATA_WHILE_SELECTED("chambers", " DISTINCT(oName) ", " 1 order by rand() "); 
    
    for($x=0; $x< count($chambers); $x++ ) {
        $oName = $chambers[$x]['oName'];
        $chm= DevSecit\GET_DATA_SELECTED("hospitals", "id, name,phone,email,thumbnail, address, map, rating, city, district,state,pincode", " name='$oName' order by rand() " ); 
        $fch[]=$chm; 
    }
    
    die(json_encode($fch)); 
}



if(isset($_REQUEST["GET_DOCTOR_BY_CHAMBER"])) {
    $fch = []; 
    $chambers = [];
    
    $q = $con->query("SELECT a.*, b.thumbnail, b.about, b.name, b.speciality, b.rating , b.experience, b.hospital, c.address, c.city, c.state, c.map  FROM `chambers` a join doctors b on a.did=b.id  JOIN hospitals c on a.oid=c.id WHERE a.oid=$oid ");
    while($data = $q->fetch_assoc()) {
        $chambers[]=$data; 
    } 
    die(json_encode($chambers)); 
}


if(isset($_REQUEST["GET_TRANSACTIONS_BY_UID"])) {
    $fch = []; 
    $chambers = [];
    
    $q = $con->query("SELECT * FROM `transactions` WHERE `uid`='$uid'  and `for`='PATIENT' order by id desc");
    while($data = $q->fetch_assoc()) {
        $chambers[]=$data; 
    } 
    die(json_encode($chambers)); 
}


if(isset($_REQUEST["ADD_TO_CART"])) {
    DevSecIt\INSERT_DB("carts", "ADD_TO_CART", []) ; 
    
    if($con->affected_rows>0) {
        die("success");
    } else {
        die("failed"); 
    }
}

// payment 

if(isset($_REQUEST["init_payment"])) {
    echo "<pre>"; 
    $q = DevSecIt\GET_DATA("carts", " `orderId`='$order_id' ");
    // print_r($q);
    
    $charge = $q['charge']; 
    $name = $q['name'];
    $phone = $q['phone'];
    $address = $q['addressMain'];
    
    die("<center> <h1>Don't reload page!<br>We are processing</h1></center><script>location.href='https://devsecit.com/pay/?name=$name&phone=$phone&address=$address&amount=$charge'</script>");
}



