<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AttributeController;
use App\Http\Controllers\Api\BookingListController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\ZoneController;

use App\Http\Controllers\Api\Branch\DashboardController as BranchDashboardController;
use App\Http\Controllers\Api\Branch\CategoryController as BranchCategoryController;
use App\Http\Controllers\Api\Branch\EmployeeController as BranchEmployeeController;
use App\Http\Controllers\Api\Branch\OrderController as BranchOrderController;
use App\Http\Controllers\Api\Branch\ProductController as BranchProductController;
use App\Http\Controllers\Api\Branch\StockHistoryController as BranchStockHistoryController;
use App\Http\Controllers\Api\Branch\TransactionController as BranchTransactionController;
use App\Http\Controllers\Api\Branch\DeliveryController as BranchDeliveryController;

use App\Http\Controllers\Api\Delivery\DeliveryController as DeliveryController;
use App\Http\Controllers\Api\Delivery\LocationController as DeliveryLocationController;
use App\Http\Controllers\Api\Delivery\OrderController as DeliveryOrderController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\SocketAuthController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/socket/authorize', [SocketAuthController::class, 'authorize']);

// Broadcasting authentication route
Broadcast::routes(['middleware' => ['auth:sanctum']]);

require __DIR__.'/apiAuth.php';

// language
Route::post('/change-language', [LanguageController::class, 'switch']);

Route::get('/settings', [SettingController::class, 'index']);
Route::get('/policies', [SettingController::class, 'policies']);
Route::get('/zones', [ZoneController::class, 'index']);


// User
Route::group(['middleware' => ['locale'], 'prefix' => 'user'], function () {
    // categories
    Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);

    // attributes
    Route::get('attributes', [AttributeController::class, 'index']);

    // products
    Route::get('products/search', [ProductController::class, 'search']);
    Route::apiResource('products', ProductController::class)->only(['index', 'show']);
 // sliders
    Route::get('sliders', [SliderController::class, 'index']);
});
Route::group(['middleware' => ['auth:sanctum']], function () {
    # notifications 
    Route::get('/notifications', [AuthController::class, 'notifications']); 
    Route::post('/notifications/{id}/read', [AuthController::class, 'markAsRead']); 
    Route::post('/notifications/read-all', [AuthController::class, 'markAllAsRead']); 
    
    # fcm token 
    Route::post('/update-fcm-token', [AuthController::class, 'updateFcmToken']); 
});


Route::group(['middleware' => ['auth:sanctum', 'locale', 'role:user'], 'prefix' => 'user'], function () {
    
        
    

    // // categories
    // Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);

    // // attributes
    // Route::get('attributes', [AttributeController::class, 'index']);

    // // products
    // Route::get('products/search', [ProductController::class, 'search']);
    // Route::apiResource('products', ProductController::class)->only(['index', 'show']);
    Route::post('products/{product}/toggle-favorite', [ProductController::class, 'toggleFavorite']);

    // reviews
    Route::post('products/{product}/review', [ReviewController::class, 'store']);

    // favorite-list
    Route::get('favorite-list', [ProductController::class, 'favoriteList']);

    // coupon
    Route::post('/cart/apply-coupon', [CouponController::class, 'apply']);

    // cart
    Route::get('cart', [CartController::class, 'index']);
    Route::post('cart/{product}', [CartController::class, 'add']);
    Route::put('cart/{product}', [CartController::class, 'updateQuantity']);
    Route::delete('cart/{product}', [CartController::class, 'remove']);
    Route::delete('cart', [CartController::class, 'clear']);

    // checkout
    Route::get('/checkout', [CheckoutController::class, 'index']);

    // orders
    Route::apiResource('/orders', OrderController::class)->only(['index', 'show', 'store']);
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel']);
    Route::get('orders/{order}/delivery-location', [OrderController::class, 'deliveryLocation'])->name('orders.delivery-location');

    // addresses
    Route::apiResource('addresses', AddressController::class)->only(['index', 'store','destroy']);

    // transactions
    Route::apiResource('transactions', TransactionController::class)->only(['index', 'show']);
    Route::post('transactions/{transaction}/pay', [TransactionController::class, 'pay']);

    // tickets
    Route::apiResource('tickets', TicketController::class);
    Route::post('/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');

    // branches
    Route::get('branches', [BranchController::class, 'index']);

    // offers
    Route::get('offers', [OfferController::class, 'index']);

    // // sliders
    // Route::get('sliders', [SliderController::class, 'index']);

    // booking lists
    Route::apiResource('booking-lists', BookingListController::class);
    Route::post('/booking-lists/{bookingList}/cancel', [BookingListController::class, 'cancel']);
});

