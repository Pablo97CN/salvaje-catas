<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // para auth
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // para tokens de Sanctum

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Tabla explícita (no usamos "users").
     */
    protected $table = 'usuarios';

    /**
     * Campos asignables en mass-assignment.
     */
    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'password',
        'rol', // 'admin' | 'cliente'
    ];

    /**
     * Campos ocultos al serializar a array/JSON.
     */
    protected $hidden = [
        'password',
        'remember_token', // por si en el futuro habilitas "remember me"
    ];

    /**
     * Casts y helpers.
     * - email_verified_at se trata como DateTime.
     * - password => 'hashed' (Laravel hashea automáticamente al asignar).
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];
}