<?php
namespace App\Http\Controllers\AdminPondok;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UangJajanController extends Controller
{
    // Ini adalah dashboard untuk Admin Uang Jajan
    public function dashboard()
    {
        return "Selamat Datang di Dashboard Pengelola Uang Jajan (Premium)";
    }
}