<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style layout-menu-fixed" dir="ltr"
  data-theme="theme-default" data-assets-path="{{ asset('assets/') }}" data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>{{ config('app.name', 'Laravel') }}</title>

  <meta name="description" content="" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />

  <!-- Icons. Uncomment required icon fonts -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}"
    class="template-customizer-theme-css" />
  <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

  <!-- Vendors CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

  <!-- Page CSS -->

  <!-- Helpers -->
  <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <!-- Menu -->

      <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
          <a href="{{ url('/') }}" class="app-brand-link" style="align-items: center;">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="me-2" style="width: 32px; height: 32px;">
            <span class="app-brand-text demo menu-text fw-bolder" style="white-space: normal; line-height: 1.1; max-width: 160px; text-transform: none;">
              {{ ucwords(config('app.name', 'Manajemen Aset')) }}
            </span>
          </a>

          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
          </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
          <!-- Dashboard -->
          <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-home-circle"></i>
              <div data-i18n="Analytics">Dashboard</div>
            </a>
          </li>

          @if((Auth::user()->role ?? 'karyawan') === 'super_admin')
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Admin</span></li>
            <li class="menu-item {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
              <a href="{{ route('admin.users.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="ManageUsers">Kelola Akun</div>
              </a>
            </li>
          @endif

          <!-- Asset Management -->
          <li class="menu-header small text-uppercase"><span class="menu-header-text">Assets</span></li>
          @if(in_array((Auth::user()->role ?? 'karyawan'), ['admin', 'super_admin'], true))
            <li class="menu-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
              <a href="{{ route('categories.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-category"></i>
                <div data-i18n="Categories">Kategori</div>
              </a>
            </li>
            <li class="menu-item {{ request()->routeIs('brands.*') ? 'active' : '' }}">
              <a href="{{ route('brands.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-tag"></i>
                <div data-i18n="Brands">Brand</div>
              </a>
            </li>
          @endif
          <li class="menu-item {{ request()->routeIs('assets.*') ? 'active' : '' }}">
            <a href="{{ route('assets.index') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-box"></i>
              <div data-i18n="Asset List">Daftar Aset</div>
            </a>
          </li>
          @if(in_array((Auth::user()->role ?? 'karyawan'), ['admin', 'super_admin'], true))
            <li class="menu-item {{ request()->routeIs('units.*') ? 'active' : '' }}">
              <a href="{{ route('units.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cube"></i>
                <div data-i18n="Units">Unit Aset</div>
              </a>
            </li>
          @endif
          <li class="menu-item {{ (request()->routeIs('loans.*') && !request()->routeIs('loans.returned')) ? 'active' : '' }}">
            <a href="{{ route('loans.index') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-archive"></i>
              <div data-i18n="Loans">Pemakaian Aset</div>
            </a>
          </li>
          <li class="menu-item {{ request()->routeIs('loans.returned') ? 'active' : '' }}">
            <a href="{{ route('loans.returned') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-history"></i>
              <div data-i18n="Returns">Riwayat Pengembalian</div>
            </a>
          </li>
          @if(in_array((Auth::user()->role ?? 'karyawan'), ['admin', 'super_admin'], true))
            <li class="menu-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
              <a href="{{ route('reports.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-file"></i>
                <div data-i18n="Reports">Laporan</div>
              </a>
            </li>
          @endif
        </ul>
      </aside>
      <!-- / Menu -->

      <!-- Layout container -->
      <div class="layout-page">
        <!-- Navbar -->

        <nav
          class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
          id="layout-navbar">
          <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
              <i class="bx bx-menu bx-sm"></i>
            </a>
          </div>

          <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <!-- Search -->
            <div class="navbar-nav align-items-center">
              <div class="nav-item d-flex align-items-center">
                <i class="bx bx-search fs-4 lh-0"></i>
                <input type="text" class="form-control border-0 shadow-none" placeholder="Search..."
                  aria-label="Search..." />
              </div>
            </div>
            <!-- /Search -->

            <ul class="navbar-nav flex-row align-items-center ms-auto">
              <!-- User -->
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                    <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                      <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                          <div class="avatar avatar-online">
                            <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('assets/img/avatars/1.png') }}" alt
                              class="w-px-40 h-auto rounded-circle" />
                          </div>
                        </div>
                        <div class="flex-grow-1">
                          <span class="fw-semibold d-block">{{ Auth::user()->name ?? 'User' }}</span>
                          <small class="text-muted">{{ ucfirst(Auth::user()->role ?? 'karyawan') }}</small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li>
                    <div class="dropdown-divider"></div>
                  </li>
                  <li>
                    <form method="POST" action="{{ route('logout') }}">
                      @csrf
                      <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Log Out</span>
                      </a>
                    </form>
                  </li>
                </ul>
              </li>
              <!--/ User -->
            </ul>
          </div>
        </nav>

        <!-- / Navbar -->

        <!-- Content wrapper -->
        <div class="content-wrapper">
          <!-- Content -->

          <div class="container-xxl flex-grow-1 container-p-y">
            {{ $slot }}
          </div>
          <!-- / Content -->

          <!-- Footer -->
          <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
              <div class="mb-2 mb-md-0">
                ©
                <script>
                  document.write(new Date().getFullYear());
                </script>
                , PT Azkayra Group
              </div>
            </div>
          </footer>
          <!-- / Footer -->

          <div class="content-backdrop fade"></div>
        </div>
        <!-- Content wrapper -->
      </div>
      <!-- / Layout page -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
  </div>
  <!-- / Layout wrapper -->

  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->
  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

  <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
  <!-- endbuild -->

  <!-- Vendors JS -->
  <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

  <!-- Main JS -->
  <script src="{{ asset('assets/js/main.js') }}"></script>

  <!-- Page JS -->
  <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    /* Ensure SweetAlert is always on top of navbar and sidebar */
    .swal2-container {
      z-index: 10000 !important;
    }
  </style>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Intercept all forms with class 'delete-form'
      const deleteForms = document.querySelectorAll('.delete-form');
      deleteForms.forEach(form => {
        form.addEventListener('submit', function (e) {
          e.preventDefault();
          const form = this;

          Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#696cff', // Sneat Primary Color
            cancelButtonColor: '#8592a3', // Sneat Secondary Color
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
              confirmButton: 'btn btn-primary me-3',
              cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
          }).then((result) => {
            if (result.isConfirmed) {
              form.submit();
            }
          });
        });
      });
    });
  </script>

  <!-- Place this tag in your head or just before your close body tag. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>