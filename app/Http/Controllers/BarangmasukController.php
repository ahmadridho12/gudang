<?php

namespace App\Http\Controllers;

use App\Models\Barangmasuk;
use App\Models\Detailbarangmasuk;
use App\Models\Barangg;
use App\Models\Kategoribm;
use App\Models\Suplierr;
use App\Models\Kategori;
use App\Models\Stok;
use App\Models\Detailstok;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BarangmasukController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');
    // Ambil semua data barang masuk, dengan opsi pencarian
    $query = Barangmasuk::query();

    // Jika ada query pencarian, tambahkan filter
     // Jika ada parameter pencarian, tambahkan kondisi WHERE
     if ($search) {
        $query->where('jumlah', 'like', '%' . $search . '%');
        // Ganti 'nama_kategori' dengan nama kolom yang sesuai di tabel Anda
    }
    
    $query->orderBy('created_at', 'desc');

    // Ambil data barang masuk dengan paginasi
    $BarangmasukList = $query->with('suplier') // Pastikan ada relasi dengan model Suplierr
                              ->orderBy('created_at', 'desc') // Urutkan berdasarkan tanggal
                              ->paginate(20); // Ganti 10 dengan jumlah data per halaman yang diinginkan
    return view('pages.transaksi.barangmasuk.index', [
            'BarangmasukList' => $BarangmasukList,
            'search' => $search,
        ]);
    // return view('pages.transaksi.barangmasuk.index', compact('BarangmasukList')); // Sesuaikan dengan huruf besar "B"
    }

    public function create()
    {
        $suplierr = Suplierr::all();
        $barangg = Barangg::all();
        $kategoribm = Kategoribm::all();

        return view('pages.transaksi.barangmasuk.add', compact('suplierr', 'barangg', 'kategoribm'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'suplier_id' => 'required|exists:suplierr,id_suplier', // Pastikan nama tabel benar
            'barang' => 'required|array',
            'tgl_masuk' => 'required|date',
            'keterangan' => 'required|string',
        ]);

        // Generate no_transaksi otomatis
        $noTransaksi = $this->generateNoTransaksi();

        // Simpan data barang masuk
        $barangMasuk = Barangmasuk::create([
            'suplier_id' => $request->suplier_id,
            'no_transaksi' => $noTransaksi,
            'tgl_masuk' => date('Y-m-d'),
            'keterangan' => $request->keterangan
        ]);
        foreach ($request->barang as $barang) {
            $idBarang = $barang['id'] ?? null; // Pastikan ini mengambil id_barang dengan benar
            $jumlahBarang = $barang['jumlah'] ?? 0;
            $hargaSebelumPpn = $barang['harga_sebelum_ppn'] ?? 0;
            $kategoriPpnId = $barang['kategori_ppn_id'];
        
            // Access id_jenis from related tables
            $barangg = Barangg::with('jenis')->find($idBarang);
            $idJenis = $barangg ? $barangg->id_jenis : null;
        
            // Hitung PPN
            $kategoriPpn = Kategoribm::find($kategoriPpnId);
        
            // Pastikan kategori PPN ditemukan
            if (!$kategoriPpn) {
                return redirect()->back()->withErrors(['msg' => 'Kategori PPN tidak ditemukan.']);
            }
        
            // Ambil persentase PPN
            $ppnPersentase = $kategoriPpn->ppn; // Pastikan ini menggunakan kolom yang benar
        
            // Hitung nilai PPN
            $ppn = $hargaSebelumPpn * ($ppnPersentase / 100);
            $hargaSetelahPpn = $hargaSebelumPpn + $ppn;
            $totalSetelahPpn = $hargaSetelahPpn * $jumlahBarang;
        
            // Simpan detail barang masuk
            $detailBarangMasuk = Detailbarangmasuk::create([
                'barang_masuk_id' => $barangMasuk->id_masuk,
                'id_barang' => $idBarang,
                'id_jenis' => $idJenis,
                'jumlah' => $jumlahBarang,
                'harga_sebelum_ppn' => $hargaSebelumPpn,
                'kategori_ppn_id' => $kategoriPpnId,
                'harga_setelah_ppn' => $hargaSetelahPpn,
                'total_setelah_ppn' => $totalSetelahPpn,
            ]);
          // Menghitung total setelah PPN
          $totalSetelahPpn = $hargaSetelahPpn * $jumlahBarang;

          // Update stok barang
          $stok = Stok::where('id_barang', $idBarang)->first();

          if ($stok) {
              // Jika stok sudah ada, perbarui jumlahnya
              $stok->qty += $jumlahBarang;
              $stok->save();

              // Simpan detail stok (stok batch per detail barang masuk)
              Detailstok::create([
                  'stok_id' => $stok->id_stok,
                  'detailmasuk_id' => $detailBarangMasuk->id_detailmasuk,
                  'barang_id' => $idBarang,
                  'qty_stok' => $jumlahBarang,
                  'harga' => $hargaSetelahPpn, // Add this line here as well
                  'total' => $hargaSetelahPpn * $jumlahBarang,

              ]);

          } else {
              // Jika stok belum ada, buat stok baru
              $newStok = Stok::create([
                  'id_barang' => $idBarang,
                  'qty' => $jumlahBarang,
              ]);

              // Simpan detail stok dengan ID stok yang baru dibuat
              Detailstok::create([
                  'stok_id' => $newStok->id_stok,
                  'detailmasuk_id' => $detailBarangMasuk->id_detailmasuk,
                  'barang_id' => $idBarang,
                  'qty_stok' => $jumlahBarang,
                  'total' => $hargaSetelahPpn * $jumlahBarang,
                  'harga' => $hargaSetelahPpn,
              ]);
          }
      }

      return redirect()->route('transaksi.barangmasuk.index')->with('success', 'Barang masuk berhasil ditambahkan.');
  }

    private function generateNoTransaksi()
    {
        // Mengambil bulan dan tahun saat ini
        $bulan = date('m');
        $tahun = date('Y');
        $prefix = 'OP/PDAM';

        // Ambil transaksi terakhir dari database untuk bulan dan tahun yang sama
        $lastTransaction = BarangMasuk::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->orderBy('id_masuk', 'desc')
            ->first();

        // Menghitung nomor transaksi selanjutnya
        if ($lastTransaction) {
            // Mengambil nomor transaksi dan memisahkannya
            $lastNoTransaksi = $lastTransaction->no_transaksi;
            $lastNumber = (int)explode('/', $lastNoTransaksi)[0]; // Ambil nomor transaksi
            $nextNumber = str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT); // Tambah nomor
        } else {
            $nextNumber = '01'; // Jika tidak ada transaksi sebelumnya, mulai dari 01
        }

        return "{$nextNumber}/{$prefix}/{$bulan}/{$tahun}";
    }

    public function keluar(Request $request)
    {
    $request->validate([
        'barang_id' => 'required|exists:barangg,id_barang',
        'jumlah' => 'required|integer|min:1',
    ]);

    $barang = Barangg::find($request->barang_id);
    $stok = Stok::where('id_barang', $barang->id_barang)->first();

    if ($stok && $stok->qty >= $request->jumlah) {
        $stok->qty -= $request->jumlah; // Kurangi jumlah stok
        $stok->save();

        // Simpan detail pengeluaran barang sesuai kebutuhan
        // Misalnya, bisa disimpan di tabel lain untuk histori keluar barang
    } else {
        return redirect()->back()->withErrors(['msg' => 'Stok tidak mencukupi.']);
    }

    return redirect()->route('transaksi.barangkeluar.index')->with('success', 'Barang keluar berhasil.');
}
public function show($id_masuk)
    {
        // Mengambil permintaan dengan id yang diberikan, beserta relasinya
        $barangMasuk = Barangmasuk::with('detail',)
            ->findOrFail($id_masuk);
    
        return view('pages.transaksi.barangmasuk.show', compact('barangMasuk'));
    }

}