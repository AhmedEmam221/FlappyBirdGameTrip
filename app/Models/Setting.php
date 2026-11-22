<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    /**
     * Get a setting value by key. Values are stored as plain text and
     * interpreted according to their type.
     */
    public static function getValue($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        if (! $setting) {
            return $default;
        }

        $value = $setting->value;

        if ($setting->type === 'boolean') {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }

        if ($setting->type === 'json') {
            $decoded = json_decode($value, true);
            return is_null($decoded) ? $default : $decoded;
        }

        return $value;
    }

    /** Store a setting value. */
    public static function setValue($key, $value, $type = 'string')
    {
        if (is_bool($value)) {
            $value = $value ? '1' : '0';
            $type = 'boolean';
        } elseif (is_array($value) || is_object($value)) {
            $value = json_encode($value);
            $type = 'json';
        }

        $setting = static::firstOrCreate(['key' => $key]);
        $setting->value = $value;
        $setting->type = $type;
        $setting->save();

        return $setting;
    }
}
