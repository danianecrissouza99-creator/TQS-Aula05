<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/Carrinho.php';

/**
 * Testes da classe Carrinho.
 *
 * Exercícios P5.1 (primeiros testes) e P5.2 (exceções).
 * - Padrão AAA: Arrange (preparar), Act (executar), Assert (verificar).
 * - Nomes no formato: testOQueSeTesta_Condicao_ResultadoEsperado.
 * - assertSame por omissão (compara valor e tipo).
 */
class CarrinhoTest extends TestCase
{
    private Carrinho $carrinho;

    protected function setUp(): void
    {
        // Corre antes de cada teste: cada teste começa com um carrinho novo (princípio "Isolado").
        $this->carrinho = new Carrinho();
    }

    // ================================================================
    // P5.1  Primeiros testes
    // ================================================================

    // Carrinho vazio -> total 0.
    public function testTotal_CarrinhoVazio_DevolveZero(): void
    {
        // Arrange: feito no setUp()

        $total = $this->carrinho->total();                    // Act

        $this->assertSame(0, $total);                         // Assert
    }

    // Um artigo: 10 x 2 = 20.
    public function testTotal_ComUmArtigo_DevolvePrecoVezesQuantidade(): void
    {
        $this->carrinho->adicionar('A', 10, 2);               // Arrange

        $total = $this->carrinho->total();                    // Act

        $this->assertSame(20, $total);                        // Assert
    }

    // Dois artigos: (10 x 2) + (5 x 1) = 25.
    public function testTotal_ComDoisArtigos_DevolveSomaDosArtigos(): void
    {
        $this->carrinho->adicionar('A', 10, 2);               // Arrange
        $this->carrinho->adicionar('B', 5, 1);

        $total = $this->carrinho->total();                    // Act

        $this->assertSame(25, $total);                        // Assert
    }

    // Três artigos: (10 x 2) + (5 x 1) + (3 x 4) = 37.
    public function testTotal_ComTresArtigos_DevolveSomaDosArtigos(): void
    {
        $this->carrinho->adicionar('A', 10, 2);               // Arrange
        $this->carrinho->adicionar('B', 5, 1);
        $this->carrinho->adicionar('C', 3, 4);

        $total = $this->carrinho->total();                    // Act

        $this->assertSame(37, $total);                        // Assert
    }

    // Dois artigos adicionados -> 2 artigos.
    public function testNumeroDeArtigos_ComDoisArtigos_DevolveDois(): void
    {
        $this->carrinho->adicionar('A', 10, 2);               // Arrange
        $this->carrinho->adicionar('B', 5, 1);

        $numero = $this->carrinho->numeroDeArtigos();         // Act

        $this->assertSame(2, $numero);                        // Assert
    }

    // Um artigo com qt 5 continua a ser 1 artigo (a quantidade não conta).
    public function testNumeroDeArtigos_ComQuantidadeCinco_DevolveUm(): void
    {
        $this->carrinho->adicionar('A', 10, 5);               // Arrange

        $numero = $this->carrinho->numeroDeArtigos();         // Act

        $this->assertSame(1, $numero);                        // Assert
    }

    // Carrinho novo -> vazio.
    public function testVazio_CarrinhoNovo_DevolveTrue(): void
    {
        // Arrange: feito no setUp()

        $vazio = $this->carrinho->vazio();                    // Act

        $this->assertTrue($vazio);                            // Assert
    }

    // Carrinho com um artigo -> não está vazio.
    public function testVazio_ComUmArtigo_DevolveFalse(): void
    {
        $this->carrinho->adicionar('A', 10, 1);               // Arrange

        $vazio = $this->carrinho->vazio();                    // Act

        $this->assertFalse($vazio);                           // Assert
    }

    // Fronteira: quantidade 20 é o máximo permitido -> aceite. 3 x 20 = 60.
    public function testAdicionar_QuantidadeVinte_EhAceite(): void
    {
        $this->carrinho->adicionar('A', 3, 20);               // Arrange

        $total = $this->carrinho->total();                    // Act

        $this->assertSame(60, $total);                        // Assert
    }

    // ----------------------------------------------------------------
    // Desconto de estudante: idades dentro e fora do intervalo,
    // incluindo os valores fronteira 18 e 25 (Data Provider).
    // ----------------------------------------------------------------

    public static function casosDesconto(): array
    {
        //                     [ idade, desconto esperado ]
        return [
            'menor de idade'   => [17, 0.0],
            'limite inferior'  => [18, 0.10],
            'no intervalo'     => [21, 0.10],
            'limite superior'  => [25, 0.10],
            'acima'            => [26, 0.0],
        ];
    }

    #[DataProvider('casosDesconto')]
    public function testDescontoEstudante_PorIdade_DevolveDescontoCorreto(int $idade, float $esperado): void
    {
        // Arrange: idade e valor esperado vêm do Data Provider

        $desconto = $this->carrinho->descontoEstudante($idade);   // Act

        $this->assertSame($esperado, $desconto);                  // Assert
    }

    // ================================================================
    // P5.2  Testar exceções
    // Ordem: primeiro expectException, só depois a chamada que dá erro.
    // ================================================================

    // Quantidade 0 (abaixo do mínimo) -> exceção.
    public function testAdicionar_QuantidadeZero_LancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->carrinho->adicionar('A', 10, 0);
    }

    // Quantidade 21 (acima do máximo) -> exceção, com verificação da mensagem.
    public function testAdicionar_QuantidadeAcimaDeVinte_LancaExcecaoComMensagem(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A quantidade tem de estar entre 1 e 20.');

        $this->carrinho->adicionar('A', 10, 21);
    }

    // Preço negativo -> exceção, com verificação da mensagem.
    public function testAdicionar_PrecoNegativo_LancaExcecaoComMensagem(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('O preco tem de ser maior que zero.');

        $this->carrinho->adicionar('A', -5, 1);
    }

    // Fronteira: preço 0 também não é permitido -> exceção.
    public function testAdicionar_PrecoZero_LancaExcecao(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->carrinho->adicionar('A', 0, 1);
    }
}
