<?php

use App\Http\Controllers\Admin\AnniversaryOrBirthdayCardController;
use App\Http\Controllers\Admin\BlockController;
use App\Http\Controllers\Admin\BlockRoadController;
use App\Http\Controllers\Admin\Corporate_customer\CompanyAttachmentController;
use App\Http\Controllers\Admin\Corporate_customer\CompanyController;
use App\Http\Controllers\Admin\Corporate_customer\CompanyOfficeController;
use App\Http\Controllers\Admin\Corporate_customer\CompanyProfileController;
use App\Http\Controllers\Admin\Corporate_customer\Employee\CompanyEmployeeAttachmentController;
use App\Http\Controllers\Admin\Corporate_customer\Employee\CompanyEmployeeController;
use App\Http\Controllers\Admin\Corporate_customer\Employee\CompanyEmployeeOfficeController;
use App\Http\Controllers\Admin\Corporate_customer\Employee\CompanyEmployeePhotographController;
use App\Http\Controllers\Admin\EmployeeAnniversaryOrBirthdayCardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\EmployeeEducationController;
use App\Http\Controllers\Admin\EmployeeOfficeController;
use App\Http\Controllers\Admin\MasterData\AppointmentService\AppointmentServiceController;
use App\Http\Controllers\Admin\MasterData\HomeService\HomeServiceCategoryController;
use App\Http\Controllers\Admin\MasterData\HomeService\HomeServiceController;
use App\Http\Controllers\Admin\MasterData\HomeService\HomeServiceListController;
use App\Http\Controllers\Admin\MasterData\HomeService\HomeServiceVariantController;
use App\Http\Controllers\Admin\MasterData\AppointmentService\ServiceCategoryController;
use App\Http\Controllers\Admin\MasterData\AppointmentService\ServiceListController;
use App\Http\Controllers\Admin\MasterData\AppointmentService\ServiceVariantController;
use App\Http\Controllers\Admin\MasterData\CallCenterController;
use App\Http\Controllers\Admin\MasterData\CallReasonController;
use App\Http\Controllers\Admin\MasterData\CustomerFeedBackController;
use App\Http\Controllers\Admin\MasterData\ExpenseAdmin\CostCategoryController;
use App\Http\Controllers\Admin\MasterData\ExpenseAdmin\CostHeadController;
use App\Http\Controllers\Admin\MasterData\ExpenseAdmin\ExpenseAdminController;
use App\Http\Controllers\Admin\MasterData\FuelController;
use App\Http\Controllers\Admin\MasterData\MembershipCardController;
use App\Http\Controllers\Admin\MasterData\PackageController;
use App\Http\Controllers\Admin\MasterData\Vehicle\VehicleBrandController;
use App\Http\Controllers\Admin\MasterData\Vehicle\VehicleBrandModelController;
use App\Http\Controllers\Admin\MasterData\Vehicle\VehicleClassController;
use App\Http\Controllers\Admin\MasterData\Vehicle\VehicleColorController;
use App\Http\Controllers\Admin\MasterData\Vehicle\VehicleConditionController;
use App\Http\Controllers\Admin\MasterData\Vehicle\VehicleGroupController;
use App\Http\Controllers\Admin\MasterData\Vehicle\VehicleTypeController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\MemberEductionController;
use App\Http\Controllers\Admin\MemberFamilyMemberController;
use App\Http\Controllers\Admin\MemberIdCardController;
use App\Http\Controllers\Admin\MemberOfficeController;
use App\Http\Controllers\Admin\MemberPhotoController;
use App\Http\Controllers\Admin\MemberSearchController;
use App\Http\Controllers\Admin\MemberWorkingExperieanceController;
use App\Http\Controllers\Admin\MasterData\AreaController;
use App\Http\Controllers\Admin\MasterData\Vehicle\VehicleController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\ModuleGroupController;
use App\Http\Controllers\Admin\ProfilePhotoController;
use App\Http\Controllers\Admin\SubModuleController;
use App\Http\Controllers\Admin\UserGroupController;
use App\Http\Controllers\Admin\WorkingExperienceController;
use App\Http\Controllers\Admin\Workshop\AttachmentController;
use App\Http\Controllers\Admin\Workshop\GeneralInfoController;
use App\Http\Controllers\Admin\Workshop\ImageController;
use App\Http\Controllers\Admin\Workshop\TimeScheduleController;
use App\Http\Controllers\Admin\Workshop\WorkshopController;
use App\Http\Controllers\Admin\Workshop\WorkshopVehicleTypeController;
use App\Http\Controllers\Client\Employee\ClientEmployeeController;
use App\Http\Controllers\Client\Employee\ClientOfficeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Models\Admin\MasterData\CostCategory;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('admin.dashboard');

