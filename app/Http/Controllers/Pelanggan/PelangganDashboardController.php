<?php

namespace App\Http\Controllers\Pelanggan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PelangganDashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->reservasis()->with('lapangan')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reservasis = $query->paginate(10)->withQueryString();

        return view('pelanggan.dashboard', compact('reservasis'));
    }
}