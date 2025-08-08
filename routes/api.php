<?php

use App\Http\Controllers\Api\AiTestController;
use App\Http\Controllers\Api\AppointmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\AreaController;
use App\Http\Controllers\api\DataSettingController;
use App\Http\Controllers\api\DistributorController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\api\PrivacySettingController;
use App\Http\Controllers\api\ProviderController;
use App\Http\Controllers\api\ReportController;
use App\Http\Controllers\api\UserController;
use App\Models\Distributor;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'me']);
});

Route::prefix('areas')->group(function () {
    Route::get('/', [AreaController::class, 'index']);      // كل المناطق
    Route::get('/{id}', [AreaController::class, 'show']);   // منطقة واحدة
    Route::post('/', [AreaController::class, 'store']);     // إنشاء
    Route::put('/{id}', [AreaController::class, 'update']); // تحديث
    Route::delete('/{id}', [AreaController::class, 'destroy']); // حذف
});

Route::prefix('dashboard')->group(function () {
Route::get('/', [AreaController::class, 'index']); // بيانات المناطق مع الحالات
Route::get('/monthly-users', [AreaController::class, 'monthlyUsersByArea']); // بيانات المستخدمين شهرياً
Route::get('/site-summary', [AreaController::class, 'siteSummary']); // ملخص الموقع
Route::get('/show/{id}', [AreaController::class, 'show']);

});

Route::get('/ai-tests/user/{userId}', [AiTestController::class, 'getByUserId']);

Route::post('/send-notification', [AiTestController::class, 'sendNotification']);

 Route::post('/appointments', [AppointmentController::class, 'store']);

Route::get('/providers', [ProviderController::class, 'index']);
Route::post('/providers/save', [ProviderController::class, 'store']);

Route::get('/distributors', [DistributorController::class, 'index']);
Route::post('/distributors/save', [DistributorController::class, 'store']);

Route::put('/distributors/{id}', [DistributorController::class, 'update']);
Route::post('/distributors/{id}', [DistributorController::class, 'update']);

Route::delete('/distributors/{id}', [DistributorController::class, 'destroy']);


Route::put('/providers/{id}', [ProviderController::class, 'update']);
Route::post('/providers/{id}', [ProviderController::class, 'update']);

Route::delete('/providers/{id}', [ProviderController::class, 'destroy']);

Route::get('/users', [UserController::class, 'index']);
Route::post('/users/save', [UserController::class, 'store']);

Route::get('/users/{id}', [UserController::class, 'show']);

Route::get('admins', [UserController::class, 'getAdmins']);
Route::delete('admins/{id}', [UserController::class, 'destroy']);

// routes/api.php

Route::post('/users/{id}', [UserController::class, 'update']);
// routes/api.php

Route::delete('/users/{id}', [UserController::class, 'destroyuser']);

Route::prefix('reports')->group(function () {
    Route::get('countries', [ReportController::class, 'allCountries']); // للحصول على قائمة الدول

    Route::get('by-country', [ReportController::class, 'byCountry']);  // ممكن تدعم فلترة الدولة
    Route::get('by-gender', [ReportController::class, 'byGender']);    // ممكن تدعم فلترة الجنس

    Route::get('date-range', [ReportController::class, 'byDateRange']); // دعم التاريخ

    Route::get('patient-report/{id}', [ReportController::class, 'patientReport']);
});

    Route::apiResource('languages', LanguageController::class);
Route::get('/privacy-settings', [PrivacySettingController::class, 'index']);
Route::post('/settings', [PrivacySettingController::class, 'update']);


Route::get('/settings/data', [DataSettingController::class, 'index']);
Route::post('/settings/data', [DataSettingController::class, 'update']);
Route::get('/default-language', [LanguageController::class, 'getDefaultLanguage']);

