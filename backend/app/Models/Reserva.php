<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $table = 'reservas';

    protected $fillable = [
        'usuario_id',
        'servicio_id',
        'fecha',
        'personas',
        'alergias',
        'ubicacion',
        'estado_reserva_id',
    ];

    protected $casts = [
        'fecha'    => 'date',
        'personas' => 'integer',
    ];

    // -----------------------
    // Relaciones
    // -----------------------

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoReserva::class, 'estado_reserva_id');
    }

    // Relación por fecha con Disponibilidad (enlaza fecha↔fecha)
    public function disponibilidad()
    {
        return $this->belongsTo(Disponibilidad::class, 'fecha', 'fecha');
    }

    // -----------------------
    // Scopes útiles
    // -----------------------

    public function scopeDelDia($query, $fecha)
    {
        return $query->whereDate('fecha', $fecha);
    }
}



