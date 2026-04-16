<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\PaketKursusController;
use App\Http\Controllers\PenilaianSkillController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\RiwayatPembayaranController;
use App\Http\Controllers\LaporanController;

// Auth
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', fn() => redirect()->route('dashboard'));

// All authenticated users
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Peserta Only (Defined before Admin to prevent /peserta/{id} wildcard collision)
    Route::middleware('role:peserta')->prefix('peserta')->name('peserta.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\DashboardPesertaController::class, 'dashboard'])->name('dashboard');
        Route::get('/nilai', [\App\Http\Controllers\DashboardPesertaController::class, 'nilai'])->name('nilai');
        Route::get('/pembayaran', [\App\Http\Controllers\DashboardPesertaController::class, 'pembayaran'])->name('pembayaran');
    });

    // Admin only — Master Data & Management
    Route::middleware('role:admin')->group(function () {
        Route::resource('peserta', PesertaController::class)->except(['create', 'edit']);
        Route::get('peserta/{pesertum}/pdf', [PesertaController::class, 'exportPdf'])->name('peserta.pdf');

        Route::resource('paket-kursus', PaketKursusController::class)
            ->except(['create', 'edit', 'show'])
            ->parameters(['paket-kursus' => 'paketKursus']);

        Route::resource('penilaian-skill', PenilaianSkillController::class)->except(['create', 'edit', 'show']);
        Route::resource('pembayaran', PembayaranController::class)->except(['create', 'edit', 'show']);
        Route::get('pembayaran/{pembayaran}/download-bukti', [PembayaranController::class, 'downloadBukti'])->name('pembayaran.download-bukti');
        Route::get('/riwayat-pembayaran', [RiwayatPembayaranController::class, 'index'])->name('riwayat-pembayaran.index');

        // Laporan
        Route::get('/laporan/peserta', [LaporanController::class, 'peserta'])->name('laporan.peserta');
        Route::get('/laporan/peserta/export-pdf', [LaporanController::class, 'exportPdfPeserta'])->name('laporan.peserta.export-pdf');
        Route::get('/laporan/pendapatan', [LaporanController::class, 'pendapatan'])->name('laporan.pendapatan');
        Route::get('/laporan/pendapatan/export-pdf', [LaporanController::class, 'exportPdfPendapatan'])->name('laporan.pendapatan.export-pdf');
        Route::get('/laporan/pendapatan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.pendapatan.export-excel');
    });
});
