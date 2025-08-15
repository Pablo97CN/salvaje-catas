<?php
use Illuminate\Support\Facades\Route;

Route::get('health', function () {
    return ['ok' => true, 'ts' => now()->toISOString()];
});