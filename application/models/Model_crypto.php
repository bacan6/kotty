<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class Model_crypto extends CI_Model{
	private static $keySeed  = 'at50lusIPOSmyID9';
    private static $ivSeed = 'arisALyaNuar4f18';

    private static function getKey() {
        return hash('sha256', self::$keySeed, true); // 32-byte key
    }

    private static function getIV() {
        return md5(self::$ivSeed, true); // 16-byte IV
    }

    public static function encrypt($plainText) {
        $cipher = "AES-256-CBC";
        $encrypted = openssl_encrypt($plainText, $cipher, self::getKey(), OPENSSL_RAW_DATA, self::getIV());
        return base64_encode($encrypted);
    }

    public static function decrypt($base64Text) {
        $cipherText = base64_decode($base64Text);
        $cipher = "AES-256-CBC";
        return openssl_decrypt($cipherText, $cipher, self::getKey(), OPENSSL_RAW_DATA, self::getIV());
    }

}
