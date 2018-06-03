<?php

# ======================================================================================================= #

function genUrlKey($id, $string, $table) {
    $key = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower($string));

    if (dbOne("SELECT `url` FROM `".$table."` WHERE `url` = ?", array($key))) {
        return genUrlKey($id, $id.' '.$string, $table);
    } else {
        return $key;
    }
}

function locationBase() {
    return (!empty($_SERVER['HTTPS']) ? 'https://' : 'http://').$_SERVER['HTTP_HOST'];
}

function locationUrl() {
    return locationBase().explode('?', $_SERVER['REQUEST_URI'])[0];
}

function locationHref() {
    $explode = explode('?', $_SERVER['REQUEST_URI'])[1];
    return $explode ? $explode : '';
}

/**
 * Simple debug function
 *
 */

function debug($data) {
    if (DEBUG) {
        echo '<pre style="width:100%;padding:5px 15px;background:#eee;border:#ddd 2px solid;overflow:auto;box-sizing:border-box;" >';
        highlight_string("<?php\n\$data = " . var_export($data, true) . ";\n?>");
        echo '</pre>';
    }
}

/**
 * Function to load config data
 *
 */

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
