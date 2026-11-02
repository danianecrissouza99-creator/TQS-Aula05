# TQS - Aula 5: Testes Unitários com PHP

Exercícios práticos da Aula 5 de Testes e Qualidade de Software (ISTEC, CTeSP em Desenvolvimento de Software, 2.º ano).

Daniane Christyni Souza da Silva, n.º 2025055
Docente: André Silva

O objetivo da aula era escrever a primeira suite de testes com o PHPUnit, com pelo menos 12 testes a passar. No fim fiquei com 26.

O enunciado original, tal como o professor o deu, está no ficheiro `ENUNCIADO.md`.

## Estrutura

| Ficheiro | O que tem |
| --- | --- |
| `src/Carrinho.php` | A classe do carrinho de compras (com o desconto de estudante que acrescentei) |
| `src/ValidadorPasse.php` | O validador de palavra-passe, criado em TDD no P5.3 |
| `tests/CarrinhoTest.php` | Os testes do P5.1 e do P5.2 |
| `tests/ValidadorPasseTest.php` | Os testes do P5.3 |
| `ENUNCIADO.md` | O enunciado original da aula |

## Preparar e correr

O PHPUnit não está no repositório (o `.gitignore` deixa-o de fora), por isso é preciso descarregá-lo para a pasta do projeto:

```
php -r "copy('https://phar.phpunit.de/phpunit-11.phar', 'phpunit');"
php phpunit --version
```

Se o segundo comando mostrar a versão, está pronto. Para correr os testes:

```
php phpunit tests               # todos os testes
php phpunit --testdox tests     # cada teste aparece como uma frase
```

Cada ponto (.) é um teste que passou e cada F é um que falhou. Por baixo de um F, o PHPUnit diz o que esperava e o que recebeu.

Este projeto não tem `phpunit.xml`, por isso é preciso pôr sempre `tests` no fim do comando.

## P5.1 - Primeiros testes

**O que era pedido:** escrever pelo menos 8 testes à classe `Carrinho`: o total com um, dois e três artigos, o número de artigos, o carrinho vazio e não vazio, e o desconto de estudante para idades dentro e fora do intervalo, incluindo os valores fronteira 18 e 25. Cada teste devia seguir o padrão Arrange, Act, Assert (preparar, executar, verificar), ter um nome que explique o que verifica e usar `assertSame`.

**O que fiz:** usei o `setUp()` para cada teste começar com um carrinho novo, assim nenhum teste depende do que outro deixou. Antes de escrever cada valor esperado fiz a conta à mão.

| Teste | O que verifica | Conta |
| --- | --- | --- |
| `testTotalVazioEhZero` | Carrinho vazio tem total 0 (ponto de partida do slide) | 0 |
| `testTotal_ComUmArtigo_...` | Total com um artigo | 10 x 2 = 20 |
| `testTotal_ComDoisArtigos_...` | Total com dois artigos | 20 + 5 = 25 |
| `testTotal_ComTresArtigos_...` | Total com três artigos | 20 + 5 + 12 = 37 |
| `testNumeroDeArtigos_ComDoisArtigos_...` | Dois artigos adicionados | 2 |
| `testNumeroDeArtigos_ComQuantidadeCinco_...` | A quantidade não conta como artigos | 1 |
| `testVazio_CarrinhoNovo_...` | Carrinho novo está vazio | true |
| `testVazio_ComUmArtigo_...` | Com um artigo já não está vazio | false |
| `testAdicionar_QuantidadeVinte_...` | Quantidade 20, o máximo, é aceite | 3 x 20 = 60 |

Nos testes que escrevi de raiz, os nomes seguem o formato sugerido nos slides, `testOQueSeTesta_Condicao_ResultadoEsperado`. O exemplo do slide ficou com o nome original.

**Desconto de estudante:** a classe `Carrinho` não tinha este método, por isso acrescentei `descontoEstudante()`. Devolve 10% entre os 18 e os 25 anos (inclusive) e 0% fora disso. Testei-o com um Data Provider, para cobrir os dois limites e os valores logo antes e logo depois:

| Caso | Idade | Desconto esperado |
| --- | --- | --- |
| menor de idade | 17 | 0% |
| limite inferior | 18 | 10% |
| no intervalo | 21 | 10% |
| limite superior | 25 | 10% |
| acima | 26 | 0% |

## P5.2 - Testar exceções

