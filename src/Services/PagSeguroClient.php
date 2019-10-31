<?php

namespace Acarolinafg\PagSeguro\Services;

use Acarolinafg\PagSeguro\Exceptions\PagSeguroException;
use \SimpleXMLElement;

/**
 * Classe com métodos e atributos necessários para realização das requisições no PagSeguro
 * @author Ana Carolina Fidelis Gonçalves <acarolinafg@gmail.com>
 */
class PagSeguroClient extends PagSeguroConfiguracoes
{
  /**
   * Cabeçalho para envio das requisições formato padrão
   * @var array
   */
  private static $header = ['Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1'];

  /**
   * Tipo da requisição
   * @example POST|GET|PATCH
   * @var string
   */
  protected $method = 'POST';

  /**
   * Parâmetros das requisições
   * @var  array
   */
  protected $parameters = null;

  /**
   * URL da requisição
   * @var string
   */
  protected $url;

  /**
   * Resultado da requisição
   * @var SimpleXMLElement
   */
  private $result;

  /**
   * Envia uma requisição para o PagSeguro
   *
   * @throws Acarolinafg\PagSeguro\PagSeguroException
   */
  protected function sendRequest()
  {
    if (is_null($this->url))
      throw new PagSeguroException("URL da requisição não encontrada.");

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $this->url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $this->headers);

    if ($this->method == 'POST') {
      curl_setopt($curl, CURLOPT_POST, true);
    } elseif ($this->method == 'PUT') {
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
    }

    if ($this->parameters !== null) {
      curl_setopt($curl, CURLOPT_POSTFIELDS, $this->parameters);
    }

    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, !$this->sandbox);

    $result = curl_exec($curl);

    $getInfo = curl_getinfo($curl);
    if (isset($getInfo['http_code']) && $getInfo['http_code'] == '503') {
      $this->log->error('Serviço em manutenção.', ['Retorno:' => $result]);

      throw new PagSeguroException('Serviço em manutenção.', 1000);
    }
    if ($result === false) {
      $this->log->error('Erro ao enviar a transação', ['Retorno:' => $result]);

      throw new PagSeguroException(curl_error($curl), curl_errno($curl));
    }

    curl_close($curl);

    //formatação do resultado
    $this->formatResult($result);
  }

  /**
   * Formata o resultado da requisição e retorna os possíveis erros
   * @param SimpleXMLElement $result
   * @throws Acarolinafg\PagSeguro\PagSeguroException
   */
  private function formatResult($result)
  {
    if ($result === 'Unauthorized' || $result === 'Forbidden') {
      $this->log->error('Erro ao enviar a transação', ['Retorno:' => $result]);

      throw new PagSeguroException($result . ': Não foi possível estabelecer uma conexão com o PagSeguro.', 1001);
    }
    if ($result === 'Not Found') {
      $this->log->error('Notificação/Transação não encontrada', ['Retorno:' => $result]);

      throw new PagSeguroException($result . ': Não foi possível encontrar a notificação/transação no PagSeguro.', 1002);
    }

    $this->result = simplexml_load_string($result);

    if (isset($result->error) && isset($result->error->message)) {
      $this->log->error($result->error->message, ['Retorno:' => $result]);

      throw new PagSeguroException($result->error->message, (int) $result->error->code);
    }
  }
}
