<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
    protected $fillable = [
        'total',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'integer',
        ];
    }

    public static function total(): int
    {
        return (int) (static::query()->value('total') ?? 0);
    }

    public static function incrementTotal(): void
    {
        $row = static::query()->first();

        if ($row) {
            $row->increment('total');

            return;
        }

        static::query()->create(['total' => 1]);
    }
}
