<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'homeroom_teacher',
        'members',
        'points',
    ];

    protected function casts(): array
    {
        return [
            'members' => 'integer',
            'points' => 'integer',
        ];
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function wasteDeposits(): HasMany
    {
        return $this->hasMany(WasteDeposit::class);
    }

    public function cashWithdrawals(): HasMany
    {
        return $this->hasMany(CashWithdrawal::class);
    }

    public function totalWaste(): float
    {
        return (float) $this->wasteDeposits()->sum('weight');
    }

    public function totalIncome(): float
    {
        return (float) $this->wasteDeposits()->sum('value');
    }

    public function totalApprovedWithdrawal(): float
    {
        return (float) $this->cashWithdrawals()->approved()->sum('amount');
    }

    public function availableBalance(): float
    {
        return max(0, $this->totalIncome() - $this->totalApprovedWithdrawal());
    }

    public function getTotalWasteAttribute(): string
    {
        return number_format($this->totalWaste(), 0, ',', '.').' kg';
    }

    public function getTotalCashAttribute(): string
    {
        return 'Rp '.number_format($this->availableBalance(), 0, ',', '.');
    }
}
