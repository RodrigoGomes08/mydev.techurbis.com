<?php

class Sensor
{
    private int $id;
    private int $id_cidade;
    private string $name;

    // Construtor
    public function __construct(int $id, int $id_cidade, string $name)
    {
        $this->id = $id;
        $this->id_cidade = $id_cidade;
        $this->name = $name;
    }

    // Getter e Setter do id
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    // Getter e Setter do id_cidade
    public function getIdCidade(): int
    {
        return $this->id_cidade;
    }

    public function setIdCidade(int $id_cidade): void
    {
        $this->id_cidade = $id_cidade;
    }

    // Getter e Setter do name
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
?>