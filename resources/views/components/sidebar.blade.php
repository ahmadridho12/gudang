<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('home') }}" class="app-brand-link">
            <img src="{{ asset('pdam.png') }}" alt="{{ config('app.name') }}" width="35">
            <span class="app-brand-text demo text-black fw-bolder ms-2">{{ config('app.name') }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Home -->
        <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('home') ? 'active' : '' }}">
            <a href="{{ route('home') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="{{ __('menu.home') }}">{{ __('menu.home') }}</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __('menu.header.main_menu') }}</span>
        </li>

        <!-- grup inventory -->
        <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('inventory.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon bx bx-package"></i>
                <div data-i18n="{{ __('Barang') }}">{{ __('Barang') }}</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('inventory.stok.*') || \Illuminate\Support\Facades\Route::is('transaction.disposition.*') ? 'active' : '' }}">
                    <a href="{{ route('inventory.stok.index') }}" class="menu-link">
                        <div
                            data-i18n="{{ __('Stok Barang') }}">{{ __('Stok Barang') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('inventory.goods.*') || \Illuminate\Support\Facades\Route::is('transaction.disposition.*') ? 'active' : '' }}">
                    <a href="{{ route('inventory.goods.index') }}" class="menu-link">
                        <div
                            data-i18n="{{ __('List Barang') }}">{{ __('List Barang') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('inventory.kategori.*') || \Illuminate\Support\Facades\Route::is('transaction.disposition.*') ? 'active' : '' }}">
                    <a href="{{ route('inventory.kategori.index') }}" class="menu-link">
                        <div
                            data-i18n="{{ __('Kategori') }}">{{ __('Kategori') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('inventory.category.*') || \Illuminate\Support\Facades\Route::is('transaction.disposition.*') ? 'active' : '' }}">
                    <a href="{{ route('inventory.category.index') }}" class="menu-link">
                        <div
                            data-i18n="{{ __('Kelompok Barang') }}">{{ __('Kelompok Barang') }}</div>
                    </a>
                </li>
                
                
                
                
            </ul>
        </li>

    <!-- grup transaksi -->

        <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('transaksi.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-transfer-alt"></i>
                <div data-i18n="{{ __('Transaksi Barang') }}">{{ __('Transaksi Barang') }}</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('transaksi.barangmasuk.*') || \Illuminate\Support\Facades\Route::is('transaction.disposition.*') ? 'active' : '' }}">
                    <a href="{{ route('transaksi.barangmasuk.index') }}" class="menu-link">
                        <div
                            data-i18n="{{ __('Barang Masuk') }}">{{ __('Barang Masuk') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('transaksi.detailbarangmasuk.*') || \Illuminate\Support\Facades\Route::is('transaction.disposition.*') ? 'active' : '' }}">
                    <a href="{{ route('transaksi.detailbarangmasuk.index') }}" class="menu-link">
                        <div
                            data-i18n="{{ __('Detail Barang Masuk') }}">{{ __('Detail Barang Masuk') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('transaksi.barangkeluar.*') || \Illuminate\Support\Facades\Route::is('transaction.disposition.*') ? 'active' : '' }}">
                    <a href="{{ route('transaksi.barangkeluar.index') }}" class="menu-link">
                        <div
                            data-i18n="{{ __('Barang Keluar') }}">{{ __('Barang Keluar') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('transaksi.detailbarangkeluar.*') || \Illuminate\Support\Facades\Route::is('transaction.disposition.*') ? 'active' : '' }}">
                    <a href="{{ route('transaksi.detailbarangkeluar.index') }}" class="menu-link">
                        <div
                            data-i18n="{{ __('Detail Barang Keluar') }}">{{ __('Detail Barang Keluar') }}</div>
                    </a>
                </li>
               
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('transaksi.permintaan.*') || \Illuminate\Support\Facades\Route::is('transaction.disposition.*') ? 'active' : '' }}">
                    <a href="{{ route('transaksi.permintaan.index') }}" class="menu-link">
                        <div
                            data-i18n="{{ __('Permintaan') }}">{{ __('Permintaan') }}</div>
                    </a>
                </li>
                {{-- <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('transaksi.barangmasuk.*') || \Illuminate\Support\Facades\Route::is('transaction.disposition.*') ? 'active' : '' }}">
                    <a href="{{ route('transaksi.barangmasuk.index') }}" class="menu-link">
                        <div
                            data-i18n="{{ __('Barang Masuk') }}">{{ __('Barang Masuk') }}</div>
                    </a>
                </li> --}}
               
            </ul>
        </li>

        <!-- grup laporan -->
        <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('laporan.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="{{ __('Laporan') }}">{{ __('laporan') }}</div>
            </a>
            <ul class="menu-sub">
                
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('lapran.rekap.*') ? 'active' : '' }}">
                    <a href="{{ route('laporan.rekap.index') }}" class="menu-link">
                        <div data-i18n="{{ __('Rekap Laporan') }}">{{ __('Rekap Laporan') }}</div>
                    </a>
                </li>
                {{-- <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('lainnya.bagian.*') ? 'active' : '' }}">
                    <a href="{{ route('lainnya.bagian.index') }}" class="menu-link">
                        <div data-i18n="{{ __('Bagian') }}">{{ __('Bagian') }}</div>
                    </a>
                </li> --}}
               
            </ul>
        </li>

       
        {{-- <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('transaction.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-mail-send"></i>
                <div data-i18n="{{ __('menu.transaction.menu') }}">{{ __('menu.transaction.menu') }}</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('transaction.incoming.*') || \Illuminate\Support\Facades\Route::is('transaction.disposition.*') ? 'active' : '' }}">
                    <a href="{{ route('transaction.incoming.index') }}" class="menu-link">
                        <div
                            data-i18n="{{ __('menu.transaction.incoming_letter') }}">{{ __('menu.transaction.incoming_letter') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('transaction.outgoing.*') ? 'active' : '' }}">
                    <a href="{{ route('transaction.outgoing.index') }}" class="menu-link">
                        <div
                            data-i18n="{{ __('menu.transaction.outgoing_letter') }}">{{ __('menu.transaction.outgoing_letter') }}</div>
                    </a>
                </li>
            </ul>
        </li> --}}
        {{-- <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('agenda.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-book"></i>
                <div data-i18n="{{ __('menu.agenda.menu') }}">{{ __('menu.agenda.menu') }}</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('agenda.incoming') ? 'active' : '' }}">
                    <a href="{{ route('agenda.incoming') }}" class="menu-link">
                        <div
                            data-i18n="{{ __('menu.agenda.incoming_letter') }}">{{ __('menu.agenda.incoming_letter') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('agenda.outgoing') ? 'active' : '' }}">
                    <a href="{{ route('agenda.outgoing') }}" class="menu-link">
                        <div
                            data-i18n="{{ __('menu.agenda.outgoing_letter') }}">{{ __('menu.agenda.outgoing_letter') }}</div>
                    </a>
                </li>
            </ul>
        </li> --}}

        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __('menu.header.other_menu') }}</span>
        </li>
        
        <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('lainnya.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-grid-alt"></i>
                <div data-i18n="{{ __('Data Lainnya') }}">{{ __('Data Lainnya') }}</div>
            </a>
            <ul class="menu-sub">
                {{-- <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('lainnya.suplier.*') ? 'active' : '' }}">
                    <a href="{{ route('lainnya.suplier.index') }}" class="menu-link">
                        <div data-i18n="{{ __('Suplier') }}">{{ __('Suplier') }}</div>
                    </a>
                </li>
               
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('lainnya.cabang.*') ? 'active' : '' }}">
                    <a href="{{ route('lainnya.cabang.index') }}" class="menu-link">
                        <div data-i18n="{{ __('Unit') }}">{{ __('Unit') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('lainnya.surat.*') ? 'active' : '' }}">
                    <a href="{{ route('lainnya.surat.index') }}" class="menu-link">
                        <div data-i18n="{{ __('Surat') }}">{{ __('Surat') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('lainnya.setnomor.*') ? 'active' : '' }}">
                    <a href="{{ route('lainnya.setnomor.index') }}" class="menu-link">
                        <div data-i18n="{{ __('Set Nomor') }}">{{ __('Set Nomor') }}</div>
                    </a>
                </li> --}}
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('lainnya.bagian.*') ? 'active' : '' }}">
                    <a href="{{ route('lainnya.bagian.index') }}" class="menu-link">
                        <div data-i18n="{{ __('Bagian') }}">{{ __('Bagian') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('lainnya.satuan.*') ? 'active' : '' }}">
                    <a href="{{ route('lainnya.satuan.index') }}" class="menu-link">
                        <div data-i18n="{{ __('Satuan') }}">{{ __('Satuan') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('lainnya.ppn.*') ? 'active' : '' }}">
                    <a href="{{ route('lainnya.ppn.index') }}" class="menu-link">
                        <div data-i18n="{{ __('PPN') }}">{{ __('PPN') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('lainnya.suplierr.*') ? 'active' : '' }}">
                    <a href="{{ route('lainnya.suplierr.index') }}" class="menu-link">
                        <div data-i18n="{{ __('Suplier') }}">{{ __('Suplier') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('lainnya.tarif-air.*') ? 'active' : '' }}">
                    <a href="{{ route('lainnya.tarif-air.index') }}" class="menu-link">
                        <div data-i18n="{{ __('Tarif Air') }}">{{ __('Tarif Air') }}</div>
                    </a>
                </li>
               
            </ul>
        </li>

        {{-- <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('gallery.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-images"></i>
                <div data-i18n="{{ __('menu.gallery.menu') }}">{{ __('menu.gallery.menu') }}</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('gallery.incoming') ? 'active' : '' }}">
                    <a href="{{ route('gallery.incoming') }}" class="menu-link">
                        <div
                            data-i18n="{{ __('menu.gallery.incoming_letter') }}">{{ __('menu.gallery.incoming_letter') }}</div>
                    </a>
                </li>
                <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('gallery.outgoing') ? 'active' : '' }}">
                    <a href="{{ route('gallery.outgoing') }}" class="menu-link">
                        <div
                            data-i18n="{{ __('menu.gallery.outgoing_letter') }}">{{ __('menu.gallery.outgoing_letter') }}</div>
                    </a>
                </li>
            </ul>
        </li> --}}
        @if(auth()->check() && auth()->user()->role == 'admin')
        {{-- <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('reference.*') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-analyse"></i>
                    <div data-i18n="{{ __('menu.reference.menu') }}">{{ __('menu.reference.menu') }}</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('reference.classification.*') ? 'active' : '' }}">
                        <a href="{{ route('reference.classification.index') }}" class="menu-link">
                            <div
                                data-i18n="{{ __('menu.reference.classification') }}">{{ __('menu.reference.classification') }}</div>
                        </a>
                    </li>
                    <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('reference.status.*') ? 'active' : '' }}">
                        <a href="{{ route('reference.status.index') }}" class="menu-link">
                            <div data-i18n="{{ __('menu.reference.status') }}">{{ __('menu.reference.status') }}</div>
                        </a>
                    </li>
                </ul>
            </li> --}}
            <!-- User Management -->
            <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('user.*') ? 'active' : '' }}">
                <a href="{{ route('user.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user-pin"></i>
                    <div data-i18n="{{ __('menu.users') }}">{{ __('menu.users') }}</div>
                </a>
            </li>
            {{-- <li class="menu-item {{ \Illuminate\Support\Facades\Route::is('user.*') ? 'active' : '' }}">
                <a href="{{ route('user.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-file"></i>
                    <div data-i18n="{{ __('menu.users') }}">{{ __('Arsip') }}</div>
                </a>
            </li> --}}
        @endif
    </ul>
</aside>
