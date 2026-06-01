<?php

class Poste implements JsonSerializable
{
    private int $id;
    private int $id_cidade;
    private int $id_estado;
    private string $longitude;
    private string $latitude;
    private string $observacao;

    public function __construct(int $id, int $id_cidade, int $id_estado, string $longitude, string $latitude, string $observacao = '')
    {
        $this->id = $id;
        $this->id_cidade = $id_cidade;
        $this->id_estado = $id_estado;
        $this->longitude = $longitude;
        $this->latitude = $latitude;
        $this->observacao = $observacao;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'id_cidade' => $this->id_cidade,
            'id_estado' => $this->id_estado,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'observacao' => $this->observacao,
        ];
    }

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

    public function getIdEstado(): int
    {
        return $this->id_estado;
    }
    public function setIdEstado(int $id_estado): void
    {
        $this->id_estado = $id_estado;
    }

    public function getLongitude(): string
    {
        return $this->longitude;
    }
    public function setLongitude(string $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getLatitude(): string
    {
        return $this->latitude;
    }
    public function setLatitude(string $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getObservacao(): string
    {
        return $this->observacao;
    }
    public function setObservacao(string $observacao): void
    {
        $this->observacao = $observacao;
    }
}