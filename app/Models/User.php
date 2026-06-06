<?php

namespace App\Models;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    protected $table = 'users';
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'moodle_password',
        'role',
        'acceso_admin',
        'acceso_virtual',
        'estado',
        'persona_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'acceso_admin' => 'boolean',
            'acceso_virtual' => 'boolean',
        ];
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function puedeAdmin(): bool
    {
        return (bool) $this->acceso_admin;
    }

    public function puedeVirtual(): bool
    {
        return (bool) $this->acceso_virtual;
    }

    public function tieneAmbosAccesos(): bool
    {
        return $this->puedeAdmin() && $this->puedeVirtual();
    }

    public function urlInicio(): string
    {
        if ($this->tieneAmbosAccesos()) {
            return route('seleccionar-acceso');
        }
        if ($this->puedeAdmin()) {
            return '/admin/dashboard';
        }
        if ($this->puedeVirtual()) {
            return '/virtual/dashboard';
        }
        return '/login';
    }
}
