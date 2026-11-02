# TQS - Aula 5: Testes Unitários com PHP

Exercícios práticos da Aula 5 de Testes e Qualidade de Software (ISTEC, CTeSP em Desenvolvimento de Software, 2.º ano).

Daniane Christyni Souza da Silva, n.º 2025055
Docente: André Silva

O objetivo da aula era escrever a primeira suite de testes com o PHPUnit, com pelo menos 12 testes a passar. No fim fiquei com 26.

O enunciado original, tal como o professor o deu, está no ficheiro `ENUNCIADO.md`.

## Estrutura

```
src/     o código testado
tests/   os testes
```

- `src/Carrinho.php` - a classe do carrinho de compras
- `src/ValidadorPasse.php` - o validador de palavra-passe, criado no P5.3
- `tests/CarrinhoTest.php` - os testes do P5.1 e do P5.2
- `tests/ValidadorPasseTest.php` - os testes do P5.3

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

**O que era pedido:** escrever pelo menos 8 testes à classe `Carrinho`: o total com um, dois e três artigos, o número de artigos, se o carrinho novo está vazio e se deixa de estar quando se adiciona um artigo. Cada teste devia seguir o padrão Arrange, Act, Assert (preparar, executar, verificar), ter um nome que explique o que verifica e usar `assertSame`.

**O que fiz:** escrevi 9 testes para estes casos. O primeiro, `testTotalVazioEhZero`, é o ponto de partida do slide, escrito como lá aparece. Usei o `setUp()` para cada teste começar com um carrinho novo, assim nenhum teste depende do que outro deixou.

Antes de escrever cada valor esperado fiz a conta à mão. Por exemplo, no teste com três artigos: (10 x 2) + (5 x 1) + (3 x 4) = 20 + 5 + 12 = 37.

Também acrescentei um teste com quantidade 20, porque é o máximo permitido e queria confirmar que é aceite.

Nos testes que escrevi de raiz, os nomes seguem o formato sugerido nos slides, `testOQueSeTesta_Condicao_ResultadoEsperado`, por exemplo `testTotal_ComDoisArtigos_DevolveSomaDosArtigos`. Os exemplos dos slides ficaram com os nomes originais.

Nos slides da aula, o P5.1 pedia também o desconto de estudante para idades dentro e fora do intervalo (18 a 25 anos). A classe `Carrinho` não tinha esse método, por isso acrescentei `descontoEstudante()`, que devolve 10% entre os 18 e os 25 anos e 0% fora disso. Testei as idades 17, 18, 21, 25 e 26 com um Data Provider, para cobrir os dois limites e os valores logo antes e logo depois.

## P5.2 - Testar exceções

**O que era pedido:** confirmar que o método `adicionar` recusa entradas inválidas com uma exceção: quantidade zero, quantidade acima de 20 e preço negativo. Em pelo menos um dos testes, devia verificar-se também a mensagem com `expectExceptionMessage`.

**O que fiz:** escrevi 5 testes: um para cada um destes casos, mais um para o preço 0, que também não é permitido (o preço tem de ser maior que zero). Nos testes da quantidade acima de 20 e do preço negativo verifico a mensagem completa.

Dois destes testes são os exemplos dos slides, escritos tal como o professor os mostrou: `testQtZeroLevantaErro` (verificar que a exceção ocorre) e `testMensagemDoErro` (verificar também a mensagem). Este último, com quantidade 0, verifica só que a mensagem fala da `quantidade`. O `expectExceptionMessage` aceita apenas uma parte do texto, o que torna o teste menos frágil se a frase mudar um pouco.

O mais importante aqui foi a ordem: primeiro o `expectException` e só depois a chamada que deve dar erro. Se for ao contrário, a exceção acontece antes de o PHPUnit saber que a deve esperar, e o teste dá erro.

## P5.3 - Ciclo TDD: validador de palavra-passe

**O que era pedido:** criar a classe `ValidadorPasse` com um método `valida(string $passe): bool`, usando TDD, ou seja, escrevendo o teste antes do código. Uma palavra-passe é válida quando tem ao mesmo tempo:

1. no mínimo 8 caracteres;
2. pelo menos uma letra maiúscula;
3. pelo menos um dígito.

A ideia era fazer um requisito de cada vez: escrever o teste, vê-lo falhar (vermelho), escrever o mínimo de código para passar (verde) e só depois avançar. No fim, melhorar o código com os testes a garantir que nada se parte (refactor).

**Como correu:**

1. Comecei pelo tamanho. Escrevi o teste para `'Ab1'` ser inválida e corri-o sem a classe existir. Deu erro, que era o esperado. Depois criei a classe só com `return strlen($passe) >= 8;` e o teste passou.
2. A seguir escrevi o teste da maiúscula (`'abcdefg1'` tem de ser inválida). Falhou, porque o código só olhava para o tamanho. Acrescentei a verificação da maiúscula e passou.
3. Fiz o mesmo com o dígito (`'Abcdefgh'` tem de ser inválida): primeiro falhou e, depois de acrescentar a verificação, passou.
4. No refactor, passei o 8 para uma constante (`TAMANHO_MINIMO`) e deixei cada regra com o seu comentário. Os testes continuaram todos verdes, o que mostra que o comportamento não mudou.

Também acrescentei um teste para uma palavra-passe que cumpre tudo (`'Segura2026'`) e um Data Provider com 7, 8 e 9 caracteres, que são os valores-limite do tamanho.

## Resultado

Com `php phpunit --testdox tests` passam os 26 testes: 19 do `Carrinho` e 7 do `ValidadorPasse` (cada linha de um Data Provider conta como um teste).

```
OK (26 tests, 29 assertions)
```

## O que aprendi

Ver o teste falhar antes de escrever o código foi o que mais me ajudou a perceber o TDD. Garante que o teste está mesmo a verificar alguma coisa e que não passa por acaso.

Também percebi que os erros costumam estar nos limites, como o 0, o 20, o 18 ou o 8, e por isso tentei ter sempre um teste mesmo em cima de cada um.
