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

// Auth::routes(['verify'=>true]);
Auth::routes();
Route::get('users/{user}/{token}', 'UserController@confirm')->name('confirm');
Route::get('email_sent', 'UserController@email_sent')->name('email_sent')->middleware('redirect_email');
Route::get('email_resend', 'AdminController@email_re_send')->name('email_re_send');
Route::get('/home/{id?}', 'HomeController@index')->name('home');
Route::get('/buy/{id}','BuyController@buy')->name('buy')->middleware('email_ver');
Route::get('/reserve/{id}','BuyController@reserve')->name('reserve')->middleware('email_ver');
Route::post('/order','BuyController@order')->name('order');
Route::get('/how_buy', function () {
   return view('pages.how_buy');
})->name('how_buy');
Route::get('/contacts', function () {
    return view('pages.contacts');
})->name('contacts');

Route::post('/contact_send', 'UserController@contact_send')->name('contact_send');
Route::post('/feedback_send', 'UserController@feedback_send')->name('feedback_send');

Route::get('/info/{id}', 'EventController@show')->name('event.view');
Route::resource('event','EventController')->middleware('auth','event');
Route::get('/event.create','EventController@create')->name('event_create')->middleware('auth', "franchise");
Route::get('/event.info.create', 'EventController@info_create')->name('event_info_create')->middleware('auth','franchise');
Route::post('/event.info.store', 'EventController@info_store')->name('event_info_store')->middleware('auth','franchise');
Route::post('/event.info.update', 'EventController@info_update')->name('event_info_update')->middleware('auth','franchise');
Route::get('/event.index','EventController@index')->name('event_index')->middleware('auth','franchise');
Route::get('/event.list/{id?}','EventController@event_list')->name('event_list')->middleware('auth');// всем кроме usera
Route::post('/event.store','EventController@store')->name('event_store')->middleware('auth');
Route::get('/event.check.active','EventController@check_active')->name('event_check_active');

Route::post('/event.change.status','EventController@change_status')->name('event_change_status');


Route::get('/profile','UserController@profile')->name('user_profile')->middleware('auth');

//позже закинуть в группы
Route::get('/franchise.index','FranchiseController@index')->name('franchise_index')->middleware('auth','event');
Route::get('/franchise.create','FranchiseController@create')->name('franchise_create')->middleware('auth','event');
Route::post('/franchise.store/{id?}','FranchiseController@store')->name('franchise_store')->middleware('auth','event');
Route::get('/franchise.show/{id}','FranchiseController@show')->name('franchise_show')->middleware('auth','event');
Route::get('/franchise.update/{id}','FranchiseController@update')->name('franchise_update')->middleware('auth','event');

///////////////////////////////////////////////////////////////////////////////////////////////////////////
///Партнеры
Route::get('/franchise.partners','FranchiseController@partners_list')->name('partners_list')->middleware('auth');;

//Create partner referral
Route::get('/partner_new','FranchiseController@partner_new')->name('partner_new');
Route::post('/partner_new_store','FranchiseController@partner_new_store')->name('partner_new_store');
Route::post('/partner_new_pay','FranchiseController@partner_new_pay')->name('partner_new_pay');
Route::get('/check_certain_partner_sell/{id}','FranchiseController@check_certain_partner_sell')->name('check_certain_partner_sell');
Route::get('/paybox_success_partner_sell','FranchiseController@paybox_success_partner_sell')->name('paybox_success_partner_sell');
Route::get('/paybox_failure_partner_sell','FranchiseController@paybox_failure_partner_sell')->name('paybox_failure_partner_sell');
Route::get('/paybox_result_partner_sell','FranchiseController@paybox_result_partner_sell')->name('paybox_result_partner_sell');
Route::post('/pending_change_paid','FranchiseController@pending_change_paid')->name('pending_change_paid');
///////////////////////////////////////////////////////////////////////////////////////////////////////////
//Profile + создать мидлвары

