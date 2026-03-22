<?php

namespace App\Http\Utils;

use Illuminate\Support\Facades\Crypt;

class IdEncryptor
{
    /**
     * Criptografa um ID de forma segura usando AES-256-CBC do Laravel.
     * Substitui base64_encode() com criptografia real.
     */
    public static function encrypt(mixed $value): string
    {
        return Crypt::encryptString((string) $value);
    }

    /**
     * Descriptografa um ID previamente criptografado.
     * Substitui base64_decode() com descriptografia real.
     */
    public static function decrypt(string $encryptedValue): string
    {
        return Crypt::decryptString($encryptedValue);
    }

    /**
     * Descriptografa e retorna como inteiro.
     */
    public static function decryptToInt(string $encryptedValue): int
    {
        return (int) self::decrypt($encryptedValue);
    }
}
