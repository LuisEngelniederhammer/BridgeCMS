<?php
session_start ();
?>
<?php
/*
 *
 * user table -> id, uid,username,password,rmail,roles,register ip
 * role table -> id,roleID,name,permissions
 * system table -> api_port,_key,client
 * config table -> css_style,version
 * pages table ->id,uid,pageName,path,dropdown,restriction,_condition
 *
 */
require_once '../system/core.cfg.php';
use system\config;
use system\database;
echo "Connecting to database<br>";
$database = new PDO ( 'mysql:host=' . config::host . ';dbname=' . config::database . ';port=' . config::port, config::user, config::password );
echo "Connection successful<br>";
echo "Generating SQL Query Strings<br>";
$UserTable = "
CREATE TABLE " . database::users . " (
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
uid VARCHAR(255) NOT NULL,
username VARCHAR(255) NOT NULL,
password VARCHAR(255) NOT NULL,
email VARCHAR(255) NOT NULL,
roles VARCHAR(255) NOT NULL,
register_ip VARCHAR(255) NOT NULL,
register_date VARCHAR(255) NOT NULL,
warnings TEXT,
banned TINYINT(1) NOT NULL,
restrictions TEXT
)";
$RoleTable = "
CREATE TABLE " . database::roles . " (
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
roleID VARCHAR(255) NOT NULL,
name VARCHAR(255) NOT NULL,
permissions TEXT
)";
$SystemTable = "
CREATE TABLE " . database::system . " (
api_port TEXT NOT NULL,
_key TEXT NOT NULL,
client TEXT NOT NULL
)";
$ConfigTable = "
CREATE TABLE " . database::cfg . " (
css_style TEXT NOT NULL,
version TEXT NOT NULL,
needsUpdate VARCHAR(255) NOT NULL
)";
$PagesTable = "
CREATE TABLE " . database::pages . " (
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
uid TEXT NOT NULL,
pageName TEXT NOT NULL,
dropdown TEXT ,
restriction TEXT ,
_condition TEXT ,
active TINYINT(1) NOT NULL,
_default TINYINT(1) NOT NULL,
head TEXT ,
body TEXT 
)";

$BackendPagesTable = "
CREATE TABLE " . database::backendPages . " (
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
pageName TEXT NOT NULL,
restriction TEXT,
filename TEXT NOT NULL
)";

$BansTable = "
CREATE TABLE " . database::bans . " (
id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
type TEXT NOT NULL,
content TEXT NOT NULL,
reason TEXT
)";
echo "SQL Query String Generation complete<br>";
echo "Generating Tables in Database<br>";
$database->query ( $UserTable );
$database->query ( $RoleTable );
$database->query ( $SystemTable );
$database->query ( $ConfigTable );
$database->query ( $PagesTable );
$database->query ( $BansTable );
$database->query ( $BackendPagesTable );
echo "done<br>";
echo "Finishing...<br>";
$file = 'index_new.php';
$newfile = '../index.php';
if (! copy ( $file, $newfile ))
{
	echo "copy $file schlug fehl...\n";
}
exit ();
?>