Route::get('/franchise.profile/{id}','FranchiseController@profile')->name('profile')->middleware('userAccess','auth');
Route::get('/franchise.profile_edit/{id}','FranchiseController@profile_edit')->name('profile_edit')->middleware('userAccess','auth');
Route::post('/franchise.profile_update/{id}','FranchiseController@profile_update')->name('profile_update')->middleware('userAccess','auth');
Route::post('/franchise.profile_image_update/{id}','FranchiseController@profile_image_update')->name('profile_image_update')->middleware('userAccess','auth');
///////////////////////////////////////////////////////////////////////////////////////////////////////////
Route::get('/hall.create/{id}', 'HallController@index')->name('hall.create');
Route::post('/hall.create', 'HallController@store')->name('hall.store');
Route::post('/hall.delete', 'HallController@destroy')->name('hall.destroy');
Route::post('/hall.delete/all', 'HallController@destroyAll')->name('hall.all.destroy');
Route::post('/hall.set/type', 'HallController@setType')->name('hall.set.type');
Route::post('/hall.set/shape', 'HallController@setShape')->name('hall.set.shape');
Route::post('/hall.set/shape_out', 'HallController@setShapeOut')->name('hall.set.shape_out');
Route::post('/hall.set/setSpeakerPosition', 'HallController@setSpeakerPosition')->name('hall.setSpeakerPosition');
Route::post('/hall.delete/row', 'HallController@deleteRow')->name('hall.delete.row');
Route::post('/hall.delete/column', 'HallController@deleteColumn')->name('hall.delete.column');
Route::get('/test', 'HallController@testFunc');
Route::get('/admin/{id?}','AdminController@index')->name('admin')->middleware('franchise');
Route::get('/category','AdminController@category')->name('category')->middleware('franchise');
Route::get('/create_category','AdminController@create_category')->name('create_category')->middleware('franchise');
Route::get('/edit_category','AdminController@edit_category')->name('edit_category')->middleware('franchise');
Route::get('/delete_category/{id}','AdminController@delete_category')->name('delete_category')->middleware('franchise');
Route::post('/store_category','AdminController@store_category')->name('store_category')->middleware('franchise');
Route::post('/update_category','AdminController@update_category')->name('update_category')->middleware('franchise');

Route::get('/user_list', 'AdminController@user_list')->name('user_list')->middleware('auth','event');
Route::get('/clients_list', 'AdminController@clients_list')->name('admin_clients_list')->middleware('auth');
Route::get('/archive', 'AdminController@archive')->name('archive')->middleware('auth');
Route::post('/archive.delete', 'AdminController@delete_from_archive')->name('delete_from_archive')->middleware('auth');
Route::post('/user_change_franch', 'AdminController@user_change_franch')->name('user_change_franch')->middleware('auth');
Route::post('/partner_sell_data', 'AdminController@partner_sell_data')->name('partner_sell_data')->middleware('auth');
Route::post('/change_franch_event_rights', 'AdminController@change_franch_event_rights')->name('change_franch_event_rights')->middleware('auth');

Route::get('/user_activity_statistics','AdminController@user_activity_statistics')->name('user_activity_statistics')->middleware('franchise');
Route::post('/getUserActivityReport','AdminController@getUserActivityReport')->name('getUserActivityReport')->middleware('franchise');
Route::post('/getUserWithRole','AdminController@getUserWithRole')->name('getUserWithRole')->middleware('franchise');


Route::post('/franchise.block','AdminController@blockFranch')->name('franchise_block')->middleware('auth');
Route::post('/franchise.unblock','AdminController@unblockFranch')->name('franchise_unblock')->middleware('auth');
///Франчайзи
///
Route::get('/franchise.clients', 'FranchiseController@clients')->name('client_list')->middleware('franchise');
Route::get('/franchise.partner_clients/{franch}', 'FranchiseController@part_clients_for_franch')->name('partner_clients')->middleware('franchise');


///Франчайзи организатор
///
Route::get('/event.admin.report', 'FranchiseController@EventAdminReport')->name('eventAdmin/report')->middleware('franchise');
Route::get('/event.admin.report.single.event/{event}', 'FranchiseController@EventAdminReportSingleEvent')->name('eventAdmin/report/single/event')->middleware('franchise');

