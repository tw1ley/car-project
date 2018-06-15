<?php

# ======================================================================================================= #

namespace App\M;

use \Imagick;

class File
{
    private const CATALOG = 'files/';
    private const PATH = MAIN_DIR.self::CATALOG;

    /**
     *
     *
     */
    public static function uploadFoto($table, $field, $id) {
        $table = strtolower($table);
        $field = strtolower($field);

        if (!empty($_FILES[$field])) {
            $file = &$_FILES[$field];
            $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            if (in_array($fileExt, array('jpg', 'jpeg', 'png'))) {
                $catalog = getConfig('files', $table, 'image', $field, 'dir');
                if ($catalog) {
                    $path_to = self::PATH.'image'.DIR_SEP.$catalog.DIR_SEP;
                    $name = getConfig('files', $table, 'image', $field, 'prefix');
                    if ($name) {
                        $newName = $name.$id.'.'.$fileExt;

                        if (move_uploaded_file($file['tmp_name'], $path_to.$newName)) {
                            dbQuery("UPDATE `".$table."` SET `".$field."` = :foto WHERE `id` = :id", array(
                                'foto' => $newName,
                                'id' => $id
                            ));
                            if ($size = getConfig('files', $table, 'image', $field, 'size')) {
                                $w = (int) explode('x', $size)[0];
                                $h = (int) explode('x', $size)[1];
                                //self::resizeImage($path_to.$newName, $w, $h);
                            }
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     *
     *
     */
    public static function deleteFoto($table, $field, $id) {
        $table = strtolower($table);
        $field = strtolower($field);

        $name = dbOne("SELECT `".$field."` FROM `".$table."` WHERE `id` = :id", array(
            'id' => $id
        ));
        if ($name) {
            $catalog = getConfig('files', $table, 'image', $field, 'dir');
            if ($catalog) {
                $path_to = self::PATH.'image'.DIR_SEP.$catalog.DIR_SEP.$name;
                if (file_exists($path_to)) {
                     @unlink($path_to);
                     dbQuery("UPDATE `".$table."` SET `".$field."` = '' WHERE `id` = :id", array(
                         'id' => $id
                     ));
                     return true;
                }
            }
        }
        return false;
    }

    /**
     *
     *
     */
    private static function resizeImage($file, $w, $h, $crop = FALSE) {
        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width-($width*abs($r-$w/$h)));
            } else {
                $height = ceil($height-($height*abs($r-$w/$h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w/$h > $r) {
                $newwidth = $h*$r;
                $newheight = $h;
            } else {
                $newheight = $w/$r;
                $newwidth = $w;
            }
        }

        $im = new Imagick($file);
        $im->resizeImage($newwidth,$newheight, Imagick::FILTER_LANCZOS, 0.9, true);
        $im->writeImage($file);

        return $dst;
    }
}
