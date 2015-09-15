<?php
/**
 * Created by Mahedi.
 * User: RDF
 * Date: 1/13/15
 * Time: 10:56 AM
 * Description: 32-byte (64 character) hexadecimal encryption key. The same encryption key used to encrypt the data must be used to decrypt the data
 */
class Crypt {
// Encrypt Function
    public function mc_encrypt($encrypt, $key){
        $encrypt = serialize($encrypt);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
        $key = pack('H*', $key);
        $mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key), -32));
        $passCrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt.$mac, MCRYPT_MODE_CBC, $iv);
        $encoded = base64_encode($passCrypt).'|'.base64_encode($iv);
        return $encoded;
    }

// Decrypt Function
    public function mc_decrypt($decrypt, $key){
        $decrypt = explode('|', $decrypt.'|');
        $decoded = base64_decode($decrypt[0]);
        $iv = base64_decode($decrypt[1]);
        if(strlen($iv)!==mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)){ return false; }
        $key = pack('H*', $key);
        $decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));
        $mac = substr($decrypted, -64);
        $decrypted = substr($decrypted, 0, -64);
        $calCMac = hash_hmac('sha256', $decrypted, substr(bin2hex($key), -32));
        if($calCMac!==$mac){ return false; }
        $decrypted = unserialize($decrypted);
        return $decrypted;
    }

}



/***uses***/
/*
define('ENCRYPTION_KEY', 'd0a7e7997b6d5fcd55f4b5c32611b87cd923e88837b63bf2941ef819dc8ca282111');

echo '<h1>256-bit CBC Encryption Function</h1>';
echo '<h3>Key</h3>';
echo '<textarea cols="65" rows="5">'.ENCRYPTION_KEY.' </textarea>';

echo '<h2>Example #1: String Data</h2>';
$data = 'Welcome to Bangladesh';
$encrypted_data = mc_encrypt($data, ENCRYPTION_KEY);
echo '<strong>Input:</strong> <br/>';
echo '<textarea cols="65" rows="5">'.$data.' </textarea>';
echo '<br/><strong>Encrypted Data:</strong><br/>';
echo '<textarea cols="65" rows="5">'.$encrypted_data.' </textarea>';
echo '<br/><strong>Decrypted Data: </strong></br>';
echo '<textarea cols="65" rows="5">'.mc_decrypt($encrypted_data, ENCRYPTION_KEY).' </textarea>';


echo '<h2>Example #2: Non-String Data</h2>';
$data = array(
    'name', 'XYZ',
    'yeas' ,'2015'
);
$encrypted_data = mc_encrypt($data, ENCRYPTION_KEY);
echo '<strong>Input:</strong> <br/>';
echo var_dump($data);
echo '<br><br><strong>Encrypted Data:</strong><br/>';
echo '<textarea cols="65" rows="5">'.$encrypted_data.' </textarea>';
echo '<br><br/><strong>Decrypted Data: </strong></br>';
echo var_dump(mc_decrypt($encrypted_data, ENCRYPTION_KEY));*/