Route::get('/event.admin.report.all', 'FranchiseController@EventAdminReportAll')->name('eventAdmin/report/all');
Route::post('/ajax_get_report', 'FranchiseController@AjaxGetReport');
///Partner report
Route::get('/event.partner.report', 'FranchiseController@partnerReport')->name('partner_report');
///Scene ticket buy and reserve
Route::post('/buy_ticket', 'BuyController@buy_ticket')->name('buy_ticket');
Route::post('/reserve_ticket', 'BuyController@reserve_ticket')->name('reserve_ticket');
Route::get('/check_payment', 'BuyController@check_payment')->name('check_payment');
Route::get('/check_certain_payment/{id}', 'BuyController@check_certain_payment')->name('check_certain_payment');
Route::get('/paybox_success', 'BuyController@paybox_success')->name('paybox_success');
Route::get('/paybox_failure', 'BuyController@paybox_failure')->name('paybox_failure');
Route::get('/paybox_result_payment', 'BuyController@paybox_result_payment')->name('paybox_result_payment');
Route::post('/reserve_ticket_for_payment', 'BuyController@reserve_ticket_for_payment')->name('reserve_ticket_for_payment');
Route::get('/checker/{row}/{column}/{price}/{event}/{type}', 'BuyController@checker')->name('checker');
Route::post('/buy_page', 'BuyController@buy_page')->name('buy_page');
Route::post('/buy_first', 'BuyController@buy_first')->name('buy_first');
Route::post('/store_tickets','BuyController@multiple_buy')->name('multiple_buy');
Route::post('/check_promo', 'PromoCodeController@check_promo')->name('check_promo');
//Attendance
Route::get('/attendance_events', 'BuyController@attendance_events')->name('attendance_events');
Route::get('/attendance_events_clients/{id}','BuyController@attendance_events_clients')->name('attendance_events_clients');
Route::post('/set_attendance_type','BuyController@set_attendance_type')->name('set_attendance_type');
Route::post('/sort_event','BuyController@sort_event')->name('sort_event');
Route::post('/sortByVistit','BuyController@sortByVistit')->name('sortByVistit');

///Клиент
///
Route::post('/register_user', 'UserController@registerUser')->name('register_user');


//All Ajax routes
//Admin event
Route::post('/switch_event', 'AdminController@switch_event')->name('switch_event');
Route::get('/search_franch', 'FranchiseController@search')->name('search_franch');
Route::get('/search_franch_admin', 'FranchiseController@search_admin')->name('search_franch_admin');
Route::get('/search_client_ajax', 'AdminController@search_client_ajax')->name('search_client_ajax');
Route::get('/get_user_data', 'AdminController@get_user_data')->name('get_user_data')->name('search_franch');
Route::post('/save_user_ajax', 'AdminController@save_user_ajax')->name('save_user_ajax')->name('search_franch');

//

//Report event
Route::post('/switch_event_report', 'AdminController@switch_event_report')->name('switch_event_report');
//Client users profile ajax
Route::post('/switch_history', 'UserController@Ajax_SwitchHistory')->name('switch_history');
Route::post('/user_send_connect_franch', 'UserController@Ajax_sendConnectFranch')->name('user_send_connect_franch');
Route::post('/profile_delete_reserve', 'UserController@Ajax_deleteReserve')->name('profile_delete_reserve');
Route::post('/buy_reserve_ticket', 'UserController@Ajax_buyReserveTicket')->name('buy_reserve_ticket');
//Event admin report ajax
Route::post('/switch_report', 'ReportController@Ajax_SwitchReport')->name('switch_report');
Route::post('/switch_client', 'ReportController@Ajax_SwitchClient')->name('switch_client');
Route::post('/add_consuption', 'ReportController@Ajax_addConsuption')->name('add_consuption');
Route::post('/add_income', 'ReportController@Ajax_addIncome')->name('add_income');
Route::post('/remove_raw_income', 'ReportController@Ajax_remove_raw_Income')->name('remove_raw_income');
Route::post('/delete_consuption', 'ReportController@Ajax_deleteConsuption')->name('delete_consuption');
Route::post('/get_consuption', 'ReportController@Ajax_getConsuption')->name('get_consuption');
Route::post('/get_raw_income', 'ReportController@Ajax_getRawIncome')->name('get_raw_income');
Route::post('/edit_consuption', 'ReportController@Ajax_editConsuption')->name('edit_consuption');
Route::post('/edit_raw_income_edit', 'ReportController@Ajax_raw_income_edit')->name('raw_income_edit');
Route::post('/change_parts', 'ReportController@Ajax_changeParts')->name('change_parts');
Route::post('/return_ticket', 'ReportController@Ajax_returnTicket')->name('return_ticket');
//
//Ticket constructor
Route::get('/create_ticket', 'TicketDesignController@create')->name('create_ticket');
Route::post('/store_ticket','TicketDesignController@store')->name('store_ticket');
Route::get('/ticket_show/{id}', 'TicketDesignController@show')->name('ticket_show');
Route::get('/download', 'TicketDesignController@download')->name('download');

