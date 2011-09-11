<?php

include_once("includes/inc.global.php");
include("classes/class.info.php");

if ($_GET["destroySess"]==1) {
	
	if ($_GET["confirm"]==1) {
		session_destroy();
		echo "Session Destroyed. <a href=index.php>Continue</a>";
	}
	else {
		echo "Really Destroy Session? <a href=pages.php?destroySess=1&confirm=1>Yes</a> | <a href=javascript:history.back(1)>No (Go back)</a>";
	}
	
	exit;
}

$p->site_section = SECTION_INFO;

print $p->MakePageHeader();
print $p->MakePageMenu();

$pg = cInfo::LoadOne($_REQUEST["id"]);

if (!$pg) {
	$p->page_title = "Oops, you have requested a non-existant page.";
	print $p->MakePageTitle();

}
else {
	$p->page_title = stripslashes($pg["title"]);
	
	if ($cUser->member_role>0)
		$p->page_title .= ' <a href=do_info_edit.php?id='.$_REQUEST["id"].'>[Edit]</a>';
	
	print $p->MakePageTitle();
	print stripslashes($pg["body"]);
}

print $p->MakePageFooter();
?>