// Branch
Route::group(['middleware' => ['auth:sanctum', 'locale', 'role:employee'], 'prefix' => 'branch'], function () {


    Route::get('categories/export', [BranchCategoryController::class, 'export'])->name('categories.export');
    Route::resource('categories', BranchCategoryController::class)->only(['index', 'show']);
    Route::post('bulk-categories', [BranchCategoryController::class, 'bulk'])->name('categories.bulk');

    // Route::get('dashboard', [BranchDashboardController::class, 'index'])->name('dashboard');

    Route::get('statistics', [BranchDashboardController::class, 'statistics'])->name('statistics');
    Route::get('home-statistics', [BranchDashboardController::class, 'homeStats'])->name('home-stats');
    Route::post('toggle-online-status', [BranchDashboardController::class, 'toggleOnlineStatus']);

    Route::post('/notifications/{id}/mark-as-read', [BranchDashboardController::class, 'markNotificationAsRead'])->name('notifications.markAsRead');

    Route::post('products/{product}/update-branch-stocks', [BranchProductController::class, 'updateBranchStocks'])->name('products.updateBranchStocks');
    Route::resource('products', BranchProductController::class)->only(['index', 'show']);

    Route::get('orders/export', [BranchOrderController::class, 'export'])->name('orders.export');
    Route::resource('orders', BranchOrderController::class)->only(['index', 'show', 'update']);
    Route::post('orders/{order}/create-invoice', [BranchOrderController::class, 'createInvoice'])->name('orders.create-invoice');
    Route::get('orders/{order}/download-invoice', [BranchOrderController::class, 'downloadInvoice'])->name('orders.download-invoice');
    Route::post('orders/{order}/comments', [BranchOrderController::class, 'storeComment'])->name('orders.comments.store');
    Route::post('orders/{order}/transfer-to-admin', [BranchOrderController::class, 'transferToAdmin'])->name('orders.transfer-to-admin');
    Route::post('orders/{order}/assign-delivery', [BranchOrderController::class, 'assignDelivery'])->name('orders.assign-delivery');
    Route::get('orders/{order}/delivery-location', [BranchOrderController::class, 'deliveryLocation'])->name('orders.delivery-location');

    Route::get('transactions/export', [BranchTransactionController::class, 'export'])->name('transactions.export');

    Route::resource('transactions', BranchTransactionController::class)->only(['index', 'show', 'destroy']);

    // branch stock history
    Route::get('stock-history', [\App\Http\Controllers\Branch\StockHistoryController::class, 'index'])->name('stock-history.index');

    // employees (branch scoped)
    Route::resource('employees', \App\Http\Controllers\Branch\EmployeeController::class)
        ->only(['index', 'create', 'show', 'edit', 'update', 'store', 'destroy']);

    // deliveries (branch scoped)
    Route::apiResource('deliveries', BranchDeliveryController::class);

});

// Delivery
Route::group(['middleware' => ['auth:sanctum', 'locale', 'role:delivery'], 'prefix' => 'delivery'], function () {

    // delivery locations
    Route::post('update-location', [DeliveryLocationController::class, 'updateLocation']);

    // orders
    Route::apiResource('orders', DeliveryOrderController::class)->only(['index', 'show']);
    Route::post('orders/{order}/start-delivery', [DeliveryOrderController::class, 'startDelivery']);
    Route::post('orders/{order}/complete', [DeliveryOrderController::class, 'complete']);

    // wallet history
    Route::get('wallet-history', [DeliveryOrderController::class, 'walletHistory']);

    // set online
    Route::post('set-online', [DeliveryController::class, 'setOnline']);

    // set offline
    Route::post('set-offline', [DeliveryController::class, 'setOffline']);

    Route::post('orders/{order}/route',[OrderController::class,'updateRoute']);
});
