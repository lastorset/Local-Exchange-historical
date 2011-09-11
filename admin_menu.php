<?php
include_once("includes/inc.global.php");
$p->site_section = ADMINISTRATION;
$p->page_title = "Administration Menu";

$cUser->MustBeLevel(1);

$query = $cDB->Query("SELECT sum(balance) from ". DATABASE_MEMBERS .";");
		
if($row = mysql_fetch_array($query)) {
		$balance = $row[0];
}			
			
$list = "<STRONG>Current Balance is: $balance</STRONG><P>";
$list .= "<STRONG>Accounts</STRONG><P>";
$list .= "<A HREF=member_create.php><FONT SIZE=2>Create a New Member Account</FONT></A><BR>";
$list .= "<A HREF=member_to_edit.php><FONT SIZE=2>Edit a Member Account</FONT></A><BR>";
if ($cUser->member_role > 1) {
	$list .= "<A HREF=member_choose.php?action=member_status_change&inactive=Y><FONT SIZE=2>Inactivate/Re-activate a Member Account</FONT></A><BR>";
}
$list .= "<A HREF=member_contact_create.php?mode=admin><FONT SIZE=2>Add a Joint Member to an Existing Account</FONT></A><BR>";
$list .= "<A HREF=member_contact_to_edit.php><FONT SIZE=2>Edit/Delete a Joint Member</FONT></A><BR>";
$list .= "<A HREF=member_unlock.php><FONT SIZE=2>Unlock Account and Reset Password</FONT></A><BR>";

if (OVRIDE_BALANCES==true) // Only display Override Balance link if it is turned on in config file
	$list .= "<A HREF=balance_to_edit.php><FONT SIZE=2>Override Member Account Balance</FONT></A> <font size=2 color=red>New!</font>";

$list .= "<P>";

if ($cUser->member_role > 1) {
	$list .= "<STRONG>Exchanges</STRONG><P>";
	$list .= "<A HREF=member_choose.php?action=trade><FONT SIZE=2>Record an Exchange for a Member</FONT></A><BR>";
	$list .= "<A HREF=trade_reverse.php><FONT SIZE=2>Reverse an Exchange that was Made in Error</FONT></A><BR>";
	$list .= "<A HREF=member_choose.php?action=feedback_choose><FONT SIZE=2>Record Feedback for a Member</FONT></A><P>";
}

$list .= "<STRONG>Offered Listings</STRONG><P>";
$list .= "<A HREF=listing_create.php?type=Offer&mode=admin><FONT SIZE=2>Create a New Offer Listing for a Member</FONT></A><BR>";
$list .= "<A HREF=member_choose.php?action=listing_to_edit&get1=type&get1val=Offer><FONT SIZE=2>Edit a Member's Offered Listing</FONT></A><BR>";
$list .= "<A HREF=member_choose.php?action=listing_delete&get1=type&get1val=Offer><FONT SIZE=2>Delete a Member's Offered Listing</FONT></A><P>";

$list .= "<STRONG>Wanted Listings</STRONG><P>";
$list .= "<A HREF=listing_create.php?type=Want&mode=admin><FONT SIZE=2>Create a New Want Listing for a Member</FONT></A><BR>";
$list .= "<A HREF=member_choose.php?action=listing_to_edit&get1=type&get1val=Want><FONT SIZE=2>Edit a Member's Wanted Listing</FONT></A><BR>";
$list .= "<A HREF=member_choose.php?action=listing_delete&get1=type&get1val=Want><FONT SIZE=2>Delete a Member's Wanted Listing</FONT></A><P>";

$list .= "<STRONG>Listings&#8212;Miscellaneous</STRONG><P>";
$list .= "<A HREF=member_choose.php?action=holiday><FONT SIZE=2>Member Going on Holiday</FONT></A>";
if ($cUser->member_role > 1) {
	$list .= "<BR><A HREF=category_create.php><FONT SIZE=2>Create a New Listing Category</FONT></A><BR>";
	$list .= "<A HREF=category_choose.php><FONT SIZE=2>Edit/Delete Listing Category</FONT></A>";
}
$list .= "<P>";


$list .= "<STRONG>System & Reporting</STRONG><P>";
if ($cUser->member_role > 1) {
	$list .= "<A HREF=export.php><FONT SIZE=2>Export/Backup Data to Spreadsheet</FONT></A><BR>";
	$list .= "<A HREF=contact_all.php><FONT SIZE=2>Send an Email to All Members</FONT></A><BR>";
}
$list .= "<A HREF=report_no_login.php><FONT SIZE=2>View Members Not Yet Logged In</FONT></A><BR><p>";

if (TAKE_MONTHLY_FEE && $cUser->member_role > 1) {
    $ts = time();

    $list .= "<strong>Monthly fee</strong><br>";
   
   // File missing??
 //   $list .= "<a href='monthly_fee_list.php'>List of monthly fees</a><br>";
    // CID = Confirmation ID.
    $list .= "<a href='take_monthly_fee.php?CID=$ts'>
                <font size=2>Take Monthly Fee</font></a><br>";
    $list .= "<a href='refund_monthly_fee.php'>
                <font size=2>Refund Monthly Fee</font></a><p>";
}

$list .= "<STRONG>News and Events</STRONG><P>";
$list .= "<A HREF=news_create.php><FONT SIZE=2>Create a News Item</FONT></A><BR>";
$list .= "<A HREF=news_to_edit.php><FONT SIZE=2>Edit a News Item</FONT></A><BR>";
$list .= "<A HREF=newsletter_upload.php><FONT SIZE=2>Upload a Newsletter</FONT></A><BR>";
$list .= "<A HREF=newsletter_delete.php><FONT SIZE=2>Delete Newsletters</FONT></A><BR>";

/*[CDM]*/
$list .= "<p><STRONG>Information Pages</STRONG><P>";
$list .= "<A HREF=create_info.php><FONT SIZE=2>Create a New Info Page</FONT></A> <font size=2 color=red>New!</font> <BR>";
$list .= "<A HREF=edit_info.php><FONT SIZE=2>Edit Info Pages</FONT></A> <font size=2 color=red>New!</font> <BR>";
$list .= "<A HREF=delete_info.php><FONT SIZE=2>Delete an Info Page</FONT></A> <font size=2 color=red>New!</font> <BR>";
$list .= "<A HREF=info_url.php><FONT SIZE=2>See Info Page URL's</FONT></A> <font size=2 color=red>New!</font> <BR>";

$p->DisplayPage($list);

?>
