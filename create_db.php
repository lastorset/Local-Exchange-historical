<?php

$running_upgrade_script = true;
include_once("includes/inc.global.php");

$query = $cDB->Query("SHOW VARIABLES LIKE 'have_innodb';");
$row = mysql_fetch_array($query);
if($row[1] != "YES")	die("Your database does not have InnoDB support. See the installation instructions for more information about InnoDB. Installation aborted.");

if($cDB->Query("SELECT * FROM " . DATABASE_MEMBERS))	die("Error - database already exists! If you want to create a new database delete the old one first. You may also get this error if you are trying to install the program and your database userid or password in inc.config.php is incorrect.");

	
$cDB->Query("CREATE TABLE " . DATABASE_MEMBERS . "( member_id varchar(15) NOT NULL default '', password varchar(50) NOT NULL default '', member_role char(1) NOT NULL default '', security_q varchar(25) default NULL, security_a varchar(15) default NULL, status char(1) NOT NULL default '', member_note varchar(100) default NULL, admin_note varchar(100) default NULL, join_date date NOT NULL default '0000-00-00', expire_date date default NULL, away_date date default NULL, account_type char(1) NOT NULL default '', email_updates int(3) unsigned NOT NULL default '0', balance decimal(8,2) NOT NULL default '0.00', PRIMARY KEY (member_id)) TYPE=InnoDB;") or die("Error - database already exists! If you want to create a new database delete the old one first.");
	
$cDB->Query("CREATE TABLE " . DATABASE_PERSONS . "( person_id mediumint(6) unsigned NOT NULL auto_increment, member_id varchar(15) NOT NULL default '', primary_member char(1) NOT NULL default '', directory_list char(1) NOT NULL default '', first_name varchar(20) NOT NULL default '', last_name varchar(30) NOT NULL default '', mid_name varchar(20) default NULL, dob date default NULL, mother_mn varchar(30) default NULL, email varchar(40) default NULL, phone1_area char(5) default NULL, phone1_number varchar(30) default NULL, phone1_ext varchar(4) default NULL, phone2_area char(5) default NULL, phone2_number varchar(30) default NULL, phone2_ext varchar(4) default NULL, fax_area char(3) default NULL, fax_number varchar(30) default NULL, fax_ext varchar(4) default NULL, address_street1 varchar(50) default NULL, address_street2 varchar(50) default NULL, address_city varchar(50) NOT NULL default '', address_state_code char(50) NOT NULL default '', address_post_code varchar(20) NOT NULL default '', address_country varchar(50) NOT NULL default '', PRIMARY KEY (person_id)) TYPE=MyISAM;") or die("Error - database already exists! If you want to create a new database delete the old one first.");

$cDB->Query("CREATE TABLE " . DATABASE_LISTINGS . "( title varchar(60) NOT NULL default '', description text, category_code smallint(4) unsigned NOT NULL default '0', member_id varchar(15) NOT NULL default '', rate varchar(30) default NULL, status char(1) NOT NULL default '', posting_date timestamp(14) NOT NULL, expire_date date default NULL, reactivate_date date default NULL, type char(1) NOT NULL default '', PRIMARY KEY (title, category_code, member_id,type)) TYPE=MyISAM;") or die("Error - database already exists! If you want to create a new database delete the old one first.");

$cDB->Query("CREATE TABLE " . DATABASE_CATEGORIES . "( category_id smallint(4) unsigned NOT NULL auto_increment, parent_id smallint(4) unsigned default NULL, description varchar(30) NOT NULL default '', PRIMARY KEY (category_id)) TYPE=MyISAM;") or die("Error - database already exists! If you want to create a new database delete the old one first.");

