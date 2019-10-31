<?php

namespace Acarolinafg\PagSeguro\Facedes;

use Illuminate\Support\Facades\Facade;

class PagSeguroCheckoutTransparenteFacede extends Facade
{
  protected static function getFacadeAccessor()
  {
    return 'pagSeguroCheckoutTransparente';
  }
}
