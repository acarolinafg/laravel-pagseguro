<?php

namespace Acarolinafg\PagSeguro\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Regra de validação do CPF e do CNPJ
 * @author Ana Carolina Fidelis Gonçalves <acarolinafg@gmail.com>
 */
class DocumentRule implements Rule
{
  /**
   * Número do documento
   * @var string
   */
  protected $document;

  /**
   * Crie uma nova instância de regra.
   *
   * @return void
   */
  public function __construct($document)
  {
    $this->document = $document;
  }

  /**
   * Determine se a regra de validação passa.
   *
   * @param  string  $attribute
   * @param  mixed  $value
   * @return bool
   */
  public function passes($attribute, $value)
  {
    return $this->validateCPF($value) || $this->valideteCNPJ($value);
  }

  /**
   * Obter a mensagem de erro de validação.
   *
   * @return string
   */
  public function message()
  {
    if (strlen($this->document) == 11)
      return 'CPF inválido.';
    elseif (strlen($this->document) == 14)
      return 'CNPJ inválido.';
    else
      return 'CPF ou CNPJ inválido.';
  }

  /**
   * Valida o CPF
   * @return bool
   */
  private function validateCPF()
  {
    if (!empty($this->document)) {
      $c = preg_replace('/\D/', '', $this->document);

      if (strlen($c) != 11 || preg_match("/^{$c[0]}{11}$/", $c)) {
        return false;
      }

      for ($s = 10, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);

      if ($c[9] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
        return false;
      }

      for ($s = 11, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);

      if ($c[10] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
        return false;
      }
    }

    return true;
  }

  /**
   * Valida o CNPJ
   * @return bool
   */
  private function valideteCNPJ()
  {
    if (!empty($this->document)) {
      $c = preg_replace('/\D/', '', $this->document);

      if (strlen($c) != 14 || preg_match("/^{$c[0]}{14}$/", $c)) {
        return false;
      }

      $b = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

      for ($i = 0, $n = 0; $i < 12; $n += $c[$i] * $b[++$i]);

      if ($c[12] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
        return false;
      }

      for ($i = 0, $n = 0; $i <= 12; $n += $c[$i] * $b[$i++]);

      if ($c[13] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
        return false;
      }
    }

    return true;
  }
}
