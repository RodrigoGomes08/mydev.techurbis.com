<?php

class Parque implements JsonSerializable
{

    private int $id;
    private int $id_freguesia;
    private string $nome;
    private int $num_max_lugares;
    private string $tipo;
    private float $tarifa;
    private string $longitude;
    private string $latitude;
    private array $lugares = [];

    public function __construct(
        int $id,
        int $id_freguesia,
        string $nome,
        int $num_max_lugares,
        string $tipo,
        float $tarifa,
        string $longitude,
        string $latitude
    ) {
        $this->id = $id;
        $this->id_freguesia = $id_freguesia;
        $this->nome = $nome;
        $this->num_max_lugares = $num_max_lugares;
        $this->tipo = $tipo;
        $this->tarifa = $tarifa;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'id_freguesia' => $this->id_freguesia,
            'nome' => $this->nome,
            'num_max_lugares' => $this->num_max_lugares,
            'tipo' => $this->tipo,
            'tarifa' => $this->tarifa,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'lugares' => $this->lugares,
        ];
    }


    // ID
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    // ID Freguesia
    public function getIdFreguesia(): int
    {
        return $this->id_freguesia;
    }

    public function setIdFreguesia(int $id_freguesia): void
    {
        $this->id_freguesia = $id_freguesia;
    }

    // Nome
    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    // Número máximo de lugares
    public function getNumMaxLugares(): int
    {
        return $this->num_max_lugares;
    }

    public function setNumMaxLugares(int $num_max_lugares): void
    {
        $this->num_max_lugares = $num_max_lugares;
    }

    // Tipo
    public function getTipo(): string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): void
    {
        $this->tipo = $tipo;
    }

    // Tarifa
    public function getTarifa(): float
    {
        return $this->tarifa;
    }

    public function setTarifa(float $tarifa): void
    {
        $this->tarifa = $tarifa;
    }

    // Longitude
    public function getLongitude(): string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): void
    {
        $this->longitude = $longitude;
    }

    // Latitude
    public function getLatitude(): string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): void
    {
        $this->latitude = $latitude;
    }

    // Lugares
    public function getLugares(): array
    {
        return $this->lugares;
    }

    public function setLugares(array $lugares): void
    {
        $this->lugares = $lugares;
    }
}