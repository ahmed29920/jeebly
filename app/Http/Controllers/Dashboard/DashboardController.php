<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Overall
        $totalSales = Order::where('status', '!=', 'canceled')->sum('final_total');
        $totalOrders = Order::count();
        $totalCustomers = User::where('role', 'user')->count();
        $averageOrderSale = $totalOrders > 0 ? $totalSales / $totalOrders : 0;
        $unpaidInvoices = Order::where('payment_status', 'pending')->sum('final_total');

        // Today
        $todaysSales = Order::whereDate('created_at', $today)->sum('final_total');
        $todaysOrders = Order::whereDate('created_at', $today)->count();
        $todaysCustomers = User::whereDate('created_at', $today)->where('role', 'user')->count();

        // Pending Orders (اللي لسه متنفذتش أو لسه under processing)
        $pendingOrders = Order::where('status', 'pending')->count();

        // Low Stock Products (products with pack sizes that have stock less than 5 in all pack sizes)
        // $lowStockProducts = Product::with('packSizes')->where(function ($query) {
        //     $query->whereHas('packSizes', function ($query) {
        //         $query->where('stock', '<=', 5);
        //     });
        // })->get();


        $lowStockProducts = Product::where(function ($q) {
            // simple products with stock less than 5 in a branch stock
            $q->where(function ($sq) {
                $sq->where('type', 'simple')
                    ->whereHas('branchProductStocks', function ($sq) {
                        $sq->where('quantity', '<=', 5);
                    });
            });

            // variable products
            $q->orWhereHas('variants', function ($sq) {
                $sq->whereHas('branchVariantStocks', function ($sq) {
                    $sq->where('quantity', '<=', 5);
                });
            });
        })->get();

        // Top 5 Customers
        $topCustomers = User::select('users.id', 'users.name', 'users.image', DB::raw('COUNT(orders.id) as orders_count'))
            ->join('orders', 'orders.user_id', '=', 'users.id')
            ->groupBy('users.id', 'users.name', 'users.image')
            ->orderByDesc('orders_count')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalSales',
            'totalOrders',
            'totalCustomers',
            'averageOrderSale',
            'unpaidInvoices',
            'todaysSales',
            'todaysOrders',
            'todaysCustomers',
            'pendingOrders',
            'lowStockProducts',
            'topCustomers',
        ));
    }


    public function toggleLanguage(Request $request)
    {
        $current = Session::get('locale', config('app.locale'));
        $new = $current === 'en' ? 'ar' : 'en';

        Session::put('locale', $new);
        App::setLocale($new);
        return back();
    }

    public function markNotificationAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    public function statistics()
    {
        $months = collect(range(0, 11))->map(function ($i) {
            return Carbon::now()->subMonths($i)->format('Y-m');
        })->reverse()->values();

        // Orders Over Time
        $ordersOverTimeRaw = Order::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Refill missing months with 0
        $ordersOverTime = $months->map(function ($month) use ($ordersOverTimeRaw) {
            return [
                'month' => $month,
                'total' => $ordersOverTimeRaw[$month] ?? 0,
            ];
        });

        // Customers Over Time
        $customersOverTimeRaw = User::where('role', 'user')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $customersOverTime = $months->map(function ($month) use ($customersOverTimeRaw) {
            return [
                'month' => $month,
                'total' => $customersOverTimeRaw[$month] ?? 0,
            ];
        });
        //------------------------------------------------------
        // Top Selling Products
        $topSellingProducts = Product::select('products.id', 'products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->join('order_items', 'order_items.product_id', '=', 'products.id')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();


        // Customers With Most Orders
        $topCustomers = User::withCount('orders')
            ->orderByDesc('orders_count')
            ->limit(5)
            ->get(['id', 'name']);

        // Products Quantities Sold Over Time

        $productsSoldOverTime = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->selectRaw('
        products.id,
        products.name,
        DATE_FORMAT(order_items.created_at, "%Y-%m") as month,
        SUM(order_items.quantity) as total_sold
    ')
            ->where('order_items.created_at', '>=', now()->subMonths(12))
            ->groupBy('products.id', 'products.name', 'month')
            ->orderBy('month')
            ->get();


        // Most Products Added To Wishlist
        $wishlistProducts = Product::withCount('favoritedBy')
            ->orderByDesc('favorited_by_count')
            ->limit(10)
            ->get(['id', 'name']);

        // Products With Most Reviews
        $topReviewedProducts = Product::withCount('reviews')
            ->orderByDesc('reviews_count')
            ->limit(10)
            ->get();


        return view('dashboard.statistics', compact(
            'topSellingProducts',
            'ordersOverTime',
            'customersOverTime',
            'topCustomers',
            'productsSoldOverTime',
            'wishlistProducts',
            'topReviewedProducts'
        ));
    }

}
