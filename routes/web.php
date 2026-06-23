<?php

use App\Http\Controllers\Dashboard\AttributeController;
use App\Http\Controllers\Dashboard\Auth\LoginController;
use App\Http\Controllers\Dashboard\Auth\ProfileController;
use App\Http\Controllers\Dashboard\BranchController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\CouponController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\OfferController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\PermissionController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\ReviewController;
use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\TicketController;
use App\Http\Controllers\Dashboard\TransactionController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\VariantController;
use App\Http\Controllers\Dashboard\UnitController;
use App\Http\Controllers\Dashboard\EmployeeController;
use App\Http\Controllers\Dashboard\BookingListController;
use App\Http\Controllers\Dashboard\SliderController;
use App\Http\Controllers\Dashboard\DeliveryController;
use App\Http\Controllers\Dashboard\ZoneController;
use App\Services\SocketService;


use App\Exports\ProductTemplateExport;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

use App\Http\Controllers\Branch\BranchController as BranchBranchController;
use App\Http\Controllers\Branch\CategoryController as BranchCategoryController;
use App\Http\Controllers\Branch\DashboardController as BranchDashboardController;
use App\Http\Controllers\Branch\OrderController as BranchOrderController;
use App\Http\Controllers\Branch\TransactionController as BranchTransactionController;
use App\Http\Controllers\Branch\ProductController as BranchProductController;
use App\Http\Controllers\Branch\DeliveryController as BranchDeliveryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Dashboard\AdminNotificationController;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Redis;

Route::get('/test-socket', function () {
    SocketService::emit(
        'test.event',
        ['message' => 'Hello from Geeble!', 'time' => now()->toDateTimeString()],
        'user-1'
    );
    return response()->json(['sent' => true]);
});

Route::get('products/template', function () {
    return Excel::download(new ProductTemplateExport, 'products_template.xlsx');
})->name('products.template');

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});


Route::get('test-fb', [NotificationController::class, 'test']);
Route::get('/debug-user', function () {
    return auth()->user();
})->middleware('auth:sanctum');

// toggle-language
Route::post('toggle-language', [DashboardController::class, 'toggleLanguage'])->name('toggle-language');

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login.get');
    Route::post('login', [LoginController::class, 'login'])->name('login');
});

