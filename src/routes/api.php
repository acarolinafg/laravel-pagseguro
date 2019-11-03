<?php
Route::group([
  'namespace' => 'Acarolinafg\PagSeguro\Http\Controllers',
  'middleware' => ['auth:api'],
  'prefix'=>'api/v2/pagseguro'
], function () {
  Route::get('session', 'PagSeguroCheckoutTransparenteController@session')->name('api.v2.pagseguro.session');
  Route::get('javascript', 'PagSeguroCheckoutTransparenteController@javascript')->name('api.v2.pagseguro.javascript');
  Route::get('javascript-content', 'PagSeguroCheckoutTransparenteController@javaScriptContent')->name('api.v2.pagseguro.javaScriptContent');
});
