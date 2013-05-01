<?
class bbcode {
	static function url($string) {
		global $db;
		$string = preg_replace("%\[url=([^\[]+?)\](.*?)\[/url\]%e", 'self::handle_url_tag(\'$1\', \'$2\')', $string);
		$string = preg_replace('%\[url\]([^\[]*?)\[/url\]%e', 'self::handle_url_tag(\'$1\')', $string);
		return $string;
	}
	static function headings($string) {
		$quote='"';
		$string = preg_replace("%======(.*?)======%ms", "<h6 class=".$quote."wiki".$quote.">$1</h6>", $string);
		$string = preg_replace("%=====(.*?)=====%ms", "<h5 style=".$quote."wiki".$quote.">$1</h5>", $string);
		$string = preg_replace("%====(.*?)====%ms", "<h4 style=".$quote."wiki".$quote.">$1</h4>", $string);
		$string = preg_replace("%===(.*?)===%ms", "<h3 style=".$quote."wiki".$quote.">$1</h3>", $string);
		$string = preg_replace("%==(.*?)==%ms", "<h2 style=".$quote."wiki".$quote.">$1</h2>", $string);
		return $string;
	} 
	
	static function references($string) {
		$string = preg_replace("%\[ref\](.*?)\[\/ref\]%ms", '<sup><a href="$1">[ref]</a></sup>', $string);
		return $string; 
	}
	static function bold($string) {
		$string = preg_replace("%\[b\](.*?)\[/b\]%ms", "<strong>$1</strong>", $string);
		return $string;
	}
	
	
	static function handle_url_tag($url, $link = '', $bbcode = false) {
		$url = trim($url);
	
		// Deal with [url][img]http://example.com/test.png[/img][/url]
		if (preg_match('%<img src=\\\\"(.*?)\\\\"%', $url, $matches))
			return handle_url_tag($matches[1], $url, $bbcode);
	
		$full_url = str_replace(array(' ', '\'', '`', '"'), array('%20', '', '', ''), $url);
		if (strpos($url, 'www.') === 0) // If it starts with www, we add http://
			$full_url = 'http://'.$full_url;
		else if (strpos($url, 'ftp.') === 0) // Else if it starts with ftp, we add ftp://
			$full_url = 'ftp://'.$full_url;
		else if (!preg_match('#^([a-z0-9]{3,6})://#', $url)) // Else if it doesn't start with abcdef://, we add http://
			$full_url = 'http://'.$full_url;
	
		// Ok, not very pretty :-)
		if ($bbcode) {
			if ($full_url == $link)
				return '[url]'.$link.'[/url]';
			else
				return '[url='.$full_url.']'.$link.'[/url]';
		} else {
			if ($link == '' || $link == $url) {
				$url = htmlspecialchars_decode($url);
				$link = strlen($url) > 55 ? substr($url, 0 , 39).' … '.substr($url, -10) : $url;
				$link = htmlspecialchars($link);
			}
			else
				$link = stripslashes($link);
	
			return '<a href="'.$full_url.'">'.$link.'</a>';
		}
	}

}

