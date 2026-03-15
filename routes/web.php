<?php

use App\Http\Controllers\Admin\AnniversaryOrBirthdayCardController;
use App\Http\Controllers\Admin\BlockController;
use App\Http\Controllers\Admin\BlockRoadController;
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
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\ModuleGroupController;
use App\Http\Controllers\Admin\ProfilePhotoController;
use App\Http\Controllers\Admin\RoadController;
use App\Http\Controllers\Admin\SMS\EmployeeAnniversarySMSController;
use App\Http\Controllers\Admin\SMS\EmployeeBirthdaySMSController;
use App\Http\Controllers\Admin\SMS\EmployeeBulkSmsController;
use App\Http\Controllers\Admin\SMS\MemberAnniversarySMSController;
use App\Http\Controllers\Admin\SMS\MemberBirthdaySMSController;
use App\Http\Controllers\Admin\SMS\memberBulkSmsController;
use App\Http\Controllers\Admin\SMS\SmsReportController;
use App\Http\Controllers\Admin\UserGroupController;
use App\Http\Controllers\Admin\Web\AlbumController;
use App\Http\Controllers\Admin\Web\EventController;
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
use App\Http\Controllers\WebSite\AboutUsController;
use App\Http\Controllers\WebSite\CommitteeController;
use App\Http\Controllers\WebSite\ContactController;
use App\Http\Controllers\WebSite\GalleryController;
use App\Http\Controllers\WebSite\HomeController;
use App\Http\Controllers\WebSite\SinglePageController;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\Html\Columns\Index;

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

    Route::resource('roads', RoadController::class)->names('road.module');
    Route::get('/road-data', [RoadController::class, 'getRoadData'])->name('road.data.index');

    Route::resource('blocks', BlockController::class)->names('admin.block.module');
    Route::get('/block-data', [BlockController::class, 'getBlockData'])->name('admin.block.data.index');

    Route::get('/block-road', [BlockRoadController::class, 'index'])->name('admin.block.block.index');
    
    Route::post('/admin/masterData/setRoad', [BlockRoadController::class, 'setRoad'])
    ->name('admin.masterData.setRoad');

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

    /*==================Web Controllers==============*/
    Route::resource('news', NewsController::class)->names('admin.news.module');
    Route::get('news-data', [NewsController::class, 'getNewsData'])->name('admin.news.data.index');

    Route::resource('sliders', WebSliderController::class)->names('admin.slider.module');
    Route::get('slider-data', [WebSliderController::class, 'getTableData'])->name('admin.slider.data.index');

    Route::resource('website-module', WebsiteController::class)->names('admin.website.module');
    Route::get('website-module-data', [WebsiteController::class, 'getTableData'])->name('admin.website.data.index');

    Route::resource('module-description', WebModuleDescriptionController::class)->names('admin.module-description.module');
    Route::get('module-description-data', [WebModuleDescriptionController::class, 'getTableData'])->name('admin.module-description.data.index');

    Route::resource('achievements', WebAchievementsController::class)->names('admin.achievements.module');
    Route::get('module-achievements-data', [WebAchievementsController::class, 'getTableData'])->name('admin.achievements.data.index');

    Route::resource('notices', WebNoticeController::class)->names('admin.notices.module');
    Route::get('module-notices-data', [WebNoticeController::class, 'getTableData'])->name('admin.notices.data.index');

    Route::resource('events', EventController::class)->names('admin.events.module');
    Route::get('module-events-data', [EventController::class, 'getTableData'])->name('admin.events.data.index');

    Route::resource('gallery-image', ImageAlbumsController::class)->names('admin.gallery-image.module');
    Route::get('module-gallery-image-data', [ImageAlbumsController::class, 'getTableData'])->name('admin.gallery-image.data.index');
    Route::post('gallery-image/update-home', [GalleryImageController::class, 'updateHomeGallery'])->name('gallery-image.module.update');
    Route::get('gallery-image/{album_id}/{gallery_id}', [GalleryImageController::class, 'destroy'])->name('gallery-image.destroy');

    Route::get('album', [AlbumController::class, 'index'])->name('admin.album.index');
    Route::post('album', [AlbumController::class, 'store'])->name('admin.album.store');
    Route::post('album/delete', [AlbumController::class, 'deleteImages'])->name('admin.album.delete');
    Route::get('/admin/album-details', [AlbumController::class, 'albumDetails'])->name('admin.album.details');

    /*===========================member-birthday=SMS=============================*/
    Route::get('member-birthday-sms', [MemberBirthdaySMSController::class,'index'])->name('admin.member-birthday-sms.index');
    Route::get('member-birthday-sms-data', [MemberBirthdaySMSController::class, 'getMemberBirthdaySMSData'])->name('admin.member-birthday-sms-data.data.index');
    Route::post('member-birthday-sms-send/{checkFlag}', [MemberBirthdaySMSController::class, 'sendMemberBirthdaySms'])->name('admin.member-birthday-sms-send');

    /*===========================Member-aaniversary=SMS=============================*/
    Route::get('member-anniversary-sms', [MemberAnniversarySMSController::class,'index'])->name('admin.member-anniversary-sms.index');
    Route::get('member-anniversary-sms-data', [MemberAnniversarySMSController::class, 'getMemberAnniversarySMSData'])->name('admin.member-anniversary-sms-data.index');
    Route::post('member-anniversary-sms-send/{checkFlag}', [MemberAnniversarySMSController::class, 'sendMemberAnniversarySms'])->name('admin.member-anniversary-sms-send');

    /*===========================Employee-birthday=SMS=============================*/
    Route::get('employee-birthday-sms', [EmployeeBirthdaySMSController::class,'index'])->name('admin.employee-birthday-sms.index');
    Route::get('employee-birthday-sms-data', [EmployeeBirthdaySMSController::class, 'getEmployeeData'])->name('admin.employee-birthday-sms-data.data.index');
    Route::post('employee-birthday-sms-send/{checkFlag}', [EmployeeBirthdaySMSController::class, 'sendMemberBirthdaySms'])->name('admin.employee-birthday-sms-send');

    /*===========================Employee-aaniversary=SMS=============================*/
    Route::get('employee-anniversary-sms', [EmployeeAnniversarySMSController::class,'index'])->name('admin.employee-anniversary-sms.index');
    Route::get('employee-anniversary-sms-data', [EmployeeAnniversarySMSController::class, 'getEmployeeData'])->name('admin.employee-anniversary-sms-data.index');
    Route::post('employee-anniversary-sms-send/{checkFlag}', [EmployeeAnniversarySMSController::class, 'sendMemberAnniversarySms'])->name('admin.employee-anniversary-sms-send');

    /*===========================SMS Balance=============================*/
    Route::get('sms-balance', [SmsReportController::class,'index'])->name('admin.sms-balance.index');

    /*===========================Member Bulk SMS=============================*/
    Route::get('member-bulk-sms', [memberBulkSmsController::class,'index'])->name('admin.member-bulk-sms.index');
    Route::post('member-bulk-sms-send', [memberBulkSmsController::class,'showMemberBulkSmsPanel'])->name('admin.member-bulk-sms-send.showMemberBulkSmsPanel');
    Route::post('send-member-custom-bulk-msg', [memberBulkSmsController::class,'sendMemberCustomBulkMsg'])->name('admin.send-member-custom-bulk-msg');
    Route::post('show-member-sms-panel-from-list', [memberBulkSmsController::class,'showMemberSmsPanelFromList'])->name('admin.show-member-sms-panel-from-list');

    /*===========================Employee Bulk SMS=============================*/
    Route::get('employee-bulk-sms', [EmployeeBulkSmsController::class,'index'])->name('admin.employee-bulk-sms.index');
    Route::post('show-custom-sms-panel', [EmployeeBulkSmsController::class,'showEmployeeBulkSmsPanel'])->name('admin.employee-showcustom-sms-panel');
    Route::post('send-employee-custom-bulk-msg', [EmployeeBulkSmsController::class,'sendEmployeeCustomBulkMsg'])->name('admin.send-employee-custom-bulk-msg');
    
    Route::post('show-employee-sms-panel-from-list', [EmployeeBulkSmsController::class,'showEmployeeSmsPanelFromList'])->name('admin.show-employee-sms-panel-from-list');

    /*===========================Anniversary and Birthday Card=============================*/
    Route::get('anniversary-birthday-card', [AnniversaryOrBirthdayCardController::class,'index'])->name('admin.anniversary-birthday-card.index');
    Route::post('anniversary-birthday-filter-member', [AnniversaryOrBirthdayCardController::class,'showMemberAnniversaryCardPanel'])->name('admin.anniversary-birthday-filter-member.showMemberAnniversaryCardPanel');
    Route::post('anniversary-birthday-card-member', [AnniversaryOrBirthdayCardController::class,'showMemberAnniversaryCard'])->name('admin.anniversary-birthday-card-member');


});

