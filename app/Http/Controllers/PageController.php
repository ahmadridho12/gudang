<?php

namespace App\Http\Controllers;

use App\Enums\LetterType;
use App\Helpers\GeneralHelper;
use App\Http\Requests\UpdateConfigRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Attachment;
use App\Models\Barangkeluar;
use App\Models\Config;
use App\Models\Disposition;
use App\Models\Letter;
use App\Models\User;
use App\Models\Barangmasuk;
use App\Models\DetailBarangKeluar;
use App\Models\Permintaan;
use App\Models\Detailbarangmasuk;
use App\Models\Stok;
use App\Models\Barangg;
use App\Models\Detailstok;
use App\Models\Satuan;
use App\Models\Suplier;
use App\Models\Suplierr;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use JetBrains\PhpStorm\NoReturn;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests\UpdateUser; // Pastikan ini benar
// use App\Http\Requests\UpdateUserRequest; // Pastikan ini sesuai dengan nama kelas Anda
use Illuminate\Http\Request; // Pastikan ini diimpor
class PageController extends Controller
{
    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $todayIncomingLetter = Letter::incoming()->today()->count();
        $todayOutgoingLetter = Letter::outgoing()->today()->count();
        $todayDispositionLetter = Disposition::today()->count();
        $todayLetterTransaction = $todayIncomingLetter + $todayOutgoingLetter + $todayDispositionLetter;
        // Barang Masuk
    $todayDetailBarangMasuk = Detailbarangmasuk::whereDate('created_at', Carbon::today())
    ->sum('jumlah');

$yesterdayDetailBarangMasuk = Detailbarangmasuk::whereDate('created_at', Carbon::yesterday())
    ->sum('jumlah');

$percentageChange = $yesterdayDetailBarangMasuk > 0 
    ? (($todayDetailBarangMasuk - $yesterdayDetailBarangMasuk) / $yesterdayDetailBarangMasuk) * 100 
    : 0;

// Debug: Tampilkan semua record hari ini
// $todayDetailBarangKeluarRecords = DetailBarangKeluar::whereDate('created_at', Carbon::today())->get();
    
// Hitung manual
$todayDetailBarangKeluar = DetailBarangKeluar::whereDate('created_at', Carbon::today())
        ->sum('jumlah'); // Ini akan menjumlahkan kolom jumlah
        $yesterdayDetailBarangKeluar = DetailBarangKeluar::whereDate('created_at', Carbon::yesterday())
        ->sum('jumlah');
    
        $percentageChangekeluar = ($yesterdayDetailBarangMasuk > 0) 
        ? (($todayDetailBarangMasuk - $yesterdayDetailBarangMasuk) / $yesterdayDetailBarangMasuk) * 100 
        : 0;

// // Cek query SQL asli
// $query = DetailBarangKeluar::whereDate('created_at', Carbon::today())->toSql();
// Log::info('Query: ' . $query);

// // Tambahkan dd() untuk melihat detail lengkap
// dd([
//     'records' => $todayDetailBarangKeluarRecords,
//     'count' => $todayDetailBarangKeluar,
//     'today' => Carbon::today(),
//     'timezone' => date_default_timezone_get()
// ]);

        $todayBarangKeluar = Barangkeluar::today()->count();
        $yesterdayBarangKeluar = Barangkeluar::yesterday()->count();

        $todaypermintaan = Permintaan::today()->count();
        $yesterdayPermintaan = Permintaan::yesterday()->count();
        $todayDetailBarangKeluar = DetailBarangKeluar::today()->count();
        $yesterdayDetailBarangKeluar = DetailBarangKeluar::yesterday()->count();

        $todayDetailbarangmasuk = Detailbarangmasuk::today()->count();
        $yesterdayDetailbarangmasuk = Detailbarangmasuk::yesterday()->count();
        $todayTotalJumlahBarangMasuk = Detailbarangmasuk::whereDate('created_at', Carbon::today())->sum('jumlah');




