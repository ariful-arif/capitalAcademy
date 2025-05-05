<?php

use App\Http\Controllers\frontend\LanguageController;
use App\Http\Controllers\instructor\BlogController;
use App\Http\Controllers\instructor\BootcampController;
use App\Http\Controllers\instructor\BootcampLiveClassController;
use App\Http\Controllers\instructor\BootcampModuleController;
use App\Http\Controllers\instructor\BootcampResourceController;
use App\Http\Controllers\instructor\CourseController;
use App\Http\Controllers\instructor\LessonController;
use App\Http\Controllers\instructor\MyProfileController;
use App\Http\Controllers\instructor\PayoutController;
use App\Http\Controllers\instructor\PayoutSettingsController;
use App\Http\Controllers\instructor\QuestionController;
use App\Http\Controllers\instructor\QuizController;
use App\Http\Controllers\instructor\SalesReportController;
use App\Http\Controllers\instructor\SectionController;
use App\Http\Controllers\instructor\TeamTrainingController;
use App\Http\Controllers\instructor\OpenAiController;
use App\Http\Controllers\instructor\TutorBookingController;
use App\Http\Controllers\instructor\LiveClassController;
use App\Http\Controllers\organization\DashboardController;
use App\Http\Controllers\organization\SubscriptionPackageController;
use App\Http\Controllers\organization\TeamController;
use App\Http\Controllers\organization\UsersController;
use App\Models\SubscriptionPackageEnrollment;
use Illuminate\Support\Facades\Route;

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

Route::name('organization.')->prefix('organization')->middleware(['organization'])->group(function () {
    // dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::controller(UsersController::class)->group(function () {
        Route::get('users', 'index')->name('users');
        Route::get('users/create', 'create')->name('users.create');
        Route::post('users/store', 'store')->name('users.store');
        Route::get('users/edit/{id}', 'edit')->name('users.edit');
        Route::post('users/update/{id}', 'update')->name('users.update');
        Route::get('users/delete/{id}', 'delete')->name('users.delete');
    });

    Route::controller(TeamController::class)->group(function () {
        Route::get('teams', 'index')->name('teams');
        Route::get('teams/create', 'create')->name('teams.create');
        Route::post('teams/store', 'store')->name('teams.store');
        Route::get('teams/edit/{id}', 'edit')->name('teams.edit');
        Route::post('teams/update/{id}', 'update')->name('teams.update');
        Route::get('teams/delete/{id}', 'delete')->name('teams.delete');

        Route::get('teams/users', 'users')->name('teams.users');
        Route::get('teams/get_team_members', 'getTeamMembers')->name('teams.get_team_members');
        Route::post('teams/users_add', 'users_add')->name('teams.users_add');

        Route::get('progress', 'progress')->name('progress');

    });

    Route::controller(SubscriptionPackageController::class)->group(function () {
        Route::get('subscription', 'index')->name('subscription');

    });

});
