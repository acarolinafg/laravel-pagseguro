<?php

namespace Acarolinafg\PagSeguro;

use Acarolinafg\PagSeguro\Services\PagSeguroCheckoutTransparente;
use Illuminate\Support\ServiceProvider;

class PagSeguroServiceProvider extends ServiceProvider
{
  public function boot()
  {
    //permitindo a publicação das configurações
    $this->publishConfig();

    //carregar as rotas web e api
    $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
    $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
  }

  public function register()
  {
    //fornecer o serviço de pagamento pelo Checkout Transparente para app
    $this->app->bind('pagSeguroCheckoutTransparente', function () {
      return new PagSeguroCheckoutTransparente;
    });
  }

  /**
   * Publicação do arquivo de configuração
   */
  private function publishConfig()
  {
    $configPath = __DIR__ . '/config/pagseguro.php';

    $this->publishes([$configPath => config_path('pagseguro.php'),], 'config');

    $this->mergeConfigFrom($configPath, 'pagseguro');
  }
}
