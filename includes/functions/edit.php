<?php

/* Declare the edit function */
if(!function_exists('edit'))
{
function edit($pagename, $pagelink, $returnas = 'p', $rows = '50', $cols = '50'){
	/* Variables */
	global $isread, $isedit, $mysql_prefix, $permission, $db;
	$defcode = $db->fetch($db->query("SELECT script FROM wiki_pages WHERE pagename='" . $db->escape($pagelink) . "'"));
	$usercode = $_POST['code'];
	$submit = $_REQUEST['submit'];
	$isread = FALSE;	// Tell extensions we are not reading
	$isedit = TRUE;		// Tell extensions we are editing
	
		
	/* Is Form activated? */
	if(isset($submit))
	{
		/* Does this page exist? */
		if($db->fetch($db->query("SELECT script FROM wiki_pages WHERE pagename='" . $db->escape($pagelink) . "'")) != "")
		{
			/* Do we have correct permissions? */
			if(haspermission($pagelink))
			{
				/* We are getting error messages when we edit, so disable checking. */
				if(TRUE)  // Blank script.  Was $usercode==""
				{
					$db->query("UPDATE wiki_pages SET script='" . $db->escape($usercode) . "' WHERE pagename='" . $db->escape($pagelink) . "'") or die($db->error());
					if(isset($_SESSION['username']))
					{
						$db->query("INSERT INTO wiki_log (`pagename`, `type`, `user`, `timestamp`) VALUES ('" . $db->escape($pagelink) . "', 'edit', '" . $_SESSION['username'] . "', now())") or error(e0001, $db->error());
					}
					else
					{
						$db->query("INSERT INTO wiki_log (`pagename`, `type`, `user`, `timestamp`) VALUES ('" . $db->escape($pagelink) . "', 'edit', '" . $_SERVER['REMOTE_ADDR'] . "', now())") or error(e0001, $db->error());
					}
                    header("location:http://wiki.eternityincurakai.com/w/" . $pagelink);
                    die;
				}
				else
				{
					die('Error: Page length must be at least 1.');
				}
			}
			else
			{
				die('You do not have sufficient priveleges to edit this page.');
			}
		}
		
		/* This page doesn't exist, let's create a page instead of editing */
		else
		{
			if($usercode) // Blank script.
			{
				/* Create the Page, redirect, and die */
				$db->query
                ("INSERT INTO wiki_pages (pagename, script, permission) VALUES ('" . 
                $db->escape($pagelink) . "', '" . 
                $db->escape($usercode) . "', '1')") or die
                ($db->error());
				if(isset($_SESSION['username']))
				{
					$db->query("INSERT INTO wiki_log (`pagename`, `type`, `user`, `timestamp`) VALUES ('" . $db->escape($pagelink) . "', 'create', '" . $_SESSION['username'] . "', now())") or error(e0001, $db->error());
				}
				else
				{
					$db->query("INSERT INTO wiki_log (`pagename`, `type`, `user`, `timestamp`) VALUES ('" . $db->escape($pagelink) . "', 'create', '" . $_SERVER['REMOTE_ADDR'] . "', now())") or error(e0001, $db->error());
				}
				header("location:/w/" . $pagelink); die;
			}
			else
			{
				die('Page length must be at least 1.');
			}
		}
	}
	
	/* Form is not activated, display Editing page. */
	else
	{
		/* Variables */
		
		
		include 'extensions/beforecode/index.php'; // Include extensions that want to go before code
		?>
		
		<!-- Wiki Navigation -->
		<?php include 'includes/header.php'; ?>
		
		<!-- Hideable menu -->
		<div id="menu">
			<input type="button" onclick="parent.location='http://wiki.eternityincurakai.com/w/<?php echo $pagelink; ?>'" value="read" />
			<input type="button" disabled onclick="parent.location='http://wiki.eternityincurakai.com/w/<?php echo $pagelink; ?>&action=edit'" value="Edit" />
		</div><br />
		
		<!-- Page Name -->
		<h1><?php echo $pagename; ?></h1>
		<p><em>From the Eternity Incurakai Wiki - an Eternity of Information!</em></p><br />
		
		<!-- Form -->
		<form action="" method="post">
			<input type="hidden" name="submit" value="yes" />
			<input type="hidden" name="<?php echo $returnas; ?>" value="<?php echo $pagelink; ?>" />
			<textarea name="code"><?php echo stripslashes($defcode); ?></textarea><br /><br />
		
		<?php
		
		/* Determine Permissions, and include aftercode */
		if(haspermission($pagelink))
		{
			?>
			<div class="actions">
				<input type="submit" />
				<input type="button" onclick="parent.location='http://wiki.eternityincurakai.com/w/<?php echo $pagelink; ?>'" value="Cancel" />
			</div>
			<?php
		}
		else
		{
			?>
			<p>You do not have permission to edit this page.  You may only view the source.</p>
			<?php
		}
		?>
		</form>
		<?php
		include 'extensions/aftercode/index.php'; // Include extensions that want to go after code
	}
}
}




