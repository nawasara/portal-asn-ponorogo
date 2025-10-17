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