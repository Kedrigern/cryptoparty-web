<?php namespace Cryptoparty;
/**
 * @author Ondřej Profant
 */

class Helpers extends \Nette\Object
{
    public static function Filetype($f) {
        $filetypes = array('epub', 'odp', 'pdf', 'pptx', 'tex', 'txt', 'unknown', 'youtube', 'zip', 'rar', 'archive', 'tar.gz', 'gz', 'tar');

        if( in_array($f, $filetypes ) ) {
            return $f;
        } else {
            return 'unknown';
        }
    }

}