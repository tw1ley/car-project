<?php

session_start();

# ======================================================================================================= #

define('DIR_SEP'   , '/');
define('MAIN_DIR'  , __DIR__.DIR_SEP);
define('CORE_DIR'  , MAIN_DIR.'core'.DIR_SEP);

define('CTRL_DIR'  , MAIN_DIR.'controller'.DIR_SEP);           /* == */   define('CTRL_EXT'  , '.class.php');
define('MDL_DIR'   , MAIN_DIR.'model'.DIR_SEP);	               /* == */   define('MDL_EXT'   , '.class.php');
define('VIEW_DIR'  , MAIN_DIR.'view'.DIR_SEP);                 /* == */   define('VIEW_EXT'  , '.phtml');
define('ABS_DIR'   , CORE_DIR.'abstract'.DIR_SEP);             /* == */   define('ABS_EXT'   , '.class.php');
define('CLASS_DIR' , CORE_DIR.'class'.DIR_SEP); 			   /* == */   define('CLASS_EXT' , '.class.php');
define('EXC_DIR'   , CORE_DIR.'exception'.DIR_SEP); 		   /* == */   define('EXC_EXT'   , '.class.php');
define('INT_DIR'   , CORE_DIR.'interface'.DIR_SEP);	           /* == */   define('INT_EXT'   , '.class.php');

define('FUN_DIR'   , CORE_DIR.'function'.DIR_SEP);			   /* == */   define('FUN_EXT'    , '.fun.php');
define('CONF_DIR'  , CORE_DIR.'config'.DIR_SEP);			   /* == */   define('CONF_EXT'   , '.conf.php');

# ======================================================================================================= #

define('DEBUG', true);

if (DEBUG) {
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
} else {
	error_reporting(0);
}

# ======================================================================================================= #

require_once FUN_DIR.'core'.FUN_EXT;

spl_autoload_register("autoloadFunction");

# ======================================================================================================= #

dbConnect();

$router = new \App\Controller\RouterController();
$router->process(array($_SERVER['REQUEST_URI']));
$router->renderView();

exit();
