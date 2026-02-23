<?php

use App\Models\SysParam;

if (!function_exists('sys_param')) {
    function sys_param(string $code, $default = null)
    {
        return SysParam::where('code', $code)->value('value') ?? $default;
    }
}
