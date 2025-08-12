<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'duracion_minutos',
        'precio',
        'activo',
        'image_path',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'precio' => 'decimal:2',
    ];

    // Si vas a servir archivos vía /api/files/{image_path}, expón un accessor:
    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? url('/api/files/'.$this->image_path) : null;
    }
}

