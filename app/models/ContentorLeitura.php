<?php

class SensorLeituras implements JsonSerializable
{
    private int $id;
    private int $id_sensor;
    private DateTime $data_leitura;
    private float $valor;

    public function __construct(
        int $id,
        int $id_sensor,
        DateTime $data_leitura,
        float $valor
    ) {
        $this->id = $id;
        $this->id_sensor = $id_sensor;
        $this->data_leitura = $data_leitura;
        $this->valor = $valor;
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

    public function getIdSensor(): int
    {
        return $this->id_sensor;
    }
    public function setIdSensor(int $id_sensor): void
    {
        $this->id_sensor = $id_sensor;
    }

    public function getDataLeitura(): DateTime
    {
        return $this->data_leitura;
    }
    public function setDataLeitura(DateTime $data_leitura): void
    {
        $this->data_leitura = $data_leitura;
    }

    public function getValor(): float
    {
        return $this->valor;
    }
    public function setValor(float $valor): void
    {
        $this->valor = $valor;
    }

    // --- Serialização JSON ---
    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'id_sensor' => $this->id_sensor,
            'data_leitura' => $this->data_leitura->format('Y-m-d H:i:s'),
            'valor' => $this->valor
        ];
    }
}
?>