//Route::prefix('admin')->middleware('auth')->group(function () {
Route::middleware(['auth', 'panel:admin'])->prefix('admin')->group(function () {
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

    /*===============MasterData Route==================*/
    Route::get('area', [AreaController::class, 'index'])->name('Admin.module.metadata.index');
    Route::get('area/divisions', [AreaController::class, 'division'])->name('Admin.module.metadata.division');
    Route::get('area/districts', [AreaController::class, 'district'])->name('Admin.module.metadata.districts');
    Route::get('area/upazila', [AreaController::class, 'upazila'])->name('Admin.module.metadata.upazila');

    /*===============vehicle Route==================*/
    Route::get('master-data/vehicle', [VehicleController::class, 'index'])->name('Admin.module.master-data.vehicle.index');
    Route::resource('master-data/vehicle-type', VehicleTypeController::class)->names('admin.modules.master-data.vehicle-type');
    Route::resource('master-data/vehicle-class', VehicleClassController::class)->names('admin.modules.master-data.vehicle-class');
    Route::resource('master-data/vehicle-brand', VehicleBrandController::class)->names('admin.modules.master-data.vehicle-brand');
    Route::resource('master-data/vehicle-brand-model', VehicleBrandModelController::class)->names('admin.modules.master-data.vehicle-brand-model');
    Route::resource('master-data/vehicle-color', VehicleColorController::class)->names('admin.modules.master-data.vehicle-color');
    Route::resource('master-data/vehicle-condition', VehicleConditionController::class)->names('admin.modules.master-data.vehicle-condition');
    Route::resource('master-data/vehicle-group', VehicleGroupController::class)->names('admin.modules.master-data.vehicle-group');

    /*===============Appointment Service Route==================*/
    Route::get('master-data/appointment-service', [AppointmentServiceController::class, 'index'])->name('Admin.module.master-data.appointment-service.index');
    Route::resource('master-data/service-category', ServiceCategoryController::class)->names('admin.modules.master-data.service-category');
    Route::post('master-data/service-category/{code}', [ServiceCategoryController::class, 'toggle'])->name('admin.modules.master-data.service-category.toggle');
    Route::resource('master-data/service-list', ServiceListController::class)->names('admin.modules.master-data.service-list');
    Route::post('master-data/service-list/{code}', [ServiceListController::class, 'toggle'])->name('admin.modules.master-data.service-list.toggle');
    Route::resource('master-data/service-variant', ServiceVariantController::class)->names('admin.modules.master-data.service-variant');
    Route::post('master-data/setServiceVariant', [ServiceVariantController::class, 'setServiceVariant'])->name('admin.modules.master-data.setServiceVariant');
    Route::post('master-data/appointment-variant-save', [ServiceVariantController::class, 'saveServiceVariant'])->name('admin.modules.master-data.appointment-variant-save');

    /*===============Home Service Route==================*/
    Route::get('master-data/home-service', [HomeServiceController::class, 'index'])->name('admin.module.master-data.home-service.index');
    Route::resource('master-data/home-service-category', HomeServiceCategoryController::class)->names('admin.modules.master-data.home-service-category');
    Route::post('master-data/home-service-category/{code}', [HomeServiceCategoryController::class, 'toggle'])->name('admin.modules.master-data.home-service-category.toggle');
    Route::resource('master-data/home-service-list', HomeServiceListController::class)->names('admin.modules.master-data.home-service-list');
    Route::post('master-data/home-service-list/{code}', [HomeServiceListController::class, 'toggle'])->name('admin.modules.master-data.home-service-list.toggle');
    Route::resource('master-data/home-service-variant', HomeServiceVariantController::class)->names('admin.modules.master-data.home-service-variant');
    Route::post('master-data/home-setServiceVariant', [HomeServiceVariantController::class, 'setServiceVariant'])->name('admin.modules.master-data.home-setServiceVariant');

    /*===============MasterData Route==================*/
    Route::resource('master-data/member-ship-card', MembershipCardController::class)->names('admin.module.master-data.member-ship-card');

    /*===============package Route==================*/
    Route::resource('master-data/package', PackageController::class)->names('admin.module.master-data.package');
    
    /*===============Call Center==================*/
    Route::get('master-data/expense', [ExpenseAdminController::class,'index'])->name('admin.module.master-data.expense');
    Route::resource('master-data/cost-category', CostCategoryController::class)->names('admin.module.master-data.expense-category');
    Route::post('master-data/cost-category/{code}', [CostCategoryController::class, 'toggle'])->name('admin.module.master-data.expense-category.toggle');
    Route::resource('master-data/cost-head', CostHeadController::class)->names('admin.module.master-data.expense-head');
    Route::post('master-data/cost-category/{code}', [CostHeadController::class, 'toggle'])->name('admin.module.master-data.expense-head.toggle');

    /*===============Fuel Route==================*/
    Route::resource('master-data/fuel', FuelController::class)->names('admin.module.master-data.fuel');

     /*===============Call Center==================*/
    Route::get('master-data/call-center', [CallCenterController::class,'index'])->name('admin.module.master-data.call-center');
    Route::resource('master-data/call-reason', CallReasonController::class)->names('admin.module.master-data.call-reason');
    Route::post('master-data/call-reason/{code}', [CallReasonController::class, 'toggle'])->name('admin.module.master-data.call-reason.toggle');
    Route::resource('master-data/customer-feedback', CustomerFeedBackController::class)->names('admin.module.master-data.customer-feedback');
    Route::post('master-data/customer-feedback/{code}', [CustomerFeedBackController::class, 'toggle'])
    ->name('admin.module.master-data.customer-feedback.toggle');
    /*===============MasterData Route end==================*/

    /*===============Corporate customer Route==================*/
    Route::resource('company', CompanyController::class)->names('admin.company-modules');
    Route::get('get-districts/{division_id}', [CompanyController::class, 'getDistricts']);
    Route::get('get-upazilas/{district_id}', [CompanyController::class, 'getUpazilas']);
    Route::get('company-office-info/{company_code}', [CompanyOfficeController::class, 'index'])->name('admin.company.office.edit');
    Route::put('company-office-info', [CompanyOfficeController::class, 'editCompanyOfficial'])->name('admin.company.office.update');
    Route::put('company-notification-permission', [CompanyOfficeController::class, 'updateNotificationPermission'])->name('admin.company.notification-permission');
    Route::put('company-setting', [CompanyOfficeController::class, 'updateSmsSettings'])->name('admin.company.setting');

    Route::get('company-profile-image/{company_code}', [CompanyProfileController::class, 'edit'])->name('admin.company.profile-image.edit');
    Route::post('profile-photo-info/{company_code}', [CompanyProfileController::class, 'update'])->name('admin.company.profile-image.update');
    Route::get('company-attachment/{company_code}', [CompanyAttachmentController::class, 'edit'])->name('admin.company.attachment.edit');
    Route::post('company-attachment', [CompanyAttachmentController::class, 'store'])->name('admin.company.attachment.store');
    Route::delete('/company-file/{id}', [CompanyAttachmentController::class, 'destroy'])
    ->name('admin.company.file.delete');

    Route::get('corporate/company-list', [CompanyController::class, 'companyList'])->name('admin.company.list');
    Route::get('corporate/employee-list', [CompanyEmployeeController::class, 'index'])->name('admin.company-employee.index');
    Route::get('customer-employee-data', [CompanyEmployeeController::class, 'getCustomerEmployeeData'])->name('admin.customer-employee-data.index');
    Route::get('customer-employee/create', [CompanyEmployeeController::class, 'create'])->name('admin.customer-employee.create');
    Route::post('customer-employee-store', [CompanyEmployeeController::class, 'store'])->name('admin.customer-employee.store');
    Route::get('customer-employee-edit/{employeeId}', [CompanyEmployeeController::class, 'edit'])->name('admin.customer-employee.edit');
    Route::put('customer-employee-update/{employeeId}', [CompanyEmployeeController::class, 'update'])->name('admin.customer-employee.update');
    
    Route::get('customer-employee-office/{employeeId}', [CompanyEmployeeOfficeController::class, 'edit'])->name('admin.customer-employee.office.edit');
    Route::put('customer-employee-office/{employeeId}', [CompanyEmployeeOfficeController::class, 'update'])->name('admin.customer-employee.office.update');

    Route::get('customer-employee-photo/{employeeId}', [CompanyEmployeePhotographController::class, 'edit'])->name('admin.customer-employee.photo.edit');
    Route::post('customer-employee-photo/{employeeId}', [CompanyEmployeePhotographController::class, 'update'])->name('admin.customer-employee.photo.update');

    Route::get('customer-employee-attachment/{employeeId}', [CompanyEmployeeAttachmentController::class, 'edit'])->name('admin.customer-employee.attachment.edit');
    Route::post('customer-employee-attachment', [CompanyEmployeeAttachmentController::class, 'store'])->name('admin.customer-employee.attachment.store');
    Route::delete('customer-employee-attachment/{employeeId}', [CompanyEmployeeAttachmentController::class, 'destory'])->name('admin.customer-employee.attachment.destory');    
    
    /*===============Workshop Route==================*/
    Route::get('workshop-list', [WorkshopController::class, 'index'])->name('admin.workshop-list.index');
    Route::get('workshop-list-data', [WorkshopController::class, 'getWorkshopData'])->name('admin.workshop-list-data.index');
    Route::resource('workshop-general-info', GeneralInfoController::class)->names('admin.workshop-general-info');
    Route::resource('workshop-time-schedule', TimeScheduleController::class)->names('admin.workshop-time-schedule');
    Route::resource('workshop-vehicle-type', WorkshopVehicleTypeController::class)->names('admin.workshop-vehicle-type');
    Route::resource('workshop-image', ImageController::class)->names('admin.workshop-image');
    Route::resource('workshop-attachment', AttachmentController::class)->names('admin.workshop-attachment');

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

Route::middleware(['auth', 'panel:client'])->prefix('client')->group(function () {
    Route::get('/', function() {
        $leftMenuModuleUrl = request()->path();
        return view('client.dashboard',compact('leftMenuModuleUrl'));
    })->name('client.dashboard');
    Route::resource('/employee', ClientEmployeeController::class)->names('client.employee');
    Route::resource('/employee/office', ClientOfficeController::class)->names('client.employee.office');
    // Route::get('/employee/create', [ClientEmployeeController::class,'create'])->name('client.employee.create');

});

require __DIR__.'/auth.php';
