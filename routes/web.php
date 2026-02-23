<?php

use App\Http\Controllers\Admin\BlockController;
use App\Http\Controllers\Admin\BlockRoadController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\EmployeeEducationController;
use App\Http\Controllers\Admin\EmployeeOfficeController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\ModuleGroupController;
use App\Http\Controllers\Admin\ProfilePhotoController;
use App\Http\Controllers\Admin\RoadController;
use App\Http\Controllers\Admin\UserGroupController;
use App\Http\Controllers\Admin\WorkingExperienceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('users',[UserController::class, 'index'])->name('admin.users.index');
    Route::get('user/{id}',[UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('user/{id}',[UserController::class, 'update'])->name('admin.users.update');
    Route::get('/users-data', [UserController::class, 'getUsers'])->name('users.data.index');

    Route::get('user-groups',[UserGroupController::class, 'index'])->name('admin.user-groups.index');
    Route::get('/user-groups-data', [UserGroupController::class, 'getUserGroups'])->name('user-groups.data.index');
    Route::get('user-group',[UserGroupController::class, 'create'])->name('admin.user-groups.create');
    Route::post('user-group',[UserGroupController::class, 'storeOrUpdate'])->name('admin.user-groups.store');
    Route::get('user-group/{id}',[UserGroupController::class, 'edit'])->name('admin.user-groups.edit');
    Route::put('user-group/{id}', [UserGroupController::class, 'storeOrUpdate'])->name('admin.user-groups.update');
    Route::patch('user-groups/{id}/status', [UserGroupController::class, 'updateStatus'])->name('admin.user-groups.status');
    Route::delete('user-groups/{id}', [UserGroupController::class, 'destroy'])->name('admin.user-groups.destroy');

    Route::get('/module-group',[ModuleGroupController::class, 'index'])->name('admin.module-group.index');
    Route::get('/module-group-data', [ModuleGroupController::class, 'getModuleGroupData'])->name('module-group.data.index');
    Route::get('/module-group-create',[ModuleGroupController::class, 'create'])->name('admin.module-group.create');
    Route::post('/module-group-store',[ModuleGroupController::class, 'store'])->name('admin.module-group.store');
    Route::get('/module-group-show', [ModuleGroupController::class, 'show'])->name('admin.module-group.show');
    Route::get('/module-group-edit/{id}',[ModuleGroupController::class, 'edit'])->name('admin.module-group.edit');
    Route::put('/module-group-update/{id}',[ModuleGroupController::class, 'update'])->name('admin.module-group.update');
    Route::delete('/module-group-destroy/{id}', [ModuleGroupController::class, 'destroy'])->name(name: 'admin.module-group.destroy');

    Route::resource('modules', ModuleController::class)->names('admin.modules');
    Route::get('/modules-data', [ModuleController::class, 'getModulesData'])->name('modules.data.index');
    Route::get('/module-groups/{panel}', [ModuleController::class, 'selectModuleData'])->name('select.modules.data');

    Route::resource('roads', RoadController::class)->names('road.module');
    Route::get('/road-data', [RoadController::class, 'getRoadData'])->name('road.data.index');

    Route::resource('blocks', BlockController::class)->names('admin.block.module');
    Route::get('/block-data', [BlockController::class, 'getBlockData'])->name('admin.block.data.index');

    Route::get('/block-road', [BlockRoadController::class, 'index'])->name('admin.block.block.index');
    
    Route::post('/admin/masterData/setRoad', [BlockRoadController::class, 'setRoad'])
    ->name('admin.masterData.setRoad');

    /*===============Employee Module Route==================*/
    Route::resource('employees', EmployeeController::class)->names('admin.employee.module');
    Route::get('/employee-data', [EmployeeController::class, 'getEmployeeData'])->name('admin.employee.data.index');

    Route::get('/employee-office-info/{id}', [EmployeeOfficeController::class, 'edit'])->name('admin.employee.office.edit');
    Route::put('/employee-office-info/{employee}/office', [EmployeeOfficeController::class, 'update'])->name('admin.employee.office.update');

    Route::get('/employee-education-info/{id}', [EmployeeEducationController::class, 'edit'])->name('admin.employee.education.edit');
    Route::post('/employee-education-info/{id}', [EmployeeEducationController::class, 'update'])->name('admin.employee.education.update');

    Route::get('/working-experience-info/{id}', [WorkingExperienceController::class, 'edit'])->name('admin.working.experience.edit');
    Route::post('/working-experience-info/{id}', [WorkingExperienceController::class, 'update'])->name('admin.working.experience.update');

    Route::get('/profile-photo-info/{id}', [ProfilePhotoController::class, 'edit'])->name('admin.profile.photo.edit');
    Route::post('/profile-photo-info/{id}', [ProfilePhotoController::class, 'update'])->name('admin.profile.photo.update');

    /*===============Member Module Route==================*/
    Route::resource('members', MemberController::class)->names('admin.member.module');
    Route::get('/member-data', [MemberController::class, 'getMemberData'])->name('admin.member.data.index');
    Route::get('members-create', [MemberController::class,'create'])->name('admin.member.module.create');

});

require __DIR__.'/auth.php';
