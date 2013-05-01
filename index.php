<?php
include 'includes/eternityx/php.php';
include 'config/config.php';
include 'classes/page.php';

########## STARTING POINT ##########
$pdo = new PDO('mysql:dbname='.DB_NAME.';host='.DB_HOST, DB_USER, DB_PASSWORD);
include 'includes/functions.php';
include 'includes/functions/bbcode.php';
include 'includes/functions/read.php'; 
include 'includes/functions/edit.php'; 
/* Variables */


$action = $_GET['action']; //we will start using editing later

// lets get that page, shall we?
$page = rawurldecode(strtok($_SERVER['REQUEST_URI'], '?'));
$page = ltrim($page, "/");
if($action == "edit")$page = str_replace("/edit", "", $page);

if($page == "") { header("location: /Main_Page"); die; }
if(preg_match('/.css/is', $page)) {
	header("Content-Type: text/css");
	include $page; die;
} else if(preg_match("/.js/is", $page)) {
header("Content-Type: text/javascript");
	include $page; die;
}

// retrieve the page information
$info = $pdo->prepare("SELECT * FROM wiki_pages WHERE `pagename`=?");
$info->bindValue(1, $page);
$info->execute();
$info = $info->fetch(PDO::FETCH_ASSOC);

$name = $info['pagename'];				// retrieve the name
$name = str_replace("_", " ", $name); 	// underscore to space fix

$content = $info['script'];
$content = bbcode::url($content);
$content = bbcode::headings($content);
$content = bbcode::bold($content);
$content = bbcode::references($content);

$page_name = $page;
	$page_link = str_replace(" ", "_", $page);

// START SUBST - ASSETS
ob_start();
$assets = ob_get_contents();
ob_end_clean();
// END SUBST - ASSETS

// START SUBST - TITLE
ob_start();
echo $name;
$title = ob_get_contents();
ob_end_clean();
// END SUBST - TITLE

// START SUBST - CONTENT
ob_start();
echo '<h1>'.$name.'</h1>';
echo '<p><em>From the Eternity Incurakai Wiki - an Eternity of Information!</em></p>';
if($action != "edit") echo '<article>'.$content.'</article>';
else {
echo "<textarea>".$content."</textarea>";
}
$content = ob_get_contents();
ob_end_clean();
// END SUBST - CONTENT

// START SUBST - METADATA
ob_start();
$metadata = ob_get_contents();
ob_end_clean();
// END SUBST - METADATA




/*

if(!isset($action))
{
	$action = 'read';
}


else 
{
	
	$page_link = $page;
	$page_name = preg_replace('/ /is','_', $page);
}
$assets = "";
$metadata = "";
$title = "Wiki";
ob_start();
if($action == 'read')
{
	$page_name = str_replace("_", " ", $page_link);
	$page_link = str_replace(" ", "_", $page_link);
	display($page_name, $page_link);
}	
			
else if($action=='edit')
{
	$page_name = str_replace("_", " ", $page_link);
	$page_link = str_replace(" ", "_", $page_name);
	edit($page_name, $page_link);
}
$content = ob_get_contents();
ob_end_clean();
$footer = false;
*/
new Eternity\Wiki\page($assets, $metadata, $content, $title, $footer); 
