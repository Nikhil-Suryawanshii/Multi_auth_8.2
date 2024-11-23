<?php

use App\Http\Controllers\Broker\BrokerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Staff\StaffController;
use App\Http\Controllers\UserController;
use App\http\Controllers\Backend\BrandController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('frontend.index');
});

Route::middleware(['auth'])->group(function() {
    Route::get('/dashboard',[UserController::class,'UserDashboard'])->name('user.dashboard');
    Route::post('/user/profile/update', [UserController::class, 'userProfileStore'])->name('user.profile.store');
    Route::get('/user/logout', [UserController::class, 'userDestroy'])->name('user.destroy');
    Route::post('/user/update/password', [UserController::class, 'userUpdatePassword'])->name('user.update.password');
    });


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


//admin routes
Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/admin/dashboard',[AdminController::class,'index'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class, 'adminDestroy'])->name('admin.destroy');
    Route::get('/admin/profile', [AdminController::class, 'adminProfile'])->name('admin.profile');
    Route::post('/admin/profile/update', [AdminController::class, 'adminProfileStore'])->name('admin.profile.store');
    Route::get('/admin/change/password', [AdminController::class, 'AdminChangePassword'])->name('admin.change.password');
    Route::post('/admin/update/password', [AdminController::class, 'AdminUpdatePassword'])->name('update.password');


    //Students Routes
    Route::get('/student/create',[StudentController::class,'create'])->name('student.create');
    Route::get('/student/list',[StudentController::class,'list'])->name('student.list');
    Route::post('/student/store',[StudentController::class, 'store'])->name('student.store');
    Route::get('/student/edit/{id}',[StudentController::class,'edit'])->name('student.edit');
    Route::put('/student/update/{id}',[StudentController::class,'update'])->name('student.update');
    Route::get('/student/delete/{id}', [StudentController::class, 'delete'])->name('student.delete');


    //Customer Routes
    Route::get('/customer/create',[CustomerController::class,'create'])->name('customer.create');
    Route::get('/customer/list',[CustomerController::class,'list'])->name('customer.list');
    Route::post('/customer/store',[CustomerController::class, 'store'])->name('customer.store');
    Route::get('/customer/edit/{id}',[CustomerController::class,'edit'])->name('customer.edit');
    Route::put('/customer/update/{id}',[CustomerController::class,'update'])->name('customer.update');
    Route::get('/customer/delete/{id}', [CustomerController::class, 'delete'])->name('customer.delete');

    //Employee Routes
    Route::get('/employee/create',[EmployeeController::class,'create'])->name('employee.create');
    Route::get('/employee/list',[EmployeeController::class,'list'])->name('employee.list');
    Route::post('/employee/store',[EmployeeController::class, 'store'])->name('employee.store');
    Route::get('/employee/edit/{id}',[EmployeeController::class,'edit'])->name('employee.edit');
    Route::put('/employee/update/{id}',[EmployeeController::class,'update'])->name('employee.update');
    Route::get('/employee/delete/{id}', [EmployeeController::class, 'delete'])->name('employee.delete');

});



//staff routes
Route::middleware(['auth','role:staff'])->group(function () {
    Route::get('/staff/dashboard',[StaffController::class,'index'])->name('staff.dashboard');
    Route::get('/staff/logout', [StaffController::class, 'staffDestroy'])->name('staff.destroy');
    Route::get('/staff/profile', [StaffController::class, 'staffProfile'])->name('staff.profile');
    Route::post('/staff/profile/update', [StaffController::class, 'staffProfileStore'])->name('staff.profile.store');
    Route::get('/staff/change/password', [StaffController::class, 'StaffChangePassword'])->name('staff.change.password');
    Route::post('/staff/update/password', [StaffController::class, 'StaffUpdatePassword'])->name('staff.password');
});



//broker routes
Route::middleware(['auth','role:broker'])->group(function () {
Route::get('/broker/dashboard',[BrokerController::class,'index'])->name('broker.dashboard');
Route::get('/broker/logout', [BrokerController::class, 'brokerDestroy'])->name('broker.destroy');
Route::get('/broker/profile', [BrokerController::class, 'brokerProfile'])->name('broker.profile');
Route::post('/broker/profile/update', [BrokerController::class, 'brokerProfileStore'])->name('broker.profile.store');
Route::get('/broker/change/password', [BrokerController::class, 'BrokerChangePassword'])->name('broker.change.password');
Route::post('/broker/update/password', [BrokerController::class, 'BrokerUpdatePassword'])->name('broker.password');
});


//login page routes
Route::get("admin/login",[AdminController::class,'adminLogin'])->name("admin.login");
Route::get("staff/login",[StaffController::class,'staffLogin'])->name("staff.login");
Route::get("broker/login",[BrokerController::class,'brokerLogin'])->name("broker.login");


 // Brand All Route
 Route::controller(BrandController::class)->group(function(){
    Route::get('/all/brand' , 'AllBrand')->name('all.brand');

});
