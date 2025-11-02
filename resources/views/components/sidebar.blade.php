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
            <svg id="logo-88" width="40" height="41" viewBox="0 0 40 41" fill="none" xmlns="http://www.w3.org/2000/svg" class="d-inline-block align-text-center">
                <path class="ccustom" fill-rule="evenodd" clip-rule="evenodd" d="M13.7146 0.516113C11.4582 0.516113 9.2943 1.41245 7.69881 3.00794L0 10.7067V14.2307C0 16.7204 1.06944 18.9603 2.77401 20.5161C1.06944 22.0719 0 24.3118 0 26.8015V30.3255L7.69881 38.0243C9.2943 39.6198 11.4582 40.5161 13.7146 40.5161C16.2043 40.5161 18.4442 39.4467 20 37.7421C21.5558 39.4467 23.7957 40.5161 26.2854 40.5161C28.5418 40.5161 30.7057 39.6198 32.3012 38.0243L40 30.3255V26.8015C40 24.3118 38.9306 22.0719 37.226 20.5161C38.9306 18.9603 40 16.7204 40 14.2307V10.7067L32.3012 3.00794C30.7057 1.41245 28.5418 0.516113 26.2854 0.516113C23.7957 0.516113 21.5558 1.58555 20 3.29012C18.4442 1.58555 16.2043 0.516113 13.7146 0.516113ZM25.7588 20.5161C25.6629 20.4286 25.5688 20.3387 25.4766 20.2465L20 14.7699L14.5234 20.2465C14.4312 20.3387 14.3371 20.4286 14.2412 20.5161C14.3371 20.6036 14.4312 20.6935 14.5234 20.7857L20 26.2623L25.4766 20.7857C25.5688 20.6935 25.6629 20.6036 25.7588 20.5161ZM22.2222 30.3255L22.2222 32.0085C22.2222 34.2525 24.0414 36.0717 26.2854 36.0717C27.363 36.0717 28.3965 35.6436 29.1585 34.8816L35.5556 28.4845V26.8015C35.5556 24.5575 33.7364 22.7383 31.4924 22.7383C30.4148 22.7383 29.3813 23.1664 28.6193 23.9284L22.2222 30.3255ZM17.7778 30.3255L11.3807 23.9284C10.6187 23.1664 9.58524 22.7383 8.50762 22.7383C6.26359 22.7383 4.44444 24.5575 4.44444 26.8015V28.4845L10.8415 34.8816C11.6035 35.6436 12.637 36.0717 13.7146 36.0717C15.9586 36.0717 17.7778 34.2525 17.7778 32.0085V30.3255ZM17.7778 9.02373V10.7067L11.3807 17.1038C10.6187 17.8658 9.58524 18.2939 8.50762 18.2939C6.26359 18.2939 4.44444 16.4747 4.44444 14.2307V12.5477L10.8415 6.15063C11.6035 5.38864 12.637 4.96056 13.7146 4.96056C15.9586 4.96056 17.7778 6.7797 17.7778 9.02373ZM28.6193 17.1038L22.2222 10.7067L22.2222 9.02373C22.2222 6.7797 24.0414 4.96056 26.2854 4.96056C27.363 4.96056 28.3965 5.38864 29.1585 6.15063L35.5556 12.5477V14.2307C35.5556 16.4747 33.7364 18.2939 31.4924 18.2939C30.4148 18.2939 29.3813 17.8658 28.6193 17.1038Z" fill="#FF630B"></path>
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
        <li class="nav-item">
            <x-nav-link href="/manage_penjualan" :active="request()->is('manage_penjualan')" icn="bi bi-cash me-2">
                Manage Penjualan</x-nav-link>
        </li>
        
    </ul>
    <hr>
    <div class="dropdown">
        <a href="#"
            class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
            id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="" width="32" height="32"
                class="rounded-circle me-2">
            <strong>mdo</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow"
            aria-labelledby="dropdownUser1" style="">
            <li><a class="dropdown-item" href="#">Sign out</a></li>
        </ul>
    </div>
</div>
