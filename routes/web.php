<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth
Auth::routes();

// Index
Route::get('/', 'IndexController@index')->name('index');

// About us
Route::get('/about_us', 'AboutController@index')->name('about_us.index');

// Timeline
Route::get('/timeline', 'TimelineController@index')->name('timeline.index');
Route::get('/timeline/sent', 'TimelineController@sent')->name('timeline.sent');

// User Image Finder
Route::get('/user/{unique_salt_id}/{file_type}', 'FileHandlerController@find');

// Account settings
Route::get('/settings', 'AccountSettingsController@index')->name('settings.index');
Route::get('/settings/email_change', 'AccountSettingsController@email_change')->name('settings.email_change');
Route::get('/settings/password_change', 'AccountSettingsController@password_change')->name('settings.password_change');

Route::post('/settings/change_basic_info', 'AccountSettingsController@change_basic_info')->name('settings.change_basic_info');
Route::post('/settings/change_email', 'AccountSettingsController@change_email')->name('settings.change_email');
Route::post('/settings/change_password', 'AccountSettingsController@change_password')->name('settings.change_password');
Route::post('/settings/change_profile_picture', 'AccountSettingsController@change_profile_picture')->name('settings.change_profile_picture');
Route::post('/settings/change_profile_banner', 'AccountSettingsController@change_profile_banner')->name('settings.change_profile_banner');

// Incog
Route::get('/incog/{username}', 'IncogController@index');
Route::post('/incog/message/send', 'IncogController@send')->name('incog.message_send');
Route::post('/incog/message/hide', 'IncogController@hide')->name('incog.hide');
Route::post('/incog/message/reply', 'IncogController@reply')->name('incog.reply');

// Profiles
Route::get('/p/{username}', 'ProfileController@index');

// Braintree
Route::get('/generate/token', 'BraintreeController@token')->name('braintree.token');