        $yesterdayIncomingLetter = Letter::incoming()->yesterday()->count();
        $yesterdayOutgoingLetter = Letter::outgoing()->yesterday()->count();
        $yesterdayDispositionLetter = Disposition::yesterday()->count();
        $yesterdayLetterTransaction = $yesterdayIncomingLetter + $yesterdayOutgoingLetter + $yesterdayDispositionLetter;

        $lowStockItems = DB::table('barangg as b')
        ->select('b.id_barang', 'b.kode_barang', 'b.deskripsi', DB::raw('SUM(ds.qty_stok) as total_stok'))
        ->leftJoin('detail_stok as ds', 'b.id_barang', '=', 'ds.barang_id')
        ->groupBy('b.id_barang', 'b.kode_barang', 'b.deskripsi') // Pastikan semua kolom yang dipilih ada di sini
        ->having('total_stok', '<', 10)
        ->get();

        $topSellingItems = DB::table('detail_barang_keluar as dbk')
        ->select('b.kode_barang', 'b.deskripsi', DB::raw('SUM(dbk.jumlah) as total_terjual'))
        ->join('barangg as b', 'dbk.id_barang', '=', 'b.id_barang')
        ->whereMonth('dbk.created_at', '=', now()->subMonth()->month) // Ambil data bulan lalu
        ->groupBy('b.kode_barang', 'b.deskripsi')
        ->orderBy('total_terjual', 'desc')
        ->limit(5) // Ambil 5 barang terlaris
        ->get();


        $qtyStokPerJenis = DB::table('detail_stok as ds')
        ->join('barangg as b', 'ds.barang_id', '=', 'b.id_barang')
        ->join('jenis as j', 'b.id_jenis', '=', 'j.id') // Menghubungkan ke tabel jenis
        ->select('b.id_jenis', 'j.nama as nama_jenis', DB::raw('SUM(ds.qty_stok) as total_qty'))
        ->groupBy('b.id_jenis', 'j.nama')
        ->get();

    // Mengambil total kolom total per id_jenis
    $totalPerJenis = DB::table('detail_stok as ds')
        ->join('barangg as b', 'ds.barang_id', '=', 'b.id_barang')
        ->join('jenis as j', 'b.id_jenis', '=', 'j.id') // Menghubungkan ke tabel jenis
        ->select('b.id_jenis', 'j.nama as nama_jenis', DB::raw('SUM(ds.total) as total_uang'))
        ->groupBy('b.id_jenis', 'j.nama')
        ->get();

    // Menggabungkan hasil
    $combinedResults = [];
    foreach ($qtyStokPerJenis as $qty) {
        $combinedResults[$qty->id_jenis]['nama_jenis'] = $qty->nama_jenis;
        $combinedResults[$qty->id_jenis]['total_qty'] = $qty->total_qty;
    }

    foreach ($totalPerJenis as $total) {
        $combinedResults[$total->id_jenis]['total_uang'] = $total->total_uang;
    }

    // Menghitung total keseluruhan
    $grandTotalQty = array_sum(array_column($combinedResults, 'total_qty'));
    $grandTotalUang = array_sum(array_column($combinedResults, 'total_uang'));


