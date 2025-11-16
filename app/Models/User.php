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

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'telefono',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'activo' => 'boolean',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Responsable de proyectos
    public function proyectosResponsable()
    {
        return $this->hasMany(Proyecto::class, 'responsable_id');
    }

    // Solicitudes creadas por el usuario
    public function solicitudes()
    {
        return $this->hasMany(SolicitudVehiculo::class, 'id_usuario_solicitante');
    }

    // Asignaciones en las que actuó como jefe de control
    public function asignacionesControl()
    {
        return $this->hasMany(AsignacionVehiculo::class, 'id_jefe_control');
    }

    // Devoluciones solicitadas por el usuario
    public function devolucionesSolicitadas()
    {
        return $this->hasMany(SolicitudDevolucion::class, 'id_usuario_solicitante');
    }

    // Devoluciones validadas por el usuario
    public function devolucionesValidadas()
    {
        return $this->hasMany(SolicitudDevolucion::class, 'validado_por');
    }

    // Abastecimientos validados por él
    public function abastecimientosValidados()
    {
        return $this->hasMany(Abastecimiento::class, 'verificado_por');
    }

    // ============================
    // SCOPES ÚTILES
    // ============================

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
