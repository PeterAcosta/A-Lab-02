<?php
class c_crypto{
private $crypto_key = 'K34AuTgD4VxÑ';
private $crypto_method = 'aes128';
public function encrypt($text){
$ivSize = openssl_cipher_iv_length($this->crypto_method);
$iv = openssl_random_pseudo_bytes($ivSize);
$encrypted = openssl_encrypt($text, $this->crypto_method, $this->crypto_key, OPENSSL_RAW_DATA, $iv);
$encrypted = base64_encode($iv . $encrypted);
return $encrypted;}
public function decrypt($encrypted){
$encrypted = base64_decode($encrypted);
$ivSize = openssl_cipher_iv_length($this->crypto_method);
$iv = substr($encrypted, 0, $ivSize);
$decrypted = openssl_decrypt(substr($encrypted, $ivSize), $this->crypto_method, $this->crypto_key, OPENSSL_RAW_DATA, $iv);
return $decrypted;}}