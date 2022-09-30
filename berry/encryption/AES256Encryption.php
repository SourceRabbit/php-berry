<?php

/*
  MIT License

  Copyright (c) 2022 Nikos Siatras

  Permission is hereby granted, free of charge, to any person obtaining a copy
  of this software and associated documentation files (the "Software"), to deal
  in the Software without restriction, including without limitation the rights
  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
  copies of the Software, and to permit persons to whom the Software is
  furnished to do so, subject to the following conditions:

  The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.

  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
  SOFTWARE.
 */

class AES256Encryption
{
    private static string $OPENSSL_CIPHER_NAME = "aes-256-cbc"; //Name of OpenSSL Cipher 
    private static int $CIPHER_KEY_LEN = 32; // 32 bytes (256 bits)

    static function getRandomIV()
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
    static function encrypt(string $key, string $data)
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
    static function decrypt(string $key, string $data)
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