$cDB->Query("CREATE TABLE " . DATABASE_TRADES . "( trade_id mediumint(8) unsigned NOT NULL auto_increment, trade_date timestamp(14) NOT NULL, status char(1) default NULL, member_id_from varchar(15) NOT NULL default '', member_id_to varchar(15) NOT NULL default '', amount decimal(8,2) NOT NULL default '0.00', category smallint(4) unsigned NOT NULL default '0', description varchar(255) default NULL, type char(1) NOT NULL default '', PRIMARY KEY (trade_id)) TYPE=InnoDB;") or die("Error - database already exists! If you want to create a new database delete the old one first.");

$cDB->Query("CREATE TABLE " . DATABASE_LOGGING . "( log_id mediumint(8) unsigned NOT NULL auto_increment, log_date timestamp(14) NOT NULL, admin_id varchar(15) NOT NULL default '', category char(1) NOT NULL default '', action char(1) NOT NULL default '', ref_id varchar(15) NOT NULL default '', note varchar(100) default NULL, PRIMARY KEY (log_id)) TYPE=InnoDB;") or die("Error - database already exists! If you want to create a new database delete the old one first.");

$cDB->Query("CREATE TABLE " . DATABASE_LOGINS . "( member_id varchar(15) NOT NULL default '', total_failed mediumint(6) unsigned NOT NULL default '0', consecutive_failures mediumint(3) unsigned NOT NULL default '0', last_failed_date timestamp(14) NOT NULL, last_success_date timestamp(14) NOT NULL default '00000000000000', PRIMARY KEY (member_id)) TYPE=MyISAM;") or die("Error - database already exists! If you want to create a new database delete the old one first.");

$cDB->Query("CREATE TABLE " . DATABASE_FEEDBACK . "( feedback_id mediumint(8) unsigned NOT NULL auto_increment, feedback_date timestamp(14) NOT NULL, status char(1) NOT NULL default '', member_id_author varchar(15) NOT NULL default '', member_id_about varchar(15) NOT NULL default '', trade_id mediumint(8) unsigned NOT NULL default '0', rating char(1) NOT NULL default '', comment text, PRIMARY KEY (feedback_id)) TYPE=MyISAM;") or die("Error - database already exists! If you want to create a new database delete the old one first.");

$cDB->Query("CREATE TABLE " . DATABASE_REBUTTAL . "( rebuttal_id mediumint(6) unsigned NOT NULL auto_increment, rebuttal_date timestamp(14) NOT NULL, feedback_id mediumint(8) unsigned default NULL, member_id varchar(15) NOT NULL default '', comment varchar(255) default NULL, PRIMARY KEY (rebuttal_id)) TYPE=MyISAM;") or die("Error - database already exists! If you want to create a new database delete the old one first.");

$cDB->Query("CREATE TABLE " . DATABASE_NEWS . "( news_id mediumint(6) unsigned NOT NULL auto_increment, title varchar(100) NOT NULL default '', description text NOT NULL, sequence decimal(6,4) NOT NULL default '0.0000', expire_date date default NULL, PRIMARY KEY (news_id)) TYPE=MyISAM;") or die("Error - database already exists! If you want to create a new database delete the old one first.");

$cDB->Query("CREATE TABLE " . DATABASE_UPLOADS . "( upload_id mediumint(6) unsigned NOT NULL auto_increment, upload_date timestamp(14) NOT NULL, title varchar(100) NOT NULL default '', type char(1) NOT NULL default '', filename varchar(100) default NULL, note varchar(100) default NULL, PRIMARY KEY (upload_id)) TYPE=MyISAM;") or die("Error - database already exists! If you want to create a new database delete the old one first.");


// Special admin account.
$city = DEFAULT_CITY;
$state = DEFAULT_STATE;
$postcode = DEFAULT_ZIP_CODE;
$country = DEFAULT_COUNTRY;
$date = strftime("%Y-%m-%d", time());

