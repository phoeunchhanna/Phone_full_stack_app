<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchasePaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Report\ProfitAndLossReportController;
use App\Http\Controllers\Report\SaleReturnReportController;
use App\Http\Controllers\Report\PurchasesReportController;
use App\Http\Controllers\Report\SalesReportController;
use App\Http\Controllers\Report\StocksReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SalePaymentController;
use App\Http\Controllers\SaleReturnController;
use App\Http\Controllers\SaleReturnPaymentController;
use App\Http\Controllers\SellerDashboardController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('userhome');
});
Route::get('/', [ClientController::class, 'userhome'])->name('client.home');
Route::get('/pro_client/{id}', [ClientController::class, 'pro_show'])->name('product_client.show');
Route::get('/pro_list_client/{id}', [ClientController::class, 'pro_list_show'])->name('product_client.list');

// show category of product
Route::get('/categories/{id}', [ClientController::class, 'show'])->name('categories.show');

// Show products by brand
Route::get('/brand/{id}/products', [ClientController::class, 'productsByBrand'])->name('brand.products');
// Show products by category
Route::get('/category/{id}', [ClientController::class, 'productsByCategory'])->name('category.products');

Route::get('/add-to-cart/{id}', [ClientController::class, 'addToCart'])->name('add.to.cart');
Route::post('/update-cart', [ClientController::class, 'updateCart'])->name('cart.update');
Route::delete('/cart/remove', [ClientController::class, 'removeFromCart'])->name('cart.remove');
Route::get('/cart', [ClientController::class, 'viewCart'])->name('cart.view');

// Checkout Routes
Route::get('/checkout', [ClientController::class, 'checkout'])->name('checkout');
Route::post('/checkout/process', [ClientController::class, 'processCheckout'])->name('checkout.process');
Route::get('/order/confirmation', [ClientController::class, 'orderConfirmation'])->name('order.confirmation');
Route::get('/order/print', [ClientController::class, 'printOrder'])->name('order.print');
// increase decrease
Route::post('/cart/increase/{id}', [ClientController::class, 'increaseQuantity'])->name('cart.increase');
Route::post('/cart/decrease/{id}', [ClientController::class, 'decreaseQuantity'])->name('cart.decrease');
Route::post('/cart/update/{id}', [ClientController::class, 'updateQuantity'])->name('cart.update');

Route::get('/contact', [ClientController::class, 'showContactForm'])->name('contact.form');
Route::post('/contact', [ClientController::class, 'submitContactForm'])->name('contact.submit');


// client side 


