# TQS - Aula 5: Testes Unitários com PHP

Exercícios da Aula 5 de Testes e Qualidade de Software (ISTEC).

Daniane Christyni Souza da Silva, n.º 2025055

O enunciado original do professor está no ficheiro `ENUNCIADO.md`.

## Como correr

É preciso ter o PHPUnit na pasta do projeto:

```
php -r "copy('https://phar.phpunit.de/phpunit-11.phar', 'phpunit');"
```

Depois:

```
php phpunit --testdox tests
```

Neste momento passam os 25 testes.

## O que fiz

### P5.1 - Primeiros testes

Escrevi testes para a classe `Carrinho`: o total com um, dois e três artigos, o número de artigos e se o carrinho está vazio ou não. Também testei a quantidade 20, que é o máximo permitido.

O enunciado pedia ainda o desconto de estudante (18 a 25 anos), mas a classe não tinha esse método, por isso acrescentei `descontoEstudante()` ao `Carrinho.php`. As idades 17, 18, 21, 25 e 26 são testadas com um Data Provider.

### P5.2 - Exceções

Testei que o `adicionar` lança `InvalidArgumentException` com quantidade 0, quantidade 21, preço negativo e preço 0. Em dois desses testes também verifico a mensagem de erro com `expectExceptionMessage`.

### P5.3 - TDD

Criei a classe `ValidadorPasse` escrevendo os testes primeiro. A palavra-passe tem de ter pelo menos 8 caracteres, uma maiúscula e um dígito.

Fui um requisito de cada vez: primeiro o tamanho, depois a maiúscula, depois o dígito. Em cada passo o teste novo falhava e só depois escrevia o código para passar. No fim fiz um pequeno refactor (o 8 passou a constante) e os testes continuaram verdes.

Os casos de 7, 8 e 9 caracteres estão num Data Provider, porque são os valores-limite do tamanho.

## Ficheiros

- `src/Carrinho.php` - classe do carrinho
- `src/ValidadorPasse.php` - validador de palavra-passe
- `tests/CarrinhoTest.php` - testes do P5.1 e P5.2
- `tests/ValidadorPasseTest.php` - testes do P5.3
