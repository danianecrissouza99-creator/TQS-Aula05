<?php

declare(strict_types=1);

/**
 * Validador de palavra-passe (exercício P5.3, feito em TDD).
 *
 * Uma palavra-passe é válida quando cumpre as 3 regras ao mesmo tempo:
 *   1. tem no mínimo 8 caracteres;
 *   2. tem pelo menos uma letra maiúscula;
 *   3. tem pelo menos um dígito.
 */
class ValidadorPasse
{
    private const TAMANHO_MINIMO = 8;

    public function valida(string $passe): bool
    {
        // Requisito 1: mínimo de 8 caracteres.
        if (strlen($passe) < self::TAMANHO_MINIMO) {
            return false;
        }

        // Requisito 2: pelo menos uma letra maiúscula (A a Z).
        if (preg_match('/[A-Z]/', $passe) !== 1) {
            return false;
        }

        // Requisito 3: pelo menos um dígito (0 a 9).
        if (preg_match('/[0-9]/', $passe) !== 1) {
            return false;
        }

        // Passou nas 3 regras -> válida.
        return true;
    }
}
