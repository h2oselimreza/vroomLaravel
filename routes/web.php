<?php

use App\Http\Controllers\Admin\AnniversaryOrBirthdayCardController;
use App\Http\Controllers\Admin\BlockController;
use App\Http\Controllers\Admin\BlockRoadController;
use App\Http\Controllers\Admin\Corporate_customer\CompanyController;
use App\Http\Controllers\Admin\EmployeeAnniversaryOrBirthdayCardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\EmployeeEducationController;
use App\Http\Controllers\Admin\EmployeeOfficeController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\MemberEductionController;
use App\Http\Controllers\Admin\MemberFamilyMemberController;
use App\Http\Controllers\Admin\MemberIdCardController;
use App\Http\Controllers\Admin\MemberOfficeController;
use App\Http\Controllers\Admin\MemberPhotoController;
use App\Http\Controllers\Admin\MemberSearchController;
use App\Http\Controllers\Admin\MemberWorkingExperieanceController;
use App\Http\Controllers\Admin\MetaData\AreaController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\ModuleGroupController;
use App\Http\Controllers\Admin\PrayerTimeController;
use App\Http\Controllers\Admin\ProfilePhotoController;
use App\Http\Controllers\Admin\RoadController;
use App\Http\Controllers\Admin\SMS\EmployeeAnniversarySMSController;
use App\Http\Controllers\Admin\SMS\EmployeeBirthdaySMSController;
use App\Http\Controllers\Admin\SMS\EmployeeBulkSmsController;
use App\Http\Controllers\Admin\SMS\MemberAnniversarySMSController;
use App\Http\Controllers\Admin\SMS\MemberBirthdaySMSController;
use App\Http\Controllers\Admin\SMS\memberBulkSmsController;
use App\Http\Controllers\Admin\SMS\SmsReportController;
use App\Http\Controllers\Admin\SubModuleController;
use App\Http\Controllers\Admin\UserGroupController;
use App\Http\Controllers\Admin\Web\AlbumController;
use App\Http\Controllers\Admin\Web\EventController;
use App\Http\Controllers\Admin\Web\FooterSliderController;
use App\Http\Controllers\Admin\Web\GalleryImageController;
use App\Http\Controllers\Admin\Web\ImageAlbumsController;
use App\Http\Controllers\Admin\Web\NewsController;
use App\Http\Controllers\Admin\Web\WebAchievementsController;
use App\Http\Controllers\Admin\Web\WebModuleDescriptionController;
use App\Http\Controllers\Admin\Web\WebNoticeController;
use App\Http\Controllers\Admin\Web\WebsiteController;
use App\Http\Controllers\Admin\Web\WebSliderController;
use App\Http\Controllers\Admin\WorkingExperienceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

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

    Route::resource('sub-modules', SubModuleController::class)->names('admin.sub-modules');
    Route::get('/sub-modules-data', [SubModuleController::class, 'getSubModulesData'])->name('sub-modules.data.index');

    /*===============MetaData Route==================*/
    Route::get('area', [AreaController::class, 'index'])->name('Admin.module.metadata.index');
    Route::get('divisions', [AreaController::class, 'division'])->name('Admin.module.metadata.division');
    Route::get('districts', [AreaController::class, 'district'])->name('Admin.module.metadata.districts');
    Route::get('upazila', [AreaController::class, 'upazila'])->name('Admin.module.metadata.upazila');

    /*===============Corporate customer Route==================*/
    Route::resource('company', CompanyController::class)->names('admin.company-modules');
    Route::get('/get-districts/{division_id}', [CompanyController::class, 'getDistricts']);
    Route::get('/get-upazilas/{district_id}', [CompanyController::class, 'getUpazilas']);

    /*===============Employee Module Route==================*/
    Route::resource('employees', EmployeeController::class)->names('admin.employee.module');
    Route::get('employee-data', [EmployeeController::class, 'getEmployeeData'])->name('admin.employee.data.index');
    Route::patch('employee/{id}/status', [EmployeeController::class, 'updateStatus'])->name('admin.employee.status');

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
    Route::patch('member/{id}/status', [MemberController::class, 'updateStatus'])->name('admin.member.status');


    Route::get('member-other-family/{id}', [MemberFamilyMemberController::class,'index'])->name('admin.member.module.otherFamily.index');
    Route::post('member-other-family/{id}', [MemberFamilyMemberController::class,'update'])->name('admin.member.module.otherFamily.update');

    Route::get('member-office/{id}', [MemberOfficeController::class,'index'])->name('admin.member.module.office.index');
    Route::put('member-office/{member}/office',
    [MemberOfficeController::class,'update'])->name('admin.member.module.office.update');

    Route::get('member-education/{id}', [MemberEductionController::class,'edit'])->name('admin.member.module.education.index');
    Route::post('member-education/{member}/education',
    [MemberEductionController::class,'update'])->name('admin.member.module.education.update');

    Route::get('member-working-experience/{id}', [MemberWorkingExperieanceController::class, 'edit'])->name('admin.member.working.experience.edit');
    Route::post('member-working-experience/{id}', [MemberWorkingExperieanceController::class, 'update'])->name('admin.member.working.experience.update');

    Route::get('member-profile-photo/{id}', [MemberPhotoController::class, 'edit'])->name('admin.member.photo.edit');
    Route::post('member-profile-photo/{id}', [MemberPhotoController::class, 'update'])->name('admin.member.photo.update');

    Route::post('show-member-info', [MemberController::class, 'showMemberInfo'])->name('admin.member.show');
    Route::get('member-search-list', [MemberSearchController::class, 'index'])->name('admin.member.search.list');
    Route::post('member-search', [MemberSearchController::class, 'search'])->name('admin.member.search');
    Route::post('member-print', [MemberSearchController::class, 'print'])->name('admin.member.search.print');

    Route::get('member-id-card', [MemberIdCardController::class,'index'])->name('member.id.card.index');
    Route::get('member-id-card-data', [MemberIdCardController::class, 'getMemberIdCardData'])->name('admin.memberIdCard.data.index');
    Route::post('print-member-id-card', [MemberIdCardController::class, 'PrintMemberIdCard'])->name('print.member.id.card');

});

Route::get('/', function() {
    return view('welcome');
});

require __DIR__.'/auth.php';
