<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'address',
        'phone',
        'password',
        'basic_salary',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function salaryIncrements()
    {
        return $this->hasMany(SalaryIncrement::class);
    }

    public function getSalaryAttribute()
    {
        return $this->getLastIncrementedSalary();
    }
    public function getLastIncrementedSalary()
    {
        $lastIncrement = $this->salaryIncrements()->latest()->first();

        // If there are no increments, fall back to the basic_salary in the User model
        return $lastIncrement ? $lastIncrement->new_salary : $this->basic_salary;
    }
    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

    public function advanceSalaries()
    {
        return $this->hasMany(AdvanceSalary::class);
    }

    public function getTotalAdvanceSalaryAttribute()
    {
        return $this->advanceSalaries()
            ->where('month', $this->month)
            ->sum('amount');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn(string $eventName) => "User has been {$eventName}")
            ->logOnlyDirty()
            ->logAll()
            ->setDescriptionForEvent(fn(string $eventName) => "User has been {$eventName}");
    }
}