// Login route
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/user/home', [HomeController::class, 'userhome'])->name('userhome');
    Route::get('/seller/dashboard', [SellerDashboardController::class, 'index'])->name('seller.dashboard');
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
    //cart purchase
    Route::post('/purchases/cart/add', [PurchaseController::class, 'add'])->name('purchases.cart.add');
    Route::get('/purchases/cart', [PurchaseController::class, 'show'])->name('purchases.cart.show');
    Route::get('/purchases/cart/items', [PurchaseController::class, 'getCarts'])->name('purchases.cart.items');
    Route::post('/purchases/cart/delete', [PurchaseController::class, 'delete'])->name('purchases.cart.delete');
    Route::post('/purchases/cart/clear', [PurchaseController::class, 'clear'])->name('purchases.cart.clear');
    Route::post('/purchases/cart/updateQuantity', [PurchaseController::class, 'updateQuantity'])->name('purchases.cart.updateQuantity');

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
    //Sale Return Payments
    Route::get('sale_return_payments/', [SaleReturnPaymentController::class, 'index'])->name('sale_return_payments.index');
    Route::get('sale_return_payments/create/{sale_return_id}', [SaleReturnPaymentController::class, 'create'])->name('sale_return_payments.create');
    Route::post('sale_return_payments/store', [SaleReturnPaymentController::class, 'store'])->name('sale_return_payments.store');
    Route::get('sale_return_payments/{sale_return_id}/edit/{saleReturnPayment}', [SaleReturnPaymentController::class, 'edit'])->name('sale_return_payments.edit');
    Route::put('sale_return_payments/{saleReturnPayment}', [SaleReturnPaymentController::class, 'update'])->name('sale_return_payments.update');
    Route::delete('sale_return_payments/{saleReturnPayment}', [SaleReturnPaymentController::class, 'destroy'])->name('sale_return_payments.destroy');

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

    Route::get('/carts', [SaleReturnController::class, 'getCarts'])->name('sale-returns.cart.items');
    Route::delete('/cart/delete', [SaleReturnController::class, 'delete'])->name('sale-returns.cart.delete');
    Route::post('/sale-returns/store', [SaleReturnController::class, 'store'])->name('salereturns.store');
    Route::get('/sale-returns', [SaleReturnController::class, 'index'])->name('salereturns.index');

    Route::get('/sale-return-payments', [SaleReturnPaymentController::class, 'index'])->name('sale_return_payments.index');
    Route::get('/sale-return-payments/create/{sale_return_id}', [SaleReturnPaymentController::class, 'create'])->name('sale_return_payments.create');
    Route::post('/sale-return-payments/store', [SaleReturnPaymentController::class, 'store'])->name('sale_return_payments.store');
    Route::get('/sale-return-payments/{sale_return_id}/edit/{saleReturnPayment}', [SaleReturnPaymentController::class, 'edit'])->name('sale_return_payments.edit');
    Route::put('/sale-return-payments/update/{saleReturnPayment}', [SaleReturnPaymentController::class, 'update'])->name('sale_return_payments.update');
    Route::delete('/sale-return-payments/{saleReturnPayment}', [SaleReturnPaymentController::class, 'destroy'])->name('sale_return_payments.destroy');

    Route::get('/sale-return/invoice/{saleReturnId}', [SaleReturnController::class, 'showSaleReturnInvoice'])->name('sale-return.invoice');
    Route::get('/sale-returns/invoice/{id}', [SaleReturnController::class, 'getInvoice']);
    // Route::get('/sale-returns/reference', [SaleReturnController::class, 'showReferenceForm'])->name('sale_returns.reference');
    Route::resource('sale-returns', SaleReturnController::class);
    Route::post('sale-returns/getSaleDetails', [SaleReturnController::class, 'getSaleDetails'])->name('sale_returns.getSaleDetails');
    Route::get('/sale-returns/cart/items', [SaleReturnController::class, 'getCarts'])->name('sale_returns.cart.items');
    Route::post('/sale-returns/cart/updateQuantity', [SaleReturnController::class, 'updateQuantity'])->name('sale_returns.cart.updateQuantity');
    //Sale Report management
    Route::get('/sales-report', [SalesReportController::class, 'index'])->name('sales.report.index');
    Route::get('/sales-report/print', [SalesReportController::class, 'printReport'])->name('sales.report.print');
    Route::post('/sales-report/filter', [SalesReportController::class, 'SaleReport'])->name('sales.report.filter');
    Route::get('/sales-report/export', [SalesReportController::class, 'exportReport'])->name('sales.report.export');
    //Purchase Report management
    Route::get('/purchases-report', [PurchasesReportController::class, 'index'])->name('purchases.report.index');
    Route::get('/purchases-report/print', [PurchasesReportController::class, 'printReport'])->name('purchases.report.print');
    Route::post('/purchases-report/filter', [PurchasesReportController::class, 'PurchaseReport'])->name('purchases.report.filter');
    Route::get('/purchases-report/export', [PurchasesReportController::class, 'exportReport'])->name('purchases.report.export');
    //Expense Report management
    Route::get('/reports/profit-loss', [ProfitAndLossReportController::class, 'profitLoss'])->name('reports.profit-loss');
    
    //Report Sale Return 
    Route::get('/sale-return-report', [SaleReturnReportController::class, 'index'])->name('sale-return.report.index');
    Route::post('/sale-return-report/filter', [SaleReturnReportController::class, 'SaleReturnReport'])->name('sale-return.report.filter');
    Route::get('/sale-return-report/print', [SaleReturnReportController::class, 'printReport'])->name('sale-return.report.print');
    Route::get('/sale-return/export', [SaleReturnReportController::class, 'exportReport'])->name('sale-return.export');
    //Report Stock
    Route::get('/stock-report', [StocksReportController::class, 'index'])->name('stock.report.index');
    Route::get('/stock-report/get-categories', [StocksReportController::class, 'getCategoriesByBrand'])->name('stocks.report.getCategories');
    Route::get('/stock-report/get-products', [StocksReportController::class, 'getProductsByFilters'])->name('stocks.report.getProducts');
    Route::get('/stock-report/export', [StocksReportController::class, 'exportExcel'])->name('stock.report.export.excel');

});
Auth::routes();
