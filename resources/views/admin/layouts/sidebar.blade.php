<nav id="sidebar" class="sidebar js-sidebar">
  <div class="sidebar-content js-simplebar">
    <a class="sidebar-brand" href="{{ route('admin.dashboard') }}">
      <span class="align-middle">MegaMarket</span>
      <img class="align-middle" src="{{ asset('images/logo0.png') }}" alt="Logo" style="background-color: white; width: 80px; height: 80px; border-radius: 50%;">
    </a>

    {{-- Dashboard --}}
    {{-- <li class="sidebar-item">
      <a class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
        
      </a>
    </li> --}}

    {{-- Products --}}
    <li class="sidebar-item">
      <div class="header show_menu d-flex align-items-center" role="button">
        <i class="align-middle" data-feather="box"></i>
        <span class="align-middle ms-2">Product Catalog</span>
        <span class="plus ms-auto"></span>
      </div>

      <ul class="menu slide_toggle list-unstyled mb-0">
        <li class="sidebar-item">
          <a class="sidebar-link {{ isActive('admin/products/create') }}" href="{{ route('products.create') }}">
            <i class="align-middle" data-feather="plus-square"></i>
            <span class="align-middle">Add Product</span>
          </a>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link {{ isActive('admin/products') }}" href="{{ route('products.index')}}">
            <i class="align-middle" data-feather="list"></i>
            <span class="align-middle">Manage Products</span>
          </a>
        </li>

        {{-- Categories --}}
        <li class="sidebar-item">
          <a class="sidebar-link">
            <i class="align-middle" data-feather="folder"></i>
            <span class="align-middle ms-2">Categories</span>
          </a>

          <ul class="list-unstyled ms-3">
            <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('categories.create') }}">
              <i class="align-middle" data-feather="folder-plus"></i>
              <span class="align-middle">Add Category</span>
            </a>
          </li>


            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ route('categories.index') }}">
                <i class="align-middle" data-feather="list"></i>
                <span class="align-middle">Manage Categories</span>
              </a>
            </li>



            {{-- Subcategories nested under Categories (plain nested list) --}}
            {{-- <li class="sidebar-item">
              <a class="sidebar-link" href="#">
                <i class="align-middle" data-feather="layers"></i>
                <span class="align-middle">Subcategories</span>
              </a>

              <ul class="list-unstyled ms-3">
                <li class="sidebar-item">
                  <a class="sidebar-link" href="#">
                    <i class="align-middle" data-feather="plus"></i>
                    <span class="align-middle">Add Subcategory</span>
                  </a>
                </li>
                <li class="sidebar-item">
                  <a class="sidebar-link" href="#">
                    <i class="align-middle" data-feather="list"></i>
                    <span class="align-middle">Manage Subcategories</span>
                  </a>
                </li>
              </ul>
            </li> --}}
          </ul>
        </li>


        {{-- Attributes --}}
        <li class="sidebar-item">
          <a class="sidebar-link">
            <i class="align-middle" data-feather="layers"></i>
            <span class="align-middle ms-2">Attributes</span>
          </a>

          <ul class="list-unstyled ms-3">
            <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('attributes.create') }}">
              <i class="align-middle" data-feather="folder-plus"></i>
              <span class="align-middle">Add Attribute</span>
            </a>
          </li>


            <li class="sidebar-item">
              <a class="sidebar-link" href="{{ route('attributes.index') }}">
                <i class="align-middle" data-feather="list"></i>
                <span class="align-middle">Manage Attributes</span>
              </a>
            </li>
          </ul>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('inventory.index')}}">
            <i class="align-middle" data-feather="archive"></i>
            <span class="align-middle">Stock / Inventory</span>
          </a>
        </li>
        
      </ul>
    </li>

    {{-- Orders --}}
    <li class="sidebar-item">
      <div class="header show_menu d-flex align-items-center" role="button">
        <i class="align-middle" data-feather="shopping-cart"></i>
        <span class="align-middle ms-2"><a id="sidebar-order" href="{{ route('orders.index')}}">Orders</a></span>
      </div>
    </li>

    {{-- Customers --}}
    <li class="sidebar-item">
      <div class="header show_menu d-flex align-items-center" role="button">
        <i class="align-middle" data-feather="users"></i>
        <span class="align-middle ms-2">Customers</span>
        <span class="plus ms-auto"></span>
      </div>

      <ul class="menu slide_toggle list-unstyled mb-0">
        <li class="sidebar-item">
          <a class="sidebar-link" href="#"><span>Customer List</span></a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="#"><span>Customer Groups</span></a>
        </li>
      </ul>
    </li>

    {{-- Sales / Transactions --}}
    <li class="sidebar-item">
      <div class="header show_menu d-flex align-items-center" role="button">
        <i class="align-middle" data-feather="credit-card"></i>
        <span class="align-middle ms-2">Sales / Transactions</span>
        <span class="plus ms-auto"></span>
      </div>

      <ul class="menu slide_toggle list-unstyled mb-0">
        <li class="sidebar-item">
          <a class="sidebar-link" href="#"><span>Invoices</span></a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="#"><span>Payments</span></a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="#"><span>Discounts / Coupons</span></a>
        </li>
      </ul>
    </li>

    {{-- Reports --}}
    <li class="sidebar-item">
      <div class="header show_menu d-flex align-items-center" role="button">
        <i class="align-middle" data-feather="bar-chart-2"></i>
        <span class="align-middle ms-2">Reports</span>
        <span class="plus ms-auto"></span>
      </div>

      <ul class="menu slide_toggle list-unstyled mb-0">
        <li class="sidebar-item">
          <a class="sidebar-link" href="#"><span>Sales Reports</span></a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="#"><span>Product Performance</span></a>
        </li>
        <li class="sidebar-item">
          <a class="sidebar-link" href="#"><span>Customer Insights</span></a>
        </li>
      </ul>
    </li>

    {{-- Store Configuration (your preferred structure) --}}
    <li class="sidebar-item">
      <div class="header show_menu d-flex align-items-center" role="button">
        <i class="align-middle" data-feather="settings"></i>
        <span class="align-middle ms-2">Store Configuration</span>
        <span class="plus ms-auto"></span>
      </div>

      <ul class="menu slide_toggle list-unstyled mb-0">
        <ul class="list-unstyled">
          <li class="sidebar-item">
            <a class="sidebar-link" href="#"><span>Store Settings</span></a>

            <ul class="list-unstyled ms-3">
              <li class="sidebar-item"><a class="sidebar-link" href="#"><span>Currencies</span></a></li>
              <li class="sidebar-item"><a class="sidebar-link" href="#"><span>Branches</span></a></li>
              <li class="sidebar-item"><a class="sidebar-link" href="{{ route('identity.index')}}"><span>Store Identity</span></a></li>
            </ul>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="#"><span>Shipping</span></a>

            <ul class="list-unstyled ms-3">
              <li class="sidebar-item"><a class="sidebar-link" href="#"><span>Shipping Methods</span></a></li>
              <li class="sidebar-item"><a class="sidebar-link" href="#"><span>Zones</span></a></li>
              <li class="sidebar-item"><a class="sidebar-link" href="#"><span>Cities</span></a></li>
              <li class="sidebar-item"><a class="sidebar-link" href="#"><span>Time Slots</span></a></li>
            </ul>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="#"><span>Payment</span></a>

            <ul class="list-unstyled ms-3">
              <li class="sidebar-item"><a class="sidebar-link" href="#"><span>Payment Methods</span></a></li>
              <li class="sidebar-item"><a class="sidebar-link" href="#"><span>Tax Categories</span></a></li>
              <li class="sidebar-item"><a class="sidebar-link" href="#"><span>Tax Rate</span></a></li>
            </ul>
          </li>

          <li class="sidebar-item"><a class="sidebar-link" href="#"><span>Admin Emails</span></a></li>
          <li class="sidebar-item"><a class="sidebar-link" href="#"><span>Notifications</span></a></li>
        </ul>
      </ul>
    </li>

    {{-- Account --}}
    <li class="sidebar-item">
      <div class="header show_menu d-flex align-items-center" role="button">
        <i class="align-middle" data-feather="user"></i>
        <span class="align-middle ms-2">Account</span>
        <span class="plus ms-auto"></span>
      </div>

      <ul class="menu slide_toggle list-unstyled mb-0">
        <li class="sidebar-item"><a class="sidebar-link" href="#"><span>Profile</span></a></li>
        <li class="sidebar-item"><a class="sidebar-link" href="#"><span>Change Password</span></a></li>
        <li class="sidebar-item"><a class="sidebar-link" href="#"><span>Logout</span></a></li>
      </ul>
    </li>
  </div>
</nav>
