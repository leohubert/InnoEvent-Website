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

Route::get('/', function () {
    $events = \App\Event::all();
    return view('event.index', ['events' => $events]);
});

Route::get('/users', function () {
    $users = \App\User::all();
    return view('users', ['users' => $users]);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::resource('events', 'EventsController');

Route::get('/payment/process', 'PaymentsController@process')->name('payment.process');
Route::get('/payment', function () {
    return view('payment.index');
})->name('payment.index');

Route::post(
    'braintree/webhook',
    '\Laravel\Cashier\Http\Controllers\WebhookController@handleWebhook'
);

Route::post(
    'braintree/webhook',
    '\Laravel\Cashier\Http\Controllers\WebhookController@handleWebhook'
);