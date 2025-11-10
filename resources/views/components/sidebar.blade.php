<style>
    .nav-link:hover,
    .nav-link:focus {
        background-color: #0dcaf0 !important;
        color: #fff !important;
        text-decoration: none;
    }
</style>

<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;">
    <div class="container-fluid">
        <a class="navbar-brand gap-3 text-white fs-5 fw-bold" href="#">
            <!-- inline SVG logo (avoids incorrect asset usage) -->
            <svg width="40" viewBox="0 0 46 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M0 33L4.60606 25H12.2448C17.2569 25 21.4947 28.7103 22.1571 33.6784L23 40H13L11.5585 36.6365C10.613 34.4304 8.44379 33 6.04362 33H0Z"
                    fill="#0047C1"></path>
                <path
                    d="M46 33L41.3939 25H33.7552C28.7431 25 24.5053 28.7103 23.8429 33.6784L23 40H33L34.4415 36.6365C35.387 34.4304 37.5562 33 39.9564 33H46Z"
                    fill="#0047C1"></path>
                <path
                    d="M4.60606 25L18.9999 0H23L22.6032 9.52405C22.2608 17.7406 15.7455 24.3596 7.53537 24.8316L4.60606 25Z"
                    fill="#0047C1"></path>
                <path
                    d="M41.3939 25L27.0001 0H23L23.3968 9.52405C23.7392 17.7406 30.2545 24.3596 38.4646 24.8316L41.3939 25Z"
                    fill="#0047C1"></path>
            </svg>
            <span class="ms-2">Proyek PBD Cuy</span>
        </a>
    </div>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <x-nav-link href="/" :active="request()->is('/')"
                icn="bi bi-house-door me-2">Dashboard</x-nav-link>
        </li>
        <li>
            <x-nav-link href="/manage_user" :active="request()->is('manage_user')" icn="bi bi-people me-2">
                Manage User</x-nav-link>
        </li>
        <li>
            <x-nav-link href="/manage_barang" :active="request()->is('manage_barang')" icn="bi bi-box-seam me-2">
                Manage Barang</x-nav-link>
        </li>
        <li>
            <x-nav-link href="/manage_satuan" :active="request()->is('manage_satuan')" icn="bi bi-tags me-2">
                Manage Satuan</x-nav-link>
        </li>
        <li class="nav-item">
            <x-nav-link href="/manage_vendor" :active="request()->is('manage_vendor')" icn="bi bi-building me-2">
                Manage Vendor</x-nav-link>
        </li>
        <hr>
        <p class="text-white-50 text-uppercase small px-3 mb-2">Transaksi</p>
        <li class="nav-item">
            <x-nav-link href="/manage_pengadaan" :active="request()->is('manage_pengadaan*') || request()->is('detail_pengadaan*')" icn="bi bi-cart-check me-2">
                Manage Pengadaan</x-nav-link>
        </li>
        <li class="nav-item">
            <!-- highlight this nav item for both manage_penerimaan and detail_penerimaan routes -->
            <x-nav-link href="/manage_penerimaan" :active="request()->is('manage_penerimaan*') || request()->is('detail_penerimaan*')"
                icn="bi bi-clipboard-check me-2">
                Manage Penerimaan</x-nav-link>
        </li>
        <li class="nav-item">
            <x-nav-link href="/manage_penjualan" :active="request()->is('manage_penjualan') || request()->is('detail_penjualan*')" icn="bi bi-cash me-2">
                Manage Penjualan</x-nav-link>
        </li>

        <li class="nav-item">
            <x-nav-link href="/manage_pembayaran" :active="request()->is('manage_pembayaran*') || request()->is('detail_pembayaran*')" icn="bi bi-card-checklist me-2">
                Manage Kartu Stok</x-nav-link>
        </li>

    </ul>
    <hr>
    <div class="dropdown">
        <a href="#"
            class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
            id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS2IYhSn8Y9S9_HF3tVaYOepJBcrYcd809pBA&s"
                alt="" width="32" height="32" class="rounded-circle me-2">
            <strong>Bro Cuy</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow"
            aria-labelledby="dropdownUser1" style="">
            <li><a class="dropdown-item" href="#">Sign out</a></li>
        </ul>
    </div>
</div>
