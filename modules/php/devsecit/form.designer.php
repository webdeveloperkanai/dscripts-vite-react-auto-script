<?php

namespace DevSecIt\FORM;


if (!function_exists("DSI_INSERT_FORM")) {

    function DSI_INSERT_FORM($page, $data, $required)
    {
        global $con, $date, $time;
        $tab = strtolower($page);

        $form = '';

        $form .= '<section class="datatables">
            <div class="row">
                <div class="col-12">
    
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                            <br><br>
                                <a href="' . strtolower($page) . '" class="float-right btn-sm btn-outline-primary">Back </a>
                                <br><br> 
                            </div> 
                            <form action="" method="POST" enctype="multipart/form-data" class="col-md-12 row">
                            ';

        foreach ($data as $dt) {
            $dtUp = ucfirst($dt);
            if (in_array($dt, $required)) {
                $form .= '<div class="col-md-4"> <p> Enter ' . $dtUp . ' </p> <input type="text" name="' . $dt . '" id="' . $dt . '"  required class="form-control" /> </div> ';
            } else {
                $form .= '<div class="col-md-4"> <p> Enter ' . $dtUp . ' </p> <input type="text" name="' . $dt . '" id="' . $dt . '"   class="form-control" /> </div> ';
            }
        }
        $form .= '<div class="col-md-4"> <p> &nbsp; </p> <input type="submit" name="ADD_NEW_DATA" id="ADD_NEW_DATA" class="btn-sm btn-primary" /> </div> ';

        $form .= "</form>  </div></div></section>";

        return $form;
    }
}

if (!function_exists("DSI_UPDATE_FORM")) {

    function DSI_UPDATE_FORM($page, $data, $required)
    {
        global $con, $date, $time, $eid;
        $tab = strtolower($page);
        $form = '';
        $d = \DevSecIt\GET_DATA($tab, "id='$eid'");
        if ($d != null) {


            $form .= '<section class="datatables">
            <div class="row">
                <div class="col-12">
    
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-2">
                            <br><br>
                                <a href="' . strtolower($page) . '" class="float-right btn-sm btn-outline-primary">Back </a>
                                <br><br> 
                            </div> 
                            <form action="" method="POST" enctype="multipart/form-data" class="col-md-12 row">
                            ';

            foreach ($data as $dt) {
                $dtUp = ucfirst($dt);
                if (in_array($dt, $required)) {
                    $form .= '<div class="col-md-4"> <p> Enter ' . $dtUp . ' </p> <input type="text" name="' . $dt . '" id="' . $dt . '" value="' . $d[$dt] . '" required class="form-control" /> </div> ';
                } else {
                    $form .= '<div class="col-md-4"> <p> Enter ' . $dtUp . ' </p> <input type="text" name="' . $dt . '" id="' . $dt . '"  value="' . $d[$dt] . '"  class="form-control" /> </div> ';
                }
            }
            $form .= '<div class="col-md-4"> <p> &nbsp; </p> 
            <input type="hidden" name="eid"  value="' . $d["id"] . '"  /> 
            <input type="submit" name="UPDATE_DSI_DATA" id="UPDATE_DSI_DATA" class="btn-sm btn-primary" value="UPDATE DATA" /> </div> ';

            $form .= "</form>  </div></div></section>";
        }
        return $form;
    }
}

