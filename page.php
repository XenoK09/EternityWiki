<?php

if(!function_exists('page_exists'))
{
function page_exists($pagename)
{
$sql="SELECT * FROM pages WHERE Pagename='$pagename'";
$result_set = mysql_query($sql) or die(mysql_error());
$num_rows = mysql_num_rows($result_set);
if($num_rows==0)
{
return FALSE;
}
else
{
return TRUE;
}
}
}
if(!function_exists('pull'))
{
function pull($myrow, $pagename)
{
$sql="SELECT * FROM pages WHERE Pagename='$pagename'";
$result = mysql_unbuffered_query($sql) or die(mysql_error());
while($row = mysql_fetch_array($result))
  {
  return $row[$myrow];
  }
}
}
if(!function_exists('getIP'))
{
function getIP() { 
$ip; 
if (getenv("HTTP_CLIENT_IP")) 
{
 $ip = getenv("HTTP_CLIENT_IP"); 
}
else if(getenv("HTTP_X_FORWARDED_FOR")) 
{
 $ip = getenv("HTTP_X_FORWARDED_FOR"); 
}
else if(getenv("REMOTE_ADDR")) 
{
 $ip = getenv("REMOTE_ADDR"); 
}
else 
{
 $ip = "0";
}
return $ip; 
}
}
// http://admincmd.blogspot.com/2007/08/php-get-client-ip-address.html
if(isset($_SESSION['username']))
{
$username=$_SESSION['username'];
}
else
{
 $username=getIP();
}
if(!function_exists('pull_perms'))
{
 function pull_perms()
 {
  if($username==0) // Here we can also block IP's from using other accounts if needed.  I'm not sure if this is already done.
  {
   return $username;
  }
  else
  {
   $sql="SELECT * FROM permissions WHERE Username='".$_SESSION['username']."'";
   $result = mysql_unbuffered_query($sql) or die(mysql_error());
   if(mysql_num_rows($result)==0) // Nothing special with this user.
   {
    return 1; // Permission is 1.
   }
   while($row = mysql_fetch_array($result)) // This user is special.
   {
    return $row['permission']; // Give them special permissions.
   }
  }
 }
}
$GLOBALS['permission']=pull_perms();
if(!function_exists('haspermission'))
{
function haspermission($pagename)
{
if(pull('Permission',$pagename)<=$GLOBALS['permission'])
if(TRUE)
{
return TRUE;
}
else
{
return FALSE;
}
}
}

include 'includes/functions/read.php';
include 'edit.php';