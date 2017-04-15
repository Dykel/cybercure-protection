<?php namespace Cybercure\Autoloader;
/*======================================================================*\
|| #################################################################### ||
|| #                This file is part of Cybercure                    # ||
|| #                          for  #RISK[Solutions]Maurice            # ||
|| # ---------------------------------------------------------------- # ||
|| #         Copyright © 2017 cybercure.ngrok.io. All Rights Reserved.# ||
|| #                                                                  # ||
|| # ----------     Cybercure IS AN OPENSOURCE SOFTWARE    ---------- # ||
|| # -------------------- https://cybercure.ngrok.io -------- ------- # ||
|| #################################################################### ||
\*======================================================================*/

function CybercureAutoload ($classname) {
    // if the class where already loaded. should not happen
    if (class_exists($classname)) {
        return true;
    }

    $path = str_replace(
            array('_', '\\'),
            '/',
            $classname
        ) . '.php';

    $fullpath = str_replace('lib/Cybercure', 'lib', plugin_dir_path( __FILE__ )) . $path;
    $fullpath = str_replace('Cybercure/Cybercure', 'Cybercure', $fullpath);


    if(file_exists($fullpath)) {
        include_once $fullpath;
        return true;
    }

    return false;
}

spl_autoload_register(__NAMESPACE__ . '\CybercureAutoload');
