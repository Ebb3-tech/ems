<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DepartmentController,
    UserController,
    TaskController,
    LeaveRequestController,
    DailyReportController,
    AttendanceController,
    NotificationController,
    CallCenterController,
    CeoController,
    EmployeeController,
    VendorController,
    ProductController,
    WalkInCustomerController,
    ShopController,
    ShopCustomerController,
    ChatController
};
use App\Http\Controllers\Auth\{
    LoginController,
    RegisterController,
    PasswordController,
    PasswordResetLinkController,
    NewPasswordController
};
use App\Models\{
    Department,
    User,
    Task,
    LeaveRequest,
    Notification,
    CustomerRequest,
    Customer,
    Vendor,
    Product,
    WalkInCustomer
};
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SaleController;

// Helper function for role-based dashboard redirect
if (!function_exists('redirectToAllowedDashboard')) {
    function redirectToAllowedDashboard($user) {
        return match ((int)$user->role) {
            5 => redirect()->route('ceo.dashboard'),
            1 => redirect()->route('employee.dashboard'),
            2 => redirect()->route('callcenter.dashboard'),
            4 => redirect()->route('shop.dashboard'),
            default => redirect()->route('dashboard'),
        };
    }
}

// Auth routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);
Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');
Route::get('/home', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login'); // guest -> login
    }

    // Reuse redirect logic
    if ((string) $user->role === '5') {
        return redirect()->route('dashboard'); // CEO dashboard
    } elseif ((string) $user->role === '1') {
        return redirect()->route('employee.dashboard'); // Employee dashboard
    } elseif ((string) $user->role === '2') {
        return redirect()->route('callcenter.index'); // Call center dashboard
    } elseif ((string) $user->role === '3') {
        return redirect()->route('marketing.dashboard'); // Marketing dashboard
    } elseif ((string) $user->role === '4') {
        return redirect()->route('shop.dashboard'); // Shop dashboard
    }

    return redirect()->route('employee.dashboard'); // default fallback
})->name('home');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/sales/filter', [SaleController::class, 'filter'])->name('sales.filter');
    Route::get('/vendors/{vendor}/products', [SaleController::class, 'getVendorProducts']);
    Route::resource('sales', SaleController::class);
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
    Route::post('/daily-reports/{id}/marks', [DailyReportController::class, 'assignMarks'])->name('daily-reports.assignMarks');
    Route::get('/daily-reports/{id}/download', [DailyReportController::class, 'download'])->name('daily-reports.download');

    Route::get('/chat/unread-counts-per-user', function () {
        $unreadCounts = DB::table('messages')
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->select('sender_id', DB::raw('count(*) as count'))
            ->groupBy('sender_id')
            ->pluck('count', 'sender_id')
            ->toArray();
            
        return response()->json(['counts' => $unreadCounts]);
    });
    
    Route::post('/chat/mark-read-from-sender', function (Request $request) {
        DB::table('messages')
            ->where('receiver_id', auth()->id())
            ->where('sender_id', $request->sender_id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        return response()->json(['success' => true]);
    });

    // Notifications
    Route::resource('notifications', NotificationController::class);
    Route::post('notifications/{notification}/reply', [NotificationController::class, 'reply'])->name('notifications.reply');

    // Departments, Users, Tasks, Leave Requests, Reports, Attendance
    Route::resource('departments', DepartmentController::class);
    Route::resource('users', UserController::class);
    Route::resource('tasks', TaskController::class);
    Route::resource('leave-requests', LeaveRequestController::class);
    Route::resource('daily-reports', DailyReportController::class);
    Route::resource('attendance', AttendanceController::class);

    // Profile
    Route::get('/profile/change-password', function() {
        return view('profile.change-password');
    })->name('profile.edit-password');

    Route::post('/profile/change-password', [PasswordController::class, 'update'])->name('profile.update-password');

    Route::get('/profile', function () {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    })->name('profile.show');

    // Dashboards
    Route::get('/dashboard', function () {
        $user = auth()->user();
        return redirectToAllowedDashboard($user);
    })->name('dashboard');

    // CEO Dashboard
    Route::get('/ceo-dashboard', function() {
        $user = auth()->user();
        if ($user->role != 5) return redirectToAllowedDashboard($user);

        return view('dashboard_ceo', [
            'departmentsCount' => Department::count(),
            'usersCount' => User::count(),
            'tasksCount' => Task::count(),
            'leaveRequestsCount' => LeaveRequest::count(),
            'notifications' => Notification::latest()->get(),
        ]);
    })->name('ceo.dashboard');

    // Employee Dashboard
    Route::get('/employee-dashboard', function() {
        $user = auth()->user();
        if ($user->role != 1) return redirectToAllowedDashboard($user);

        return view('dashboard_employee', [
            'departmentsCount' => Department::count(),
            'usersCount' => User::count(),
            'tasksCount' => Task::count(),
            'leaveRequestsCount' => LeaveRequest::count(),
            'notifications' => Notification::where('user_id', $user->id)->latest()->get(),
            'tasks' => Task::where('assigned_to', $user->id)->get(),
        ]);
    })->name('employee.dashboard');

    // Call Center Routes
    Route::prefix('callcenter')->group(function() {
        Route::get('/dashboard', function() {
            $user = auth()->user();
            if ($user->role != 2) return redirectToAllowedDashboard($user);

            return view('callcenter.dashboard', [
                'requestsCount' => CustomerRequest::count(),
                'customersCount' => Customer::count(),
                'pendingRequestsCount' => CustomerRequest::where('status', 'pending')->count(),
                'processingRequestsCount' => CustomerRequest::where('status', 'processing')->count(),
                'completedRequestsCount' => CustomerRequest::where('status', 'completed')->count(),
                'canceledRequestsCount' => CustomerRequest::where('status', 'canceled')->count(),
            ]);
        })->name('callcenter.dashboard');

        Route::get('/create', [CallCenterController::class, 'create'])->name('callcenter.create');
        Route::post('/', [CallCenterController::class, 'store'])->name('callcenter.store');
        Route::post('/status/{id}', [CallCenterController::class, 'updateStatus'])->name('callcenter.updateStatus');
        Route::get('/requests-filter', [CallCenterController::class, 'filterRequests'])->name('callcenter.requestsFilter');
        Route::get('/search', [CallCenterController::class, 'search'])->name('callcenter.search');
        Route::get('/', [CallCenterController::class, 'index'])->name('callcenter.index');
        Route::get('/customers/{id}/history', [CallCenterController::class, 'history'])->name('customers.history');
        Route::post('/requests/{id}/update-comment', [CallCenterController::class, 'updateComment'])->name('requests.updateComment');
        Route::get('/callcenter/my-tasks', [CallCenterController::class, 'myTasks'])->name('callcenter.my-tasks');
        Route::post('/callcenter/tasks/{task}/status', [CallCenterController::class, 'updateTaskStatus'])
            ->name('callcenter.tasks.update-status');
    });
    Route::get('/clients/search', [SaleController::class, 'searchClient']);

    // Shop Routes
   Route::prefix('shop')->name('shop.')->group(function() {

    Route::get('/dashboard', function() {
        $user = auth()->user();

        // Allow role 4 (shop) and role 5 (CEO)
        if (!in_array($user->role, [4, 5])) {
            return redirectToAllowedDashboard($user);
        }

        return view('shop.dashboard', [
            'vendorsCount' => Vendor::count(),
            'productsCount' => Product::count(),
            'walkInCustomersCount' => WalkInCustomer::count(),
        ]);
    })->name('dashboard');

    Route::resource('vendors', VendorController::class);
    Route::resource('products', ProductController::class);

    Route::get('customers/create', [ShopCustomerController::class, 'create'])->name('customers.create');
    Route::post('customers', [ShopCustomerController::class, 'store'])->name('customers.store');
    Route::get('customers/{customer}', [ShopCustomerController::class, 'show'])->name('customers.show');
    Route::resource('walk-in-customers', WalkInCustomerController::class)->names([
        'index'   => 'walk-in-customers.index',
        'create'  => 'walk-in-customers.create',
        'store'   => 'walk-in-customers.store',
        'show'    => 'walk-in-customers.show',
        'edit'    => 'walk-in-customers.edit',
        'update'  => 'walk-in-customers.update',
        'destroy' => 'walk-in-customers.destroy',
    ]);

});

});

// Redirect root
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});
