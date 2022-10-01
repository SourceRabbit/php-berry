<?php

class AES128Encryption
{

    private static string $OPENSSL_CIPHER_NAME = "aes-128-cbc"; //Name of OpenSSL Cipher 
    private static int $CIPHER_KEY_LEN = 16; // 16 bytes (128 bits)

    static function getRandomIV(): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()-=_+ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < AES128Encryption::$CIPHER_KEY_LEN; $i++)
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**

     * Encrypt data using AES Cipher (CBC) with 128 bit key
     * @param type $key - key to use should be 16 bytes long (128 bits)
     * @param type $data - data to encrypt
     * @return encrypted data in base64 encoding with iv attached at end after a :
     */
    static function encrypt(string $key, string $data): string
    {
        $iv = AES128Encryption::getRandomIV();

        if (strlen($key) < AES128Encryption::$CIPHER_KEY_LEN)
        {
            $key = str_pad($key, AES128Encryption::$CIPHER_KEY_LEN, "0"); //0 pad to len 16
        }
        else if (strlen($key) > AES128Encryption::$CIPHER_KEY_LEN)
        {
            $key = substr($str, 0, AES128Encryption::$CIPHER_KEY_LEN); //truncate to 16 bytes
        }

        $encodedEncryptedData = base64_encode(openssl_encrypt($data, AES128Encryption::$OPENSSL_CIPHER_NAME, $key, OPENSSL_RAW_DATA, $iv));
        $encodedIV = base64_encode($iv);
        $encryptedPayload = $encodedEncryptedData . ":" . $encodedIV;
        return $encryptedPayload;
    }

    /**
     * Decrypt data using AES Cipher (CBC) with 128 bit key
     * @param type $key - key to use should be 16 bytes long (128 bits)
     * @param type $data - data to be decrypted in base64 encoding with iv attached at the end after a :
     * @return decrypted data
     */
    static function decrypt(string $key, string $data): string
    {
        if (strlen($key) < AES128Encryption::$CIPHER_KEY_LEN)
        {
            $key = str_pad($key, AES128Encryption::$CIPHER_KEY_LEN, "0"); //0 pad to len 16
        }
        else if (strlen($key) > AES128Encryption::$CIPHER_KEY_LEN)
        {
            $key = substr($str, 0, AES128Encryption::$CIPHER_KEY_LEN); //truncate to 16 bytes
        }

        $parts = explode(':', $data); //Separate Encrypted data from iv.
        $decryptedData = openssl_decrypt(base64_decode($parts[0]), AES128Encryption::$OPENSSL_CIPHER_NAME, $key, OPENSSL_RAW_DATA, base64_decode($parts[1]));
        return $decryptedData;
    }

}

?>