<?php
Route::prefix('api/pagseguro')->middleware('auth:api')->name('api.pagseguro.')
  ->namespace('Acarolinafg\PagSeguro\Http\Controllers')->group(function () {
    Route::get('session', 'PagSeguroCheckoutTransparenteController@session')->name('session');
    Route::get('javascript', 'PagSeguroCheckoutTransparenteController@javascript')->name('javascript');
    Route::get('javascript-content', 'PagSeguroCheckoutTransparenteController@javaScriptContent')->name('javaScriptContent');
  });
