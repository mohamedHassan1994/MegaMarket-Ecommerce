<nav class="navbar navbar-expand navbar-light navbar-bg">
    <a class="sidebar-toggle js-sidebar-toggle">
        <i class="hamburger align-self-center"></i>
    </a>

    <div class="navbar-collapse collapse">
        <!-- 🔍 Search bar -->
		<form class="d-flex flex-grow-1 ms-3 me-3">
			<div class="search-wrapper w-100">
				<input class="form-control search-input" type="search" placeholder="Search..." aria-label="Search">
				<button class="search-btn" type="submit">
					<i data-feather="search"></i>
				</button>
			</div>
		</form>



        <ul class="navbar-nav ms-auto navbar-align">
            <!-- 🌐 Language Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="langDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="align-middle me-1" data-feather="globe"></i> Language
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="langDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ url('lang/en') }}">
                            English
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ url('lang/ar') }}">
                            العربية
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Existing Notifications -->
            <li class="nav-item dropdown">
                <a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-bs-toggle="dropdown">
                    <div class="position-relative">
                        <i class="align-middle" data-feather="bell"></i>
                        <span class="indicator">4</span>
                    </div>
                </a>
                <!-- ... keep your notification dropdown ... -->
            </li>

            <!-- Existing Messages -->
            <li class="nav-item dropdown">
                <a class="nav-icon dropdown-toggle" href="#" id="messagesDropdown" data-bs-toggle="dropdown">
                    <div class="position-relative">
                        <i class="align-middle" data-feather="message-square"></i>
                    </div>
                </a>
                <!-- ... keep your messages dropdown ... -->
            </li>

            <!-- Profile -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                    <img src="img/avatars/avatar.jpg" class="avatar img-fluid rounded me-1" alt="User" />
                    <span class="text-dark">Charles Hall</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="user"></i> Profile</a>
                    <a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="settings"></i> Settings</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Log out</a>
                </div>
            </li>
        </ul>
    </div>