            return view('pages.dashboard', [
            'greeting' => GeneralHelper::greeting(),
            'currentDate' => Carbon::now()->isoFormat('dddd, D MMMM YYYY'),
            'todayIncomingLetter' => $todayIncomingLetter,
            'todayOutgoingLetter' => $todayOutgoingLetter,
            'todayDispositionLetter' => $todayDispositionLetter,
            'todayLetterTransaction' => $todayLetterTransaction,
            'todayBarangKeluar' => $todayBarangKeluar,
            'yesterdayBarangKeluar' => $yesterdayBarangKeluar,
            'todaypermintaan' => $todaypermintaan,
            'yesterdayPermintaan' => $yesterdayPermintaan,
            'todayDetailbarangmasuk' => $todayDetailbarangmasuk,
            'yesterdayDetailbarangmasuk' => $yesterdayDetailbarangmasuk,
            'activeUser' => User::active()->count(),
            'DetailStok' => Detailstok::sum('qty_stok'),
            'suplier' => Suplierr::count(),
            'barang' => Barangg::count(),
            'Satuan' => Satuan::count(),
            'percentageIncomingLetter' => GeneralHelper::calculateChangePercentage($todayDetailbarangmasuk, $yesterdayDetailbarangmasuk),
            'percentageOutgoingLetter' => GeneralHelper::calculateChangePercentage($yesterdayOutgoingLetter, $todayOutgoingLetter),
            'percentageDispositionLetter' => GeneralHelper::calculateChangePercentage($yesterdayDispositionLetter, $todayDispositionLetter),
            'percentageLetterTransaction' => GeneralHelper::calculateChangePercentage($yesterdayLetterTransaction, $todayLetterTransaction),
            'lowStockItems' => $lowStockItems, // Tambahkan variabel lowStockItems
            'topSellingItems' => $topSellingItems, // Tambahkan variabel barang terlaris
            'barangPerJenis' => $combinedResults, // Tambahkan variabel barangPerJenis
            'grandTotalQty' => $grandTotalQty, // Total qty
            'grandTotalUang' => $grandTotalUang, // Total uang
            'todayDetailBarangMasuk' => $todayDetailBarangMasuk,
            'percentageChange' => round($percentageChange, 2),
            'todayDetailBarangKeluar' => $todayDetailBarangKeluar,
            'percentageChangekeluar' =>  round($percentageChangekeluar, 2),

        ]);
    }

    /**
     * @param Request $request
     * @return View
     */
    public function profile(Request $request): View
    {
        return view('pages.profile', [
            'data' => auth()->user(),
        ]);
    }

    /**
     * @param UpdateUserRequest $request
     * @return RedirectResponse
     */
    public function profileUpdate(UpdateUserRequest $request): RedirectResponse
    {
        try {
            $newProfile = $request->validated();
            if ($request->hasFile('profile_picture')) {
//               DELETE OLD PICTURE
                $oldPicture = auth()->user()->profile_picture;
                if (str_contains($oldPicture, '/storage/avatars/')) {
                    $url = parse_url($oldPicture, PHP_URL_PATH);
                    Storage::delete(str_replace('/storage', 'public', $url));
                }

//                UPLOAD NEW PICTURE
                $filename = time() .
                    '-' . $request->file('profile_picture')->getFilename() .
                    '.' . $request->file('profile_picture')->getClientOriginalExtension();
                $request->file('profile_picture')->storeAs('public/avatars', $filename);
                $newProfile['profile_picture'] = asset('storage/avatars/' . $filename);
            }
            auth()->user()->update($newProfile);
            return back()->with('success', __('menu.general.success'));
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    /**
     * @return RedirectResponse
     */
    public function deactivate(): RedirectResponse
    {
        try {
            auth()->user()->update(['is_active' => false]);
            Auth::logout();
            return back()->with('success', __('menu.general.success'));
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return View
     */
    public function settings(Request $request): View
    {
        return view('pages.setting', [
            'configs' => Config::all(),
        ]);
    }

    /**
     * @param UpdateConfigRequest $request
     * @return RedirectResponse
     */
    public function settingsUpdate(UpdateConfigRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            foreach ($request->validated() as $code => $value) {
                Config::where('code', $code)->update(['value' => $value]);
            }
            DB::commit();
            return back()->with('success', __('menu.general.success'));
        } catch (\Throwable $exception) {
            DB::rollBack();
            return back()->with('error', $exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function removeAttachment(Request $request): RedirectResponse
    {
        try {
            $attachment = Attachment::find($request->id);
            $oldPicture = $attachment->path_url;
            if (str_contains($oldPicture, '/storage/attachments/')) {
                $url = parse_url($oldPicture, PHP_URL_PATH);
                Storage::delete(str_replace('/storage', 'public', $url));
            }
            $attachment->delete();
            return back()->with('success', __('menu.general.success'));
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }
}
