<?php
	print_r($_GLOBALS['settings']);
	//load config, typecast to object for easy access
	$_GLOBALS['settings'] = (object) parse_ini_file($_GLOBALS['settings']->Folders['root'].'../config/config.ini', true);

	//include functions
	require $_GLOBALS['settings']->Folders['root'].'../lib/functions/getfarnellproducts.php';
	require $_GLOBALS['settings']->Folders['root'].'../lib/functions/getmouserproducts.php';
?>
<?php
	print_r($_POST);
?>