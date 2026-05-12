<?php

class Role
{
    private int $id;
    private string $nome_role;
    private string $cor;

    public function __construct(int $id, string $nome_role, string $cor)
    {
        $this->id = $id;
        $this->nome_role = $nome_role;
        $this->cor = $cor;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
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