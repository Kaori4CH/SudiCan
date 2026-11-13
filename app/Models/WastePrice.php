<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WastePrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unit',
        'price',
    ];

    protected $appends = [
        'updatedAt',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function wasteDeposits(): HasMany
    {
        return $this->hasMany(WasteDeposit::class);
    }

    public function getUpdatedAtAttribute(): string
    {
        $val = $this->attributes['updated_at'] ?? null;

        return $val ? Carbon::parse($val)->translatedFormat('d F Y') : '-';
    }
}
