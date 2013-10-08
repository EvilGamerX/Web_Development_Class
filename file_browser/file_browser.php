<?PHP
	//I pledge my honour I have abided by the Stevens Honour Code. Stephen Gaspar

        if(isset($_POST["Directory"]))
        {
            $path = $_POST["Directory"];
        }else if(isset($_GET["path"]))
        {
            $path = $_GET["path"];
        }else
        {
            echo "<form action=\"file_browser.php\" method=\"post\">";
            echo "<input type=\"text\" name=\"Directory\" size=\"42\" placeholder=\"Enter Directory\">";
            echo "<input type=\"submit\" name=\"submit\" value=\"Submit\">";
            exit();
        }
	$files = array();
	$i = 0;
        
	if($open = opendir($path))
	{
		while(($elem = readdir($open)) !== FALSE)
		{
			if($elem != "." && $elem != "..")
			{
				if(is_dir($path."/".$elem))
				{
					$dirs[$i++] = $elem;
				}else if(is_file($path."/".$elem))
				{
					$files[$i++] = $elem;
				}
			}
		}

            closedir($open);
             
            $tmp = dirname($path)."/";
            $temp = str_replace("//", "/", $tmp);
            $temp = str_replace("\\", "/", $tmp);
            echo "<a href=\"file_browser.php?path=$tmp\">..</a></BR>\n";

                        
            if(isset($dirs))
            {
                natcasesort($dirs);
                
                foreach($dirs as $print)
                {
                    $tmp = $path."/".$print."/";
                    $tmp = str_replace("//", "/", $tmp);
                    $tmp = str_replace("\\", "/", $tmp);
                    echo "<a href=\"file_browser.php?path=$tmp\">$print</a></BR>\n";
                }
            }
            
            if(isset($files))
            {
                natcasesort($files);
                foreach($files as $print)
                {
                    echo "$print</BR>\n";
                }
            }
        }
?>