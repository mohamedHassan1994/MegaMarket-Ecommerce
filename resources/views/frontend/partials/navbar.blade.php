<nav class="navbar navbar-expand-lg bg-light text-uppercase fs-6 p-3 border-bottom align-items-center">
    <div class="container-fluid">
      <div class="row justify-content-between align-items-center w-100">

        <!-- Logo -->
        <div class="col-auto">
          <a class="navbar-logo" href="{{ url('/') }}">
            <img src="{{ asset('images/icons/logo0.png') }}" alt="Logo" width="120" height="auto">
          </a>
        </div>

        <!-- Menu -->
        <div class="col-auto">
          <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
            aria-controls="offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
            aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
              <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
            </div>

            <div class="offcanvas-body">
              <ul class="navbar-nav">

                <!-- Dynamic Categories -->
                 @foreach($categories as $category)
                  <li class="nav-item dropdown mega-dropdown">
                      <a class="nav-link dropdown-toggle" href="{{ route('category.show', $category->slug) }}"
                        id="dropdown{{ $category->id }}">
                          {{ $category->name }}
                      </a>

                      @if($category->children->count())
                          <div class="dropdown-menu mega-menu p-3" aria-labelledby="dropdown{{ $category->id }}">
                              <div class="row">
                                  <!-- LEFT SIDE: Subcategories -->
                                  <div class="col-md-7">
                                      <ul class="list-unstyled">
                                          @foreach($category->children as $subcategory)
                                              <li class="dropdown-submenu mb-2">
                                                  <a class="dropdown-item @if($subcategory->children->count()) dropdown-toggle @endif"
                                                    href="{{ route('category.show', $subcategory->slug) }}">
                                                      {{ $subcategory->name }}
                                                  </a>

                                                  @if($subcategory->children->count())
                                                      <ul class="dropdown-menu">
                                                          @foreach($subcategory->children as $subsubcategory)
                                                              <li>
                                                                  <a class="dropdown-item"
                                                                    href="{{ route('category.show', $subsubcategory->slug) }}">
                                                                      {{ $subsubcategory->name }}
                                                                  </a>
                                                              </li>
                                                          @endforeach
                                                      </ul>
                                                  @endif
                                              </li>
                                          @endforeach
                                      </ul>
                                  </div>

                                  <!-- RIGHT SIDE: Dynamic Image -->
                                  <div class="col-md-5 text-center">
                                      @php
                                          $image = $category->getFirstProductImage();
                                      @endphp

                                      @if($image)
                                          <img src="{{ asset('storage/' . $image) }}"
                                              alt="{{ $category->name }}"
                                              class="img-fluid rounded shadow-sm">
                                      @else
                                          <img src="{{ asset('storage/images/default-category.jpg') }}"
                                              alt="No Image"
                                              class="img-fluid rounded shadow-sm">
                                      @endif
                                  </div>

                              </div>
                          </div>
                      @endif
                  </li>
              @endforeach

              </ul>
            </div>
          </div>
        </div>

        <!-- Right Side (Wishlist / Cart / Account / Search) -->
        <div class="col-3 col-lg-auto">
          <ul class="list-unstyled d-flex m-0">
            <li class="d-none d-lg-block">
              <a href="#" class="text-uppercase mx-3">
                Wishlist <span class="wishlist-count">(0)</span>
              </a>
            </li>
            <li class="d-none d-lg-block">
              <a href="#" class="text-uppercase mx-3" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart"
                aria-controls="offcanvasCart">
                Cart <span class="cart-count">(0)</span>
              </a>
            </li>

            <!-- Account -->
            <li class="d-none d-lg-block">
                @guest
                    <a href="{{ route('login') }}" class="text-uppercase mx-3">
                        <i class="bi bi-person me-1"></i> Sign In
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-uppercase mx-3">
                        <i class="bi bi-person me-1"></i> Account
                    </a>
                @endguest
            </li>

            <!-- Mobile Account -->
            <li class="d-lg-none">
                @guest
                    <a href="{{ route('login') }}" class="mx-2"><i class="bi bi-person"></i></a>
                @else
                    <a href="{{ route('login') }}" class="mx-2"><i class="bi bi-person"></i></a>
                @endguest
            </li>

            <!-- Search -->
            <li class="search-box mx-2">
              <a href="#search" class="search-button">
                <svg width="24" height="24" viewBox="0 0 24 24">
                  <use xlink:href="#search"></use>
                </svg>
              </a>
            </li>

            <!-- Mobile Wishlist -->
            <li class="d-lg-none">
              <a href="#" class="mx-2">
                <svg width="24" height="24" viewBox="0 0 24 24">
                  <use xlink:href="#heart"></use>
                </svg>
              </a>
            </li>

            <!-- Mobile Cart -->
            <li class="d-lg-none">
              <a href="#" class="mx-2" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart"
                aria-controls="offcanvasCart">
                <svg width="24" height="24" viewBox="0 0 24 24">
                  <use xlink:href="#cart"></use>
                </svg>
              </a>
            </li>
          </ul>
        </div>

      </div>
    </div>
</nav>
