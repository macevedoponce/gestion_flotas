<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abastecimiento extends Model
{
    use HasFactory;

    protected $table = 'abastecimientos';
    protected $primaryKey = 'id_abastecimiento';

    protected $fillable = [
        'id_jornada',
        'id_conductor',
        'fecha',
        'km_reportado',
        'foto_tablero_antes',
        'foto_surtidor_cero',
        'foto_tablero_despues',
        'foto_surtidor_final',
        'foto_comprobante',
        'ubicacion',
        'estado_verificacion',
        'verificado_por',
        'fecha_verificacion',
        'km_validado',
        'litros_validado',
        'precio_total_validado',
        'codigo_comprobante',
        'observaciones',
    ];

    protected $casts = [
        'fecha'                => 'datetime',
        'fecha_verificacion'   => 'datetime',
        'ubicacion'            => 'array', // GeoJSON para APIs
        'km_reportado'         => 'decimal:2',
        'km_validado'          => 'decimal:2',
        'litros_validado'      => 'decimal:2',
        'precio_total_validado'=> 'decimal:2',
    ];

    // ============================
    // RELACIONES
    // ============================

    public function jornada()
    {
        return $this->belongsTo(Jornada::class, 'id_jornada');
    }

    public function conductor()
    {
        return $this->belongsTo(Conductor::class, 'id_conductor');
    }

    public function verificador()
    {
        return $this->belongsTo(User::class, 'verificado_por');
    }

    // ============================
    // MÉTODOS DE NEGOCIO
    // ============================

    // Determina si el abastecimiento está listo para ser verificado
    public function completo()
    {
        return $this->km_reportado !== null &&
               $this->foto_tablero_antes &&
               $this->foto_surtidor_cero &&
               $this->foto_tablero_despues &&
               $this->foto_surtidor_final &&
               $this->foto_comprobante;
    }

    // Aprobación del jefe de control
    public function aprobar(array $validacion)
    {
        $this->km_validado           = $validacion['km_validado'] ?? $this->km_reportado;
        $this->litros_validado       = $validacion['litros_validado'];
        $this->precio_total_validado = $validacion['precio_total_validado'];
        $this->codigo_comprobante    = $validacion['codigo_comprobante'] ?? null;
        $this->estado_verificacion   = 'APROBADO';
        $this->verificado_por        = auth()->id();
        $this->fecha_verificacion    = now();
        $this->save();
    }

    // Rechazo del jefe de control
    public function rechazar(string $observacion)
    {
        $this->estado_verificacion   = 'RECHAZADO';
        $this->observaciones         = $observacion;
        $this->verificado_por        = auth()->id();
        $this->fecha_verificacion    = now();
        $this->save();
    }

    // Verifica que el km sea coherente con el flujo de la jornada
    public function kmEsCoherente()
    {
        $inicio = $this->jornada?->reporteInicial?->km_inicial;
        $final  = $this->jornada?->reporteFinal?->km_final;

        if (!$inicio) return true;
        if ($this->km_reportado < $inicio) return false;
        if ($final && $this->km_reportado > $final) return false;

        return true;
    }
}
