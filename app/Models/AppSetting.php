<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function getValue(string $key, ?string $default = null): ?string
    {
        $value = static::query()->where('key', $key)->value('value');

        return $value ?? $default;
    }

    public static function setValue(string $key, ?string $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    public static function paymentDetails(): array
    {
        return [
            'account_number' => static::getValue('payment.account_number', '1234567890'),
            'account_name' => static::getValue('payment.account_name', 'R&A Auto Rentals'),
            'bank_name' => static::getValue('payment.bank_name', 'Commercial Bank'),
            'branch_name' => static::getValue('payment.branch_name', 'Galle Branch'),
            'help_text' => static::getValue(
                'payment.help_text',
                'Only online transfer or cash is available at the moment. Payment remains pending until settlement.'
            ),
        ];
    }
}

