<?php

if (!function_exists('formatRibuan')) {
    /**
     * Format angka dengan pemisah ribuan menggunakan titik.
     *
     * @param  mixed  $number
     * @return string
     */
    function formatRibuan($number)
    {
        return number_format($number, 0, ',', '.');
    }
}