Route::get('/', [HomeController::class, 'index'])->name('website.home');
Route::get('about-society', [AboutUsController::class, 'aboutSociety'])->name('website.about.society');
Route::get('history-of-society', [AboutUsController::class, 'aboutSociety'])->name('website.history-of-society');
Route::get('message-from-president', [AboutUsController::class, 'aboutSociety'])->name('website.message-from-president');
Route::get('message-from-general-secretary', [AboutUsController::class, 'aboutSociety'])->name('message-from-general-secretary');
Route::get('message-from-office-secretary', [AboutUsController::class, 'aboutSociety'])->name('message-from-office-secretary');
Route::get('message-from-pnp-secretary', [AboutUsController::class, 'aboutSociety'])->name('message-from-pnp-secretary');
Route::get('campaign', action: [AboutUsController::class, 'aboutSociety'])->name('website.campaign');
Route::get('about/achievements', action: [AboutUsController::class, 'achievements'])->name('website.about.achievements');
Route::get('achievement-details/{id}', action: [AboutUsController::class, 'show'])->name('website.achievements.show');

/*=======================Committee================*/
Route::get('present-executive-committee', [SinglePageController::class, 'index'])->name('website.present-executive-committee');
Route::get('present-sub-committee', [SinglePageController::class, 'index'])->name('website.present-sub-committee');
Route::get('adviser-comittee', [SinglePageController::class, 'index'])->name('website.adviser-comittee');
Route::get('central-mosque-committee', [SinglePageController::class, 'index'])->name('website.central-mosque-committee');

