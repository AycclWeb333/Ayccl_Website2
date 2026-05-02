<?php

use App\Http\Controllers\AccountSettingsController;
use App\Http\Controllers\Admin\AboutUs\AboutCompanyController;
use App\Http\Controllers\Admin\AboutUs\AboutCompanySectionController;
use App\Http\Controllers\Admin\AboutUs\CertificatesController;
use App\Http\Controllers\Admin\AboutUs\FuturePlansController;
use App\Http\Controllers\Admin\AboutUs\ManagementController;
use App\Http\Controllers\Admin\AboutUs\SocialResponsibilityController;
use App\Http\Controllers\Admin\AboutUs\VisionAndMessageController;
use App\Http\Controllers\Admin\General\CategoriesController;
use App\Http\Controllers\Admin\General\PagesController;
use App\Http\Controllers\Admin\HumanResouces\EmployeeAdvantagesController;
use App\Http\Controllers\Admin\ElectronicServices\InternshipRequestController;
use App\Http\Controllers\Admin\ElectronicServices\JobApplicaitonController;
use App\Http\Controllers\Admin\ElectronicServices\VisitController;
use App\Http\Controllers\Admin\MediaCenter\DocumentsController;
use App\Http\Controllers\Admin\MediaCenter\NewsController;
use App\Http\Controllers\Admin\MediaCenter\PhotosGalaryController;
use App\Http\Controllers\Admin\MediaCenter\VideosController;
use App\Http\Controllers\Admin\SalesAndMarketing\HadhramiController;
use App\Http\Controllers\Admin\SalesAndMarketing\ProductsController;
use App\Http\Controllers\Admin\SalesAndMarketing\CustomerServiceController;
use App\Http\Controllers\Admin\Home\StartUpController;
use App\Http\Controllers\Admin\ContactUs\ContactUsAdminController;
use App\Http\Controllers\Admin\ExternalLinks\ExternalLinksController;
use App\Http\Controllers\Admin\Users\UsersManagementtController;
use App\Http\Controllers\Admin\General\StatisticsController;
use App\Http\Controllers\ContentManagementController;
use App\Http\Controllers\StartPage;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\AboutUs;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\Admin\CementBlog\CementBlogController;
use App\Http\Controllers\Admin\AboutUs\EnvironmentController;
use App\Http\Controllers\Admin\AboutUs\OurProjectController;
use App\Http\Controllers\Admin\HumanResouces\EmployeeController;
use App\Http\Controllers\Admin\HumanResouces\OurGuestController;
use App\Http\Controllers\Admin\MediaCenter\InspectionCertificateController;
use App\Http\Controllers\Admin\MediaCenter\SpecificationController;
use App\Http\Controllers\CementBlogWebController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\ElectronicServiceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HumanResourcesController;
use App\Http\Controllers\MediaCenterController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SustainableDevelopmentController;
use App\Http\Controllers\WebsiteManagementController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\FormSubmissionController;
use App\Http\Controllers\Admin\Settings\MailSettingController;


use Laravel\Pail\ValueObjects\Origin\Console;

// if (! function_exists('localizedRoute')) {
// function localizedRoute($name, $parameters = [], $absolute = true)
// {
//     $locale = app()->getLocale();

//     // Convert string parameters to array
//     if (is_string($parameters)) {
//         $parameters = [$parameters];
//     }

//     // For lang.switch route, we need both locale and lang parameters
//     if ($name === 'lang.switch') {

//         if (isset($parameters['lang'])) {
//             $langParam = $parameters['lang'];
//         } else {
//             $langParam = 'ar';
//         }
//         return route($name, ['locale' => $locale, 'lang' => $langParam], $absolute);
//     }
//     $parameters = array_merge(['locale' => $locale], $parameters);
//     return route($name, $parameters, $absolute);
// }
// }

