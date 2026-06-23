<?php

function mask_phone(string $phone, int $keepStart = 4, int $keepEnd = 2, int|null $fixedStars = null, string $maskChar = '*'): string {
    $len = strlen($phone);
    if ($len <= $keepStart + $keepEnd) return $phone; // terlalu pendek, kembalikan apa adanya

    if (is_int($fixedStars)) {
        $mask = str_repeat($maskChar, max(0, $fixedStars));
    } else {
        $maskLen = $len - $keepStart - $keepEnd;
        $mask = str_repeat($maskChar, max(1, $maskLen));
    }

    return substr($phone, 0, $keepStart) . $mask . substr($phone, -$keepEnd);
}

/**
 * Samarkan alamat email: tampilkan beberapa huruf depan local-part, sisanya
 * di-mask, domain tetap utuh. Contoh: "budi.santoso@gmail.com" -> "bu****@gmail.com".
 */
function mask_email(string $email, int $keepStart = 2, string $maskChar = '*'): string {
    $at = strpos($email, '@');
    if ($at === false) {
        // Bukan email valid — tampilkan beberapa huruf depan lalu mask sisanya.
        $keep = min($keepStart, strlen($email));
        return substr($email, 0, $keep) . str_repeat($maskChar, max(1, strlen($email) - $keep));
    }

    $local  = substr($email, 0, $at);
    $domain = substr($email, $at); // termasuk '@'

    $keep = min($keepStart, strlen($local));
    $maskLen = max(1, strlen($local) - $keep);

    return substr($local, 0, $keep) . str_repeat($maskChar, $maskLen) . $domain;
}