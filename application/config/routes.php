<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$route['404_override'] = 'Error';
$route['default_controller'] = 'Login';
$route['translate_uri_dashes'] = FALSE;

$route['dashboard'] = "dashboard/index";
$route['login'] = 'Login/index';
$route['forget-password'] = "login/forget_password";
$route['reset-password'] = "login/reset_password";
$route['logout'] = "login/logout";

$route['category-list'] = "settings/category";
$route['add-category'] = "settings/add_category";
$route['category-delete/(:any)'] = "settings/delete_category/$1";
$route['category-edit/(:any)'] = "settings/edit_category/$1";


$route['subcategory-list'] = "settings/sub_category";
$route['add-subcategory'] = "settings/add_subcategory";
$route['subcategory-delete/(:any)'] = "settings/delete_subcategory/$1";
$route['subcategory-edit/(:any)'] = "settings/edit_subcategory/$1";

$route['grn-type-list'] = "settings/grn_list";
$route['add-grn-type'] = "settings/add_grn_type";
$route['grn-type-delete/(:any)'] = "settings/delete_grn/$1";
$route['edit-grn-type/(:any)'] = "settings/edit_grn_type/$1";

$route['sales-type-list'] ="settings/sales_type_list";
$route['add-sales-type'] = "settings/add_sales_type";
$route['sales-type-delete/(:any)'] = "settings/delete_sales_type/$1";

$route['vendor-list'] ="settings/vendor_list";

$route['size-list'] = "settings/size_list";
$route['add-size'] = "settings/add_size";
$route['delete-size/(:any)'] = "settings/delete_size/$1";
$route['size-edit/(:any)'] = "settings/edit_size/$1";


$route['model-list'] = "settings/model_list";
$route['add-model'] = "settings/add_model";
$route['delete-model/(:any)'] = "settings/delete_model/$1";
$route['model-edit/(:any)'] = "settings/edit_model/$1";


$route['quality-list'] = "settings/quality_list";
$route['add-quality'] = "settings/add_quality";
$route['delete-quality/(:any)'] = "settings/delete_quality/$1";
$route['quality-edit/(:any)'] = "settings/edit_quality/$1";


$route['units-of-measure'] = "settings/units_of_measure";
$route['add-units-of-measure'] = "settings/add_units_of_measure";
$route['delete-unit/(:any)'] = "settings/delete_unit/$1";
$route['edit-unit/(:any)'] = "settings/edit_unit/$1";

$route['operations-list'] = "settings/operations_list";
$route['add-operations'] = "settings/add_operations";

$route['location-list'] = "settings/location_list";
$route['add-location'] = "settings/add_location";
$route['location-delete/(:any)'] = "settings/delete_location/$1";
$route['location-edit/(:any)'] = "settings/edit_location/$1";

$route['unit-conversion'] = "settings/unit_conversion";
$route['add-unit-conversion'] = "settings/add_unit_conversion";
$route['delete-unit-conversion/(:any)'] = "settings/delete_unit_conversion/$1";

$route['location-barcode/(:any)'] = "barcode/location_barcode/$1";
$route['box-slip/(:any)'] = "barcode/box_slip/$1";
$route['dispatch-box-slip/(:any)'] = "barcode/dispatch_box_slip/$1";

$route['branches'] = "settings/branches";
$route['add-branch'] = "settings/add_branches";
$route['delete-branch/(:any)'] = "settings/delete_branch/$1";

$route['store-list'] = "settings/store_list";
$route['add-store'] = "settings/add_store";
$route['delete-store/(:any)'] = "settings/delete_store/$1";

$route['invoice-list'] = "settings/list_invoice";
$route['add-invoice'] = "settings/add_invoice";
$route['delete-invoice/(:any)'] = "settings/delete_invoice/$1";

$route['package-type-list'] = "settings/package_type_list";
$route['add-package-type'] = "settings/add_package_type";

$route['trolley-type-list'] = "settings/trolley_type_list";
$route['add-trolley-type'] = "settings/add_trolley_type";

$route['tax-rates'] = "settings/tax_rate";
$route['add-tax-rate'] = "settings/add_tax_rate";
$route['default-tax-rates'] = "settings/default_tax_rate";
$route['general'] = "settings/general";
$route['change-branch'] = "settings/change_branch";
$route['change-store'] = "settings/change_store";
$route['tax-rate-delete/(:any)'] = "settings/delete_tax_rate/$1";

$route['supplier-option'] = "Options/index/supplier-option";

