<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DepartmentController,UserController,TaskController,LeaveRequestController,DailyReportController,AttendanceController,NotificationController,CallCenterController,
    CeoController,
    EmployeeController,
    VendorController,
    ProductController,
    WalkInCustomerController,
    ShopController
};

use App\Http\Controllers\Auth\{LoginController, RegisterController};
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

// Public routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Protected routes
Route::middleware('auth')->group(function () {

    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

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


    });

    // Shop Routes
    Route::prefix('shop')->group(function() {

        Route::get('/dashboard', function() {
            $user = auth()->user();
            if ($user->role != 4) return redirectToAllowedDashboard($user);

            return view('shop.dashboard', [
                'vendorsCount' => Vendor::count(),
                'productsCount' => Product::count(),
                'walkInCustomersCount' => WalkInCustomer::count(),
            ]);
        })->name('shop.dashboard');

        Route::resource('vendors', VendorController::class);
        Route::resource('products', ProductController::class);
        Route::resource('walk-in-customers', WalkInCustomerController::class);
    });
});

// Redirect root
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});
 