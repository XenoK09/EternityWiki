<?php
// Use this page to define "special" pages
//
// These cannot be edited through the wiki, and they can contain any PHP or HTML content.
//
// For each special page, create a page as /specials/***.php (it does not matter what it is called)
// Then, put it here (you can 'save it as a draft' as it will not be activated until it is put here) with the following syntax:
// special('special_name','pagename.php');
// where special_name is the name of the special page that will be seen by the user.  Like 'Special:Delete'.  Make sure you include the Special: prefix in this.
// and pagename.php is the name of the page in the /specials directory.  Like 'delete.php'.  Make sure you include the .php prefix, but no directory information.
special('Special:Test','test.php');