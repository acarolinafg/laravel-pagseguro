<?php

namespace Acarolinafg\PagSeguro\Services;

use Acarolinafg\PagSeguro\Exceptions\PagSeguroException;
use Illuminate\Validation\Factory as Validator;
use Psr\Log\LoggerInterface as Log;

/**
 * Configurações para comunicação com a API do PagSeguro
 *
 * @author Ana Carolina Fidelis Gonçalves <acarolinafg@gmail.com>
 */
class PagSeguroConfiguracoes
{
  /**
   * Definição do ambiente de Produção ou Sandbox(Teste)
   * @example  Ambiente de testes definir o valor true
   * @example  Ambiente de produção definir o valor false
   * @var bool
   */
  protected $sandbox;

  /**
   * Email da conta PagSeguro
   * @var string
   */
  protected $email;

  /**
   * Token da conta PagSeguro
   * @var string
   */
  protected $token;

  /**
   * URL de notificação para o PagSeguro
   * @var string
   */
  protected $notificationURL;

  /**
   * URLs para conexão com o PagSeguro
   * @var array
   */
  protected $URLs;

  /**
   * Instância de log
   * @var Log
   */
  protected $log;

  /**
   * Instância de validação
   * @var Validator
   */
  protected $validator;

  /**
   * @param Log $log
   * @param Validator $validator
   */
  public function __construct(Log $log, Validator $validator)
  {
    $this->log = $log;
    $this->validator = $validator;

    //Configurações do ambiente (arquivo .env)
    $this->sandbox = config('pagseguro.sandbox', env('PAGSEGURO_SANDBOX', true));
    $this->email = config('pagseguro.email', env('PAGSEGURO_EMAIL', ''));
    $this->token = config('pagseguro.token', env('PAGSEGURO_TOKEN', ''));
    $this->notificationURL = config('pagseguro.notificationURL', env('PAGSEGURO_NOTIFICATION', ''));

    //Carregar as URLs
    $this->setURLs();
  }

  /**
   * Retorna o valor da URL
   * @param string $key
   */
  public function getUrl($key)
  {
    if (array_key_exists($key, $this->URLs))
      return $this->URLs[$key];

    throw new PagSeguroException("Chave {$key} não definida no vetor de URLs de conexão.");
  }

  /**
   * Retorna o vetor de URLs de conexão
   * @return array
   */
  public function getURLs()
  {
    return $this->URLs;
  }

  /**
   * Definição das URLs para conexão com o PagSeguro
   */
  private function setURLs()
  {
    $sandbox = $this->sandbox ? 'sandbox.' : '';

    $baseURL = "https://ws.{$sandbox}.pagseguro.uol.com.br";

    $baseURLv2 = "{$baseURL}/v2";

    $this->URLs = [
      'checkout.js'               => "{$baseURLv2}/checkout/pagseguro.directpayment.js",
      'checkout.session'          => "{$baseURLv2}/checkout/sessions",
      'transaction'               => "{$baseURLv2}/transactions/",
      'transaction.cancel'        => "{$baseURLv2}/transactions/cancels/",
      'transaction.refunds'       => "{$baseURLv2}/transactions/refunds/",
      'transaction.notification'  => "{$baseURLv2}/transactions/notifications/",
    ];
  }
}
