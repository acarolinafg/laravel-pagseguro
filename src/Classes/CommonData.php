<?php

namespace Acarolinafg\PagSeguro\Classes;

use Acarolinafg\PagSeguro\Exceptions\PagSeguroException;

/**
 * Classe abstrata CommonData
 *
 * @author Ana Carolina Fidelis Gonçalves <acarolinafg@gmail.com>
 */
abstract class CommonData
{

  /**
   * Nome inicial do atributo no PagSeguro
   * @var string
   */
  protected $alias = '';

  /**
   * Nome dos atributos da classe
   * @var string
   */
  protected $attributes = [];

  /**
   * Instância do objeto
   * @param array $data
   */
  public function __construct(array $data)
  {
    $this->is_keys($data);
  }

  /**
   * Cria as regras de validação de cada atributo
   */
  abstract public function rules(): array;

  /**
   * Transforma o objeto em um array
   * @param bool $useAlias
   * @return array
   */
  public function toArray(bool $useAlias = false): array
  {
    $array = [];

    foreach ($this->attributes as $key) {
      $value = $this->{$key};
      $alias = $useAlias ? $this->alias . ucfirst($key) : $key;
      $array[$alias] = $value;
    }
    return $array;
  }

  /**
   * Verifica se não falta campos no array enviado
   * @param array $data
   * @throws PagSeguroException
   */
  protected function is_keys(array $data)
  {
    foreach ($this->attributes as $value) {
      if (!array_key_exists($value, $data)) {
        throw new PagSeguroException("Falta o campo \${$this->alias}['{$value}']");
      }
    }
  }
}
