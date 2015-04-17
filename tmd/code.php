<?php


namespace tmd;


class code {
    public $key = '';

    public $cipher = MCRYPT_BLOWFISH;
    public $mode = MCRYPT_MODE_ECB;

    private function createIv()
    {
        $iv_size = mcrypt_get_iv_size($this->cipher, $this->mode);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        return $iv;
    }

    function enCrypt($pure_string) {
        $iv = $this->createIv();
        $encrypted_string = mcrypt_encrypt($this->cipher, $this->key, $pure_string, $this->mode, $iv);
        return self::enBase64($encrypted_string);
    }

    function deCrypt($encrypted_string) {
        $iv = $this->createIv();
        $encrypted_string = self::deBase64($encrypted_string);
        $decrypted_string = mcrypt_decrypt($this->cipher, $this->key, $encrypted_string, $this->mode, $iv);
        return $decrypted_string;
    }

    static function enBase64($str)
    {
        return strtr(base64_encode($str), '+/=', '-*!');
    }

    static function deBase64($str)
    {
        return base64_decode(strtr($str, '-*!', '+/='));
    }

}