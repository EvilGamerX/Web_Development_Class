<?PHP
	
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

    echo "Searching ".$url."<BR/><HR><BR/>\n";
    search_directory($url);
    
    function search_directory($url)
    {
        $url = str_replace(array("\\", "//"), "/", $url."/");
        
        $i = 0;
        if($open = opendir($url))
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
                            echo $url.$print."<BR/>\n";
                            page_info($url.$print);
                            echo "<HR>\n";
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
        $meta = get_meta_tags($file, TRUE);
        
        echo "<BR/>Metatags:<BR/>\n";
        
        foreach($meta as $k=>$item)
        {
            echo "$k => $item</BR>\n";
        }
	
        echo "</BR>Word Count:<BR/>\n";

        $text = strtolower(strip_tags(file_get_contents($file)));
        
        $arr = superExplode($text, "() \r\n.\t;:,+\"|!?@#$\'%^&*~`=-_[]{}/<>\\¶");
        
        $disp = array_count_values($arr);

        ksort($disp);

        foreach($disp as $k=>$e)
        {
            echo "$k | $e</BR>\n";
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