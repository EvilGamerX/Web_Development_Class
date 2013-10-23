<?php

    include("../include/db.include.php");

    if(isset($_POST["meta"]))
    {
        $meta_check = "checked";
    }else
    {
        $meta_check = "";
    }
    if(isset($_POST["word"]))
    {
        $word_checked = "checked";
    }  else 
    {
        $word_checked = "";
    }
    
    echo "<form action=\"searcher.php\" method=\"post\">";
    echo "<input type=\"text\" name=\"Directory\" size=\"42\" placeholder=\"Enter a Word\">";
    echo "<input type=\"submit\" name=\"submit\" value=\"Submit\">";
    echo "<BR>";
    echo "<input type=\"checkbox\" name=\"meta\" value=\"1\" $meta_check>Search Meta";
    echo "<input type=\"checkbox\" name=\"word\" value=\"1\" $word_checked>Search Words";
    echo "</form>";
    
    if(isset($_POST["Directory"]))
    {
        $url = $_POST["Directory"];
    }else
    {
        exit();
    }
    
    $linkid = connect();
    
    $url = strip_tags($url);
    $url = str_replace(";", "\;", $url);
    
    if($meta_check == "checked")
    {
        echo "Pages that have the meta tag $url <BR/>";

        $sql = "SELECT pages.dir, pages.page_name, meta_content.meta_tag, meta_content.meta_data FROM pages, meta_content WHERE meta_content.meta_data = \"$url\" AND pages.pid = meta_content.pid;";
        $res = send_sql($sql);
        
        while($result = mysql_fetch_assoc($res))
        {
            //print_r($result);
            echo $result["dir"]." | ".$result["page_name"]." | ".$result["meta_tag"]." | ".$result["meta_data"];
            echo "<BR/>";
        }
        
        echo "<BR/><hr><BR/>";
    }
    

    if($word_checked == "checked")
    {
        $sql = "SELECT pages.dir, pages.page_name, word_count.word, word_count.word_count FROM pages, word_count WHERE word_count.word = \"$url\" AND pages.pid = word_count.pid ORDER BY word_count.word_count DESC;";
        $res = send_sql($sql);
        
        echo "Pages that have the word $url <BR>";
        
        while($result = mysql_fetch_assoc($res))
        {
            //print_r($result);
            echo $result["dir"]." | ".$result["page_name"]." | ".$result["word"]." | ".$result["word_count"];
            echo "<BR/>";
        }
        
    }
?>
