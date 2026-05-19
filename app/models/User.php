<?php

class User
{
    private int $id;
    private int $id_role;
    private string $nome;
    private string $data_nascimento;
    private string $telefone;
    private string $morada;
    private string $email;
    private string $password;
    private int $ativo;
    private int $tem_mobilidade_reduzida;

    public function __construct(
        int $id,
        int $id_role,
        string $nome,
        string $data_nascimento,
        string $telefone,
        string $morada,
        string $email,
        string $password,
        int $ativo,
        int $tem_mobilidade_reduzida,
    ) {
        $this->id = $id;
        $this->id_role = $id_role;
        $this->nome = $nome;
        $this->data_nascimento = $data_nascimento;
        $this->telefone = $telefone;
        $this->morada = $morada;
        $this->email = $email;
        $this->password = $password;
        $this->ativo = $ativo;
        $this->tem_mobilidade_reduzida = $tem_mobilidade_reduzida;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getIdRole(): int
    {
        return $this->id_role;
    }
    public function setIdRole(int $id_role): void
    {
        $this->id_role = $id_role;
    }

    public function getNome(): string
    {
        return $this->nome;
    }
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function getDataNascimento(): string
    {
        return $this->data_nascimento;
    }
    public function setDataNascimento(string $data_nascimento): void
    {
        $this->data_nascimento = $data_nascimento;
    }

    public function getTelefone(): string
    {
        return $this->telefone;
    }
    public function setTelefone(string $telefone): void
    {
        $this->telefone = $telefone;
    }

    public function getMorada(): string
    {
        return $this->morada;
    }
    public function setMorada(string $morada): void
    {
        $this->morada = $morada;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getAtivo(): int
    {
        return $this->ativo;
    }
    public function setAtivo(int $ativo): void
    {
        $this->ativo = $ativo;
    }

    public function getTemMobilidadeReduzida(): int
    {
        return $this->tem_mobilidade_reduzida;
    }
    public function setTemMobilidadeReduzida(int $valor): void
    {
        $this->tem_mobilidade_reduzida = $valor;
    }
}