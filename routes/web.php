<?php

Auth::routes();
// View Welcome Page
Route::get('/', 'LandingController')->name('welcome');

Route::group(['prefix' => '/{area}'], function () {
    // Set selected area
    Route::get('/', 'UserAreaController@store')->name('user.area.store');
    // Categories
    Route::resource('/categories', 'CategoryController');
    // Show listings in specific area and category
    Route::get('/categories/{category}/listings', 'ListingController@index')->name('listings.index');

    // Store a favourite for listing
    Route::get('/favourites', 'ListingFavouritesController@index')->name('listings.favourites.index');
    Route::get('/viewed', 'ListingViewedController@index')->name('listings.viewed.index');
    Route::post('/listings/{listing}/favourites', 'ListingFavouritesController@store')->name('listings.favourites.store');
    Route::delete('/listings/{listing}/favourites', 'ListingFavouritesController@destroy')->name('listings.favourites.destroy');
    Route::post('/listings/{listing}/contact', 'ListingContactsController@store')->name('listings.contacts.store');
    Route::get('/{listing}/payment', 'ListingPaymentController@show')->name('listings.payment.show');
    Route::post('/{listing}/payment', 'ListingPaymentController@store')->name('listings.payment.store');
    // Show single listing
    Route::get('/{listing}', 'ListingController@show')->name('listings.show');
    Route::resource('/listings', 'ListingController')->except('show', 'index');
});
