<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disponibilidad extends Model
{
    use HasFactory;

    protected $table = 'disponibilidades';

    protected $fillable = [
        'fecha',
        'abierta',
        'nota',
    ];

    protected $casts = [
        'fecha'  => 'date',
        'abierta'=> 'boolean',
    ];

    // ---------------------------
    // Relación con Reserva (por fecha)
    // Un día tiene 0..1 reservas; se enlaza por la columna 'fecha' (no por id).
    // ---------------------------
    public function reserva()
    {
        return $this->hasOne(Reserva::class, 'fecha', 'fecha');
    }

    // ---------------------------
    // Atributo calculado: ¿está disponible?
    // disponible = (abierta == true) && (no existe reserva en ese día)
    // ---------------------------
    protected $appends = ['esta_disponible'];

    public function getEstaDisponibleAttribute(): bool
    {
        $hayReservaActiva = $this->relationLoaded('reserva')
            ? ($this->reserva !== null)
            : $this->reserva()->exists();

        return $this->abierta && !$tieneReserva;
    }

    // ---------------------------
    // Scope para listar solo días disponibles
    // ---------------------------
    public function scopeDisponibles($query)
    {
        return $query
            ->where('abierta', true)
            ->whereDoesntHave('reserva', function ($r) {
                $r->whereHas('estado', fn($e) => $e->where('codigo','!=','cancelada'));
            });
    }
}
