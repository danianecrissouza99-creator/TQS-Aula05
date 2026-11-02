<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/ValidadorPasse.php';

/**
 * Exercício P5.3  ·  Ciclo TDD  ·  Validador de palavra-passe
 *
 * Ciclo seguido para cada requisito:
 *   1. Vermelho: escrever o teste e vê-lo falhar.
 *   2. Verde:    escrever o mínimo de código para passar.
 *   3. Refactor: melhorar o código, com os testes a garantir que nada se parte.
 *
 * Uma palavra-passe é válida quando cumpre, ao mesmo tempo:
 *   1. tem no mínimo 8 caracteres;
 *   2. tem pelo menos uma letra maiúscula;
 *   3. tem pelo menos um dígito.
 */
class ValidadorPasseTest extends TestCase
{
    private ValidadorPasse $validador;

    protected function setUp(): void
    {
        $this->validador = new ValidadorPasse();
    }

    // Requisito 1: 'Ab1' tem só 3 caracteres -> inválida.
    public function testValida_PasseCurta_DevolveFalse(): void
    {
        $resultado = $this->validador->valida('Ab1');         // Act

        $this->assertFalse($resultado);                       // Assert
    }

    // Requisito 2: 'abcdefg1' tem 8 caracteres e dígito, mas nenhuma maiúscula -> inválida.
    public function testValida_SemMaiuscula_DevolveFalse(): void
    {
        $resultado = $this->validador->valida('abcdefg1');    // Act

        $this->assertFalse($resultado);                       // Assert
    }

    // Requisito 3: 'Abcdefgh' tem 8 caracteres e maiúscula, mas nenhum dígito -> inválida.
    public function testValida_SemDigito_DevolveFalse(): void
    {
        $resultado = $this->validador->valida('Abcdefgh');    // Act

        $this->assertFalse($resultado);                       // Assert
    }

    // Cumpre as 3 regras -> válida.
    public function testValida_CumpreTodasAsRegras_DevolveTrue(): void
    {
        $resultado = $this->validador->valida('Segura2026');  // Act

        $this->assertTrue($resultado);                        // Assert
    }

    // ----------------------------------------------------------------
    // Valores fronteira do tamanho (Data Provider)
    // ----------------------------------------------------------------

    public static function casosTamanho(): array
    {
        //                          [ palavra-passe, válida? ]
        return [
            '7 caracteres'  => ['Abcdef1',   false],
            '8 caracteres'  => ['Abcdefg1',  true],
            '9 caracteres'  => ['Abcdefgh1', true],
        ];
    }

    #[DataProvider('casosTamanho')]
    public function testValida_TamanhoNaFronteira_DevolveResultadoCorreto(string $passe, bool $esperado): void
    {
        $resultado = $this->validador->valida($passe);        // Act

        $this->assertSame($esperado, $resultado);             // Assert
    }
}
