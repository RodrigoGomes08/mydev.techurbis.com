<?php

class Sensor implements JsonSerializable
{
    private int $id;
    private int $id_cidade;
    private string $name;
    private array $leituras = [];

    public function __construct(int $id, int $id_cidade, string $name)
    {
        $this->id = $id;
        $this->id_cidade = $id_cidade;
        $this->name = $name;
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

    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    // --- Serialização JSON ---
    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'id_cidade' => $this->id_cidade,
            'name' => $this->name,
            'leituras' => $this->leituras,   // SensorLeituras também implementa JsonSerializable
        ];
    }
}
?>