//Certificate constructor
Route::get('/create_certificate', 'CertificateController@create')->name('create_certificate');
Route::post('/store_cretificate','CertificateController@store')->name('store_certificate');
Route::get('/certificate_show/{ticket}','CertificateController@show')->name('certificate_show');
Route::get('/sendCert','CertificateController@sendCert')->name('sendCert');
//PromoCodes
Route::get('/promo_list','PromoCodeController@index')->name('promo_list');
Route::get('/promo_create','PromoCodeController@create')->name('promo_create');
Route::get('/promo_edit','PromoCodeController@edit')->name('promo_edit');
Route::post('/promo_store','PromoCodeController@store')->name('promo_store');
Route::post('/promo_update','PromoCodeController@update')->name('promo_update');

////Ref
Route::post('/reff_gen', 'ReflinkController@reff_gen')->name('reff_gen');
Route::post('/reff_gen_social', 'ReflinkController@reff_gen_social')->name('reff_gen_social');

Route::post('/part_reff_gen', 'ReflinkController@part_reff_gen')->name('part_reff_gen');
//Scanner
Route::get('/scanner/{id}', 'TicketController@scanner')->name('scanner');
Route::post('/scanner_find', 'TicketController@scanner_find')->name('scanner_find');
Route::post('/scanner_check', 'TicketController@scanner_check')->name('scanner_check');
Route::get('/scanner_success', 'TicketController@scanner_success')->name('scanner_success');
Route::get('/scanner_error', 'TicketController@scanner_error')->name('scanner_error');
Route::get('/scanner_list', 'TicketController@scanner_choice')->name('scanner_list');
Route::get('/scanner_done', 'TicketController@scanner_done')->name('scanner_done');

////Excell Export users
Route::get('/export', 'AdminController@export_users')->name('export');
Route::get('/export_f', 'AdminController@export_users_franch')->name('export_f');
Route::get('/export_p', 'AdminController@export_users_partners')->name('export_p');
Route::get('/export_s', 'AdminController@export_users_sales')->name('export_s');
////Excell Export clients
Route::get('/export_c', 'AdminController@export_clients')->name('export_c');
Route::get('/export_c_with', 'AdminController@export_clients_with')->name('export_c_with');
Route::get('/export_c_without', 'AdminController@export_clients_without')->name('export_c_without');
Route::get('/export_c_event/{id}', 'AdminController@export_clients_event')->name('export_c_event');
Route::post('/export_all_events_attendance', 'AdminController@export_all_events_attendance')->name('export_all_events_attendance');
Route::get('/export_attendance/{id}', 'AdminController@export_attendance')->name('export_attendance');
Route::get('/export_attendance_xml/{id}', 'AdminController@export_attendance_xml')->name('export_attendance_xml');

////Option list
Route::post('/search_client', 'OptionController@search_client')->name('search_client');
Route::post('/search_seller', 'OptionController@search_seller')->name('search_seller');
Route::post('/save_active_client', 'OptionController@save_active_client')->name('save_active_client');
Route::post('/save_franch_option', 'OptionController@save_franch_option')->name('save_franch_option');
Route::post('/setUserPerPage', 'OptionController@setUserPerPage')->name('setUserPerPage');
Route::post('/setClientPerPage', 'OptionController@setClientPerPage')->name('setClientPerPage');

//Resend ticket ajax
Route::post('/resend_mail', 'BuyController@resend_ticket');

///Admin Buy
Route::get('/admin.buy', 'BuyController@buy_for_client_events')->name('buy_for_client_events')->middleware('franchise');
Route::get('/admin.buy_one/{id}', 'BuyController@buy_for_client_one_event')->name('buy_for_client_one_event')->middleware('franchise');
Route::get('/admin.details_view/{id}', 'BuyController@event_details_view')->name('event_details_view')->middleware('franchise');
Route::post('/ajax_set_new_reserve_date', 'AdminController@ajax_set_new_reserve_date')->name('ajax_set_new_reserve_date')->middleware('franchise');
Route::get('/admin_buy_reserved/{id}', 'BuyController@admin_buy_reserved')->name('admin_buy_reserved')->middleware('franchise');

////// Session 
Route::post('/setUserTab', 'SessionController@setUserTab')->name('setUserTab');
Route::post('/setClientTab', 'SessionController@setClientTab')->name('setClientTab');

//////Tilda
Route::get('/tilda_create/{id}', 'EventController@tilda_create')->name('tilda_create')->middleware('franchise');
Route::post('/tilda_store', 'EventController@tilda_store')->name('tilda_store')->middleware('franchise');


/////CRON Url
Route::get('/send_certificate', 'CronController@send_certificate')->name('send_certificate');
Route::get('/notification', 'CronController@notification')->name('notification');
Route::get('/withdraw_reservation', 'CronController@withdraw_reservation')->name('withdraw_reservation');

Route::get('', 'HomeController@main')->name('main');
