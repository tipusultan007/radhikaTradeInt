<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="{{ url('/') }}" class="logo">
                <img
                    src="{{ asset('assets/img/Radhikas.svg') }}"
                    alt="navbar brand"
                    class="navbar-brand"
                    height="20"
                />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item">
                    <a
                        data-bs-toggle="collapse"
                        href=""
                        class="collapsed"
                        aria-expanded="false"
                    >
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('users.index') }}">
                        <i class="fas fa-user-alt"></i>
                        <p>Admin Users</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('customers.index') }}">
                        <i class="fas fa-users"></i>
                        <p>Customers</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('products.index') }}">
                        <i class="fas fa-boxes"></i>
                        <p>Products</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('warehouses.index') }}">
                        <i class="fas fa-warehouse"></i>
                        <p>Warehouse</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('sales.index') }}">
                        <i class="fas fa-cart-arrow-down"></i>
                        <p>Sales</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('accounts.index') }}">
                        <i class="fas fa-cart-arrow-down"></i>
                        <p>Accounts</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('assets.index') }}">
                        <i class="fas fa-cart-arrow-down"></i>
                        <p>Assets</p>
                    </a>
                </li>
                <li class="nav-item submenu">
                    <a data-bs-toggle="collapse" href="#Expense_Management">
                        <i class="fas fa-money-check"></i>
                        <p>Expense Management</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="Expense_Management">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('expense_categories.index') }}">
                                    <span class="sub-item">Expense Categories</span>
                                </a>
                            </li>
                            <li class="active">
                                <a href="{{ route('expenses.index') }}">
                                    <span class="sub-item">Expenses</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <li class="nav-item">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->
