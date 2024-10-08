<!-- Sidebar -->
<div class="sidebar no-print" data-background-color="dark">
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
                    <a href="{{ route('purchases.index') }}">
                        <i class="fas fa-boxes"></i>
                        <p>Purchases</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('warehouses.index') }}">
                        <i class="fas fa-warehouse"></i>
                        <p>Warehouse</p>
                    </a>
                </li>

                <li class="nav-item submenu">
                    <a data-bs-toggle="collapse" href="#Sales">
                        <i class="fas fa-money-check"></i>
                        <p>Sales</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="Sales">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('sales.index') }}">
                                    <span class="sub-item">Sales</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pending-sales') }}">
                                    <span class="sub-item">Pending</span> <span class="badge badge-danger">{{ \App\Models\Sale::pendingSale() }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dispatched-sales') }}">
                                    <span class="sub-item">Dispatched</span>
                                    <span class="badge badge-info">{{ \App\Models\Sale::dispatchedSale() }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('delivered-sales') }}">
                                    <span class="sub-item">Delivered</span>
                                    <span class="badge badge-success">{{ \App\Models\Sale::deliveredSale() }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>



                <li class="nav-item submenu">
                    <a data-bs-toggle="collapse" href="#Commission">
                        <i class="fas fa-money-check"></i>
                        <p>Commission</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="Commission">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('sales-commissions.index') }}">
                                    <span class="sub-item">Sales Commissions</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('commission-withdraw.index') }}">
                                    <span class="sub-item">Commission Withdraw</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item submenu">
                    <a data-bs-toggle="collapse" href="#Investments">
                        <i class="fas fa-dollar-sign"></i>
                        <p>Investments</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="Investments">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('investments.index') }}">
                                    <span class="sub-item">Investments</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('investment_withdraws.index') }}">
                                    <span class="sub-item">Investment Withdraws</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="{{ route('assets.index') }}">
                        <i class="fas fa-cart-arrow-down"></i>
                        <p>Assets</p>
                    </a>
                </li>



                <li class="nav-item submenu">
                    <a data-bs-toggle="collapse" href="#Salary">
                        <i class="fas fa-money-check"></i>
                        <p>Salary Management</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="Salary">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('payroll.index') }}">
                                    <span class="sub-item">Salary List</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('advance_salary.index') }}">
                                    <span class="sub-item">Advance Salary</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item submenu">
                    <a data-bs-toggle="collapse" href="#Accounting">
                        <i class="fas fa-money-check"></i>
                        <p>Accounting</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="Accounting">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ route('accounts.index') }}">
                                    <span class="sub-item">Accounts</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('balance_transfers.index') }}">
                                    <span class="sub-item">Balance Transfer</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('journals.index') }}">
                                    <span class="sub-item">Journal</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('balance_sheet.show') }}">
                                    <span class="sub-item">Balance Sheet</span>
                                </a>
                            </li>
                        </ul>
                    </div>
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
                            <li>
                                <a href="{{ route('expenses.index') }}">
                                    <span class="sub-item">Expenses</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="{{ route('activity.logs') }}">
                        <i class="fas fa-file"></i>
                        <p>Activity Log</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('product.summary') }}">
                        <i class="fas fa-chart-area"></i>
                        <p>Summary Report</p>
                    </a>
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
