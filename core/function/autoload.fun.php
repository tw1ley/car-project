<?php

# ======================================================================================================= #

/**
 * Declaration of autoload function
 *
 */

function autoloadFunction($class) {
    $file = '';
    $preg = array(
        'interface' => 'App\\I\\',
        'abstract' => 'App\\A\\',
        'class' => 'App\\C\\',
        'exception' => 'App\\E\\',
        'controller' => 'App\\Controller\\',
        'model' => 'App\\Model\\'
    );

    foreach ($preg as $type => $namespace) {
        if (preg_match('/^'.preg_quote($namespace).'/', $class)) {
            $file = str_replace($namespace, '', $class);
            break;
        }
    }

    if ($type && $file) {
        switch ($type) {
            case 'interface'  : require_once INT_DIR.$file.INT_EXT; break;
            case 'abstract'   : require_once ABS_DIR.$file.ABS_EXT; break;
            case 'class'      : require_once CLASS_DIR.$file.CLASS_EXT; break;
            case 'exception'  : require_once EXC_DIR.$file.EXC_EXT; break;
            case 'controller' : require_once CTRL_DIR.$file.CTRL_EXT; break;
            case 'model'      : require_once MDL_DIR.$file.MDL_EXT; break;
			default           : debug($class);
        }
    }
}

/**
 * Set autoload function
 *
 */

spl_autoload_register("autoloadFunction");
