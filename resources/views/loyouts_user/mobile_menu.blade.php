<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="mobileMenuLabel">Menu</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="mb-4">
            <form class="d-flex">
                <input type="search" class="form-control me-2" placeholder="Search...">
                <button class="btn btn-outline-primary" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="#">Home</a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#laptopCollapse">
                    Laptop <i class="fas fa-chevron-down float-end mt-1"></i>
                </a>
                <div class="collapse" id="laptopCollapse">
                    <ul class="nav flex-column ps-3">
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="collapse" href="#lenovoCollapse">
                                Lenovo <i class="fas fa-chevron-down float-end mt-1"></i>
                            </a>
                            <div class="collapse" id="lenovoCollapse">
                                <ul class="nav flex-column ps-3">
                                    <li class="nav-item"><a class="nav-link" href="#">Lenovo 1</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#">Lenovo 2</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#">Lenovo 3</a></li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#">DELL</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">APPLE</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">HP</a></li>
                    </ul>
                </div>
            </li>
            
            <!-- Other menu items similarly -->
        </ul>
    </div>
</div>