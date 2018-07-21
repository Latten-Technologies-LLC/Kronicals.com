<?php
use App\Libraries\Notifications;
use App\Libraries\TutorialSystem;
use Illuminate\Http\Request;

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

Route::get('/timeline/anons', 'TimelineController@anons')->name('timeline.anons');
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
Route::post('/incog/message/confess', 'IncogController@confess')->name('incog.confess');

// Profiles
Route::get('/p/{username}', 'ProfileController@index');
Route::get('/p/{username}/feed', 'ProfileController@feed');
Route::get('/p/{username}/followings', 'ProfileController@followings');
Route::get('/p/{username}/followers', 'ProfileController@followers');
Route::get('/p/{username}/about', 'ProfileController@about');


// Braintree
Route::get('/generate/token', 'BraintreeController@token')->name('braintree.token');
Route::post('/ads/disable', 'BraintreeController@disableAds')->name('braintree.disablead');

// Search
Route::post('/search/live', 'SearchController@live')->name('search.live');

// Following system
Route::post('/follow/subscribe', 'FollowController@subscribe')->name('follow.subscribe');
Route::post('/follow/unsubscribe', 'FollowController@unsubscribe')->name('follow.unsubscribe');

// Notifications
Route::post('/notifications/read', function()
{
    $n = new Notifications();
    $n->markAllRead(auth()->user()->unique_salt_id);
})->name('notifications.read');

Route::get('/notifications/notify', function(){
    event(new App\Events\PostLiked('3'));
    return 'sent message';
});

// Posting system
Route::post('/posting/new', 'PostsController@make')->name('posting.new');
Route::post('/posting/action/like', 'PostsController@like')->name('posting.action.like');
Route::post('/posting/action/unlike', 'PostsController@unlike')->name('posting.action.unlike');
Route::post('/posting/action/delete', 'PostsController@delete')->name('posting.action.delete');
Route::post('/posting/action/comment', 'PostsController@comment')->name('posting.action.comment');

// Tutorial system
Route::post('/tutorials/update', function(Request $request)
{
    $t = new TutorialSystem();
    $t->update($request->tut);
})->name('tutorials.update');

// Diary
Route::get('/diary', 'DiaryController@index')->name('diary.index');
Route::get('/diary/view/{entry_id}', 'DiaryController@view')->name('diary.view');
Route::post('/diary/convert', 'DiaryController@convert')->name('diary.convert');