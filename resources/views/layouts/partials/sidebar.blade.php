<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('home')}}" class="brand-link">
        <img src="{{ asset('images/logo1.png') }}" alt="POSKasir Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ auth()->user()->getAvatar() }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->getFullname() }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item has-treeview">
                    <a href="{{route('home')}}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-solid fa-wallet"></i>
                        <p>
                          POS
                          <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item has-treeview">
                            <a href="{{ route('cart.index') }}" class="nav-link {{ activeSegment('cart') }}">
                                <i class="nav-icon fas fa-solid fa-cash-register"></i>
                                <p>Kasir</p>
                            </a>
                        </li>

                        <li class="nav-item has-treeview">
                            <a href="{{ route('penjualans.index') }}" class="nav-link {{ activeSegment('penjualans') }}">
                                <i class="nav-icon fas fa-cart-plus"></i>
                                <p>Penjualan</p>
                            </a>
                        </li>



                        <li class="nav-item has-treeview">
                            <a href="{{ route('customers.index') }}" class="nav-link {{ activeSegment('customers') }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Customers</p>
                            </a>
                        </li>

                    </ul>
                </li>





                <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-solid fa-truck"></i>
                      <p>
                        Pengadaan
                        <i class="fas fa-angle-left right"></i>
                      </p>
                    </a>

                <ul class="nav nav-treeview">

                    <li class="nav-item has-treeview">
                        <a href="{{ route('buy.index') }}" class="nav-link {{ activeSegment('buy') }}">
                            <i class="nav-icon fas fa-solid fa-warehouse"></i>
                            <p>Gudang</p>
                        </a>
                    </li>

                    <li class="nav-item has-treeview">
                        <a href="{{ route('products.index') }}" class="nav-link {{ activeSegment('products') }}">
                            <i class="nav-icon fas fa-solid fa-box"></i>
                            <p>Produk</p>
                        </a>
                    </li>

                    <li class="nav-item has-treeview">
                        <a href="{{ route('pembelians.index') }}" class="nav-link {{ activeSegment('pembelians') }}">
                            <i class="nav-icon fas fa-solid fa-cart-flatbed"></i>
                            <p>Pembelian</p>
                        </a>
                    </li>

                    <li class="nav-item has-treeview">
                        <a href="{{ route('history_stoks.index') }}" class="nav-link {{ activeSegment('settings') }}">
                            <i class="nav-icon fas fa-solid fa-clock-rotate-left"></i>
                            <p>Histori Stok</p>
                        </a>
                    </li>

                  </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-book"></i>
                      <p>
                        Data Master
                        <i class="fas fa-angle-left right"></i>
                      </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item has-treeview">
                            <a href="{{ route('kategoris.index') }}" class="nav-link {{ activeSegment('kategoris') }}">
                                <i class="nav-icon fas fa-th-large"></i>
                                <p>Kategori Barang</p>
                            </a>
                        </li>

                        <li class="nav-item has-treeview">
                            <a href="{{ route('supliers.index') }}" class="nav-link {{ activeSegment('supliers') }}">
                                <i class="nav-icon fas fa-solid fa-boxes-stacked"></i>
                                <p>Supplier</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="{{ route('settings.index') }}" class="nav-link {{ activeSegment('settings') }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Pengaturan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="document.getElementById('logout-form').submit()">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                        <form action="{{route('logout')}}" method="POST" id="logout-form">
                            @csrf
                        </form>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
