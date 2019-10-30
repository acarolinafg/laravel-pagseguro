<?php
Route::group([
  'namespace' => 'Acarolinafg\PagSeguro\Http\Controllers',
  'middleware' => ['web']
], function () {
  Route::get('/pagseguro', function () {
    return 'Hello World pagseguro WEB!';
  });
});
