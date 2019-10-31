<?php

namespace Acarolinafg\PagSeguro\Services;

/**
 * Classe de Checkout Transparente do PagSeguro
 * @author Ana Carolina Fidelis Gonçalves <acarolinafg@gmail.com>
 */
class PagSeguroCheckoutTransparente extends PagSeguroClient
{
  /**
   * Inicia uma sessão para o Checkout
   * @return string
   */
  public function startSession()
  {
    //definição dos parâmetros
    $param = ['email' => $this->email, 'token' => $this->token];
    $this->parameters = pagseguro_str_parameters($param);

    //definição da URL
    $this->url = $this->getUrl('checkout.session');

    //enviar requisição
    $this->sendRequest();

    return (string) $this->result->id;
  }
}