$route['add-customer'] = "Customer/index/add-customer";
$route['customer-list'] = "Customer/index/customer-list";

$route['add-employee'] = "Employee/index/add-employee";
$route['employee-list'] = "Employee/index/employee-list";

$route['department-list'] = "settings/department";
$route['add-department'] = "settings/add_department";
$route['department-delete/(:any)'] = "settings/delete_department/$1";

$route['add-supplier'] = "Suppliers/index/add-supplier";
$route['supplier-list'] = "Suppliers/index/supplier-list";
$route['add-oem'] = "Suppliers/index/add-oem";
$route['oem-list'] = "Suppliers/index/oem-list";

$route['create-sales-order'] = "Sales_order/index/create-sales-order";
$route['sales-order-list'] = "Sales_order/index/sales-order-list";
$route['get-product-qty'] = "Sales_order/index/get-product-qty";
$route['sales-order/(:any)'] = "Sales_order/index/sales-order/$1";
$route['sales-order-edit'] = "Sales_order/index/sales-order-edit";
$route['sales-order-edit/(:any)'] = "Sales_order/index/sales-order-edit/$1";

$route['create-pick-list'] = "Pick_list/index/create-pick-list";
$route['pick-list'] = "Pick_list/index/pick-list";
$route['get-order-list'] = "Pick_list/index/get-order-list";
$route['sales-order-product-list'] = "Pick_list/index/sales-order-product-list";
$route['get-pick-list-item'] = "Pick_list/index/get-pick-list-item";
$route['show-box-detail'] = "Pick_list/index/show-box-detail";
$route['save-box-detail'] = "Pick_list/index/save-box-detail";
$route['dispatch-list'] = "Pick_list/index/dispatch-list";
$route['dispatch-detail/(:any)'] = "Pick_list/index/dispatch-detail/$1";
$route['check-pick-list'] = "Pick_list/index/check-pick-list";
$route['general-issues'] = "Pick_list/index/general-issues";
$route['general-issues/(:any)'] = "Pick_list/index/general-issues/$1";
$route['general-issues-list'] = "Pick_list/index/general-issues-list";
$route['show-gi-box-detail'] = "Pick_list/index/show-gi-box-detail";
$route['po-product-list'] = "Pick_list/index/po-product-list";
$route['get-general-issue-details'] = "Pick_list/index/get-general-issue-details";

$route['get-fg-list'] = "Pick_list/index/get-fg-list";
$route['get-total-fg-list'] = "Pick_list/index/get-total-fg-list";


$route['update-no-of-stickers'] = "Pick_list/index/update-no-of-stickers";
$route['get-gi-box-detail'] = "Pick_list/index/get-gi-box-detail";

$route['create-purchase-order'] = "Purchase_order/index/create-purchase-order";
$route['purchase-order-list'] = "Purchase_order/index/purchase-order-list";
$route['purchase-order/(:any)'] = "Purchase_order/index/purchase-order/$1";

$route['product-stock'] = "Stocks/index/product-stock";
$route['product-stock/(:any)'] = "Stocks/index/product-stock/$1";

$route['product-stock-export'] = "Stocks/index/product-stock-export";
$route['product-stock-export/(:any)'] = "Stocks/index/product-stock-export/$1";

$route['grn-stock'] = "Stocks/index/grn-stock";
$route['total-stock-value'] = "Stocks/index/total-stock-value";
$route['put-away-pending'] = "Stocks/index/put-away-pending";
$route['stock-audit'] = "Stocks/index/stock-audit";
$route['location-detail'] ="Stocks/index/location-detail";
$route['box-detail'] ="Stocks/index/box-detail";
$route['save-stock-audit'] ="Stocks/index/save-stock-audit";

$route['scrap-list'] = "Scrap/index/scrap-list";
$route['create-scrap'] = "Scrap/index/create-scrap";
$route['get-box-detail'] = "Scrap/index/get-box-detail";
$route['save-scrap-detail'] = "Scrap/index/save-scrap-detail";
$route['delete-scrap-detail'] = "Scrap/index/delete-scrap-detail";
$route['delete-scrap/(:any)'] = "Scrap/index/delete-scrap/$1";

