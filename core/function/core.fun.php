<?php

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

function debug($data) {
    echo '<pre style="width:100%;padding:5px 15px;background:#eee;border:#ddd 2px solid;overflow:auto;box-sizing:border-box;" >';
    highlight_string("<?php\n\$data = " . var_export($data, true) . ";\n?>");
    echo '</pre>';
}

function getConfig() {
    global $config;
    if ($steps = func_get_args()) {
        $file = array_shift($steps);
        if (empty($config[$file])) {
            if (file_exists(CONF_DIR.$file.CONF_EXT)) {
                require_once CONF_DIR.$file.CONF_EXT;
            } else {
                return false;
            }
        }
        if (!empty($config[$file])) {
            $pointer = &$config[$file];
            foreach ($steps as $step) {
                if (!empty($pointer[$step])) {
                    $pointer = &$pointer[$step];
                } else {
                    return false;
                }
            }
            return $pointer;
        }
    }
}

function dbConnect() {
    $config = getConfig('database');
    \App\Model\Database::connect($config['host'], $config['user'], $config['password'], $config['database']);
}

function dbArray($query, $parms = array()) {
    return \App\Model\Database::getArray($query, $parms);
}

function dbRow($query, $parms = array()) {
    return \App\Model\Database::getRow($query, $parms);
}

function dbOne($query, $parms = array()) {
    return \App\Model\Database::getOne($query, $parms);
}
