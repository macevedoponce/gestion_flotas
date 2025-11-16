<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ceco extends Model
{
    use HasFactory;

    protected $table = 'cecos';

    protected $primaryKey = 'id_ceco';

    public $timestamps = true;

    protected $fillable = [
        'codigo_ceco',
        'descripcion_ceco',
        'responsable_id',
        'tipo_ceco',
    ];

    protected $casts = [
        'responsable_id' => 'integer',
    ];

    // ============================
    // RELACIONES
    // ============================

    // Usuario responsable del CECO
    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    // Proyectos asociados al CECO
    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'id_ceco');
    }
}
