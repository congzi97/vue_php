<?php

class paw {

    /**
     * RSA 解密
     * @param $data
     * @return bool|string
     */
    public static function rsaDecrypt($data) {
        $data = base64_decode($data);
        $key_private = file_get_contents(CZ_BLOG_DIR.'/cert/priv.pem', 'r');
        if(!openssl_private_decrypt($data,$data,openssl_pkey_get_private($key_private))) {
            return false;
        }
        return $data;
    }
    /**
     * 判断密码是否正确
     * @param $mdPassStr
     * @param $pass
     * @param $myKey
     * @return bool|string
     */
    public static function decode($mdPassStr,$pass,$myKey){
        // 解密出 hash值
        $de1 = self::authcode(base64_decode($mdPassStr),'DECODE',md5($myKey));
        $de2 = self::Hash_Act($pass,'DECODE',$de1);
        return $de2;
    }
    /**
     * 密码加密
     * @param $pass
     * @param $myKey
     * @return string
     */
    public static function encode($pass,$myKey){
        $encodePass  = self::Hash_Act($pass,'ENCODE');
        $encodePass2 = self::authcode($encodePass,'ENCODE',md5($myKey));
        return base64_encode($encodePass2);
    }
    /*
     * password_hash(str, PASSWORD_DEFAULT) – 对密码加密.
     * password_verify(str,之前加密过的字符串) – 验证已经加密的密码，检验其hash字串是否一致.
     * password_needs_rehash(str, PASSWORD_DEFAULT) – 给密码重新加密.
     * password_get_info() – 返回加密算法的名称和一些相关信息.
     */
    public static function Hash_Act($str,$options = 'ENCODE',$encode = false) {
        switch ($options){
            case 'ENCODE':
                return password_hash($str, PASSWORD_DEFAULT);
                break;
            case 'DECODE':
                return password_verify($str,$encode);
                break;
        }
    }

    /**
     * 加密
     * @param $data
     * @param string $key
     * @return string
     */
    public static function encrypt($data, $key = '')
    {
        $key    =   md5( '' === $key ? KEY : $key);
        $x      =   0;
        $len    =   strlen($data);
        $l      =   strlen($key);
        $char = '';$str = '';
        for ($i = 0; $i < $len; $i++) {
            if ($x == $l)
            {
                $x = 0;
            }
            $char .= $key{$x};
            $x++;
        }
        for ($i = 0; $i < $len; $i++) {
            $str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
        }
        return base64_encode($str);
    }

    /**
     * 解密
     * @param $data
     * @param string $key
     * @return string
     */
    public static function decrypt($data, $key = '')
    {
        $key = md5( '' === $key ? KEY : $key);
        $x = 0;
        $data = base64_decode($data);
        $len = strlen($data);
        $l = strlen($key);
        $char = '';$str = '';
        for ($i = 0; $i < $len; $i++)
        {
            if ($x == $l)
            {
                $x = 0;
            }
            $char .= substr($key, $x, 1);
            $x++;
        }
        for ($i = 0; $i < $len; $i++)
        {
            if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1)))
            {
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            }
            else
            {
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        return $str;
    }
    /**
     * 加密/解密 函数
     * @param $string
     * @param string $operation  DECODE 解密
     * @param string $key
     * @param int $expiry
     * @return string
     */
    public static function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
        $ckey_length = 4;
        // 密匙
        $key = md5('' === $key ?  KEY : $key);
        // 密匙a会参与加解密
        $keya = md5(substr($key, 0, 16));
        // 密匙b会用来做数据完整性验证
        $keyb = md5(substr($key, 16, 16));
        // 密匙c用于变化生成的密文
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length):
            substr(md5(microtime()), -$ckey_length)) : '';
        // 参与运算的密匙
        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);
        // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，
//解密时会通过这个密匙验证数据完整性
        // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) :
            sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);
        $result = '';
        $box = range(0, 255);
        $rndkey = array();
        // 产生密匙簿
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }
        // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        // 核心加解密部分
        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            // 从密匙簿得出密匙进行异或，再转成字符
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if($operation == 'DECODE') {
            // 验证数据有效性，请看未加密明文的格式
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) &&
                substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
            // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
            return $keyc.str_replace('=', '', base64_encode($result));
        }
    }

}

