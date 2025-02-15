<?php

namespace Tests\OpenBoleto\Banco;
use OpenBoleto\Banco\Santander;

class SantanderTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiateWithoutArgumentsShouldWork()
    {
        $this->assertInstanceOf('OpenBoleto\\Banco\\Santander', new Santander());
    }

    public function testInstantiateShouldWork()
    {
        $instance = new Santander(array(
            // Parâmetros obrigatórios
            'dataVencimento' => new \DateTime('2013-01-01'),
            'valor' => 23.00,
            'sequencial' => 12345678901, // Até 13 dígitos
            'agencia' => 1234, // Até 4 dígitos
            'carteira' => 102, // 101, 102 ou 201
            'conta' => 1234567, // Código do cedente: Até 7 dígitos
            // IOS – Seguradoras (Se 7% informar 7. Limitado a 9%)
            // Demais clientes usar 0 (zero)
            'ios' => '0', // Apenas para o Santander
        ));

        $this->assertInstanceOf('OpenBoleto\\Banco\\Santander', $instance);
        $this->assertEquals('03399.12347 56700.123450 67890.101024 6 55650000002300', $instance->getLinhaDigitavel());
        $this->assertSame('0012345678901', (string) $instance->getNossoNumero());
    }

    protected function getFatorVencimento($linhaDigitavel)
    {
        $parts = explode(' ', $linhaDigitavel);
        return substr($parts[4], 0, 4);
    }

    public function testFatorVencimentoLimite2025()
    {
        $instance = new Santander(array(
            // Parâmetros obrigatórios
            'dataVencimento' => new \DateTime('2025-02-21'),
            'valor' => 1.00,
            'sequencial' => 12345678901, // Até 13 dígitos
            'agencia' => 1234, // Até 4 dígitos
            'carteira' => 102, // 101, 102 ou 201
            'conta' => 1234567, // Código do cedente: Até 7 dígitos
            // IOS – Seguradoras (Se 7% informar 7. Limitado a 9%)
            // Demais clientes usar 0 (zero)
            'ios' => '0', // Apenas para o Santander
        ));

        $this->assertInstanceOf('OpenBoleto\\Banco\\Santander', $instance);

        $this->assertEquals('9999', $this->getFatorVencimento($instance->getLinhaDigitavel()));
    }

    public function testFatorVencimentoReset2025()
    {
        $instance = new Santander(array(
            // Parâmetros obrigatórios
            'dataVencimento' => new \DateTime('2025-02-22'),
            'valor' => 1.00,
            'sequencial' => 12345678901, // Até 13 dígitos
            'agencia' => 1234, // Até 4 dígitos
            'carteira' => 102, // 101, 102 ou 201
            'conta' => 1234567, // Código do cedente: Até 7 dígitos
            // IOS – Seguradoras (Se 7% informar 7. Limitado a 9%)
            // Demais clientes usar 0 (zero)
            'ios' => '0', // Apenas para o Santander
        ));

        $this->assertInstanceOf('OpenBoleto\\Banco\\Santander', $instance);
        $this->assertEquals('1000', $this->getFatorVencimento($instance->getLinhaDigitavel()));
    }

    public function testFatorVencimentoPrimeiro2025()
    {
        $instance = new Santander(array(
            // Parâmetros obrigatórios
            'dataVencimento' => new \DateTime('2025-02-23'),
            'valor' => 1.00,
            'sequencial' => 12345678901, // Até 13 dígitos
            'agencia' => 1234, // Até 4 dígitos
            'carteira' => 102, // 101, 102 ou 201
            'conta' => 1234567, // Código do cedente: Até 7 dígitos
            // IOS – Seguradoras (Se 7% informar 7. Limitado a 9%)
            // Demais clientes usar 0 (zero)
            'ios' => '0', // Apenas para o Santander
        ));

        $this->assertInstanceOf('OpenBoleto\\Banco\\Santander', $instance);

        $parts = explode(' ', $instance->getLinhaDigitavel());
        $this->assertEquals('1001', $this->getFatorVencimento($instance->getLinhaDigitavel()));
    }
}