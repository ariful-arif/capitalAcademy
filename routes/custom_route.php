<?php

use App\Http\Controllers\frontend\CourseController;
use App\Http\Controllers\frontend\MycourseController;
use App\Http\Controllers\frontend\SubscribedController;
use App\Http\Controllers\student\MyProfileController;
use Illuminate\Support\Facades\Route;

Route::controller(CourseController::class)->group(function () {
    Route::get('compare', 'compare')->name('compare');
});

Route::controller(MycourseController::class)->middleware('auth')->group(function () {
    Route::get('Invoice/{id}', 'invoice')->name('invoice');
});

 //manage profile
 Route::controller(MyProfileController::class)->group(function () {

    Route::post('resume/education-add', 'education_add')->name('manage1.education_add');
    Route::post('resume/education-update/{index}', 'education_update')->name('manage1.education_update');
    Route::get('resume/education-remove/{index}', 'education_remove')->name('manage1.education.remove');
});