$cDB->Query("INSERT INTO " . DATABASE_MEMBERS . "(member_id, password, member_role, security_q, security_a, status, member_note, admin_note, join_date, expire_date, away_date, account_type, email_updates, balance) VALUES ('ADMIN','5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '9',NULL,NULL,'A',NULL,'Special account created during install. Ok to inactivate once an Admin Level 2 acct has been created.', '$date', NULL,NULL,'S',7,0.00);") or die("Error - Could not insert row into member table.");

$cDB->Query("INSERT INTO " . DATABASE_PERSONS . "(person_id, member_id, primary_member, directory_list, first_name, last_name, mid_name, dob, mother_mn, email, phone1_area, phone1_number, phone1_ext, phone2_area, phone2_number, phone2_ext, fax_area, fax_number, fax_ext, address_street1, address_street2, address_city, address_state_code, address_post_code, address_country) VALUES (1,'admin','Y','Y','Special Admin','Account',NULL,NULL,NULL, NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL, NULL, NULL, '$city', '$state', '$postcode','$country');") or die("Error - Could not insert row into person table.");


// System account.
if (defined("SYSTEM_ACCOUNT_ID")) {
    $cDB->Query("
        INSERT INTO " .
            DATABASE_MEMBERS . "(member_id, password, member_role, security_q,
                security_a, status, member_note, admin_note, join_date,
                expire_date, away_date, account_type, email_updates, balance)
            VALUES ('system', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', '0',
                NULL, NULL, 'A', NULL, 'System account created during install.',
                '$date', NULL, NULL, 'O', 7, 0.00)")
    or die("Error - Could not insert row into member table.");

    $system_account_id = SYSTEM_ACCOUNT_ID;
    $cDB->Query("
        INSERT INTO " .
            DATABASE_PERSONS . "(person_id, member_id, primary_member,
                directory_list, first_name, last_name, mid_name, dob, mother_mn,
                email, phone1_area, phone1_number, phone1_ext, phone2_area,
                phone2_number, phone2_ext, fax_area, fax_number, fax_ext,
                address_street1, address_street2, address_city,
                address_state_code, address_post_code, address_country)
            VALUES (2, '$system_account_id', 'Y', 'Y', 'system', 'system', NULL,
                NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL,
                NULL, NULL, NULL, NULL, '$city', '$state', '$postcode',
                '$country')")
    or die("Error - Could not insert row into person table.");
}


$cDB->Query("INSERT INTO " . DATABASE_CATEGORIES . "(parent_id, description) VALUES (null,'Arts & Crafts');") or die("Error - Could not insert row into categories table.");

$cDB->Query("INSERT INTO " . DATABASE_CATEGORIES . " (parent_id, description) VALUES (null,'Building Services');") or die("Error - Could not insert row into categories table.");

$cDB->Query("INSERT INTO " . DATABASE_CATEGORIES . " (parent_id, description) VALUES (null,'Business & Administration');") or die("Error - Could not insert row into categories table.");

$cDB->Query("INSERT INTO " . DATABASE_CATEGORIES . " (parent_id, description) VALUES (null,'Children & Childcare');") or die("Error - Could not insert row into categories table.");

$cDB->Query("INSERT INTO " . DATABASE_CATEGORIES . " (parent_id, description) VALUES (null,'Computers');") or die("Error - Could not insert row into categories table.");

$cDB->Query("INSERT INTO " . DATABASE_CATEGORIES . " (parent_id, description) VALUES (null,'Counseling & Therapy');") or die("Error - Could not insert row into categories table.");

$cDB->Query("INSERT INTO " . DATABASE_CATEGORIES . " (parent_id, description) VALUES (null,'Food');") or die("Error - Could not insert row into categories table.");

$cDB->Query("INSERT INTO " . DATABASE_CATEGORIES . " (parent_id, description) VALUES (null,'Gardening & Yard Work');") or die("Error - Could not insert row into categories table.");

$cDB->Query("INSERT INTO " . DATABASE_CATEGORIES . " (parent_id, description) VALUES (null,'Goods');") or die("Error - Could not insert row into categories table.");

