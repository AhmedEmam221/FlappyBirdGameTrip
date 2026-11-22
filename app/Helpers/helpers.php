<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    /**
     * Get a setting value by key, with an optional default.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        try {
            return Setting::getValue($key, $default);
        } catch (\Exception $e) {
            return $default;
        }
    }
}
