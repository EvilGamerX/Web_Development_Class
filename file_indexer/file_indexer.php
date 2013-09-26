<?PHP
	$url = "http://cs465.free.fr/files/hw3b.html";
	
	$meta = get_meta_tags($url);
	
	foreach($meta as $k=>$item)
	{
		echo "$k => $item</BR>\n";
	}
	
	echo "</BR>\n";
	
	$text = rawurlencode(file_get_contents($url));
	
	strip_tags($text);
	
	$arr = superExplode(strtolower($text), " \n.\t'%20-%23\";:,%27%26");
	
	$disp = array_count_values($arr);
	
	ksort($disp);
	
	foreach($disp as $k=>$e)
	{
		echo "$k | $e</BR>\n";
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