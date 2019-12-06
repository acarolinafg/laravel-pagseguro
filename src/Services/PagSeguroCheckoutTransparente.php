<?php

namespace Acarolinafg\PagSeguro\Services;

use Acarolinafg\PagSeguro\Classes\BillingAddress;
use Acarolinafg\PagSeguro\Classes\CreditCardHolder;
use Acarolinafg\PagSeguro\Classes\Installment;
use Acarolinafg\PagSeguro\Classes\Sender;
use Acarolinafg\PagSeguro\Classes\Item;
use Acarolinafg\PagSeguro\Classes\Shipping;
use Acarolinafg\PagSeguro\Exceptions\PagSeguroException;

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
   * Mode de pagamento
   * @var string
   */
  private $paymentMode = 'default';

  /**
   * Moeda utilizada. Opção única de moeda até o momento BRL(Real).
   * @var string
   */
  private $currency = 'BRL';

  /**
   * Método de pagamento
   * @example creditCard
   * @example boleto
   * @example eft
   * @var string
   */
  private $paymentMethod = 'creditCard';

  /**
   * Especifica um valor extra que deve ser adicionado ou subtraído ao valor
   * total do pagamento (Desconto ou Taxa Extra).
   * @var float
   */
  private $extraAmount = '0.00';

  /**
   * Informações do parcelamento no caso do cartão de crédito
   * @var Installment
   */
  private $installment;

  /**
   * Define o método de pagamento
   * @param string $paymentMethod
   */
  public function setPaymentMethod($paymentMethod = 'creditCard')
  {
    $this->paymentMethod = $paymentMethod;
    return $this;
  }

  /**
   * Armazena email do vendedor
   * @param string $receiverEmail
   */
  public function setReceiverEmail($receiverEmail)
  {
    $receiverEmail =  pagseguro_clear_value($receiverEmail);
    $this->validate(['receiverEmail' => $receiverEmail], ['receiverEmail' => 'nullable|email|max:60']);
    $this->receiverEmail = $this->sandbox ? 'vendedor@sandbox.pagseguro.com.br' : $receiverEmail;
    return $this;
  }

  /**
   * Armazena o valor de desconto
   * @param mixed $discount
   */
  public function setDiscount($discount)
  {
    $discount = pagseguro_format_money($discount);
    $rules = ['discount' => 'nullable|between:0.00,9999999.00'];
    $this->validate(['discount' => $discount], $rules);
    $this->extraAmount = -1 * $discount;
    return $this;
  }

  /**
   * Armazena o valor de uma taxa extra ao pagamento
   * @param mixed $rate
   */
  public function setRate($rate)
  {
    $rate = pagseguro_format_money($rate);
    $rules = ['rate' => 'nullable|between:0.00,9999999.00'];
    $this->validate(['rate' => $rate], $rules);
    $this->extraAmount = $rate;
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
   * Armazena o código de referência
   * @param string $reference
   */
  public function setReference($reference)
  {
    $reference =  pagseguro_clear_value($reference);
    $this->validate(['reference' => $reference], ['reference' => 'nullable|max:200']);
    $this->reference = $reference;
    return $this;
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
    if (isset($data['complement']))
      $this->shipping->setComplement($data['complement']);
    $this->shipping->setType($data['type']);
    $this->shipping->setCost($data['cost']);
    $this->validate($this->shipping->toArray(), $this->shipping->rules());
    return $this;
  }

  /**
   * Armazena o cartão de crédito
   * @param array $data
   */
  public function setCreditCard(array $data)
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

  public function setInstallment(array $data)
  {
    $this->installment = new Installment($data);
    $this->installment->setQuantity($data['quantity']);
    $this->installment->setValue($data['value']);

    if (isset($data['noInterestInstallmentQuantity']))
      $this->installment->setNoInterestInstallmentQuantity($data['noInterestInstallmentQuantity']);

    $this->validate($this->installment->toArray(), $this->installment->rules());
    return $this;
  }

  public function send()
  {
    //dados para o envio da transação
    $params = [
      'email'           => $this->email,
      'token'           => $this->token,
      'paymentMode'     => $this->paymentMode,
      'paymentMethod'   => $this->paymentMethod,
      'currency'        => $this->currency,
      'extraAmount'     => $this->extraAmount,
    ];

    //dados do vendedor
    if (!empty($this->receiverEmail))
      $params['receiverEmail'] = $this->receiverEmail;

    //url para envio de notificações
    if (!empty($this->notificationURL))
      $params['notificationURL'] = $this->notificationURL;

    //código de referência
    if (!empty($this->reference))
      $params['reference'] = $this->reference;

    //itens de cobrança
    if ($this->itens) {
      foreach ($this->itens as $item) {
        $params = array_merge($params, $item->toArray(true));
      }
    } else {
      throw new PagSeguroException("Falta o(s) itens. Utilize o método addItem(array \$data)");
    }

    //dados do comprador
    if ($this->sender) {
      $params = array_merge($params, $this->sender->toArray(true));
    } else {
      throw new PagSeguroException("Falta os dados do comprador. Utilize o método setSender(array \$data)");
    }

    //dados do frete
    if ($this->shipping) {
      $params = array_merge($params, $this->shipping->toArray(true));
    } else {
      $params['shippingAddressRequired'] = 'false';
    }

    //dados de cobrança do método de cartão de crédito
    if ($this->paymentMethod === 'creditCard') {
      //dados do cartão de crédito
      if ($this->creditCard) {
        $params = array_merge($params, $this->creditCard->toArray(true));
      } else {
        throw new PagSeguroException("Falta os dados do cartão de crédito. Utilize o método setCreditCard(array \$data)");
      }

      //dados de parcelamento
      if ($this->installment) {
        $params = array_merge($params, $this->installment->toArray(true));
      } else {
        throw new PagSeguroException("Falta os dados do parcelamento. Utilize o método setInstallment(array \$data)");
      }

      //endereço de cobrança
      if ($this->billingAddress) {
        $params = array_merge($params, $this->billingAddress->toArray(true));
      } else {
        throw new PagSeguroException("Falta o endereço de cobrança. Utilize o método setBillingAddress(array \$data)");
      }
    }

    //tranforma os dados da transação em string
    $this->parameters = pagseguro_str_parameters($params);

    //definição da url
    $this->url = $this->getUrl('transaction');

    //enviar requisição
    $this->sendRequest();

    return $this->result;
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