$cDB->Query("INSERT INTO " . DATABASE_CATEGORIES . " (parent_id, description) VALUES (null,'Health & Personal');") or die("Error - Could not insert row into categories table.");

$cDB->Query("INSERT INTO " . DATABASE_CATEGORIES . " (parent_id, description) VALUES (null,'Household');") or die("Error - Could not insert row into categories table.");

$cDB->Query("INSERT INTO " . DATABASE_CATEGORIES . " (parent_id, description) VALUES (null,'Miscellaneous');") or die("Error - Could not insert row into categories table.");

$cDB->Query("INSERT INTO " . DATABASE_CATEGORIES . " (parent_id, description) VALUES (null,'Music & Entertainment');") or die("Error - Could not insert row into categories table.");

$cDB->Query("INSERT INTO " . DATABASE_CATEGORIES . " (parent_id, description) VALUES (null,'Pets');") or die("Error - Could not insert row into categories table.");

$cDB->Query("INSERT INTO " . DATABASE_CATEGORIES . " (parent_id, description) VALUES (null,'Sports & Recreation');") or die("Error - Could not insert row into categories table.");

$cDB->Query("INSERT INTO " . DATABASE_CATEGORIES . " (parent_id, description) VALUES (null,'Teaching');") or die("Error - Could not insert row into categories table.");

$cDB->Query("INSERT INTO " . DATABASE_CATEGORIES . " (parent_id, description) VALUES (null, 'Transportation');") or die("Error - Could not insert row into categories table.");

$cDB->Query("INSERT INTO " . DATABASE_CATEGORIES . " (parent_id, description) VALUES (null,'Freebies');") or die("Error - Could not insert row into categories table.");

$cDB->Query("CREATE TABLE " . DATABASE_SESSION . "(id CHAR(32) NOT NULL, data TEXT, ts TIMESTAMP, PRIMARY KEY(id), KEY(ts))") or
    die("Error - Cannot create session table.");
 
/* BEGIN upgrade to 0.4.0 */

$cDB->Query("ALTER TABLE `person` ADD `about_me` text") or die ("Error altering person table. Does the web user account have alter table permission?");

$cDB->Query("ALTER TABLE `person` ADD `age` varchar(20) default NULL") or die ("Error altering person table. Does the web user account have alter table permission?");

$cDB->Query("ALTER TABLE `person` ADD `sex` varchar(1) default NULL") or die ("Error altering person table. Does the web user account have alter table permission?");

$cDB->Query("ALTER TABLE `member` ADD `confirm_payments` int(1) default '0'") or die ("Error altering member table. Does the web user account have alter table permission?");

$cDB->Query("CREATE TABLE cdm_pages (
  id int(11) NOT NULL auto_increment,
  `date` int(30) default NULL,
  title varchar(255) default NULL,
  body text,
  active int(1) default '1',
  PRIMARY KEY  (id)
) ENGINE=MyISAM AUTO_INCREMENT=6;")
 or die("Error creating cdm_pages table.  Does the web user account have add table permission?");

$cDB->Query("CREATE TABLE trades_pending (
  id mediumint(8) unsigned NOT NULL auto_increment,
  trade_date timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  member_id_from varchar(15) NOT NULL default '',
  member_id_to varchar(15) NOT NULL default '',
  amount decimal(8,2) NOT NULL default '0.00',
  category smallint(4) unsigned NOT NULL default '0',
  description varchar(255) default NULL,
  typ varchar(1) default NULL,
  `status` varchar(1) default 'O',
  member_to_decision varchar(2) default '1',
  member_from_decision varchar(2) default '1',
  PRIMARY KEY  (id)
) ENGINE=MyISAM AUTO_INCREMENT=17")
	or die("Error creating trades_pending table.  Does the web user account have add table permission?");

/* END upgrade to 0.4.0 */

				
$p->DisplayPage("Database has been created. Click <A HREF=member_login.php>here</A> to login.");

?>
