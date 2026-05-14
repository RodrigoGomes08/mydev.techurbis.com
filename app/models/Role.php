<?php

class Role
{
    private int $id_role;
    private string $nome_role;
    private string $cor;

    public function __construct(int $id_role, string $nome_role, string $cor)
    {
        $this->id_role = $id_role;
        $this->nome_role = $nome_role;
        $this->cor = $cor;
    }

    public function getIdRole(): int
    {
        return $this->id_role;
    }
    public function setIdRole(int $id_role): void
    {
        $this->id_role = $id_role;
    }

    public function getNomeRole(): string
    {
        return $this->nome_role;
    }
    public function setNomeRole(string $nome_role): void
    {
        $this->nome_role = $nome_role;
    }

    public function getCor(): string
    {
        return $this->cor;
    }
    public function setCor(string $cor): void
    {
        $this->cor = $cor;
    }
}