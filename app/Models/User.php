<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

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
        $lastIncrement = $this->salaryIncrements()->latest('increment_date')->first();

        // If there are no increments, fall back to the basic_salary in the User model
        return $lastIncrement ? $lastIncrement->new_salary : $this->basic_salary;
    }
    public function payrolls()
    {
        return $this->hasMany(Payroll::class);
    }

}
