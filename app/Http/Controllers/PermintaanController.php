<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permintaan;
use App\Models\Jenis;
use App\Models\Barangg;
use App\Models\DetailPermintaan;
use App\Models\User;
use App\Models\Bagian;
use App\Models\Stok;
use App\Models\DetailBarangKeluar;
use App\Models\BarangKeluar;
use App\Models\Detailstok;
use App\Models\DetailBarangMasuk;
use App\Models\Kategori;
use App\Models\Tipe;



use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\View\View;


class PermintaanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search'); // Jika ada pencarian
        $data = Permintaan::with(['user', 'bagiann', 'tipe']) // Pastikan relasi sudah benar
            ->when($search, function($query) use ($search) {
                return $query->where('no_trans', 'like', '%' . $search . '%')
                             ->orWhere('keterangan', 'like', '%' . $search . '%')
                             ->orWhereHas('bagiann', function($q) use ($search) {
                                 $q->where('nama_bagian', 'like', '%' . $search . '%');
                             })
                             ->orWhereHas('tipe', function($q) use ($search) {
                                 $q->where('nama_tipe', 'like', '%' . $search . '%'); // Pencarian di kolom 'nama' dari tabel 'jenis'
                             });
            })
            ->orderBy('created_at', 'desc') // Mengurutkan berdasarkan created_at
            ->paginate(25);
    
        $bagians = Bagian::all();
        $kategoris = Kategori::all();
        // $jenisa = Jenis::all();
        return view('pages.transaksi.permintaan.index', compact('data', 'search', 'bagians',  'kategoris'));
    }
    
    public function create()
{
    $user = User::all();
    $bagians = Bagian::all();
    $jenisa = Jenis::all();
    $barangs = Barangg::all();
    $kategoris = Kategori::all();
    

    // Debug: Pastikan nama tabel dan kolom benar
    $stokData = DB::table('detail_stok')
        ->select('barang_id', DB::raw('SUM(qty_stok) as total_stok'))
        ->groupBy('barang_id')
        ->get();

    // Log untuk debugging
    Log::info('Stok Data Raw:', $stokData->toArray());

    // Konversi ke array yang bisa digunakan di frontend
    $stokDataArray = $stokData->mapWithKeys(function($item) {
        return [$item->barang_id => $item->total_stok];
    });

    return view('pages.transaksi.permintaan.add', [
        'user' => $user,
        'bagians' => $bagians,
        'jenisa' => $jenisa,
        'barangs' => $barangs,
        'kategoris' => $kategoris,
        'stokData' => $stokDataArray
    ]);
}

    public function store(Request $request)
{
    try {
        // Tambahkan logging untuk debugging
        Log::info('Request Data:', $request->all());

        // Validasi input
        $validated = $request->validate([
            'keterangan' => 'required|string',
            'tgl_permintaan' => 'required|date',
            'nama_bagian' => 'required|string',
            'nama_tipe' => 'required|string',
            'barang' => 'required|array',
            'barang.*.id_barang' => 'required|exists:barangg,id_barang',
            'barang.*.qty' => 'required|integer|min:1',
        ]);

        // Proses selanjutnya...
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Tangkap error validasi
        return redirect()->back()
            ->withErrors($e->validator)
            ->withInput();
    } catch (\Exception $e) {
        // Tangkap error lainnya
        Log::error('Error in store method: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
    // Ambil ID bagian
    $bagianId = Bagian::where('nama_bagian', $request->nama_bagian)->value('id_bagian');
    if (!$bagianId) {
        return redirect()->back()->withErrors(['nama_bagian' => 'Bagian tidak ditemukan.']);
    }

    // Ambil ID Tipe menggunakan model Kategori
    $kategoriId = Kategori::where('nama_tipe', $request->nama_tipe)->value('id_tipe'); // Menggunakan model Kategori
    if (!$kategoriId) {
        return redirect()->back()->withErrors(['nama_tipe' => 'Tipe tidak ditemukan.']);
    }

    // Simpan data ke tabel permintaan
    $permintaan = new Permintaan();
    $permintaan->keterangan = $request->keterangan;
    $permintaan->tgl_permintaan = $request->tgl_permintaan;
    $permintaan->no_trans = $this->generateNoTransaksi('permintaan');
    $permintaan->bagian = $bagianId;
    $permintaan->tipe_id = $kategoriId; // Simpan ID tipe yang diperoleh
    $permintaan->id_user = auth()->user()->id;
    $permintaan->save();
    
        // Simpan detail permintaan (barang yang dipesan)
        foreach ($request->barang as $barang) {
            $detail = new DetailPermintaan();
            $detail->id_permintaan = $permintaan->id_permintaan; // Set id_permintaan di detail
            $detail->id_barang = $barang['id_barang'];
            $detail->qty = $barang['qty'];

            // Ambil harga dari DetailMasuk dan simpan di DetailPermintaan
            $detailMasuk = DetailBarangMasuk::where('id_barang', $barang['id_barang'])->first();

            // Pastikan harga diambil dari kolom yang benar
            if ($detailMasuk) {
                $detail->harga = $detailMasuk->harga_setelah_ppn; // Menggunakan harga_setelah_ppn
            } else {
                $detail->harga = 0; // Atur harga ke 0 jika tidak ada
            }

            $detail->save();
        }

        // Proses penyimpanan barang keluar
        $this->createBarangKeluar($request, $permintaan);

        return redirect()->route('transaksi.permintaan.index')->with('success', 'Permintaan berhasil disimpan!');
    }

    private function generateNoTransaksi($type)
    {
        $bulan = date('m');
        $tahun = date('Y');
        $prefix = $type === 'permintaan' ? 'DPPB/PDAM' : 'BPP/PDAM';

        // Mengambil transaksi terakhir dari database untuk bulan dan tahun yang sama
        $lastTransaction = $type === 'permintaan'
            ? Permintaan::whereYear('created_at', $tahun)
                ->whereMonth('created_at', $bulan)
                ->orderBy('id_permintaan', 'desc')
                ->first()
            : BarangKeluar::whereYear('tanggal_keluar', $tahun)
                ->whereMonth('tanggal_keluar', $bulan)
                ->orderBy('id_keluar', 'desc')
                ->first();

        // Menghitung nomor transaksi selanjutnya
        if ($lastTransaction) {
            $lastNoTransaksi = $lastTransaction->no_trans; // Ambil nomor transaksi
            $lastNumber = (int)explode('/', $lastNoTransaksi)[0]; // Ambil nomor transaksi
            $nextNumber = str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT); // Tambah nomor
        } else {
            $nextNumber = '01'; // Jika tidak ada transaksi sebelumnya, mulai dari 01
        }

        return "{$nextNumber}/{$prefix}/{$bulan}/{$tahun}";
    }

   
    private function reduceStockFIFO($id_barang, $qty)
{
    $detailStok = Detailstok::where('barang_id', $id_barang)
            ->orderBy('created_at') // Atur berdasarkan tanggal masuk
        ->get();

    $totalStokTersedia = $detailStok->sum('qty_stok'); // Total qty stok tersedia

    // Cek apakah stok cukup
    if ($totalStokTersedia < $qty) {
        throw new \Exception("Stok tidak cukup untuk memenuhi permintaan.");
    }

    $detailsToSave = []; // Untuk menyimpan detail barang keluar

    foreach ($detailStok as $detail) {
        if ($qty <= 0) {
            break;
        }

            // Log jumlah yang akan dikurangi
        Log::info("Detail Stok ID: {$detail->id_detailstok}, Qty Stok: {$detail->qty_stok}, Qty Permintaan: $qty");

        if ($detail->qty_stok >= $qty) {
                // Jika stok cukup, simpan detail barang keluar
            $detailsToSave[] = [
                'id_barang' => $id_barang,
                'jumlah' => $qty,
                    'harga' => $detail->harga_setelah_ppn, // Pastikan kunci ini konsisten
            ];

                // Kurangi jumlah di detail_stok
            $detail->qty_stok -= $qty;
            $detail->save();

                // Jika jumlah menjadi 0, hapus catatan dari detail_stok
                if ($detail->qty_stok == 0) {
                    $detail->delete();
                }
    
                $qty = 0; // Semua qty sudah terkurangi
        } else {
                // Jika jumlah di detail_stok kurang dari qty yang diminta
                // Simpan detail barang keluar untuk harga ini
            $detailsToSave[] = [
                'id_barang' => $id_barang,
                'jumlah' => $detail->qty_stok,
                    'harga' => $detail->harga_setelah_ppn, // Pastikan kunci ini konsisten
            ];

                $qty -= $detail->qty_stok; // Kurangi qty yang diminta
                $detail->delete(); // Hapus catatan dari detail_stok
            }
        }
    
        // Jika masih ada qty yang tersisa setelah semua detail_stok
        if ($qty > 0) {
            // Beri tahu berapa banyak qty yang berhasil dikurangi
            throw new \Exception("Stok tidak cukup untuk memenuhi permintaan. Total qty yang berhasil dikurangi.");
    }

    return $detailsToSave; // Kembalikan detail barang keluar
}

    
    private function createBarangKeluar($request, $permintaan)
{
    $barangKeluar = BarangKeluar::create([
        'no_transaksi' => str_replace('DPPB', 'BPP', $permintaan->no_trans),
        'id_permintaan' => $permintaan->id_permintaan,
        'tanggal_keluar' => now(),
    ]);

    DB::transaction(function () use ($request, $barangKeluar) {
        foreach ($request->barang as $barang) {
            // Memanggil fungsi reduceStockFIFO
            $detailsToSave = $this->reduceStockFIFO($barang['id_barang'], $barang['qty']);

            // Simpan detail barang keluar untuk setiap harga dan jumlah
            foreach ($detailsToSave as $detail) {
                DetailBarangKeluar::create([
                    'id_barangkeluar' => $barangKeluar->id_keluar,
                    'id_barang' => $detail['id_barang'],
                    'jumlah' => $detail['jumlah'],
                        'harga' => $detail['harga'], // Gunakan kunci yang benar
                        'total' => $detail['jumlah'] * $detail['harga'], // Hitung total dengan kunci yang benar
                ]);
            }
        }
    });
}

    public function show($id_permintaan)
    {
        // Mengambil permintaan dengan id yang diberikan, beserta relasinya
        $permintaan = Permintaan::with('detailpermintaan.barang.satuan', 'bagiann')
            ->findOrFail($id_permintaan);
    
        return view('pages.transaksi.permintaan.show', compact('permintaan'));
    }
        public function print($id_permintaan)
    {
        // Ambil data permintaan berdasarkan ID
        $permintaan = Permintaan::with(['user', 'bagiann', 'detailPermintaan.barang'])->findOrFail($id_permintaan);
        
        // Mengambil tanggal saat ini
        $currentDate = now()->format('d-m-Y');

        return view('pages.transaksi.permintaan.print', compact('permintaan', 'currentDate'));
    }




    public function update(Request $request, $id_permintaan)
    {

        // Validate input
        $request->validate([
            'keterangan' => 'required|string|max:255',
            'tgl_permintaan' => 'required',
            'bagian' => 'required',
            'tipe_id' => 'required',

            // 'id' => 'required',
        ]);

        $permintaan = Permintaan::find($id_permintaan);
        // $satuans = Satuan::all();

        $permintaan->update([
            'keterangan' => $request->keterangan,
            'tgl_permintaan' => $request->tgl_permintaan,
            'bagian' => $request->bagian,
            'tipe_id' => $request->tipe_id,
        ]);

        // Redirect or show a success message
        return redirect()->route('transaksi.permintaan.index')->with('success', 'Barang berhasil diperbarui!');


    } 

    public function getBagians()
    {
        $bagians = Bagian::all();
        return response()->json(['bagians' => $bagians]);
    }
    
    public function getKategoris()
    {
        $kategoris = Kategori::all();
        return response()->json(['kategoris' => $kategoris]);
    }


}