**O que era pedido:** confirmar que o método `adicionar` recusa entradas inválidas com uma exceção: quantidade zero, quantidade acima de 20 e preço negativo. Em pelo menos um dos testes, devia verificar-se também a mensagem com `expectExceptionMessage`.

**O que fiz:** escrevi 5 testes. Dois são os exemplos dos slides, escritos tal como o professor os mostrou.

| Teste | Entrada | Verifica a mensagem? |
| --- | --- | --- |
| `testQtZeroLevantaErro` (slide) | quantidade 0 | Não |
| `testMensagemDoErro` (slide) | quantidade 0 | Sim, só a palavra "quantidade" |
| `testAdicionar_QuantidadeAcimaDeVinte_...` | quantidade 21 | Sim, a frase completa |
| `testAdicionar_PrecoNegativo_...` | preço -5 | Sim, a frase completa |
| `testAdicionar_PrecoZero_...` | preço 0 | Não |

O teste do preço 0 não era pedido, mas acrescentei-o porque o preço tem de ser maior que zero e o 0 é exatamente o limite.

O `testMensagemDoErro` mostra que o `expectExceptionMessage` aceita só uma parte do texto, o que torna o teste menos frágil se a frase mudar um pouco.

O mais importante aqui foi a ordem: primeiro o `expectException` e só depois a chamada que deve dar erro. Se for ao contrário, a exceção acontece antes de o PHPUnit saber que a deve esperar, e o teste dá erro.

## P5.3 - Ciclo TDD: validador de palavra-passe

**O que era pedido:** criar a classe `ValidadorPasse` com um método `valida(string $passe): bool`, usando TDD, ou seja, escrevendo o teste antes do código. Uma palavra-passe é válida quando tem ao mesmo tempo:

1. no mínimo 8 caracteres;
2. pelo menos uma letra maiúscula;
3. pelo menos um dígito.

A ideia era fazer um requisito de cada vez: escrever o teste, vê-lo falhar (vermelho), escrever o mínimo de código para passar (verde) e só depois avançar. No fim, melhorar o código com os testes a garantir que nada se parte (refactor).

**Como correu:**

| Passo | O que fiz | Resultado |
| --- | --- | --- |
| Vermelho | Teste para `'Ab1'` ser inválida, sem a classe existir | Erro: a classe não existia |
| Verde | Classe só com `return strlen($passe) >= 8;` | Passou |
| Vermelho | Teste para `'abcdefg1'` (sem maiúscula) ser inválida | Falhou: o código só via o tamanho |
| Verde | Acrescentei a verificação da maiúscula | Passou |
| Vermelho | Teste para `'Abcdefgh'` (sem dígito) ser inválida | Falhou |
| Verde | Acrescentei a verificação do dígito | Passou |
| Refactor | Passei o 8 para a constante `TAMANHO_MINIMO` e comentei cada regra | Continuou tudo a passar |

O facto de os testes continuarem verdes depois do refactor mostra que o comportamento não mudou.

No fim, os testes do validador ficaram assim:

| Teste | Palavra-passe | Esperado |
| --- | --- | --- |
| `testValida_PasseCurta_...` | `Ab1` | inválida |
| `testValida_SemMaiuscula_...` | `abcdefg1` | inválida |
| `testValida_SemDigito_...` | `Abcdefgh` | inválida |
| `testValida_CumpreTodasAsRegras_...` | `Segura2026` | válida |
| Data Provider: 7 caracteres | `Abcdef1` | inválida |
| Data Provider: 8 caracteres | `Abcdefg1` | válida |
| Data Provider: 9 caracteres | `Abcdefgh1` | válida |

O Data Provider serve para testar os valores-limite do tamanho (7, 8 e 9) com um só teste.

## Resultado

Com `php phpunit --testdox tests` passam os 26 testes (cada linha de um Data Provider conta como um teste):

| Ficheiro | Testes |
| --- | --- |
| `CarrinhoTest.php` | 19 (14 do P5.1 e 5 do P5.2) |
| `ValidadorPasseTest.php` | 7 |
| **Total** | **26** |

```
OK (26 tests, 29 assertions)
```

## O que aprendi

Ver o teste falhar antes de escrever o código foi o que mais me ajudou a perceber o TDD. Garante que o teste está mesmo a verificar alguma coisa e que não passa por acaso.

Também percebi que os erros costumam estar nos limites, como o 0, o 20, o 18 ou o 8, e por isso tentei ter sempre um teste mesmo em cima de cada um.
