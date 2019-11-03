<?php
Route::group([
  'namespace' => 'Acarolinafg\PagSeguro\Http\Controllers',
  'middleware' => ['auth:api'],
  'name' => 'api.v2.pagseguro',
  'prefix'=>'pagseguro/api/v2'
], function () {
  Route::get('session', 'PagSeguroCheckoutTransparenteController@session')->name('session');
  Route::get('javascript', 'PagSeguroCheckoutTransparenteController@javascript')->name('javascript');
  Route::get('javascript-content', 'PagSeguroCheckoutTransparenteController@javaScriptContent')->name('javaScriptContent');
});
