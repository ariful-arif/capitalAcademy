<?php

use App\Http\Controllers\CommonController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\ModalController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\frontend\HomeController;
use App\Http\Controllers\PaymentController;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Passport\Token;
use Laravel\Passport\PersonalAccessTokenResult;
use Laravel\Passport\PersonalAccessTokenFactory;
use Illuminate\Support\Facades\DB;

Route::get('/token-login', function (Request $request) {
    $tokenString = $request->query('token');

    if (!$tokenString) {
        abort(403, 'Missing token');
    }

    // Validate token
    $tokenId = explode('|', $tokenString)[0]; // token_id is before the |
    $token = DB::table('oauth_access_tokens')->where('id', $tokenId)->first();

    if (!$token || $token->revoked) {
        abort(403, 'Invalid or revoked token');
    }

    $userModel = \App\Models\User::find($token->user_id);

    if (!$userModel || $userModel->role !== 'admin') {
        abort(403, 'Unauthorized');
    }

    // Log the user in (create session)
    Auth::login($userModel);

    // Now redirect to the actual admin dashboard
    return redirect('/admin/dashboard');
});

Route::get('/admin/magic-login/{user}', function (Request $request, $userId) {
    if (! $request->hasValidSignature()) {
        abort(401, 'Invalid or expired link.');
    }

    // $user = User::findOrFail($userId);
    $user = User::where('id', $userId)->firstOrFail();


    if ($user->role !== 'admin') {
        abort(403, 'Not authorized.');
    }

    Auth::login($user); // Log in user to web session

    return redirect('/admin/dashboard'); // Laravel Blade dashboard
})->name('admin.magic-login.redirect');


//Cache clear route
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Cache::flush();

    return 'Application cache cleared';
});

Route::get('home/switch/{id}', [HomeController::class, 'homepage_switcher'])->name('home.switch');

//Redirect route
Route::get('/dashboard', function () {
    if (auth()->user()->role == 'admin') {
        return redirect(route('admin.dashboard'));
    }elseif(auth()->user()->role == 'organization'){
        return redirect(route('organization.dashboard'));
    }elseif(auth()->user()->role == 'student'){
        return redirect(route('my.courses'));
    } else {
        return redirect(route('home'));
    }
})->middleware(['auth', 'verified'])->name('dashboard');

//Common modal route
Route::get('modal/{view_path}', [ModalController::class, 'common_view_function'])->name('modal');
Route::any('get-video-details/{url?}', [CommonController::class, 'get_video_details'])->name('get.video.details');
Route::get('view/{path}', [CommonController::class, 'rendered_view'])->name('view');

Route::get('closed_back_to_mobile_ber', function () {
    session()->forget('app_url');
    return redirect()->back();
})->name('closed_back_to_mobile_ber');

//Mobile payment redirect
Route::get('payment/web_redirect_to_pay_fee', [PaymentController::class, 'webRedirectToPayFee'])->name('payment.web_redirect_to_pay_fee');

//Installation routes
Route::controller(InstallController::class)->group(function () {
    Route::get('/install_ended', 'index');
    Route::get('install/step0', 'step0')->name('step0');
    Route::get('install/step1', 'step1')->name('step1');
    Route::get('install/step2', 'step2')->name('step2');
    Route::any('install/step3', 'step3')->name('step3');
    Route::get('install/step4', 'step4')->name('step4');
    Route::get('install/step4/{confirm_import}', 'confirmImport')->name('step4.confirm_import');
    Route::get('install/install', 'confirmInstall')->name('confirm_install');
    Route::post('install/validate', 'validatePurchaseCode')->name('install.validate');
    Route::any('install/finalizing_setup', 'finalizingSetup')->name('finalizing_setup');
    Route::get('install/success', 'success')->name('success');
});
//Installation routes
