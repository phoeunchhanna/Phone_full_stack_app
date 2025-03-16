<div class="header">
    <div class="header-left">
        <a href="{{ route('home') }}" class="logo">
            <img src="{{ asset('assets/img/sinet8.png') }}" alt="Logo">
        </a>
        <a href="{{ route('home') }}" class="logo logo-small">
            <img src="{{ asset('assets/img/sinet8.png') }}" alt="Logo" width="30" height="30">
        </a>
    </div>
    <div class="menu-toggle">
        <a href="javascript:void(0);" id="toggle_btn" aria-label="Toggle Navigation Menu">
            <i class="fas fa-bars"></i>
        </a>

        {{-- <a href="#" id="toggle_btn">
            <i class="fas fa-bars"></i>
        </a> --}}
    </div>
    <a class="mobile_btn" id="mobile_btn">
        <i class="fas fa-bars"></i>
    </a>
    <ul class="nav user-menu">

        <li>

            <div class="header-title m-2 text-light">
                ថ្ងៃនេះ, ថ្ងៃទី {{ now()->format('d') }} ខែ
                {{ now()->translatedFormat('F') }} ឆ្នាំ
                {{ now()->format('Y') }}
            </div>
        </li>

        {{-- <li class="nav-item me-2">
            @can('ផ្ទាំងលក់ផលិតផល')
                <a href="{{ route('close.sale') }}" class="nav-link header-nav-list win-maximize">
                    <i>POS</i>
                </a>
            @endcan
        </li> --}}
        {{-- <li class="nav-item me-2">
            @can('ផ្ទាំងលក់ផលិតផល')
                <a href="{{ route('pos.index') }}" class="nav-link header-nav-list win-maximize">
                    <i>POS</i>
                </a>
            @endcan
        </li> --}}

        <li class="nav-item dropdown noti-dropdown me-2">
            <a href="#" class="dropdown-toggle nav-link header-nav-list" data-bs-toggle="dropdown">
                <i class="bi bi-bell"></i>
                <span class="badge badge-pill badge-danger">
                    @php
                        // Fetch products where current stock is less than or equal to the stock alert
                        $low_quantity_products = \App\Models\Product::with('stock') // Eager load stock
                            ->whereHas('stock', function ($query) {
                                $query->whereColumn('current', '<=', 'stock_alert');
                            })
                            ->get();
                        echo $low_quantity_products->count();
                    @endphp
                </span>
            </a>
            <div class="dropdown-menu notifications">
                <div class="topnav-dropdown-header">
                    <strong>{{ $low_quantity_products->count() }} សេចក្តីជូនដំណឹង</strong>
                </div>
                <div class="noti-content">
                    <ul class="notification-list">
                        @forelse($low_quantity_products as $product)
                            <li class="notification-message">
                                <a href="{{ route('products.show', $product->id) }}">
                                    <div class="media d-flex">
                                        <span class="avatar avatar-sm flex-shrink-0">
                                            <img class="avatar-img rounded-circle" alt="រូបភាពផលិតផល"
                                                src="{{ asset('storage/' . $product->image) }}">
                                        </span>
                                        <div class="media-body flex-grow-1">
                                            <p class="noti-details">
                                                <span class="noti-title">ឈ្មោះផលិតផល: "{{ $product->name }}"</span>
                                                បាកូដ:
                                                {{ $product->code }}
                                                <span class="noti-title"> មានបរិមាណតិច!</span>
                                            </p>
                                            <p class="noti-time">
                                                <span class="notification-time">ចំនួន:
                                                    {{ $product->stock->current }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li class="notification-message">
                                <p>គ្មានផលិតផលដែលមានបរិមាណតិច។</p>
                            </li>
                        @endforelse
                    </ul>
                </div>
                <div class="topnav-dropdown-footer">
                    <a href="#">មើលសេចក្តីជូនដំណឹងទាំងអស់</a>
                </div>
            </div>
        </li>


        <li class="nav-item zoom-screen me-2">
            {{-- <a href="#" class="nav-link header-nav-list win-maximize">
                <i class="bi bi-fullscreen fs-6"></i>
            </a> --}}
            <a href="#" class="nav-link header-nav-list win-maximize" aria-label="Maximize window">
                <i class="fas fa-expand"></i>
            </a>

        </li>
        <li class="nav-item dropdown has-arrow new-user-menus">
            <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                <span class="user-img">
                    <img src="{{ asset('storage/' . Auth::user()->avatar ?? 'default-avatar.png') }}"
                        alt="Profile Image" class="avatar-img rounded-circle border border-5-hover" width="40"
                        height="40">
                    <div class="user-text">
                        <h6>សួរស្តី!, {{ Auth::user()->name ?? 'Guest' }}</h6>
                    </div>
                </span>
            </a>
            <div class="dropdown-menu">
                <div class="user-header">
                    <div class="avatar avatar-sm">
                        <img src="{{ asset('storage/' . Auth::user()->avatar ?? 'default-avatar.png') }}"
                            alt="{{ Auth::user()->name ?? 'Guest' }}" class="avatar-img rounded-circle">
                    </div>
                    <div class="user-text">
                        <h6>{{ Auth::user()->name ?? 'Guest' }}</h6>
                        <p class="text-muted mb-0">{{ Auth::user()->email ?? 'Not logged in' }}</p>
                    </div>
                </div>
                <a class="dropdown-item" href="{{ route('profile.edit') }}">ព៌ត័មានផ្ទាល់ខ្លួន</a>
                <a class="dropdown-item" href="#" onclick="confirmLogout(event)">ចាកចេញ</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>

            </div>
        </li>
    </ul>
</div>
<script>
    function confirmLogout(event) {
        event.preventDefault();
        Swal.fire({
            title: "តើអ្នកប្រាកដថា?",
            text: "អ្នកនឹងត្រូវចាកចេញពីប្រព័ន្ធ!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "បាទ/ចាស, ចាកចេញ!",
            cancelButtonText: "អត់ទេ, មិនចាកចេញ!"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>
