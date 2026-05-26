<?php

class Contentor implements JsonSerializable
{
    // =========================
    // ATRIBUTOS
    // =========================

    private int $id;
    private int $id_cidade;
    private int $id_estado;
    private int $capacidade_max;
    private float $longitude;
    private float $latitude;
    private string $tipo;
    private string $identificacao;
    private string $observacao;
    private bool $is_full;

    // =========================
    // CONSTRUTOR
    // =========================

    public function __construct(
        int $id,
        int $id_cidade,
        int $id_estado,
        int $capacidade_max,
        float $longitude,
        float $latitude,
        string $tipo,
        string $identificacao,
        ?string $observacao = '',
        ?bool $is_full = false
    ) {
        $this->id = $id;
        $this->id_cidade = $id_cidade;
        $this->id_estado = $id_estado;
        $this->capacidade_max = $capacidade_max;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->tipo = $tipo;
        $this->identificacao = $identificacao;
        $this->observacao = $observacao ?? '';
        $this->is_full = $is_full ?? false;
    }

    // =========================
    // GETTERS E SETTERS
    // =========================

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'id_cidade' => $this->id_cidade,
            'id_estado' => $this->id_estado,
            'capacidade_max' => $this->capacidade_max,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'tipo' => $this->tipo,
            'identificacao' => $this->identificacao,
            'observacao' => $this->observacao,
            'is_full' => $this->is_full,
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

    // ID CIDADE
    public function getIdCidade(): int
    {
        return $this->id_cidade;
    }

    public function setIdCidade(int $id_cidade): void
    {
        $this->id_cidade = $id_cidade;
    }

    // ID ESTADO
    public function getIdEstado(): int
    {
        return $this->id_estado;
    }

    public function setIdEstado(int $id_estado): void
    {
        $this->id_estado = $id_estado;
    }

    // CAPACIDADE MAX
    public function getCapacidadeMax(): int
    {
        return $this->capacidade_max;
    }

    public function setCapacidadeMax(int $capacidade_max): void
    {
        $this->capacidade_max = $capacidade_max;
    }

    // LONGITUDE
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    // LATITUDE
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    // TIPO
    public function getTipo(): string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): void
    {
        $this->tipo = $tipo;
    }

    // IDENTIFICACAO
    public function getIdentificacao(): string
    {
        return $this->identificacao;
    }

    public function setIdentificacao(string $identificacao): void
    {
        $this->identificacao = $identificacao;
    }

    // OBSERVACAO
    public function getObservacao(): string
    {
        return $this->observacao;
    }

    public function setObservacao(?string $observacao): void
    {
        $this->observacao = $observacao ?? '';
    }

    // IS FULL
    public function getIsFull(): bool
    {
        return $this->is_full;
    }

    public function setIsFull(bool $is_full): void
    {
        $this->is_full = $is_full;
    }
}