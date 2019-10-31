<?php

namespace Acarolinafg\PagSeguro\Http\Controllers;

use Acarolinafg\PagSeguro\Services\PagSeguroCheckoutTransparente;
use App\Http\Controllers\Controller;

class PagSeguroCheckoutTransparenteController extends Controller
{
  /**
   * @var PagSeguroCheckoutTransparente
   */
  private $pagseguro;

  /**
   * Instância as depêndencias
   */
  public function __construct()
  {
    $this->pagseguro = app('pagSeguroCheckoutTransparente');
  }

  /**
   * Gera a sessão para realizar transações
   * @return Illuminate\Http\Response
   */
  public function session()
  {
    return response()->json(['session' => $this->pagseguro->startSession()]);
  }

  /**
   * Retorna a URL do arquivo JavaScript necessário para gerar o token no browser.
   * @return string
   */
  public function javaScript()
  {
    return PagSeguro::getUrl('checkout.javascript');
  }

  /**
   * Inclui o arquivo javascript necessário para gerar o token no browser.
   * @return Illuminate\Http\Response
   */
  public function javaScriptContent()
  {
    $scriptContent = file_get_contents($this->pagseguro->getUrl('checkout.javascript'));

    return response()->make($scriptContent, '200')->header('Content-Type', 'text/javascript');
  }
}
