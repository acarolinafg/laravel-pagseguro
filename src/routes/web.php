<?php
Route::group([
  'namespace' => 'Acarolinafg\PagSeguro\Http\Controllers',
  'middleware' => ['web'],
  'prefix'=>'pagseguro'
], function () {
  Route::get('session', 'PagSeguroCheckoutTransparenteController@session')->name('pagseguro.session');
  Route::get('javascript', 'PagSeguroCheckoutTransparenteController@javascript')->name('pagseguro.javascript');
  Route::get('javascript-content', 'PagSeguroCheckoutTransparenteController@javaScriptContent')->name('pagseguro.javaScriptContent');
});
