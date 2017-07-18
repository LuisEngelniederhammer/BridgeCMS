<?php
session_start ();
require_once 'system/core.class.php';
require_once 'system/stylemgr.class.php';
require_once 'system/pagemgr.class.php';
require_once 'system/menumgr.class.php';
require_once 'system/template.controller.class.php';

use system\styleManager;
use system\pageManager;
use system\Core;

$styleMgr = new styleManager ();
$pageMgr = pageManager::init ();
// FIXME
if (empty ( $_SERVER ['QUERY_STRING'] ))
{
	Core::setURL ( SYSTEM_ROOT_HOST . '/index.php?' . $pageMgr->getDefaultPageID () );
	die ();
}
else
{
	$thisPage = $pageMgr->getPage ( $_SERVER ['QUERY_STRING'] );
}
?>
<html>
<head>

<?php
$styleMgr->parseStyle ( false );
echo $thisPage ['head'];
?>
</head>
<body>
<?php 
$pageTpl = new system\TemplateController('debug');
?>
</body>

</html>
