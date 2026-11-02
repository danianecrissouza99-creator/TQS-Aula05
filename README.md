# Exercícios da Aula 5 — Testes Unitários com PHP

Projeto base para os exercícios práticos. Todos abrem este mesmo projeto e trabalham sobre ele.

O objetivo da aula é escrever a vossa primeira suite de testes com o PHPUnit. No fim, deves ter pelo menos **12 testes a passar**.

## O que está dentro

```
src/     o código a testar (a classe Carrinho)
tests/   onde vais escrever os teus testes
```

## Preparar o ambiente (uma vez)

1. Abre a pasta do projeto no VS Code (Ficheiro, Abrir Pasta).
2. Abre o terminal integrado (menu Terminal, Novo Terminal).
3. Dentro da pasta do projeto, instala o PHPUnit (é um ficheiro só):

```
php -r "copy('https://phar.phpunit.de/phpunit-11.phar', 'phpunit');"
php phpunit --version
```

Se o segundo comando mostrar a versão, o PHPUnit está pronto.

## Como correr os testes

```
php phpunit tests              correr todos os testes
php phpunit --testdox          mostrar cada teste como uma frase
```

No resultado, cada **ponto (.)** é um teste que passou e cada **F** é um teste que falhou. Por baixo de um F, o PHPUnit explica o que esperava e o que obteve.

---

## P5.1 — Primeiros testes (45 min)

Abre `tests/CarrinhoTest.php`. Já lá está o primeiro teste feito, como exemplo. Escreve os restantes.

**O que fazer:** escrever pelo menos **8 testes** sobre a classe `Carrinho`:

- o total com um, dois e três artigos;
- o número de artigos no carrinho;
- o carrinho novo está vazio, e com um artigo já não está;
- o total de um carrinho vazio é zero (já feito).

**Regras:**
- cada teste segue o padrão **Arrange, Act, Assert** (preparar, executar, verificar);
- o nome do teste descreve o que verifica (evita `test1` ou `testCarrinho`);
- usa `assertSame(esperado, obtido)`.

> Confirma sempre a conta à mão antes de escrever o resultado esperado.

---

## P5.2 — Testar exceções (35 min)

Ainda no `tests/CarrinhoTest.php`. O método `adicionar` deve recusar entradas inválidas lançando uma exceção.

**O que fazer:** escrever testes que confirmem que:

- quantidade **zero** lança `InvalidArgumentException`;
- quantidade **acima de 20** lança exceção;
- preço **negativo** lança exceção;
- usa `expectExceptionMessage` para verificar a mensagem em pelo menos um deles.

**Como se escreve:**

```php
public function testQtZeroLevantaErro(): void
{
    $this->expectException(InvalidArgumentException::class);
    $this->carrinho->adicionar('A', 10, 0);
}
```

> Atenção à ordem: primeiro `expectException`, só **depois** a chamada que deve dar erro.

---

## P5.3 — Ciclo TDD: validador de palavra-passe (40 min)

Aqui trabalhas ao contrário: escreves o **teste primeiro**, vê-lo falhar (vermelho), e só depois escreves o código para ele passar (verde).

Abre `tests/ValidadorPasseTest.php`, que tem as instruções passo a passo.

**O que fazer:** criar a classe `ValidadorPasse` (em `src/ValidadorPasse.php`) com um método `valida(string $passe): bool`. Uma palavra-passe é válida quando, ao mesmo tempo:

1. tem no mínimo **8 caracteres**;
2. tem pelo menos uma **letra maiúscula**;
3. tem pelo menos um **dígito**.

**Faz um requisito de cada vez:**
1. escreve um teste para o requisito 1 e vê-lo falhar;
2. cria o código mínimo para passar;
3. só então avança para o requisito seguinte.

---

## Entrega

- Confirma que **todos os testes passam** com `php phpunit --testdox`.
- Guarda o projeto no Git e envia para o GitHub.
- Entrega o link do repositório ao docente.

> O ficheiro `phpunit` não deve ir para o GitHub (o `.gitignore` já trata disso).
