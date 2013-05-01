<?php
include 'functions/read.php'; 
include 'functions/edit.php';

function page_exists($pagename)
{
	$page = new Eternity\database();
	
	function returner($test)
	{
		return $test;
	}
	
	$result = $page->query("SELECT * FROM wiki_pages WHERE pagename='" . $page->escape($pagename) . "'") or returner(false);
	while($row = $page->create_array($result))
	{
		$script = $row['script'];
	}
	
	if($script == "")
	{
		return true;
	}
	else
	{
		return false;
	}
}

function pull($myrow, $pagename)
{
	$page2 = new Eternity\database();
	$result = $page2->query("SELECT * FROM wiki_pages WHERE pagename='" . $page2->escape($pagename) . "'");
	while($row = $page2->create_array($result))
	{
		return $row[$myrow];
	}
}


function haspermission($pagelink)
	{
		return true;
	}
	
function booltostr($bool, $true, $false)
	{
		if($bool)
		{
			return $true;
		}
		else
		{
			return $false;
		}
	}
	
function allowhtml($html)
		{
			global $code;
			$code = str_replace(htmlspecialchars($html), $html, $code);
			return $code;
		}

	
function syntax($orig, $html)
		{
			global $code;
			$code = stripslashes(preg_replace($orig, $html, $code));
			return $code;
		}
function special($pagename, $path)
		{
			if($_GET['p']==$pagename)
			{
				include '../specials/'.$path;
			}
		}