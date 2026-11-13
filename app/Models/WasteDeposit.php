<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WasteDeposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'classroom_id',
        'student_id',
        'waste_price_id',
        'waste_type',
        'weight',
        'value',
        'points',
        'validator_id',
        'validator_name',
        'deposit_date',
        'note',
    ];

    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
            'value' => 'decimal:2',
            'points' => 'integer',
            'deposit_date' => 'date',
        ];
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function wastePrice(): BelongsTo
    {
        return $this->belongsTo(WastePrice::class);
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validator_id');
    }
}
