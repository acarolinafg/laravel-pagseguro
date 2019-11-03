<?php
Route::group([
  'namespace' => 'Acarolinafg\PagSeguro\Http\Controllers',
  'middleware' => ['web'],
  'name' => 'pagseguro',
  'prefix'=>'pagseguro'
], function () {
  Route::get('session', 'PagSeguroCheckoutTransparenteController@session')->name('session');
  Route::get('javascript', 'PagSeguroCheckoutTransparenteController@javascript')->name('javascript');
  Route::get('javascript-content', 'PagSeguroCheckoutTransparenteController@javaScriptContent')->name('javaScriptContent');
});
