<?PHP

    #I pledge my honour I have abided by the Stevens honour Code - Stephen Gaspar
    include("../include/db.include.php");

    if(isset($_POST["Directory"]))
    {
        $url = $_POST["Directory"];
    }else
    {
        echo "<form action=\"file_indexer.php\" method=\"post\">";
        echo "<input type=\"text\" name=\"Directory\" size=\"42\" placeholder=\"Enter Directory\">";
        echo "<input type=\"submit\" name=\"submit\" value=\"Submit\">";
        exit();
    }
    
    //echo "as";
   $linkid = connect();
   
   //echo mysql_error();
   
   $sql = "TRUNCATE TABLE pages;";
   send_sql($sql);
   
   $sql = "DELETE FROM meta_content;";
   send_sql($sql);
   
   $sql = "DELETE FROM word_count;";
   send_sql($sql);
   
    $url = strip_tags($url);
    
    //echo "Searching $url <BR/><HR><BR/>\n";
    
    $url = str_replace(";", "\;", $url);
    search_directory($url);
    
    function search_directory($url)
    {
        $url = str_replace(array("\\", "//"), "/", $url."/");
        
        $i = 0;
        if(($open = opendir($url)) == TRUE)
	{
            while(($elem = readdir($open)) !== FALSE)
            {
                if($elem != "." && $elem != "..")
		{
                    if(is_dir($url."/".$elem))
                    {
                        $dirs[$i++] = $elem;
                    }else if(is_file($url."/".$elem))
                    {
                        $files[$i++] = $elem;
                    }
		}
            }
        
            closedir($open);
            
            if(isset($files))
            {
                natcasesort($files);
                foreach($files as $print)
                {
                    $file_info = pathinfo($url."/".$print);
                    if(isset($file_info['extension']))
                    {
                        $file_info['extension'] = strtolower($file_info['extension']);
                        if($file_info['extension'] === "html" | $file_info['extension'] === "htm")
                        {
                            $sql = "INSERT INTO pages (page_name, dir) VALUES (\"$print\", \"$url\");";
                            send_sql($sql);
                            page_info($url.$print);
                            //echo "<HR>\n";
                        }
                    }
                }
            }
            
            if(isset($dirs))
            {
                natcasesort($dirs);
                
                foreach($dirs as $print)
                {
                    search_directory($url.$print);
                }
            }
        }
    }
        
    function page_info($file)
    { 
        
        $sql = "SELECT pid FROM pages ORDER BY pid DESC LIMIT 1;";
        $res = mysql_fetch_assoc(send_sql($sql));
        
        $pid = $res["pid"];
      
        $meta = get_meta_tags($file, TRUE);
        
        //echo "<BR/>Metatags:<BR/>\n";
        
        foreach($meta as $k=>$item)
        {
            $sql = "INSERT INTO meta_content (pid, meta_tag, meta_data) VALUES ('$pid', '$k', '$item');";
            send_sql($sql);
            //echo "$k => $item</BR>\n"   ;
        }
	
        //echo "</BR>Word Count:<BR/>\n";

        $text = strtolower(strip_tags(file_get_contents($file)));
        
        $arr = superExplode($text, "() \r\n.\t;:,+\"|!?@#$\'%^&*~`=-_[]{}/<>\\¶");
        
        if(!empty($arr[0]))
        {
        
            $disp = array_count_values($arr);

            ksort($disp);

            foreach($disp as $k=>$e)
            {
                $sql = "INSERT INTO word_count (pid, word, word_count) VALUES ('$pid', '$k', '$e');";
                send_sql($sql);
                //echo "$k | $e</BR>\n";
            }
        }
    }
	
    function superExplode($str, $tok)
    {
	$i = 0;
	
	$arr[$i++] = strtok($str, $tok);
	
	while(($data = strtok($tok)) !== FALSE)
	{
		$arr[$i++] = $data;
	}
	
	return $arr;
    }
?>