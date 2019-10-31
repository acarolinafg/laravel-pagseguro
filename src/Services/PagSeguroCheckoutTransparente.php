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

  /**
   * Cancelar transação
   * @param string $transactionCode
   */
  public function cancelTransaction($transactionCode)
  {
    //definição dos parâmetros
    $param = [
      'email' => $this->email,
      'token' => $this->token,
      'transactionCode' => $transactionCode
    ];
    $this->parameters = pagseguro_str_parameters($param);

    //definição da URL
    $this->url = $this->getUrl('transaction.cancel');

    //enviar requisição
    $this->sendRequest();

    return (string) $this->result;
  }

  /**
   * Estorna transação se o valor não é informado o estorno ocorre no valor total da transação
   * @param string $transactionCode
   * @param mixed $refundValue
   */
  public function refundsTransaction($transactionCode, $refundValue = NULL)
  {
    //definição dos parâmetros
    $param = [
      'email' => $this->email,
      'token' => $this->token,
      'transactionCode' => $transactionCode
    ];

    if (!is_null($refundValue))
      $param['refundValue'] = pagseguro_format_money($refundValue);

    $this->parameters = pagseguro_str_parameters($param);

    //definição da URL
    $this->url = $this->getUrl('transaction.refunds');

    //enviar requisição
    $this->sendRequest();

    return (string) $this->result;
  }
}
