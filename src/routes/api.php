<?php
Route::group([
  'namespace' => 'Acarolinafg\PagSeguro\Http\Controllers',
  'middleware' => ['api']
], function () {
  Route::get('/api/pagseguro', function () {
    return 'Hello World pagseguro API!';
  });
});
