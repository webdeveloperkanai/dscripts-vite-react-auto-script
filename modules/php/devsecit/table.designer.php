<?php

namespace DevSecIt\TABLES;

if (!function_exists('DSI_TABLE_GEN')) {
    function DSI_TABLE_GEN($page, $data, $edit, $delete)
    {
        global $con, $date, $time;
        $tab = strtolower($page);

        $table =
            '
        <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

        <section class="datatables">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-body">
                        <div class="mb-2">
                        <br><br>
                            <a href="' .
            strtolower($page) .
            '.add" class="float-right btn-sm btn-outline-primary">Add New </a>
                            <br><br> 
                        </div>
                        <p class="card-subtitle mb-3">

                        </p>
                        <div class="table-responsive">
                            <table id="myTable" class="display nowrap"> 
                            <thead> <tr> <th>SL </th>';
        foreach ($data as $dt) {
            $dt = ucfirst($dt);
            $table .= " <th>$dt </th> ";
        }

        if ($edit == 'on' || $delete == 'on') {
            $table .= '<th>Action</th>';
        }
        $table .= '</tr> </thead> <tbody>';
        $q = $con->query("SELECT * FROM `$tab` WHERE `status`='Active' ");
        if ($q->num_rows > 0) {
            $sl = 1;
            while ($mdt = $q->fetch_array()) {
                $id = $mdt['id'];

                $table .= '<tr> <td>' . $sl++ . '</td>';
                foreach ($data as $dt) {
                    $table .= '  <td> ' . $mdt[$dt] . '</td>';
                }
                if ($edit == 'on' || $delete == 'on') {
                    $table .= '<td>';
                    if ($edit == 'on') {
                        $table .= " <a href='$tab.edit?eid=$id' class='btn-sm btn-primary'> Edit </a>   ";
                    }
                    if ($delete == 'on') {
                        $table .= "<a href='?did=$id' class='btn-sm btn-danger' onclick='return confirm(`This record will be trashed!`)'> Delete </a>";
                    }
                    $table .= ' </td>';
                }
                $table .= '</tr> ';
            }
        }

        $table .= " </tbody> </table> </div>
        </div>
    </div>

</div>
</div>
</section>
<script>
    new DataTable('#myTable', {
        responsive: {
            details: {
                display: DataTable.Responsive.display.modal({
                    header: function(row) {
                        var data = row.data();
                        return 'Details for ' + data[0] + ' ' + data[1];
                    }
                }),
                renderer: DataTable.Responsive.renderer.tableAll()
            }
        }
    });
</script>

</div>";
        return $table;
    }
}

if (!function_exists('DSI_TABLE_TRASH')) {
    function DSI_TABLE_TRASH($page, $data, $edit, $delete)
    {
        global $con, $date, $time;
        $tab = strtolower($page);

        $table =
            '
        <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

        <section class="datatables">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-body">
                        <div class="mb-2">
                        <br><br>
                            <a href="' .
            strtolower($page) .
            '" class="float-right btn-sn btn-outline-primary"> Back </a>
                            <br><br> 
                        </div>
                        <p class="card-subtitle mb-3">

                        </p>
                        <div class="table-responsive">
                            <table id="myTable" class="display nowrap"> 
                            <thead> <tr> <th>SL </th>';
        foreach ($data as $dt) {
            $dt = ucfirst($dt);
            $table .= " <th>$dt </th> ";
        }

        if ($edit == 'on' || $delete == 'on') {
            $table .= '<th>Action</th>';
        }
        $table .= '</tr> </thead> <tbody>';
        $q = $con->query("SELECT * FROM `$tab` WHERE `status`!='Active' ");
        if ($q->num_rows > 0) {
            $sl = 1;
            while ($mdt = $q->fetch_array()) {
                $id = $mdt['id'];
                $table .= '<tr> <td>' . $sl++ . '</td>';
                foreach ($data as $dt) {
                    $table .= '  <td> ' . $mdt[$dt] . '</td>';
                }
                if ($edit == 'on' || $delete == 'on') {
                    $table .= '<td>';

                    $table .= " <a href='?eid=$id' class='btn-sm btn-primary'> Recover </a>   ";

                    $table .= "<a href='?did=$id' class='btn-sm btn-danger' onclick='return confirm(`This record will be deleted!`)'> Delete </a>";

                    $table .= ' </td>';
                }
                $table .= '</tr> ';
            }
        }

        $table .= " </tbody> </table> </div>
        </div>
    </div>

</div>
</div>
</section>
<script>
    new DataTable('#myTable', {
        responsive: {
            details: {
                display: DataTable.Responsive.display.modal({
                    header: function(row) {
                        var data = row.data();
                        return 'Details for ' + data[0] + ' ' + data[1];
                    }
                }),
                renderer: DataTable.Responsive.renderer.tableAll()
            }
        }
    });
</script>

</div>";
        return $table;
    }
}
