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

    protected ?float $cachedTotalWaste = null;

    protected ?float $cachedTotalIncome = null;

    protected ?float $cachedTotalApprovedWithdrawal = null;

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
        return $this->cachedTotalWaste ??= (float) $this->wasteDeposits()->sum('weight');
    }

    public function totalIncome(): float
    {
        return $this->cachedTotalIncome ??= (float) $this->wasteDeposits()->sum('value');
    }

    public function totalApprovedWithdrawal(): float
    {
        return $this->cachedTotalApprovedWithdrawal ??= (float) $this->cashWithdrawals()->approved()->sum('amount');
    }

    public function availableBalance(): float
    {
        return max(0, $this->totalIncome() - $this->totalApprovedWithdrawal());
    }

    public static function findByNameOrAlias(string $name): ?self
    {
        $classroom = self::where('name', $name)->first();
        if ($classroom) {
            return $classroom;
        }

        $romanToArabic = [
            '/^X\s+/i' => '10 ',
            '/^XI\s+/i' => '11 ',
            '/^XII\s+/i' => '12 ',
        ];
        $arabicToRoman = [
            '/^10\s+/' => 'X ',
            '/^11\s+/' => 'XI ',
            '/^12\s+/' => 'XII ',
        ];

        foreach ($arabicToRoman as $pattern => $replacement) {
            if (preg_match($pattern, $name)) {
                $alias = preg_replace($pattern, $replacement, $name);
                $found = self::where('name', $alias)->first()
                    ?? self::where('name', 'like', $alias.'%')->first();
                if ($found) {
                    return $found;
                }
            }
        }

        foreach ($romanToArabic as $pattern => $replacement) {
            if (preg_match($pattern, $name)) {
                $alias = preg_replace($pattern, $replacement, $name);
                $found = self::where('name', $alias)->first()
                    ?? self::where('name', 'like', $alias.'%')->first();
                if ($found) {
                    return $found;
                }
            }
        }

        $clean = preg_replace('/\s+/', '', strtolower($name));

        return self::all()->first(function ($c) use ($clean) {
            $classClean = preg_replace('/\s+/', '', strtolower($c->name));

            return $classClean === $clean || str_starts_with($classClean, $clean);
        });
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
