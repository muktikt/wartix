<?php
namespace App\Services;

class MaskService
{
    public static function email(string $email): string
    {
        [$local, $domain] = explode('@', $email);
        $masked = substr($local, 0, 2) . str_repeat('*', max(3, strlen($local) - 2));
        return $masked . '@' . $domain;
    }

    public static function phone(string $phone): string
    {
        return substr($phone, 0, 4) . str_repeat('*', 4) . substr($phone, -4);
    }

    public static function nik(string $nik): string
    {
        return substr($nik, 0, 4) . str_repeat('*', 8) . substr($nik, -4);
    }

    public static function username(string $username): string
    {
        return substr($username, 0, 2) . str_repeat('*', 3) . substr($username, -4);
    }
}