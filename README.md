# TQS · Aula 5 — Testes Unitários com PHP

Exercícios práticos da Aula 5 da unidade curricular **Testes e Qualidade de Software** (ISTEC · CTeSP em Desenvolvimento de Software · 2.º ano).

O objetivo foi escrever uma suite de testes unitários com o **PHPUnit**, testar exceções e desenvolver uma classe nova com o ciclo **TDD** (vermelho, verde, refactor).

**Resultado final:** 25 testes a passar · `OK (25 tests, 27 assertions)`

---

## Autora

- **Nome:** Daniane Christyni Souza da Silva
- **N.º de aluna:** 2025055
- **Docente:** André Silva

---

## Estrutura do projeto

```
src/
  Carrinho.php            classe Carrinho (total, artigos, vazio, desconto de estudante)
  ValidadorPasse.php      classe criada em TDD (exercício P5.3)
tests/
  CarrinhoTest.php        testes dos exercícios P5.1 e P5.2
  ValidadorPasseTest.php  testes do exercício P5.3
```

---

## Como correr os testes

1. Instalar o PHPUnit dentro da pasta do projeto (é um ficheiro só):

   ```
   php -r "copy('https://phar.phpunit.de/phpunit-11.phar', 'phpunit');"
   php phpunit --version
   ```

2. Correr os testes:

   ```
   php phpunit tests                 # todos os testes
   php phpunit --testdox tests       # cada teste mostrado como uma frase
   php phpunit tests/CarrinhoTest.php
   ```

> O ficheiro `phpunit` não está no repositório (está no `.gitignore`).

---

## P5.1 — Primeiros testes

Testes à classe `Carrinho`, seguindo o padrão **AAA** (Arrange, Act, Assert), com `setUp()` para cada teste começar com um carrinho novo e `assertSame` por omissão.

| Teste | O que verifica |
| --- | --- |
| `testTotal_CarrinhoVazio_DevolveZero` | Carrinho vazio tem total 0 |
| `testTotal_ComUmArtigo_DevolvePrecoVezesQuantidade` | 10 × 2 = 20 |
| `testTotal_ComDoisArtigos_DevolveSomaDosArtigos` | (10 × 2) + (5 × 1) = 25 |
| `testTotal_ComTresArtigos_DevolveSomaDosArtigos` | (10 × 2) + (5 × 1) + (3 × 4) = 37 |
| `testNumeroDeArtigos_ComDoisArtigos_DevolveDois` | Dois artigos adicionados = 2 |
| `testNumeroDeArtigos_ComQuantidadeCinco_DevolveUm` | A quantidade não conta como artigos |
| `testVazio_CarrinhoNovo_DevolveTrue` | Carrinho novo está vazio |
| `testVazio_ComUmArtigo_DevolveFalse` | Com um artigo já não está vazio |
| `testAdicionar_QuantidadeVinte_EhAceite` | Valor-limite: quantidade 20 é aceite |
| `testDescontoEstudante_PorIdade_DevolveDescontoCorreto` | Data Provider com 5 idades: 17, 18, 21, 25 e 26 |

O desconto de estudante (10% entre os 18 e os 25 anos, inclusive) foi acrescentado à classe `Carrinho` para cumprir o enunciado da aula. Os valores-limite 18 e 25 são testados com um **Data Provider**.

---

## P5.2 — Testar exceções

O método `adicionar` tem de recusar entradas inválidas com `InvalidArgumentException`.

| Teste | O que verifica |
| --- | --- |
| `testAdicionar_QuantidadeZero_LancaExcecao` | Quantidade 0 é recusada |
| `testAdicionar_QuantidadeAcimaDeVinte_LancaExcecaoComMensagem` | Quantidade 21 é recusada, com verificação da mensagem |
| `testAdicionar_PrecoNegativo_LancaExcecaoComMensagem` | Preço negativo é recusado, com verificação da mensagem |
| `testAdicionar_PrecoZero_LancaExcecao` | Valor-limite: preço 0 também é recusado |

---

## P5.3 — Ciclo TDD: validador de palavra-passe

A classe `ValidadorPasse` foi desenvolvida com o teste escrito **antes** do código. Uma palavra-passe é válida quando tem, ao mesmo tempo:

1. no mínimo 8 caracteres;
2. pelo menos uma letra maiúscula;
3. pelo menos um dígito.

**Como decorreu o ciclo:**

| Fase | O que foi feito | Resultado |
| --- | --- | --- |
| 🔴 Vermelho | Testes dos requisitos escritos sem o código correspondente | Testes a falhar |
| 🟢 Verde 1 | Verificação do tamanho mínimo | Requisito 1 a passar |
| 🟢 Verde 2 | Verificação da letra maiúscula | Requisitos 1 e 2 a passar |
| 🟢 Verde 3 | Verificação do dígito | Todos a passar |
| 🔵 Refactor | Tamanho mínimo passou a constante e cada regra ficou comentada | Todos continuam a passar |

| Teste | O que verifica |
| --- | --- |
| `testValida_PasseCurta_DevolveFalse` | `'Ab1'` é inválida (curta) |
| `testValida_SemMaiuscula_DevolveFalse` | `'abcdefg1'` é inválida (sem maiúscula) |
| `testValida_SemDigito_DevolveFalse` | `'Abcdefgh'` é inválida (sem dígito) |
| `testValida_CumpreTodasAsRegras_DevolveTrue` | `'Segura2026'` é válida |
| `testValida_TamanhoNaFronteira_DevolveResultadoCorreto` | Data Provider: 7 caracteres inválida, 8 e 9 válidas |

---

## Boas práticas aplicadas

- **Princípios FIRST:** testes rápidos, isolados (`setUp()`), repetíveis e autovalidáveis.
- **Padrão AAA** em todos os testes.
- **Nomes descritivos** no formato `testOQueSeTesta_Condicao_ResultadoEsperado`.
- **`assertSame` por omissão**, para comparar valor e tipo.
- **Três tipos de caso:** nominais, de fronteira e de erro.
- **Data Providers** para testar vários valores-limite com um só teste.
