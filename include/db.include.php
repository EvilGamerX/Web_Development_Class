<?php

    $gl_dbhost = "localhost";
    $gl_usr = "evilgamerx";
    $gl_pass = "sg06017";
    $gl_db = "index_dump";
    
    function connect()
    {
        global $gl_dbhost, $gl_usr, $gl_pass;
        
        if(!($linkid = @mysql_connect($gl_dbhost, $gl_usr, $gl_pass)))
        {
            echo "Cannot Connect to DataBase";
            exit;
        }
       
        return $linkid;
    }
    
    function send_sql($sql)
    {
        global $gl_db;
        
        if(!($res = @mysql_db_query($gl_db, $sql)))
        {
            echo mysql_error();
            exit;
        }
        
        return $res;
    }
?>
