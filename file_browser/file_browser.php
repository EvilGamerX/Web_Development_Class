<?PHP
	//I pledge my honour I have abided by the Stevens Honour Code. Stephen Gaspar

	$path = "C:\www";
	$d = array();
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
	}
	
	closedir($open);
	
	natcasesort($dirs);
	natcasesort($files);
	
	echo "<a href=\" \">..</a></BR>\n";
	
	foreach($dirs as $print)
	{
		echo "<a href=\" \">$print</a></BR>\n";
	}
	
	foreach($files as $print)
	{
		echo "$print</BR>\n";
	}
?>