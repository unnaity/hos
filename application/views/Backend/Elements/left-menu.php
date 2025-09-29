<!-- Sidemenu -->
<?php /* ?>

<div class="main-sidebar main-sidemenu main-sidebar-sticky side-menu">
    <div class="sidemenu-logo">
        <a class="main-logo" href="#<?php //echo BASE_URL.'dashboard'?>">
            <img src="<?php echo IMAGE_PATH.'logo.png'?>" class="header-brand-img desktop-logo" alt="logo">
            <img src="<?php echo IMAGE_PATH.'small-logo.png'?>" class="header-brand-img icon-logo" alt="logo">
        </a>
    </div>
    <div class="main-sidebar-body">
        <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg"
            fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
            <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
        </svg></div>
        <ul class="nav  hor-menu">            
            <!--li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL.'dashboard'?>"><i class="fe fe-airplay"></i>
                <span class="sidemenu-label">Dashboard</span></a>
            </li-->  
            
            <li class="nav-item">
                <a class="nav-link with-sub" href=""><i class="fe fe-database"></i>
                <span class="sidemenu-label">Masters</span><i class="angle fe fe-chevron-right"></i></a>
                <ul class="nav-sub">
                    <li class="side-menu-label1"><a href="javascript:void(0)">Masters</a></li>
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'category-list'?>">Category</a>
                    </li> 
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'size-list'?>">Size</a>
                    </li> 
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'model-list'?>">Model</a>
                    </li> 
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'quality-list'?>">Material</a>
                    </li>                    
                    
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'unit-conversion'?>">Unit Conversion</a>
                    </li>
                    <!--li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'operations-list'?>">Operations</a>
                    </li-->
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'product-list'?>">Products</a>
                    </li>
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'branches'?>">Branches</a>
                    </li>
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'store-list'?>">Stores</a>
                    </li>
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'location-list'?>">Locations</a>
                    </li>                    
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'department-list'?>">Department</a>
                    </li>                    
                    <!--li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php //echo BASE_URL.'variant-list'?>">Variant</a>
                    </li-->
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'tax-rates'?>">Taxes</a>
                    </li>  
                    
                </ul>    
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL.'purchase-order-list'?>"><i class="fe fe-shopping-cart"></i>
                <span class="sidemenu-label">Purchase Order</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link with-sub" href=""><i class="fa fa-list-alt"></i>
                <span class="sidemenu-label">Inwards</span><i class="angle fe fe-chevron-right"></i></a>
                <ul class="nav-sub">
                    <li class="side-menu-label1"><a href="javascript:void(0)">Inwards</a></li>
                    <!--li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'package-type-list'?>">Package Type</a>
                    </li>
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'trolley-type-list'?>">Trolley Type</a>
                    </li--> 
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'product-grn-list'?>">Product GRN</a>
                    </li>
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'quality-check-list'?>">Quality Check</a>
                    </li>
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'put-away-list'?>">Put Away</a>
                    </li>
                    <!--li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php //echo BASE_URL.'put-away-list'?>">Put Away List</a>
                    </li-->                    
                </ul>    
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL.'sales-order-list'?>"><i class="fa fa-sellsy"></i>
                <span class="sidemenu-label">Sales Order</span></a>
            </li> 

            <li class="nav-item">
                <a class="nav-link with-sub" href=""><i class="fa fa-truck"></i>
                <span class="sidemenu-label">Dispatch</span><i class="angle fe fe-chevron-right"></i></a>
                <ul class="nav-sub">
                    <li class="side-menu-label1"><a href="javascript:void(0)">Dispatch</a></li>
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'create-pick-list'?>">Pick List</a>
                    </li>                   
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'dispatch-list'?>">Dispatch Report</a>
                    </li>
                </ul>    
            </li>  
            
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL.'general-issues-list'?>"><i class="fa fa-inbox"></i>
                <span class="sidemenu-label">General Issues</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link with-sub" href=""><i class="fa fa-address-book-o"></i>
                <span class="sidemenu-label">Contacts</span><i class="angle fe fe-chevron-right"></i></a>
                <ul class="nav-sub">
                    <li class="side-menu-label1"><a href="javascript:void(0)">Contacts</a></li>
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'customer-list'?>">Customer</a>
                    </li>
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'oem-list'?>">OEM</a>
                    </li>
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'supplier-list'?>">Vendor</a>
                    </li>
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'employee-list'?>">Employee</a>
                    </li>
                </ul>    
            </li>

            

            <!--li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL.'dashboard'?>"><i class="fa fa-industry" aria-hidden="true"></i>
                <span class="sidemenu-label">Make</span></a>
            </li--> 

            

            <!--li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL.'dashboard'?>"><i class="fa fa-industry" aria-hidden="true"></i>
                <span class="sidemenu-label">Make</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link with-sub" href=""><i class="fa fa-cubes"></i>
                <span class="sidemenu-label">Stocks</span><i class="angle fe fe-chevron-right"></i></a>
                <ul class="nav-sub">
                    <li class="side-menu-label1"><a href="javascript:void(0)">Stocks</a></li>
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'grn-stock'?>">
                            <span class="sidemenu-label">GRN Stock</span>
                        </a>
                    </li>                   
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'product-stock'?>">
                            <span class="sidemenu-label">Put Away Stock</span>
                        </a>
                    </li>
                </ul>    
            </li-->  
            
            <li class="nav-item">
                <a class="nav-link with-sub" href=""><i class="icon icon-chart"></i>
                <span class="sidemenu-label">Reports</span><i class="angle fe fe-chevron-right"></i></a>
                <ul class="nav-sub">
                    <li class="side-menu-label1"><a href="javascript:void(0)">Reports</a></li>
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'grn-stock'?>">
                            <span class="sidemenu-label">GRN Stock</span>
                        </a>
                    </li>                   
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'product-stock'?>">
                            <span class="sidemenu-label">Put Away Stock</span>
                        </a>
                    </li>
                </ul>  

                <!--a class="nav-link" href="<?php //echo BASE_URL.'product-stock'?>"><i class="icon icon-chart"></i>
                <span class="sidemenu-label">Reports</span></a-->
            </li>
            <li class="nav-item">
                <a class="nav-link with-sub" href="">
                    <i class="fe fe-settings"></i>
                    <span class="sidemenu-label">Settings</span>
                    <i class="angle fe fe-chevron-right"></i>
                </a>
                <ul class="nav-sub">
                    <li class="side-menu-label1"><a href="javascript:void(0)">Settings</a></li>
                    <li class="nav-sub-item">
                        <a class="nav-sub-link" href="<?php echo BASE_URL.'general'?>">General</a>
                    </li>                                                          
                </ul>
            </li>   
            
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL.'logout'?>"><i class="fe fe-power"></i> 
                <span class="sidemenu-label">Sign Out</span></a>
            </li>            
        </ul>        
    </div>
</div>
<!-- End Sidemenu -->

<?php */ ?>

