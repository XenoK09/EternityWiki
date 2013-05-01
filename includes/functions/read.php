<?php
/****************************************************************
* EternityWiki - EternityX1 app/addon [version 1.0]
*(C) Copyright 2012, Eternity Incurakai, All Rights Reserved
*----------------------------------------------------------------
* Licensed under the ESCLv1 License 
* (http://eternityinc-official.com/license)
*----------------------------------------------------------------
* This is the function that is used for outputting the contents of 
* a page
*
* SRV_ROOT 		-	defines the server root
* $pagename		-	defines the pagename
*
****************************************************************/

/* Define display function */
$displayed=FALSE;
function display($pagename, $pagelink) {
	/* Declare Variables */
	global $isread, $isedit, $code, $is_special_match, $db, $diplayed;	// declare globals for extensions.
	/* if the page exists... */
	$is_special_match=FALSE; 				// Start without a match
	if(!$displayed)
//	if($db->fetch($db->query("SELECT script FROM wiki_pages WHERE pagename='" . $db->escape($pagelink) . "'"))  != "" or $is_special_match)
	{		
		$displayed=TRUE;
		/* RENDER PAGE TOP */		
		?>
				
		<!-- Wiki Navigation -->
				 
		<!-- Hideable Menu -->
		<div id="menu">
			<input type="button" disabled value="read" />
			<input type="button" onclick="parent.location='http://wiki.eternityincurakai.com/w/<?php echo $pagelink; ?>&action=edit'" value="Edit" />
		</div><br />
				
		<!-- Page Name -->
		<h1><?php echo $pagename; ?></h1>
		<p><em>From the Eternity Incurakai Wiki - an Eternity of Information!</em></p>
				
				
		<?php
		/* RENDER PAGE BOTTOM */
		if(!$is_special_match)					// If we don't have a match, read the page.  If we do, skip reading the page.
		{
		$code = $db->fetch($db->query("SELECT script FROM wiki_pages WHERE pagename='" . $db->escape($pagelink) . "'"));
		$isread = TRUE; 				// Tell extensions we are reading
		$isedit = FALSE; 				// Tell extensions we are not editing
		
		include 'extensions/beforecode/index.php'; 	// Include extensions that want to go before code
		include 'includes/functions/bbcode.php';
		$code = bbcode::url($code);
		$code = bbcode::h2($code);
		$code = bbcode::bold($code);
		// include 'syntax.php';
		$code = stripslashes($code);
		echo $code;			/*=== THIS IS THE OUTPUT ===*/
		include 'extensions/aftercode/index.php'; 	// Include extensions that want to go after the code
	}
	
	/* page does not exist... */
	else
	{
		/* Set the Content Type */
		header('Status:404 Not Found');
				
		/* RENDER PAGE */
		?>
						<!-- Wiki Navigation -->
		
		<!-- Hideable Menu -->
		<div id="menu">
			<input type="button" onclick="parent.location='http://wiki.eternityincurakai.com/w/<?php echo $pagelink; ?>&action=edit'" value="Create" />
		</div><br />
				
		<!-- Page Name -->
		<h1><?php echo $pagename; ?></h1>
		<p><em>From the Eternity Incurakai Wiki - an Eternity of Information!</em></p><br />
				
		<!-- Contents -->
		<p>This page does not exist.  To create it, click on the menu button, and then on the create button.</p>
				
		<?php
	}
}
}
	
/* END OF FILE */
	