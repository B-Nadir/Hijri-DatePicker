<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DatePickerController;

Route::get('/', [DatePickerController::class, 'index']);
