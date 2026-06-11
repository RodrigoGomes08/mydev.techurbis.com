<?php

class Sensor implements JsonSerializable
{
    private int $id;
    private int $id_cidade;
    private string $nome;
    private array $leituras = [];

    public function __construct(int $id, int $id_cidade, string $nome)
    {
        $this->id = $id;
        $this->id_cidade = $id_cidade;
        $this->nome = $nome;
    }

    // --- Leituras ---
    public function addLeitura(SensorLeituras $leitura): void
    {
        $this->leituras[] = $leitura;
    }

    public function getLeituras(): array
    {
        return $this->leituras;
    }

    // --- Getters / Setters ---
    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getIdCidade(): int
    {
        return $this->id_cidade;
    }
    public function setIdCidade(int $id_cidade): void
    {
        $this->id_cidade = $id_cidade;
    }

    public function getNome(): string
    {
        return $this->nome;
    }
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    // --- Serialização JSON ---
    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'id_cidade' => $this->id_cidade,
            'nome' => $this->nome,
            'leituras' => $this->leituras,   // SensorLeituras também implementa JsonSerializable
        ];
    }
}
?>