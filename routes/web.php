<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MusicController;
Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

require __DIR__.'/auth.php';
