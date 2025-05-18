<?php

if (isset($_REQUEST['DSI_BACKUP_CLEAN'])) {
    try {
        chdir("../");
        unlink("backup.zip");
        unlink("backup.sql");
    } catch (\Throwable $th) {
        //throw $th;
    }
}
