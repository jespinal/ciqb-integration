<?php
defined('BASEPATH') OR exit('No direct script access allowed');


if ( ! function_exists('get_secure_hash'))
{
    /**
     * This function generates a "secure" random hash based on data obtained
     * from the kernel's random number generator through the special file /dev/urandom
     * and encrypts it to a 256 bits hash using the SHA algorithm
     * 
     * @param integer $aiLength The length of the returned hash
     * @param integer $aiInput The amount of bytes to read from the urandom device
     * @return string 
     * @author Pavel Espinal
    */
    function get_secure_hash($aiLength = 64, $aiInput = 128){

        /* @var $lrHandler resource */
        $lrHandler  = fopen('/dev/urandom', 'r');

        $lsHash     = fgets($lrHandler, $aiInput);

        fclose($lrHandler);
        
        // Random madness sequence initiated...
        if (function_exists('mhash')) 
        {
            // mhash also returns binary, so we use bin2hex at the outermost place.
            $lsHash = bin2hex(mhash(MHASH_SHA256, $lsHash));
        } else {
            $lsHash = hash('sha256', bin2hex($lsHash));
        }

        // more randomness ?? (too much, maybe???)
        $lsHash = str_shuffle($lsHash);

        return substr($lsHash, 0, $aiLength);
    }
}