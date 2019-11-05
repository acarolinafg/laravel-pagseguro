<?php

namespace Acarolinafg\PagSeguro\Services;

use Acarolinafg\PagSeguro\Classes\BillingAddress;
use Acarolinafg\PagSeguro\Classes\CreditCardHolder;
use Acarolinafg\PagSeguro\Classes\Sender;
use Acarolinafg\PagSeguro\Classes\Item;
use Acarolinafg\PagSeguro\Classes\Shipping;

/**
 * Classe de Checkout Transparente do PagSeguro
 * @author Ana Carolina Fidelis Gonçalves <acarolinafg@gmail.com>
 */
class PagSeguroCheckoutTransparente extends PagSeguroClient
{
  /**
   * Código de referência
   * @var string
   */
  private $reference;

  /**
   * Email do vendedor
   * @var string
   */
  private $receiverEmail;

  /**
   * Comprador
   * @var Sender
   */
  private $sender;

  /**
   * Itens da compra
   * @var array Item
   */
  private $itens = [];

  /**
   * Frete
   * @var Shipping
   */
  private $shipping;

  /**
   * Endereço de cobrança do cartão de crédito
   * @var BillingAddress
   */
  private $billingAddress;

  /**
   * Cartão de crédito e titular
   * @var CreditCardHolder
   */
  private $creditCard;

  /**
   * Regras de validação para atributos
   * @var array
   */
  private $rules = [
    'reference' => 'nullable|max:200',
    'receiverEmail' => 'nullable|email|max:60'
  ];

  /**
   * Armazena o código de referência
   * @param string $reference
   */
  public function setReference($reference)
  {
    $this->reference = pagseguro_clear_value($reference);
  }

  /**
   * Armazena email do vendedor
   * @param string $receiverEmail
   */
  public function setReceiverEmail($receiverEmail)
  {
    $this->receiverEmail = $this->sandbox ? 'vendedor@sandbox.pagseguro.com.br' : pagseguro_clear_value($receiverEmail);
  }

  /**
   * Armazena o comprador da transação
   * @param array $data
   */
  public function setSender(array $data)
  {
    $this->sender = new Sender($data);
    $this->sender->setHash($data['hash']);
    $this->sender->setName($data['name']);
    $email = $this->sandbox ? 'comprador@sandbox.pagseguro.com.br' : pagseguro_clear_value($data['email']);
    $this->sender->setEmail($email);
    $this->sender->setPhone($data['phone']);
    $this->sender->setDocument($data['document']);
    $this->validate($this->sender->toArray(), $this->sender->rules());

    return $this;
  }

  /**
   * Adiciona um item ao vetor de itens
   * @param array $data
   */
  public function addItem(array $data)
  {
    $count = count($this->itens) + 1;
    $item = new Item($data, $count);
    $item->setId($data["id"]);
    $item->setDescription($data['description']);
    $item->setAmount($data['amount']);
    $item->setQuantity($data['quantity']);
    $this->validate($item->toArray(), $item->rules());
    $this->itens[] = $item;
    return $this;
  }

  /**
   * Armazena o frete
   * @param array $data
   */
  public function setShipping(array $data)
  {
    $this->shipping = new Shipping($data);
    $this->shipping->setStreet($data['street']);
    $this->shipping->setNumber($data['number']);
    $this->shipping->setDistrict($data['district']);
    $this->shipping->setCity($data['city']);
    $this->shipping->setState($data['state']);
    $this->shipping->setPostalCode($data['postalCode']);
    $this->shipping->setComplement($data['complement']);
    $this->shipping->setCost($data['cost']);
    $this->validate($this->shipping->toArray(), $this->shipping->rules());
    return $this;
  }

   /**
   * Armazena o cartão de crédito
   * @param array $data
   */
  public function setcreditCard(array $data)
  {
    $this->creditCard = new CreditCardHolder($data);
    $this->creditCard->setToken($data['token']);
    $this->creditCard->setName($data['name']);
    $this->creditCard->setCPF($data['CPF']);
    $this->creditCard->setPhone($data['phone']);
    $this->creditCard->setBirthDate($data['birthDate']);
    $this->validate($this->creditCard->toArray(), $this->creditCard->rules());
    return $this;
  }

  /**
   * Armazena o endereço de cobrança para o cartão de crédito
   * @param array $data
   *
   */
  public function setBillingAddress(array $data)
  {
    $this->billingAddress = new BillingAddress($data);
    $this->billingAddress->setStreet($data['street']);
    $this->billingAddress->setNumber($data['number']);
    $this->billingAddress->setDistrict($data['district']);
    $this->billingAddress->setCity($data['city']);
    $this->billingAddress->setState($data['state']);
    $this->billingAddress->setPostalCode($data['postalCode']);
    $this->billingAddress->setComplement($data['complement']);
    $this->validate($this->billingAddress->toArray(), $this->billingAddress->rules());
    return $this;
  }

  public function send()
  {
    return $this;
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
