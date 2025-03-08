<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchasePaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SalePaymentController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SaleReturnController;
use Illuminate\Support\Facades\Route;

// Login route
Route::get('/', function () {
    return view('auth.login');
})->name('login');
Route::middleware(['auth', 'user_type:admin'])->group(function () {
    // Admin User Management
    Route::resource('users', UserController::class);

    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/{id}', [UserController::class, 'update'])->name('users.update');

});
// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/user/home', [HomeController::class, 'userhome'])->name('userhome');


    // ========================================================resource==============================================================
// Category management
    Route::resource('categories', CategoryController::class);

// Brands management
    Route::resource('brands', BrandController::class);

// Products management
    Route::resource('products', ProductController::class);

// Sales management
    Route::resource('sales', SaleController::class);

// Supplier management
    Route::resource('suppliers', SupplierController::class);

// Customer management
    Route::resource('customers', CustomerController::class);

// Purchase management
    Route::resource('purchases', PurchaseController::class);

// Expense management
    Route::resource('expenses', ExpenseController::class);

// Expense category management
    Route::resource('expense_categories', ExpenseCategoryController::class);

    //profile
    Route::get('profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::get('profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::patch('profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::patch('/profile/update-password', [UserController::class, 'updatePassword'])->name('profile.update.password');

    Route::get('/load-more-products', [ProductController::class, 'loadMoreProducts'])->name('products.loadMore');
    Route::post('/cart/checkout', [ProductController::class, 'exportToSale'])->name('cart.checkout');
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/export/sales', [SaleController::class, 'exportSalesToExcel'])->name('export.sales');
    Route::post('/products/search', [ProductController::class, 'search'])->name('products.search');
    Route::get('/products/category/{categoryName}', [PosController::class, 'searchByCategory']);
    Route::get('/export-products-excel', [ProductController::class, 'exportProductsToExcel'])->name('export-products-excel');
    //pos management
    Route::get('/pos/search', [PosController::class, 'search'])->name('products.search');
    Route::get('/pos/details', [PosController::class, 'getDetails'])->name('products.getDetails');
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos', [PosController::class, 'store'])->name('pos.store');
    Route::post('/pos/barcode', [PosController::class, 'barcodescan'])->name('pos.barcode');
    Route::post('/pos/search', [PosController::class, 'search'])->name('pos.search');
    Route::get('/products/category/{categoryName}', [PosController::class, 'searchByCategory'])->name('products.searchByCategory');
    Route::post('/pos/filter-products', [ProductController::class, 'filterProducts'])->name('filter.products');
    Route::post('/sales/confirm', [SaleController::class, 'confirmSale'])->name('sales.confirm');

    //sales
    Route::get('/sales/{sales}/print-invoice-pos', [SaleController::class, 'print_invoice_pos'])->name('sales.print-pos');
    Route::get('/sales/{sales}/print-invoice', [SaleController::class, 'print_invoice'])->name('sales.print');
    //cart sales
    Route::post('/sales/cart/add', [SaleController::class, 'add'])->name('sales.cart.add');
    Route::get('/sales/cart', [SaleController::class, 'show'])->name('sales.cart.show');
    Route::get('/sales/cart/items', [SaleController::class, 'getCarts'])->name('sales.cart.items');
    Route::post('/sales/cart/delete', [SaleController::class, 'delete'])->name('sales.cart.delete');
    Route::post('/sales/cart/clear', [SaleController::class, 'clear'])->name('sales.cart.clear');
    Route::post('/sales/cart/updateQuantity', [SaleController::class, 'updateQuantity'])->name('sales.cart.updateQuantity');
    Route::post('/sales/cart/updatediscount', [SaleController::class, 'updatediscount'])->name('sales.cart.updatediscount');


    //purchase
    Route::get('/export/purchases', [PurchaseController::class, 'exportPurchasesToExcel'])->name('export.purchases');
    //cart purchase
    Route::post('/purchases/cart/add', [PurchaseController::class, 'add'])->name('purchases.cart.add');
    Route::get('/purchases/cart', [PurchaseController::class, 'show'])->name('purchases.cart.show');
    Route::get('/purchases/cart/items', [PurchaseController::class, 'getCarts'])->name('purchases.cart.items');
    Route::post('/purchases/cart/delete', [PurchaseController::class, 'delete'])->name('purchases.cart.delete');
    Route::post('/purchases/cart/clear', [PurchaseController::class, 'clear'])->name('purchases.cart.clear');
    Route::post('/purchases/cart/updateQuantity', [PurchaseController::class, 'updateQuantity'])->name('purchases.cart.updateQuantity');
    //Report
    Route::get('admin/reports/sales', [ReportController::class, 'SaleReport'])->name('admin.reports.sales');

    Route::get('admin/reports/products', [ReportController::class, 'ProductReport'])->name('admin.reports.product');
    Route::get('admin/reports/purchases', [ReportController::class, 'PurchaseReport'])->name('admin.purchase.report');
    Route::get('/profit-loss-report', [ReportController::class, 'ProfitAndLoss'])->name('profit.loss.report');

// ========================================================payment==============================================================
    //purchase payments
    Route::get('/purchase_payments', [PurchasePaymentController::class, 'index'])->name('purchase_payments.index');
    Route::get('purchase_payments/create/{purchase_id}', [PurchasePaymentController::class, 'create'])->name('purchase_payments.create');
    Route::post('/purchase_payments', [PurchasePaymentController::class, 'store'])->name('purchase_payments.store');
    Route::get('/purchase_payments/{purchase_id}/edit/{purchase_payment}', [PurchasePaymentController::class, 'edit'])->name('purchase_payments.edit');
    Route::put('/purchase_payments/{purchase_payment}', [PurchasePaymentController::class, 'update'])->name('purchase_payments.update');
    Route::delete('/purchase_payments/{purchase_payment}', [PurchasePaymentController::class, 'destroy'])->name('purchase_payments.destroy');
    //Sale payments
    Route::get('/sale_payments', [SalePaymentController::class, 'index'])->name('sale_payments.index');
    Route::get('sale_payments/create/{sale_id}', [SalePaymentController::class, 'create'])->name('sale_payments.create');
    Route::post('/sale_payments', [SalePaymentController::class, 'store'])->name('sale_payments.store');
    Route::get('/sale_payments/{sale_id}/edit/{sale_payment}', [SalePaymentController::class, 'edit'])->name('sale_payments.edit');
    Route::put('/sale_payments/{sale_payment}', [SalePaymentController::class, 'update'])->name('sale_payments.update');
    Route::delete('/sale_payments/{sale_payment}', [SalePaymentController::class, 'destroy'])->name('sale_payments.destroy');

    Route::get('/persmissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/persmissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('/persmissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('/persmissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::post('/persmissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('/persmissions{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');


    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::post('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');


    Route::get('/permissions/search', [RoleController::class, 'searchpermission'])->name('permissions.search');

    Route::get('/permissions/search/edit', [RoleController::class, 'searchpermissionEdit'])->name('permissions.search.edit');

    Route::resource('users', UserController::class);

    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/{id}', [UserController::class, 'update'])->name('users.update');


//purchase management
// Route::get('/sales-returns', [SaleReturnController::class, 'index'])->name('salesreturn.index');
// Route::get('/sales-returns', [SaleReturnController::class, 'getSaleReturns'])->name('salesreturn.get');
// Route::get('/sales-return/create', [SaleReturnController::class, 'create'])->name('salesreturn.create');
// Route::post('/sales-return', [SaleReturnController::class, 'store'])->name('salesreturn.store');



// Route::get('/sales-return/{id}/edit', [SaleReturnController::class, 'edit'])->name('salesreturn.edit');
// Route::put('/sales-return/{id}', [SaleReturnController::class, 'update'])->name('salesreturn.update');
// Route::delete('/sales-return/{id}', [SaleReturnController::class, 'destroy'])->name('salesreturn.destroy');
// Route::delete('/sales-return/carts/{cartsalereturn}', [SaleReturnController::class, 'destroycart'])->name('salesreturn.destroycart');
// Route::put('/sales-return/carts/{cart}', [SaleReturnController::class, 'updatecart'])->name('updatecart.update');
// Route::post('/sales-return/barcode', [SaleReturnController::class, 'barcodescan'])->name('salesreturn.barcode');
// Route::post('/sales-return/search', [SaleReturnController::class, 'search'])->name('salesreturn.search');


Route::get('/carts', [SaleReturnController::class, 'getCarts'])->name('sale-returns.cart.items');
Route::delete('/cart/delete', [SaleReturnController::class, 'delete'])->name('sale-returns.cart.delete');
Route::post('/sale-returns/store', [SaleReturnController::class, 'store'])->name('salereturns.store');
Route::get('/sale-returns', [SaleReturnController::class, 'index'])->name('salereturns.index');

Route::get('/sale_return_payments', [PurchasePaymentController::class, 'index'])->name('sale_return_payments.index');
Route::get('sale_return_payments/create/{purchase_id}', [PurchasePaymentController::class, 'create'])->name('sale_return_payments.create');
Route::post('/sale_return_payments', [PurchasePaymentController::class, 'store'])->name('sale_return_payments.store');
Route::get('/sale_return_payments/{purchase_id}/edit/{purchase_payment}', [PurchasePaymentController::class, 'edit'])->name('sale_return_payments.edit');
Route::put('/sale_return_payments/{purchase_payment}', [PurchasePaymentController::class, 'update'])->name('sale_return_payments.update');
Route::delete('/sale_return_payments/{purchase_payment}', [PurchasePaymentController::class, 'destroy'])->name('sale_return_payments.destroy');

// Route::post('/purchases/cart/add', [PurchaseController::class, 'add'])->name('purchases.cart.add');
// Route::get('/purchases/cart', [PurchaseController::class, 'show'])->name('purchases.cart.show');
// Route::get('/purchases/cart/items', [PurchaseController::class, 'getCarts'])->name('purchases.cart.items');
// Route::post('/purchases/cart/delete', [PurchaseController::class, 'delete'])->name('purchases.cart.delete');
// Route::post('/purchases/cart/clear', [PurchaseController::class, 'clear'])->name('purchases.cart.clear');
// Route::post('/purchases/cart/updateQuantity', [PurchaseController::class, 'updateQuantity'])->name('purchases.cart.updateQuantity');

Route::get('/sale-return/invoice/{saleReturnId}', [SaleReturnController::class, 'showSaleReturnInvoice'])->name('sale-return.invoice');

Route::get('/sale-returns/reference', [SaleReturnController::class, 'showReferenceForm'])->name('sale_returns.reference');
Route::resource('sale-returns', SaleReturnController::class);
Route::post('sale-returns/getSaleDetails', [SaleReturnController::class, 'getSaleDetails'])->name('sale_returns.getSaleDetails');
Route::get('/sale-returns/cart/items', [SaleReturnController::class, 'getCarts'])->name('sale_returns.cart.items');
Route::post('/sale-returns/cart/updateQuantity', [SaleReturnController::class, 'updateQuantity'])->name('sale_returns.cart.updateQuantity');


Route::get('products-report/export', [ReportController::class, 'ProductReport'])->name('products-report.export');
Route::get('purchases-report/export', [ReportController::class, 'PurchaseReport'])->name('purchases-report.export');
Route::get('sales-report/export', [ReportController::class, 'SaleReport'])->name('sales-report.export');  
});
Auth::routes();
