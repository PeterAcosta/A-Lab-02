<?php
/** ** *************************************************************************************************************************************
 * Class c_crypto
 * User: Peter Acosta
 * Date: Lunes 7 de Mayo de 2018
 * Clase para Encriptar/Desencriptar usando la libreria OpenSSL de PHP 7
 */


class c_crypto
{

    private $crypto_key = 'K34AuTgD4VxÑ';
    private $crypto_method = 'aes128';


    /** ** *************************************************************************************************
     * @example encrypt($text)
     * @since Lunes 7 de Mayo de 2018
     * @author Peter Acosta
     * Encripta un texto
     * @param string $text
     * @return string
     */
    public function encrypt($text)
    {
        $ivSize = openssl_cipher_iv_length($this->crypto_method);
        $iv = openssl_random_pseudo_bytes($ivSize);
        $encrypted = openssl_encrypt($text, $this->crypto_method, $this->crypto_key, OPENSSL_RAW_DATA, $iv);
        // For storage/transmission, we simply concatenate the IV and cipher text
        $encrypted = base64_encode($iv . $encrypted);
        return $encrypted;
    }

    /** ** *************************************************************************************************
     * @example encrypt($text)
     * @since Lunes 7 de Mayo de 2018
     * @author Peter Acosta
     * Desencripta un string previamente encripado con el metodo encrypt()
     * @param string $encrypted
     * @return string $decrypted
     */
    public function decrypt($encrypted)
    {
        $encrypted = base64_decode($encrypted);
        $ivSize = openssl_cipher_iv_length($this->crypto_method);
        $iv = substr($encrypted, 0, $ivSize);
        $decrypted = openssl_decrypt(substr($encrypted, $ivSize), $this->crypto_method, $this->crypto_key, OPENSSL_RAW_DATA, $iv);
        return $decrypted;
    }


}