<div class="container-fluid main-container" style="background:#525252">
    <div class="main-content pt-0">
        <div class="navbar navbar-expand-lg navbar-collapse responsive-navbar p-0">
            <div class="collapse navbar-collapse" id="navbarSupportedContent-5">
                <div class="p-2 br-3">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL . 'dashboard' ?>">Dashboard</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><!--i class="fe fe-database"></i-->
                                <span class="">Masters</span></a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">

                                <!-- <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'grn-type-list' ?>">Material Type</a>
                                </li> -->
                                <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'raw-material-list' ?>">RM</a>
                                </li>
                                <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'sfg-list' ?>">SFG</a>
                                </li>
                                <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'fg-list' ?>">FG</a>
                                </li>
                                <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'units-of-measure' ?>">Unit of Measures</a>
                                </li>
                                <!-- <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'branches' ?>">Branches</a>
                                </li>
                                <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'store-list' ?>">Warehouse</a>
                                </li> -->
                                <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'location-list' ?>">Locations</a>
                                </li>
                                <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'supplier-list' ?>">Vendor</a>
                                </li>
                                <!-- <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'department-list' ?>">Department</a>
                                </li> -->
                                <!--li class="dropdown-item">
                                    <a class="dropdown-item" href="<?php //echo BASE_URL.'variant-list'
                                                                    ?>">Variant</a>
                                </li-->
                                <!-- <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'tax-rates' ?>">Taxes</a>
                                </li> -->

                            </ul>
                        </li>

                        <!-- <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL . 'purchase-order-list' ?>" >Purchase Order</a>
                        </li> -->
                        <!-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="">RM Inwards</span></a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'rm-grn-list' ?>">RM GRN</a>
                                </li>
                                <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'rm-quality-check-list' ?>">Quality Check</a>
                                </li>
                                <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'rm-put-away-list' ?>">Put Away</a>
                                </li>
                            </ul>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL . 'bom-list' ?>">BOM</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="">Inwards</span></a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'sfg-grn-list' ?>">GRN</a>
                                </li>
                                <!-- <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'sfg-quality-check-list' ?>">Quality Check</a>
                                </li> -->
                                <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'sfg-put-away-list' ?>">Put Away</a>
                                </li>
                            </ul>
                        </li>
                        <!-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="">Inwards</span></a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown"> -->
                        <!--li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'package-type-list' ?>">Package Type</a>
                                </li>
                                <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'trolley-type-list' ?>">Trolley Type</a>
                                </li-->
                        <!-- <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'product-grn-list' ?>">Product GRN</a>
                                </li>
                                <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'quality-check-list' ?>">Quality Check</a>
                                </li>
                                <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'put-away-list' ?>">Put Away</a>
                                </li>
                            </ul>
                        </li> -->
                        


                        <!-- <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL . 'bom-list' ?>">Bom</a>
                        </li> -->

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="">Outwards</span></a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <!-- <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'create-pick-list' ?>">Pick List</a>
                                </li> -->
                                <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'general-issues-list' ?>">BOM Based Issues</a>
                                </li>
                                <!-- <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'scrap-list' ?>">Scrap</a>
                                </li> -->
                                <!-- <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'sales-order-list' ?>">Sales Order</a>
                                </li> -->
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL . 'stock-audit' ?>" style="width: 110px;">Stock Audit</a>
                        </li>
                        <!-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="">Contacts</span></a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'customer-list' ?>">Customer</a>
                                </li>
                                <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'oem-list' ?>">OEM</a>
                                </li>

                                <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'supplier-list' ?>">Vendor</a>
                                </li>

                                <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'employee-list' ?>">Employee</a>
                                </li>
                            </ul>
                        </li> -->

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="">Reports</span></a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <!-- <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'grn-stock' ?>">QC Pending List</a>
                                </li> -->
                                <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'put-away-pending' ?>">Put Away Pending List</a>
                                </li>
                                <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'total-stock-value' ?>">Stock Value</a>
                                </li>
                                <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'product-stock' ?>">Product Stock List</a>
                                </li>
                                <li class="border-bottom">
                                    <a class="dropdown-item" href="<?php echo BASE_URL . 'dispatch-list' ?>">Dispatch List</a>
                                </li>
                            </ul>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL . 'scrap-list' ?>" style="width: 130px;">Scrap</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL . 'stock-audit' ?>" style="width: 105px;">Stock Audit</a>
                        </li> -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>