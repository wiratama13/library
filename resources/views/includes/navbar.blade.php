
<!-- Navbar -->

<nav
  class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
  id="layout-navbar"
>
  <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
      <i class="bx bx-menu bx-sm"></i>
    </a>
  </div>

  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    

    <ul class="navbar-nav flex-row align-items-center ms-auto">
      <!-- Place this tag where you want the button to render. -->
      
      <!-- Notif -->
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          @php
            $now = date("Y-m-d");
            $late_date = App\Models\Transaction::with('member:id,name')
            ->where('date_end', '<', $now)
            ->where('status', '=', 1)->get();
            $tr_count = $late_date->count();
          @endphp
          @if (count($late_date) === 0)
         <div class="avatar d-flex justify-content-center align-items-center ">
             <i class="fa-regular fa-bell fa-xl"></i>
          </div>
          @elseif(count($late_date) > 0)
           {{-- <div class="avatar avatar-online d-flex justify-content-center align-items-center">
             
          </div> --}}
            <div class="avatar position-relative mt-3 me-2">
            <i class="fa-regular fa-bell fa-xl"></i> 
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">{{ $tr_count }}</span>
          </div>

          @else

          @endif
        
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
      
              <li>
                {{-- <a class="dropdown-item" href="#">                
                 @foreach ($late_date as $late)
                   {{ $late->member->name }} 
                 @endforeach
                </a> --}}
                {!! notification() !!}
                </li>
        </ul>
      </li>
      <!--/ User -->

      {{-- User --}}
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="avatar avatar-online">
            <img src="../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item" href="#">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar avatar-online">
                    <img src="../assets/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                  </div>
                </div>
                <div class="flex-grow-1">
                  <span class="fw-semibold d-block" href="#">
                          {{ Auth::user()->name }}
                  </span>
                  <small class="text-muted">Admin</small>
                </div>
              </div>
            </a>
          </li>
          
          
          <li>
            <a class="dropdown-item" 
                  href="{{ route('logout') }}"
                  onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">
              <i class="bx bx-power-off me-2"></i>
              <span class="align-middle">Log Out</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
              </form>
          </li>
          
        </ul>
      </li>
    </ul>
  </div>
</nav>

<!-- / Navbar -->