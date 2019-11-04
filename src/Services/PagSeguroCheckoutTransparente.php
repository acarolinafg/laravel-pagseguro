<?php

namespace Acarolinafg\PagSeguro\Services;

use Acarolinafg\PagSeguro\Classes\Sender;

/**
 * Classe de Checkout Transparente do PagSeguro
 * @author Ana Carolina Fidelis Gonçalves <acarolinafg@gmail.com>
 */
class PagSeguroCheckoutTransparente extends PagSeguroClient
{
  /**
   * Comprador
   * @var Sender
   */
  private $sender = [];


  /**
   * Armazena o comprador da transação
   * @param array $data
   */
  public function setSender(array $data)
  {
    $this->sender = new Sender;
    $this->sender->setHash($data['hash']);
    $this->sender->setName($data['name']);
    $this->sender->setEmail($data['email']);
    $this->sender->setPhone($data['phone']);
    $this->sender->setDocument($data['document']);

    $this->validate($this->sender->toArray(), $this->sender->rules());
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