Route::get('show-facilities', [SinglePageController::class, 'index'])->name('website.showFacilities');
Route::get('ec-meetings', [SinglePageController::class, 'index'])->name('website.ec-meetings');
Route::get('agm', [SinglePageController::class, 'index'])->name('website.agm');
Route::get('gm', [SinglePageController::class, 'index'])->name('website.gm');

/*=======================Archive================*/
Route::get('previous-executive-committee', [SinglePageController::class,'index'])->name('website.previous-executive-committee');
Route::get('previous-president', [SinglePageController::class,'index'])->name('website.previous-president');
Route::get('previous-general-secretary', [SinglePageController::class,'index'])->name('website.previous-general-secretary');

/*=======================Maps================*/
Route::get('a-block', [SinglePageController::class,'index'])->name('website.a-block');
Route::get('b-block', [SinglePageController::class,'index'])->name('website.b-block');
Route::get('c-block', [SinglePageController::class,'index'])->name('website.c-block');
Route::get('d-block', [SinglePageController::class,'index'])->name('website.d-block');
Route::get('e-block', [SinglePageController::class,'index'])->name('website.e-block');
Route::get('f-block', [SinglePageController::class,'index'])->name('website.f-block');
Route::get('g-block', [SinglePageController::class,'index'])->name('website.g-block');

/*=======================Maps================*/
Route::get('vacancy', [SinglePageController::class,'index'])->name('website.vacancy');
Route::get('career-result', [SinglePageController::class,'index'])->name('website.career-result');
Route::get('advertisement', [SinglePageController::class,'index'])->name('website.advertisement');

/*=======================Download================*/
Route::get('letters', [SinglePageController::class,'index'])->name('website.letters');
Route::get('forms', [SinglePageController::class,'index'])->name('website.forms');

/*=======================Download================*/
Route::get('show-tender', [SinglePageController::class,'index'])->name('website.show-tender');

/*=======================Events================*/
Route::get('event', [\App\Http\Controllers\WebSite\EventController::class,'index'])->name('website.event.index');
Route::get('event/event-details/{id}', [\App\Http\Controllers\WebSite\EventController::class,'show'])->name('website.event.details');

/*=======================Web Member================*/
Route::get('apply-member-ship', [\App\Http\Controllers\WebSite\MemberController::class,'applyMembership'])->name('website.member.apply-member-ship');
Route::post('apply-member-ship', [\App\Http\Controllers\WebSite\MemberController::class,'applyForMembershipMailSend'])->name('website.member.apply-member-ship');

Route::get('apply-for-car-sticker', [\App\Http\Controllers\WebSite\MemberController::class,'applyForCarSticker'])->name('website.member.apply-car-sticker');
Route::post('apply-for-car-sticker', [\App\Http\Controllers\WebSite\MemberController::class,'applyForCarStickerMailSend'])->name('website.member.apply-car-sticker');

Route::get('life-members', [\App\Http\Controllers\WebSite\MemberController::class,'lifeMember'])->name('website.life-member');
Route::get('donar-members', [\App\Http\Controllers\WebSite\MemberController::class,'donarMember'])->name('website.donar-member');

Route::get('contact-us', [ContactController::class,'index'])->name('website.contact-us.index');
Route::post('contact-us', [ContactController::class, 'contactUsMailSend'])->name('website.contact-us.contactUsMailSend');

Route::get('gallery', [GalleryController::class,'index'])->name('website.gallery.index');
Route::get('gallery/{albumId}/{albumName}', [GalleryController::class,'show'])->name('website.gallery.show');

require __DIR__.'/auth.php';
