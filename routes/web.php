<?php

/*
|--------------------------------------------------------------------------
| Web routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Locale routes
Route::post('/locale', 'LocaleController@updateLocale')->name('locale');

// Remote Redirect routes
if (isset(parse_url(config('app.url'))['host']) && request()->getHost() != parse_url(config('app.url'))['host']) {
    Route::get('/{id}/+', 'RedirectController@index')->name('link.preview');
    Route::get('/{id}', 'RedirectController@index')->name('link.redirect');
    Route::post('/{id}/password', 'RedirectController@validatePassword');
    Route::post('/{id}/consent', 'RedirectController@validateConsent');
}

// Auth routes
Auth::routes(['verify' => true]);
Route::post('login/tfa/validate', 'Auth\LoginController@validateTfaCode')->name('login.tfa.validate');
Route::post('login/tfa/resend', 'Auth\LoginController@resendTfaCode')->name('login.tfa.resend');

// Install routes
Route::prefix('install')->group(function () {
    Route::middleware('install')->group(function () {
        Route::get('/', 'InstallController@index')->name('install');
        Route::get('/requirements', 'InstallController@requirements')->name('install.requirements');
        Route::get('/permissions', 'InstallController@permissions')->name('install.permissions');
        Route::get('/database', 'InstallController@database')->name('install.database');
        Route::get('/account', 'InstallController@account')->name('install.account');

        Route::post('/database', 'InstallController@storeConfig');
        Route::post('/account', 'InstallController@storeDatabase');
    });

    Route::get('/complete', 'InstallController@complete')->name('install.complete');
});

// Update routes
Route::prefix('update')->group(function () {
    Route::get('/', 'UpdateController@index')->name('update');
    Route::get('/overview', 'UpdateController@overview')->name('update.overview');
    Route::get('/complete', 'UpdateController@complete')->name('update.complete');

    Route::post('/overview', 'UpdateController@updateDatabase');
});

// Home routes
Route::get('/', 'HomeController@index')->name('home');
Route::post('/shorten', 'HomeController@createLink')->middleware('throttle:10,1')->name('guest');

// Contact routes
Route::get('/contact', 'ContactController@index')->name('contact');
Route::post('/contact', 'ContactController@send')->middleware('throttle:5,10');

// Page routes
Route::get('/pages/{id}', 'PageController@show')->name('pages.show');

// Dashboard routes
Route::get('/dashboard', 'DashboardController@index')->middleware('verified')->name('dashboard');

// Link routes
Route::get('/links', 'LinkController@index')->middleware('verified')->name('links');
Route::get('/links/{id}/edit', 'LinkController@edit')->middleware('verified')->name('links.edit');
Route::get('/links/export', 'LinkController@export')->middleware('verified')->name('links.export');
Route::post('/links/new', 'LinkController@store')->name('links.new');
Route::post('/links/{id}/edit', 'LinkController@update');
Route::post('/links/{id}/destroy', 'LinkController@destroy')->name('links.destroy');

// Space routes
Route::get('/spaces', 'SpaceController@index')->middleware('verified')->name('spaces');
Route::get('/spaces/new', 'SpaceController@create')->middleware('verified')->name('spaces.new');
Route::get('/spaces/{id}/edit', 'SpaceController@edit')->middleware('verified')->name('spaces.edit');
Route::post('/spaces/new', 'SpaceController@store');
Route::post('/spaces/{id}/edit', 'SpaceController@update');
Route::post('/spaces/{id}/destroy', 'SpaceController@destroy')->name('spaces.destroy');

// Domain routes
Route::get('/domains', 'DomainController@index')->middleware('verified')->name('domains');
Route::get('/domains/new', 'DomainController@create')->middleware('verified')->name('domains.new');
Route::get('/domains/{id}/edit', 'DomainController@edit')->middleware('verified')->name('domains.edit');
Route::post('/domains/new', 'DomainController@store');
Route::post('/domains/{id}/edit', 'DomainController@update');
Route::post('/domains/{id}/destroy', 'DomainController@destroy')->name('domains.destroy');

// Pixel routes
Route::get('/pixels', 'PixelController@index')->middleware('verified')->name('pixels');
Route::get('/pixels/new', 'PixelController@create')->middleware('verified')->name('pixels.new');
Route::get('/pixels/{id}/edit', 'PixelController@edit')->middleware('verified')->name('pixels.edit');
Route::post('/pixels/new', 'PixelController@store');
Route::post('/pixels/{id}/edit', 'PixelController@update');
Route::post('/pixels/{id}/destroy', 'PixelController@destroy')->name('pixels.destroy');

// Stat routes
Route::prefix('/stats/{id}')->group(function () {
    Route::get('/', 'StatController@index')->name('stats.overview');

    Route::get('/referrers', 'StatController@referrers')->name('stats.referrers');
    Route::get('/countries', 'StatController@countries')->name('stats.countries');
    Route::get('/cities', 'StatController@cities')->name('stats.cities');
    Route::get('/languages', 'StatController@languages')->name('stats.languages');
    Route::get('/browsers', 'StatController@browsers')->name('stats.browsers');
    Route::get('/platforms', 'StatController@platforms')->name('stats.platforms');
    Route::get('/devices', 'StatController@devices')->name('stats.devices');

    Route::prefix('/export')->group(function () {
        Route::get('/referrers', 'StatController@exportReferrers')->name('stats.export.referrers');
        Route::get('/countries', 'StatController@exportCountries')->name('stats.export.countries');
        Route::get('/cities', 'StatController@exportCities')->name('stats.export.cities');
        Route::get('/languages', 'StatController@exportLanguages')->name('stats.export.languages');
        Route::get('/browsers', 'StatController@exportBrowsers')->name('stats.export.browsers');
        Route::get('/platforms', 'StatController@exportPlatforms')->name('stats.export.platforms');
        Route::get('/devices', 'StatController@exportDevices')->name('stats.export.devices');
    });

    Route::post('/password', 'StatController@validatePassword')->name('stats.password');
});

// QR routes
Route::get('/qr/{id}', 'QrController@index')->name('qr');

// Account routes
Route::prefix('account')->middleware('verified')->group(function () {
    Route::get('/', 'AccountController@index')->name('account');

    Route::get('/profile', 'AccountController@profile')->name('account.profile');
    Route::post('/profile', 'AccountController@updateProfile')->name('account.profile.update');
    Route::post('/profile/resend', 'AccountController@resendAccountEmailConfirmation')->name('account.profile.resend');
    Route::post('/profile/cancel', 'AccountController@cancelAccountEmailConfirmation')->name('account.profile.cancel');

    Route::get('/security', 'AccountController@security')->name('account.security');
    Route::post('/security', 'AccountController@updateSecurity');

    Route::get('/preferences', 'AccountController@preferences')->name('account.preferences');
    Route::post('/preferences', 'AccountController@updatePreferences');

    Route::get('/plan', 'AccountController@plan')->name('account.plan');
    Route::post('/plan', 'AccountController@updatePlan')->middleware('payment');

    Route::get('/payments', 'AccountController@indexPayments')->middleware('payment')->name('account.payments');
    Route::get('/payments/{id}/edit', 'AccountController@editPayment')->middleware('payment')->name('account.payments.edit');
    Route::post('/payments/{id}/cancel', 'AccountController@cancelPayment')->name('account.payments.cancel');

    Route::get('/invoices/{id}', 'AccountController@showInvoice')->middleware('payment')->name('account.invoices.show');

    Route::get('/api', 'AccountController@api')->name('account.api');
    Route::post('/api', 'AccountController@updateApi');

    Route::get('/delete', 'AccountController@delete')->name('account.delete');
    Route::post('/destroy', 'AccountController@destroyUser')->name('account.destroy');
});

// Admin routes
Route::prefix('admin')->middleware('admin', 'license')->group(function () {
    Route::redirect('/', 'admin/dashboard');

    Route::get('/dashboard', 'AdminController@dashboard')->name('admin.dashboard');

    Route::get('/settings/{id}', 'AdminController@settings')->name('admin.settings');
    Route::post('/settings/{id}', 'AdminController@updateSetting');

    Route::get('/users', 'AdminController@indexUsers')->name('admin.users');
    Route::get('/users/new', 'AdminController@createUser')->name('admin.users.new');
    Route::get('/users/{id}/edit', 'AdminController@editUser')->name('admin.users.edit');
    Route::post('/users/new', 'AdminController@storeUser');
    Route::post('/users/{id}/edit', 'AdminController@updateUser');
    Route::post('/users/{id}/destroy', 'AdminController@destroyUser')->name('admin.users.destroy');
    Route::post('/users/{id}/disable', 'AdminController@disableUser')->name('admin.users.disable');
    Route::post('/users/{id}/restore', 'AdminController@restoreUser')->name('admin.users.restore');

    Route::get('/pages', 'AdminController@indexPages')->name('admin.pages');
    Route::get('/pages/new', 'AdminController@createPage')->name('admin.pages.new');
    Route::get('/pages/{id}/edit', 'AdminController@editPage')->name('admin.pages.edit');
    Route::post('/pages/new', 'AdminController@storePage');
    Route::post('/pages/{id}/edit', 'AdminController@updatePage');
    Route::post('/pages/{id}/destroy', 'AdminController@destroyPage')->name('admin.pages.destroy');

    Route::get('/payments', 'AdminController@indexPayments')->name('admin.payments');
    Route::get('/payments/{id}/edit', 'AdminController@editPayment')->name('admin.payments.edit');
    Route::post('/payments/{id}/approve', 'AdminController@approvePayment')->name('admin.payments.approve');
    Route::post('/payments/{id}/cancel', 'AdminController@cancelPayment')->name('admin.payments.cancel');

    Route::get('/invoices/{id}', 'AdminController@showInvoice')->name('admin.invoices.show');

    Route::get('/plans', 'AdminController@indexPlans')->name('admin.plans');
    Route::get('/plans/new', 'AdminController@createPlan')->name('admin.plans.new');
    Route::get('/plans/{id}/edit', 'AdminController@editPlan')->name('admin.plans.edit');
    Route::post('/plans/new', 'AdminController@storePlan');
    Route::post('/plans/{id}/edit', 'AdminController@updatePlan');
    Route::post('/plans/{id}/disable', 'AdminController@disablePlan')->name('admin.plans.disable');
    Route::post('/plans/{id}/restore', 'AdminController@restorePlan')->name('admin.plans.restore');

    Route::get('/coupons', 'AdminController@indexCoupons')->name('admin.coupons');
    Route::get('/coupons/new', 'AdminController@createCoupon')->name('admin.coupons.new');
    Route::get('/coupons/{id}/edit', 'AdminController@editCoupon')->name('admin.coupons.edit');
    Route::post('/coupons/new', 'AdminController@storeCoupon');
    Route::post('/coupons/{id}/edit', 'AdminController@updateCoupon');
    Route::post('/coupons/{id}/disable', 'AdminController@disableCoupon')->name('admin.coupons.disable');
    Route::post('/coupons/{id}/restore', 'AdminController@restoreCoupon')->name('admin.coupons.restore');

    Route::get('/tax-rates', 'AdminController@indexTaxRates')->name('admin.tax_rates');
    Route::get('/tax-rates/new', 'AdminController@createTaxRate')->name('admin.tax_rates.new');
    Route::get('/tax-rates/{id}/edit', 'AdminController@editTaxRate')->name('admin.tax_rates.edit');
    Route::post('/tax-rates/new', 'AdminController@storeTaxRate');
    Route::post('/tax-rates/{id}/edit', 'AdminController@updateTaxRate');
    Route::post('/tax-rates/{id}/disable', 'AdminController@disableTaxRate')->name('admin.tax_rates.disable');
    Route::post('/tax-rates/{id}/restore', 'AdminController@restoreTaxRate')->name('admin.tax_rates.restore');

    Route::get('/links', 'AdminController@indexLinks')->name('admin.links');
    Route::get('/links/{id}/edit', 'AdminController@editLink')->name('admin.links.edit');
    Route::post('/links/{id}/edit', 'AdminController@updateLink');
    Route::post('/links/{id}/destroy', 'AdminController@destroyLink')->name('admin.links.destroy');

    Route::get('/spaces', 'AdminController@indexSpaces')->name('admin.spaces');
    Route::get('/spaces/{id}/edit', 'AdminController@editSpace')->name('admin.spaces.edit');
    Route::post('/spaces/{id}/edit', 'AdminController@updateSpace');
    Route::post('/spaces/{id}/destroy', 'AdminController@destroySpace')->name('admin.spaces.destroy');

    Route::get('/domains', 'AdminController@indexDomains')->name('admin.domains');
    Route::get('/domains/new', 'AdminController@createDomain')->name('admin.domains.new');
    Route::get('/domains/{id}/edit', 'AdminController@editDomain')->name('admin.domains.edit');
    Route::post('/domains/new', 'AdminController@storeDomain');
    Route::post('/domains/{id}/edit', 'AdminController@updateDomain');
    Route::post('/domains/{id}/destroy', 'AdminController@destroyDomain')->name('admin.domains.destroy');

    Route::get('/pixels', 'AdminController@indexPixels')->name('admin.pixels');
    Route::get('/pixels/{id}/edit', 'AdminController@editPixel')->name('admin.pixels.edit');
    Route::post('/pixels/{id}/edit', 'AdminController@updatePixel');
    Route::post('/pixels/{id}/destroy', 'AdminController@destroyPixel')->name('admin.pixels.destroy');
});

// Pricing routes
Route::prefix('pricing')->middleware('payment')->group(function () {
    Route::get('/', 'PricingController@index')->name('pricing');
});

// Checkout routes
Route::prefix('checkout')->middleware('verified', 'payment')->group(function () {
    Route::get('/cancelled', 'CheckoutController@cancelled')->name('checkout.cancelled');
    Route::get('/pending', 'CheckoutController@pending')->name('checkout.pending');
    Route::get('/complete', 'CheckoutController@complete')->name('checkout.complete');

    Route::get('/{id}', 'CheckoutController@index')->name('checkout.index');
    Route::post('/{id}', 'CheckoutController@process');
});

// Cronjob routes
Route::get('/cronjob', 'CronjobController@index')->name('cronjob');

// Webhook routes
Route::post('webhooks/paypal', 'WebhookController@paypal')->name('webhooks.paypal');
Route::post('webhooks/stripe', 'WebhookController@stripe')->name('webhooks.stripe');
Route::post('webhooks/razorpay', 'WebhookController@razorpay')->name('webhooks.razorpay');
Route::post('webhooks/paystack', 'WebhookController@paystack')->name('webhooks.paystack');
Route::post('webhooks/cryptocom', 'WebhookController@cryptocom')->name('webhooks.cryptocom');
Route::post('webhooks/coinbase', 'WebhookController@coinbase')->name('webhooks.coinbase');

// Developer routes
Route::prefix('/developers')->group(function () {
    Route::get('/', 'DeveloperController@index')->name('developers');
    Route::get('/links', 'DeveloperController@links')->name('developers.links');
    Route::get('/spaces', 'DeveloperController@spaces')->name('developers.spaces');
    Route::get('/domains', 'DeveloperController@domains')->name('developers.domains');
    Route::get('/pixels', 'DeveloperController@pixels')->name('developers.pixels');
    Route::get('/stats', 'DeveloperController@stats')->name('developers.stats');
    Route::get('/account', 'DeveloperController@account')->name('developers.account');
});

// Redirect routes
Route::get('/{id}/+', 'RedirectController@index')->name('link.preview');
Route::get('/{id}', 'RedirectController@index')->name('link.redirect');
Route::post('/{id}/password', 'RedirectController@validatePassword');
Route::post('/{id}/consent', 'RedirectController@validateConsent');