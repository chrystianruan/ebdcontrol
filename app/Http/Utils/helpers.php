<?php

use App\Http\Utils\IdEncryptor;

if (!function_exists('encryptId')) {
    /**
     * Criptografa um ID de forma segura (substitui base64_encode).
     */
    function encryptId(mixed $value): string
    {
        return IdEncryptor::encrypt($value);
    }
}

if (!function_exists('decryptId')) {
    /**
     * Descriptografa um ID (substitui base64_decode).
     */
    function decryptId(string $encryptedValue): string
    {
        return IdEncryptor::decrypt($encryptedValue);
    }
}

if (!function_exists('decryptIdToInt')) {
    /**
     * Descriptografa um ID e retorna como inteiro.
     */
    function decryptIdToInt(string $encryptedValue): int
    {
        return IdEncryptor::decryptToInt($encryptedValue);
    }
}

if (!function_exists('cacheBust')) {
    /**
     * Retorna o caminho do asset com query string de cache busting.
     * Ex: cacheBust('css/filtros.css') => '/css/filtros.css?v=1709395200'
     */
    function cacheBust(string $path): string
    {
        $fullPath = public_path($path);
        $version = file_exists($fullPath) ? filemtime($fullPath) : time();
        return '/' . ltrim($path, '/') . '?v=' . $version;
    }
}