</nav>



			{{-- 🔝 Secondary Navbar --}}
			<nav class="navbar navbar-expand-lg navbar-light bg-white border rounded shadow-sm mb-4">
				<div class="container-fluid">
					<ul class="navbar-nav me-auto mb-2 mb-lg-0 nav-links-large">

						{{-- Home --}}
						<li class="nav-item">
							<a class="nav-link d-flex align-items-center {{ request()->is('admin/dashboard') ? 'active' : '' }}" 
							href="{{ route('admin.dashboard') }}">
								<i class="fa fa-home me-2"></i> Home
							</a>
						</li>

						{{-- Products --}}
						<li class="nav-item">
							<a class="nav-link d-flex align-items-center {{ request()->is('admin/products*') ? 'active' : '' }}" 
							href="{{ route('products.index') }}">
								<i class="fa fa-box me-2"></i> Products
							</a>
						</li>

						{{-- Stock / Inventory --}}
						<li class="nav-item">
							<a class="nav-link d-flex align-items-center {{ request()->is('admin/inventory*') ? 'active' : '' }}" 
							href="{{ route('inventory.index')}}">
								<i class="fa fa-box me-2"></i> Stock / Inventory
							</a>
						</li>

						{{-- Orders --}}
						<li class="nav-item">
							<a class="nav-link d-flex align-items-center {{ request()->is('admin/order*') ? 'active' : '' }}" 
							href="{{ route('orders.index')}}">
								<i class="fa fa-shopping-cart me-2"></i> Orders
							</a>
						</li>

						{{-- Customers --}}
						<li class="nav-item">
							<a class="nav-link d-flex align-items-center {{ request()->is('admin/customers*') ? 'active' : '' }}" 
							href="">
								<i class="fa fa-users me-2"></i> Customers
							</a>
						</li>

						{{-- Reports --}}
						<li class="nav-item">
							<a class="nav-link d-flex align-items-center {{ request()->is('admin/reports*') ? 'active' : '' }}" 
							href="">
								<i class="fa fa-shopping-cart me-2"></i> Detailed Reports
							</a>
						</li>

					</ul>
				</div>
			</nav>

			{{-- 🎨 Custom styles --}}
			<style>
				.nav-links-large .nav-link {
					font-size: 1.15rem;      /* bigger text */
					font-weight: 500;
					color: #6c757d;          /* softer grey */
					padding: 12px 22px;      /* bigger hit area */
					margin-right: 20px;      /* more spacing */
					border-radius: 8px;      /* rounded for nicer hover */
					transition: all 0.2s ease-in-out;
				}

				.nav-links-large .nav-link:hover {
					color: #343a40;          /* darker grey on hover */
					background-color: #f8f9fa;
					border-bottom: 3px solid #0d6efd;  /* 🔹 underline effect */
					border-radius: 0;  
				}

				.nav-links-large .nav-link.active {
					color: #0d6efd !important;   /* bootstrap primary blue */
					font-weight: 700;
					background-color: #e9f2ff; 
					border-bottom: 3px solid #0d6efd;  /* 🔹 underline effect */
					border-radius: 0;    /* light blue background for active */
				}

				.search-wrapper {
					position: relative;
					width: 100%;
				}

				.search-input {
					background-color: #ebedf7 !important;
					border: none !important;
					height: 42px;
					font-size: 14px;
					border-radius: 50px; /* makes it curvy */
					padding: 0 45px 0 18px; /* space for button on the right */
					width: 100%;
					box-shadow: none !important;
				}

				.search-btn {
					position: absolute;
					top: 50%;
					right: 12px;
					transform: translateY(-50%);
					background: none;
					border: none;
					color: #555;
					cursor: pointer;
					font-size: 16px;
				}

				.search-btn:hover {
					color: #000;
				}

                .breadcrumb {
                    margin-bottom: 0;
                    background: #f8f9fa !important;
                    font-size: 0.95rem;
                }
                .breadcrumb .breadcrumb-item + .breadcrumb-item::before {
                    content: "›";  /* arrow separator */
                }
                .breadcrumb .active {
                    font-weight: 600;
                }


			</style>


            {{-- 🔗 Breadcrumbs --}}
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb bg-white p-3 rounded shadow-sm">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-home me-1"></i> Administration
                        </a>
                    </li>

                    @php
                        $segments = request()->segments();
                        $url = '';
                    @endphp

                    @foreach($segments as $index => $segment)
                        @continue($segment === 'admin') {{-- 🚀 skip "admin" part --}}

                        @php $url .= '/' . $segment; @endphp

                        @if ($index + 1 < count($segments))
                            <li class="breadcrumb-item">
                                <a href="{{ url($url) }}">{{ ucfirst($segment) }}</a>
                            </li>
                        @else
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ ucfirst($segment) }}
                            </li>
                        @endif
                    @endforeach
                </ol>
            </nav>


                {{-- 🔎 Filters --}}
                @unless(request()->is('admin/dashboard'))
                    <div class="mb-4 rounded bg-light">
                        {{-- Clickable header --}}
                        <div id="filtersHeader"
                            class="filter-header d-flex justify-content-between align-items-center p-3"
                            role="button"
                            tabindex="0"
                            aria-expanded="true"
                            aria-controls="filtersCollapse"
                            style="cursor: pointer;">
                            <h6 class="mb-0 fw-bold">
                                <i class="fa fa-filter me-1"></i> Filters
                            </h6>

                            <div>
                                <button type="submit" form="product-filters" class="btn btn-dark btn-lg me-2 px-4 rounded-pill">
                                    Filter
                                </button>
                                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-lg px-4 rounded-pill">
                                    Clear all
                                </a>
                            </div>
                        </div>

                    
                        {{-- Collapsible content --}}
                        <div class="collapse" id="filtersCollapse">
                            <div class="p-3 border-top">
                                <form method="GET" action="{{ route('products.index') }}" id="product-filters">
                                    @csrf
                                    <div class="row g-3">

                                    {{-- Name/Code --}}
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Name / Code</label>
                                        <input type="text" name="name" class="form-control big-input"
                                            placeholder="Enter name or code" value="{{ request('name') }}">
                                    </div>

                                    {{-- Enabled --}}
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Enabled</label>
                                        <select name="vendor" class="form-select big-input">
                                            <option value="1" {{ request('enabled') === '1' ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ request('enabled') === '0' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>

                                    {{-- Category --}}
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Category</label>
                                        <select name="category_id" class="form-select big-input">
                                            <option value="">All</option>
                                            @foreach($parent_category as $cat)
                                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Vendor --}}
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Vendor</label>
                                        <select name="vendor" class="form-select big-input">
                                            <option value="">All</option>
                                            <option value="vendor1" {{ request('vendor') == 'vendor1' ? 'selected' : '' }}>Vendor 1</option>
                                            <option value="vendor2" {{ request('vendor') == 'vendor2' ? 'selected' : '' }}>Vendor 2</option>
                                        </select>
                                    </div>

                                    {{-- Attribute name --}}
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Attribute Name</label>
                                        <select name="attribute_name" class="form-select big-input">
                                            <option value="">All</option>
                                            <option value="color" {{ request('attribute_name') == 'color' ? 'selected' : '' }}>Color</option>
                                            <option value="size" {{ request('attribute_name') == 'size' ? 'selected' : '' }}>Size</option>
                                        </select>
                                    </div>

                                    {{-- Attribute value --}}
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Attribute Value</label>
                                        <input type="text" name="attribute_value" class="form-control big-input"
                                            placeholder="Enter attribute value" value="{{ request('attribute_value') }}">
                                    </div>

                                    {{-- Option name --}}
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Option Name</label>
                                        <select name="option_name" class="form-select big-input">
                                            <option value="">All</option>
                                            <option value="material" {{ request('option_name') == 'material' ? 'selected' : '' }}>Material</option>
                                        </select>
                                    </div>

                                    {{-- Option value --}}
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Option Value</label>
                                        <input type="text" name="option_value" class="form-control big-input"
                                            placeholder="Enter option value" value="{{ request('option_value') }}">
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endunless

                {{-- Extra CSS --}}
                <style>
                    .big-input {
                        height: 55px !important; /* taller inputs & selects */
                        font-size: 1rem;
                    }
                    .btn-lg {
                        padding: 12px 24px;
                        font-size: 1rem;
                    }

					.filter-header {
						 background-color: white;
						 border-radius: 50px;
					}
                </style>