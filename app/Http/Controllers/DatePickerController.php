<?php

namespace App\Http\Controllers;

use App\Lib\Hijri;
use Illuminate\Http\Request;

class DatePickerController extends Controller
{
    public function index()
    {
        return view('datepicker', [
            'monthNames' => Hijri::MONTH_NAMES,
            'arabicMonthNames' => Hijri::ARABIC_MONTH_NAMES,
        ]);
    }
}
