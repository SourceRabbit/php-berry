<?php

class AES256Encryption
{

    private static string $OPENSSL_CIPHER_NAME = "aes-256-cbc"; //Name of OpenSSL Cipher 
    private static int $CIPHER_KEY_LEN = 32; // 32 bytes (256 bits)

    static function getRandomIV(): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()-=_+ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < AES256Encryption::$CIPHER_KEY_LEN; $i++)
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**

     * Encrypt data using AES Cipher (CBC) with 256 bit key
     * @param type $key - key to use should be 32 bytes long (256 bits)
     * @param type $data - data to encrypt
     * @return encrypted data in base64 encoding with iv attached at end after a :
     */
    static function encrypt(string $key, string $data): string
    {
        $iv = AES256Encryption::getRandomIV();

        if (strlen($key) < AES256Encryption::$CIPHER_KEY_LEN)
        {
            $key = str_pad($key, AES256Encryption::$CIPHER_KEY_LEN, "0"); //0 pad to len 32
        }
        else if (strlen($key) > AES256Encryption::$CIPHER_KEY_LEN)
        {
            $key = substr($str, 0, AES256Encryption::$CIPHER_KEY_LEN); //truncate to 32 bytes
        }

        $encodedEncryptedData = base64_encode(openssl_encrypt($data, AES256Encryption::$OPENSSL_CIPHER_NAME, $key, OPENSSL_RAW_DATA, $iv));
        $encodedIV = base64_encode($iv);
        $encryptedPayload = $encodedEncryptedData . ":" . $encodedIV;
        return $encryptedPayload;
    }

    /**
     * Decrypt data using AES Cipher (CBC) with 256 bit key
     * @param type $key - key to use should be 32 bytes long (256 bits)
     * @param type $data - data to be decrypted in base64 encoding with iv attached at the end after a :
     * @return decrypted data
     */
    static function decrypt(string $key, string $data): string
    {
        if (strlen($key) < AES256Encryption::$CIPHER_KEY_LEN)
        {
            $key = str_pad($key, AES256Encryption::$CIPHER_KEY_LEN, "0"); //0 pad to len 32
        }
        else if (strlen($key) > AES256Encryption::$CIPHER_KEY_LEN)
        {
            $key = substr($str, 0, AES256Encryption::$CIPHER_KEY_LEN); //truncate to 32 bytes
        }

        $parts = explode(':', $data); //Separate Encrypted data from iv.
        $decryptedData = openssl_decrypt(base64_decode($parts[0]), AES256Encryption::$OPENSSL_CIPHER_NAME, $key, OPENSSL_RAW_DATA, base64_decode($parts[1]));
        return $decryptedData;
    }

}

?>