if (! function_exists('localizedRoute')) {

    function localizedRoute($name, $parameters = [], $absolute = true)
    {
        //// إذا كان الاسم هو بالفعل رابط كامل (يبدأ بـ http)، أرجعه كما هو
        if (str_starts_with($name, 'http')) {
            return $name;
        }
        try {
           // // بفضل URL::defaults، لارافل سيضيف {locale} تلقائياً
            return route($name, $parameters, $absolute);
        } catch (\Exception $e) {
            //// حل احتياطي في حال فشل التعرف على المسار
            return url(app()->getLocale() . '/' . ltrim($name, '/'));
        }
    }
}

// Default route to redirect to a specific locale
Route::get('/', function () {
    $locale = Session::get('locale') ?? app()->getLocale();
    return redirect($locale);
});

Route::group([
    'prefix' => '{locale}',
    'middleware' => ['SetLocale'], // custom locale middleware is applied here
], function () {

    Route::prefix('Admin-Panel')->group(function () {
        Auth::routes();
    });

            Route::prefix('Admin-Panel')->middleware(['auth'])->group(function () {

                Route::get('/Dashboard', [HomeController::class, 'index'])->name('home');

                Route::prefix('/Post')->middleware(['auth'])->group(function () {
                    Route::put('/Activation/{id}', [PostController::class, 'activation'])->name('post.activation');
                    Route::delete('/Media-delete/{id}', [PostController::class, 'mediaDestroy'])->name('media.destroy');

                });
                Route::prefix('/Media-Center')->middleware(['auth'])->group(function () {
                    Route::resource('news', NewsController::class);
                    Route::resource('photos', PhotosGalaryController::class);
                    Route::resource('videos', VideosController::class);
                    Route::resource('categories', CategoriesController::class);
                    Route::resource('documents',DocumentsController::class);
                    Route::resource('inspection-certificates',InspectionCertificateController::class);
                    Route::resource('specifications',SpecificationController::class);
                    Route::get('show-file/{id}', [DocumentsController::class, 'show'])->name('file.show');
                });
                Route::prefix('/About-Us')->middleware(['auth'])->group(function () {
                    Route::resource('about-company', AboutCompanyController::class);
                    Route::resource('about-company-sections', AboutCompanySectionController::class);
                    Route::put('about-company-sections-activation/{id}', [AboutCompanySectionController::class, 'activation'])->name('about-company-sections-activation');
                    Route::resource('management-board', ManagementController::class);
                    Route::resource('vision-and-message',VisionAndMessageController::class);
                    Route::resource('future-plans',FuturePlansController::class);
                    Route::resource('social-reponsibility', SocialResponsibilityController::class);
                    Route::resource('prizes-and-certificates', CertificatesController::class);
                    Route::resource('our-projects', OurProjectController::class);
                    Route::resource('environments', EnvironmentController::class);
                });
                Route::prefix('/General')->middleware(['auth'])->group(function () {
                    Route::resource('statistics', StatisticsController::class);
                    Route::put('statistics/{id}/toggle-active', [StatisticsController::class, 'toggleActive'])->name('statistics.toggleActive');
                });
                Route::prefix('/Sales-And-Marketing')->middleware(['auth'])->group(function () {
                    Route::resource('hadhrami', HadhramiController::class);
                    Route::resource('products', ProductsController::class);
                    Route::resource('customer-service', CustomerServiceController::class);
                });
                Route::prefix('/Human-Resources')->middleware(['auth'])->group(function () {
                    Route::resource('employee-advantages', EmployeeAdvantagesController::class);
                    Route::resource('employees',EmployeeController::class);
                    Route::resource('our-guests',OurGuestController::class);
                });

                Route::prefix('/electronic-services')->middleware(['auth'])->group(function () {
                    Route::resource('job-application', JobApplicaitonController::class);
                    Route::resource('ask-for-visit', VisitController::class);
                    Route::resource('internship-request',InternshipRequestController::class);
                });

                // cement-blogs
                Route::prefix('/Blogs')->middleware(['auth'])->group(function () {
                    Route::resource('cement-blogs', CementBlogController::class);
                });

                Route::resource('home-page', StartUpController::class);
                Route::resource('contactus-page', ContactUsAdminController::class);
                Route::resource('external-links', ExternalLinksController::class);
                Route::middleware(['SuperAdmin'])->group(function () {
                    Route::resource('users-management', UsersManagementtController::class);
                    Route::put('users-management/{users_management}/toggle-active', [UsersManagementtController::class, 'toggleActive'])->name('users-management.toggleActive');
                });

                Route::put('pages/{id}', [PagesController::class, 'update'])->name('pages.update');
                Route::get('/change-password', [UsersManagementtController::class, 'changePasswordIndex'])->name('change-password.index');
                Route::post('change-password', [UsersManagementtController::class, 'changePassword'])->name('change-password');

                // Route::prefix('/users-management')->middleware(['auth'])->group(function () {
                //     Route::resource('users-data', UsersManagementController::class);
                //     Route::resource('users-privileges', UsersManagementController::class);
                // });

                Route::get('/account-settings', [AccountSettingsController::class, 'accountSettings'])->name('accountSettings');

                Route::prefix('/settings')->group(function () {
                    Route::get('/mail', [MailSettingController::class, 'index'])->name('admin.settings.mail');
                    Route::get('/mail/{id}/edit', [MailSettingController::class, 'edit'])->name('admin.settings.mail.edit');
                    Route::put('/mail/{id}', [MailSettingController::class, 'update'])->name('admin.settings.mail.update');
                });

                Route::group(['prefix' => '/website-management'], function () {
                    Route::get('/website-variables', [WebsiteManagementController::class, 'variables'])->name('webmgt.website_variables');
                    Route::get('/website-logs', [WebsiteManagementController::class, 'logs'])->name('webmgt.website_logs');
                });

                Route::group(['prefix' => '/content-management'], function () {
                    Route::get('/home', [ContentManagementController::class, 'index'])->name('content.home');
                    Route::get('/about-us', [ContentManagementController::class, 'aboutUs'])->name('content.aboutUs');
                    Route::get('/sales-and-marketing', [ContentManagementController::class, 'index'])->name('content.salesAndManagement');
                    Route::get('/human-resources', [ContentManagementController::class, 'index'])->name('content.humanResources');
                    Route::get('/media-center', [ContentManagementController::class, 'index'])->name('content.mediaCenter');
                    Route::get('/blogs', [ContentManagementController::class, 'index'])->name('content.blogs');
                    Route::get('/electronic-services', [ContentManagementController::class, 'index'])->name('content.electronicServices');
                    Route::get('/contact-us', [ContentManagementController::class, 'index'])->name('content.contactUs');
                });

            });




    Route::get('/', [StartPage::class, 'index'])->name('welcome');
    Route::get('/lang/{lang}', action: [LanguageController::class, 'switchLang'])->name('lang.switch');

    //  من نحن
    Route::get('/aboutus', [AboutUsController::class, 'index'])->name('aboutus');
    Route::get('/management', [AboutUsController::class, 'managementIndex'])->name('management');
    Route::get('/visionIndex', [AboutUsController::class, 'visionIndex'])->name('visionIndex');
    Route::get('/futureplans', [AboutUsController::class, 'futurePlansIndex'])->name('futureplans');
    Route::get('/socialresponsibility', [AboutUsController::class, 'socialResponsibilityIndex'])->name('socialresponsibility');
    Route::get('/certificates', [AboutUsController::class, 'certificatesIndex'])->name('certificates');
    Route::get('/ourprojects', [AboutUsController::class, 'ourProjectsIndex'])->name('ourprojects');
    Route::get('/environment', [AboutUsController::class, 'environmentIndex'])->name('environment');

    //  التسويق والمبيعات
    Route::get('/hadrami', [SalesController::class, 'hadramiIndex'])->name('hadrami');
    Route::get('/products', [SalesController::class, 'productsIndex'])->name('products');
    Route::get('/customerservice', [SalesController::class, 'customerserviceIndex'])->name('customerservice');
    // Route::get('/suggestionsandcomplaints', [SalesController::class, 'suggestionsandcomplaintsIndex'])->name('suggestionsandcomplaints');
    // Route::get('/productdetails', [SalesController::class, 'details'])->name('productsdetails');
    // Route::get('/view-document/{filename}', function ($locale , $filename) {
    //     // $path = storage_path('app/public/files/products/' . $filename);
    //     $path = public_path('files\products/' . $filename);
    //     // dd($path);
    //     if (!file_exists($path)) {
    //         abort(404);
    //     }
    //     return response()->file($path, [
    //         'Content-Type' => 'application/pdf',
    //         'Content-Disposition' => 'inline; filename="' . $filename . '"'
    //     ]);
    // })->name('viewpdf');
    // Route::get('/view-pdf/{filename}', [SalesController::class, 'viewPdf'])->name('view.pdf');


    //  مدونة اسمنتية
    Route::get('/cementBlog', [CementBlogWebController::class, 'index'])->name('cementBlog');

    // الموارد البشرية
    Route::get('/employeesAdvantages', [HumanResourcesController::class, 'employeesAdvantagesIndex'])->name('employeesAdvantages');
    Route::get('/jobApplication', [HumanResourcesController::class, 'jobApplicationIndex'])->name('jobApplication');
    Route::get('/askForVisit', [HumanResourcesController::class, 'askForVisitIndex'])->name('askForVisit');
    Route::get('/Internship-Request', [HumanResourcesController::class, 'askForTrainingIndex'])->name('askForTraining');
    Route::get('/employees', [HumanResourcesController::class, 'employeesIndex'])->name('employees');
    Route::get('/our-guests', [HumanResourcesController::class, 'ourGuestsIndex'])->name('ourGuests');



    //  المركز الاعلامي
    Route::get('/newsAndActivities', [MediaCenterController::class, 'newsAndActivitiesIndex'])->name('newsAndActivities');
    Route::get('/News/{id}/{slug?}', [MediaCenterController::class, 'newsShowIndex'])->name('News.show');
    Route::get('/photosGalary', [MediaCenterController::class, 'photosGalaryIndex'])->name('photosGalary');
    Route::get('/videos', [MediaCenterController::class, 'videosIndex'])->name('videos');
    Route::get('/documents', [MediaCenterController::class, 'documentsIndex'])->name('documents');
    Route::get('/documents/{id}/{slug?}', [MediaCenterController::class, 'documentsShowIndex'])->name('landing.documents.show');
    Route::get('/inspection-certificates', [MediaCenterController::class, 'inspectionCertificatesIndex'])->name('inspectionCertificates');
    Route::get('/inspection-certificates/{id}/{slug?}', [MediaCenterController::class, 'inspectionCertificatesShowIndex'])->name('landing.inspectionCertificates.show');
    Route::get('/specifications', [MediaCenterController::class, 'specificationsIndex'])->name('specifications');
    Route::get('/specifications/{id}/{slug?}', [MediaCenterController::class, 'specificationsShowIndex'])->name('landing.specifications.show');

    //  التنمية المستدامة
    // Route::get('/sustainableDevelopment', [SustainableDevelopmentController::class, 'index'])->name('sustainableDevelopment');

    //  تواصل بنا
    Route::get('/contactus', [ContactUsController::class, 'index'])->name('contactus');


    //الخدمات الإلكترونية
    Route::get('/jobApplication', [ElectronicServiceController::class, 'jobApplicationIndex'])->name('jobApplication');
    Route::get('/askForVisit', [ElectronicServiceController::class, 'askForVisitIndex'])->name('askForVisit');
    Route::get('/Internship-Request', [ElectronicServiceController::class, 'askForTrainingIndex'])->name('askForTraining');


    // Public form submissions (emails)
    Route::post('/forms/ask-visit', [FormSubmissionController::class, 'submitVisit'])->name('forms.askVisit');
    Route::post('/forms/ask-training', [FormSubmissionController::class, 'submitTraining'])->name('forms.askTraining');
});