// admin routes
Route::prefix('admin')->as('admin.')->middleware('locale')->group(function () {

    // Profile routes
    Route::get('profile', [ProfileController::class, 'showProfile'])->name('profile');
    Route::post('profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');


    Route::middleware(['auth', 'notUser'])->group(function () {

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('statistics', [DashboardController::class, 'statistics'])->name('statistics');
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
        Route::post('/notifications/{id}/mark-as-read', [DashboardController::class, 'markNotificationAsRead'])->name('notifications.markAsRead');


        # settings
        Route::controller(SettingController::class)->middleware(['role_or_permission:admin|Settings'])->group(function () {
            Route::get('settings', 'index')->name('settings.index');
            Route::put('settings', 'update')->name('settings.update');
        });

        # categories
        Route::group(['middleware' => ['role_or_permission:admin|Categories']], function () {
            Route::get('categories/export', [CategoryController::class, 'export'])->name('categories.export');
            Route::resource('categories', CategoryController::class);
            Route::post('bulk-categories', [CategoryController::class, 'bulk'])->name('categories.bulk');
        });


        # attributes
        Route::group(['middleware' => ['role_or_permission:admin|Attributes']], function () {
            Route::resource('attributes', AttributeController::class);
            Route::post('bulk-attributes', [AttributeController::class, 'bulk'])->name('attributes.bulk');
        });

        # products
        Route::group(['middleware' => ['role_or_permission:admin|Products']], function () {
            Route::get('products/template', function () {
                return Excel::download(new ProductTemplateExport, 'products_template.xlsx');
            })->name('products.template');
            Route::get('products/import', [ProductController::class, 'importPage'])->name('products.import');
            Route::post('products/import', [ProductController::class, 'import'])->name('products.import');
            Route::any('products/{productImage}/remove-image', [ProductController::class, 'removeImage'])->name('products.removeImage');

            Route::get('products/export', [ProductController::class, 'export'])->name('products.export');
            Route::post('bulk-products', [ProductController::class, 'bulk'])->name('products.bulk');
            Route::get('search-products', [ProductController::class, 'search'])->name('products.search');
            Route::any('products/{product}/update-bookable', [ProductController::class, 'updateBookable'])->name('products.updateBookable');
            Route::post('products/{product}/update-branch-stocks', [ProductController::class, 'updateBranchStocks'])->name('products.updateBranchStocks');
            Route::resource('products', ProductController::class);

        });

        # coupons
        Route::group(['middleware' => ['role_or_permission:admin|Coupons']], function () {
            Route::resource('coupons', CouponController::class);
            Route::post('bulk-coupons', [CouponController::class, 'bulk'])->name('coupons.bulk');
        });

        # orders
        Route::group(['middleware' => ['role_or_permission:admin|Orders']], function () {
            Route::get('orders/export', [OrderController::class, 'export'])->name('orders.export');
            Route::resource('orders', OrderController::class)->only(['index', 'show', 'update']);
            Route::post('orders/{order}/create-invoice', [OrderController::class, 'createInvoice'])->name('orders.create-invoice');
            Route::get('orders/{order}/download-invoice', [OrderController::class, 'downloadInvoice'])->name('orders.download-invoice');
            Route::post('orders/{order}/comments', [OrderController::class, 'storeComment'])->name('orders.comments.store');
            Route::post('orders/{order}/assign-to-branch', [OrderController::class, 'assignToBranch'])->name('orders.assign-to-branch');
            Route::get('orders/{order}/delivery-location', [OrderController::class, 'deliveryLocation'])->name('orders.delivery-location');
        });

        # booking lists
        Route::group(['middleware' => ['role_or_permission:admin|Orders']], function () {
            Route::resource('booking-lists', BookingListController::class)->only(['index', 'show', 'edit', 'update']);
        });

        # tickets
        Route::group(['middleware' => ['role_or_permission:admin|Tickets']], function () {
            Route::resource('tickets', TicketController::class)->only(['index', 'show', 'update', 'destroy']);
            Route::post('tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
            Route::get('tickets/filter', [TicketController::class, 'filter'])->name('tickets.filter');
            Route::post('tickets/{ticket}/change-status', [TicketController::class, 'changeStatus'])->name('tickets.changeStatus');
        });

        # reviews
        Route::group(['middleware' => ['role_or_permission:admin|Reviews']], function () {
            Route::resource('reviews', ReviewController::class)->only(['index', 'update', 'destroy']);
            Route::post('bulk-reviews', [ReviewController::class, 'bulk'])->name('reviews.bulk');
        });

        # transactions
        Route::group(['middleware' => ['role_or_permission:admin|Reviews']], function () {
            Route::get('transactions/export', [TransactionController::class, 'export'])->name('transactions.export');

            Route::resource('transactions', TransactionController::class)->only(['index', 'show', 'destroy']);
        });

        # offers
        Route::group(['middleware' => ['role_or_permission:admin|Offers']], function () {
            Route::get('offers/export', [OfferController::class, 'export'])->name('offers.export');
            Route::resource('offers', OfferController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
            Route::patch('offers/{offer}/toggle-status', [OfferController::class, 'toggleStatus'])->name('offers.toggle-status');


        });

        # branches
        Route::group(['middleware' => ['role_or_permission:admin|Branches']], function () {
            Route::resource('branches', BranchController::class);
        });

        # units
        Route::group(['middleware' => ['role_or_permission:admin|Units']], function () {
            Route::resource('units', UnitController::class);
            Route::post('bulk-units', [UnitController::class, 'bulk'])->name('units.bulk');
        });

        # employees
        Route::group(['middleware' => ['role_or_permission:admin|Employees']], function () {
            Route::resource('employees', EmployeeController::class);
            Route::post('bulk-employees', [EmployeeController::class, 'bulk'])->name('employees.bulk');
        });

        # variants
        Route::group(['middleware' => ['role_or_permission:admin|Variants']], function () {
            Route::resource('variants', VariantController::class);
        });

        # sliders
        Route::group(['middleware' => ['role_or_permission:admin|Sliders']], function () {
            Route::resource('sliders', SliderController::class);
        });

        # deliveries
        Route::group(['middleware' => ['role_or_permission:admin|Deliveries']], function () {
            Route::resource('deliveries', DeliveryController::class);
            Route::post('bulk-deliveries', [DeliveryController::class, 'bulk'])->name('deliveries.bulk');
        });

        # zones
        Route::group(['middleware' => ['role_or_permission:admin|Zones']], function () {
            Route::resource('zones', ZoneController::class);
        });

        # users and roles
        Route::group(['middleware' => 'role:admin'], function () {
            # roles
            Route::resource('roles', RoleController::class);

            # users
            Route::get('users/role/{role}', [UserController::class, 'index'])->name('users.index');
            Route::resource('users', UserController::class)->except(['index']);

	 # admin notifications
            Route::get('notifications/send', [AdminNotificationController::class, 'create'])->name('notifications.create');
            Route::post('notifications/send', [AdminNotificationController::class, 'store'])->name('notifications.store');


            # permissions
            // Route::resource('permissions', PermissionController::class);
        });
    });
});


// branch routes
Route::prefix('branch')->as('branch.')->middleware('locale')->group(function () {



    // Profile routes
    Route::get('profile', [ProfileController::class, 'showProfile'])->name('profile');
    Route::post('profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');


    Route::middleware(['auth', 'notUser'])->group(function () {


        # categories
        Route::group(['middleware' => ['role:employee']], function () {
            Route::get('categories/export', [BranchCategoryController::class, 'export'])->name('categories.export');
            Route::resource('categories', BranchCategoryController::class)->only(['index', 'show']);
            Route::post('bulk-categories', [BranchCategoryController::class, 'bulk'])->name('categories.bulk');
        });



        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('dashboard', [BranchDashboardController::class, 'index'])->name('dashboard');
        Route::get('statistics', [BranchDashboardController::class, 'statistics'])->name('statistics');
        Route::post('/notifications/{id}/mark-as-read', [BranchDashboardController::class, 'markNotificationAsRead'])->name('notifications.markAsRead');


        # products
        Route::group(['middleware' => ['role:employee']], function () {

            Route::post('products/{product}/update-branch-stocks', [BranchProductController::class, 'updateBranchStocks'])->name('products.updateBranchStocks');
            Route::resource('products', BranchProductController::class)->only(['index', 'show']);

        });

        # orders
        Route::group(['middleware' => ['role:employee']], function () {
            Route::get('orders/export', [BranchOrderController::class, 'export'])->name('orders.export');
            Route::resource('orders', BranchOrderController::class)->only(['index', 'show', 'update']);
            Route::post('orders/{order}/create-invoice', [BranchOrderController::class, 'createInvoice'])->name('orders.create-invoice');
            Route::get('orders/{order}/download-invoice', [BranchOrderController::class, 'downloadInvoice'])->name('orders.download-invoice');
            Route::post('orders/{order}/comments', [BranchOrderController::class, 'storeComment'])->name('orders.comments.store');
            Route::post('orders/{order}/transfer-to-admin', [BranchOrderController::class, 'transferToAdmin'])->name('orders.transfer-to-admin');
        });


        # transactions
        Route::group(['middleware' => ['role:employee']], function () {
            Route::get('transactions/export', [BranchTransactionController::class, 'export'])->name('transactions.export');

            Route::resource('transactions', BranchTransactionController::class)->only(['index', 'show', 'destroy']);

            # branch stock history
            Route::get('stock-history', [\App\Http\Controllers\Branch\StockHistoryController::class, 'index'])->name('stock-history.index');

            # employees (branch scoped)
            Route::resource('employees', \App\Http\Controllers\Branch\EmployeeController::class)
                ->only(['index', 'create','show','edit', 'update', 'store', 'destroy']);

            # deliveries (branch scoped)
            Route::resource('deliveries', BranchDeliveryController::class);
        });


    });
});
Route::get('websocket-test', function () {
    return view('websocket-test');
})->name('websocket-test');

Broadcast::routes(['middleware' => ['web', 'auth']]);