$route['create-raw-material-grn'] = "Raw_material/index/create-raw-material-grn";
$route['rm-quality-check-list'] = "Raw_material/index/rm-quality-check-list";
$route['rm-quality-check'] = "Raw_material/index/rm-quality-check";
$route['rm-grn-detail'] = "Raw_material/index/rm-grn-detail";
$route['raw-material-list'] = "Raw_material/index/raw-material-list";
$route['add-raw-material'] = "Raw_material/index/add-raw-material";
$route['get-rm-hsn-code'] = "Raw_material/index/get-rm-hsn-code";
$route['show-rm-detail'] = "Raw_material/index/show-rm-detail";
$route['show-rm-input-box'] = "Raw_material/index/show-rm-input-box";
$route['rm-box-slip/(:any)'] = "barcode/rm_box_slip/$1";
$route['rm-grn-list'] = "Raw_material/index/rm-grn-list";
$route['rm-put-away-list'] = "Raw_material/index/rm-put-away-list";
$route['rm-create-put-away'] = "Raw_material/index/rm-create-put-away";
$route['get-rm-unit'] = "Raw_material/index/get-rm-unit";
$route['raw-material-edit'] = "Raw_material/index/raw-material-edit";
$route['raw-material-delete/(:any)'] = "Raw_material/index/raw-material-delete/$1";
$route['raw-material-edit/(:any)'] = "Raw_material/index/raw-material-edit/$1";
$route['add-fg'] = "Raw_material/index/add-fg";
$route['edit-fg'] = "Raw_material/index/edit-fg";
$route['fg-list'] = "Raw_material/index/fg-list";
$route['general-issue-detail/(:any)'] = "Pick_list/index/general-issue-detail/$1";
$route['delete-bom/(:any)'] = "Raw_material/index/delete-bom/$1";

$route['fg-alias/(:any)'] = "Raw_material/index/fg-alias/$1";
$route['fg-alias-list'] = "Raw_material/index/fg-alias-list";
$route['delete-fg-alias/(:any)'] = "Raw_material/index/delete-fg-alias/$1";
$route['delete-alias/(:any)'] = "Raw_material/index/delete-alias/$1";
$route['get-unit-list'] = "Semi_finish_good/index/get-unit-list";
$route['get-unit-dropdown'] = "Semi_finish_good/index/get-unit-dropdown";

$route['create-bom'] = "Raw_material/index/create-bom";
$route['bom-list'] = "Raw_material/index/bom-list";
$route['bom-detail/(:any)'] = "Raw_material/index/bom-detail/$1";
$route['bom-edit/(:any)'] = "Raw_material/index/bom-edit/$1";
$route['get-bom-items'] = "Raw_material/index/get-bom-items";

$route['get-gi-detail'] = "Pick_list/index/get-gi-detail";

$route['sfg-list'] = "Semi_finish_good/index/sfg-list";
$route['add-sfg'] = "Semi_finish_good/index/add-sfg";
$route['edit-sfg/(:any)'] = "Semi_finish_good/index/edit-sfg/$1";
$route['delete-sfg/(:any)'] = "Semi_finish_good/index/delete-sfg/$1";
$route['sfg-grn-list'] = "Semi_finish_good/index/sfg-grn-list";
$route['get-sfg-hsn-code'] = "Semi_finish_good/index/get-sfg-hsn-code";
$route['create-sfg-grn'] = "Semi_finish_good/index/create-sfg-grn";
$route['sfg-quality-check-list'] = "Semi_finish_good/index/sfg-quality-check-list";
$route['sfg-quality-check'] = "Semi_finish_good/index/sfg-quality-check";
$route['sfg-grn-detail'] = "Semi_finish_good/index/sfg-grn-detail";
$route['sfg-put-away-list'] = "Semi_finish_good/index/sfg-put-away-list";
$route['sfg-create-put-away'] = "Semi_finish_good/index/sfg-create-put-away";
$route['sfg-box-slip/(:any)'] = "barcode/sfg_box_slip/$1";
$route['show-sfg-detail'] = "Semi_finish_good/index/show-sfg-detail";

$route['show-input-sfg-box'] = "Semi_finish_good/index/show-input-sfg-box";
$route['get-grn-items'] = "Semi_finish_good/index/get-grn-items";

$route['(:any)'] = "Products/index/$1";
$route['product-edit/(:any)'] = "Products/index/product-edit/$1";
$route['add-product/(:any)'] = "Products/index/add-product/$1";
$route['product-delete/(:any)'] = "Products/index/product-delete/$1";
$route['product-alias/(:any)'] = "Products/index/product-alias/$1";
$route['delete-product-alias/(:any)'] = "Products/index/delete-product-alias/$1";


