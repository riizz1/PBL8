<?php
    $host = "localhost";  
    $user = "root";       
    $pass = "";           
    $db   = "db_pbl8";  
    
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    
    try {
        $koneksi = new mysqli($host, $user, $pass, $db);
        
        $koneksi->set_charset("utf8");
    
    } catch (mysqli_sql_exception $e) {
        die("Connection failed: " . $e->getMessage());
    }
    
?>