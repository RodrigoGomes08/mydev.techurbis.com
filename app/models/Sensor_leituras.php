<?php

class SensorLeituras
{
    private int $id;
    private int $id_sensor;
    private DateTime $data_leitura;
    private string $leitura_string;
    private float $leitura_num;

    // Construtor
    public function __construct(
        int $id,
        int $id_sensor,
        DateTime $data_leitura,
        string $leitura_string,
        float $leitura_num
    ) {
        $this->id = $id;
        $this->id_sensor = $id_sensor;
        $this->data_leitura = $data_leitura;
        $this->leitura_string = $leitura_string;
        $this->leitura_num = $leitura_num;
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

    // Getter e Setter do id_sensor
    public function getIdSensor(): int
    {
        return $this->id_sensor;
    }

    public function setIdSensor(int $id_sensor): void
    {
        $this->id_sensor = $id_sensor;
    }

    // Getter e Setter da data_leitura
    public function getDataLeitura(): DateTime
    {
        return $this->data_leitura;
    }

    public function setDataLeitura(DateTime $data_leitura): void
    {
        $this->data_leitura = $data_leitura;
    }

    // Getter e Setter da leitura_string
    public function getLeituraString(): string
    {
        return $this->leitura_string;
    }

    public function setLeituraString(string $leitura_string): void
    {
        $this->leitura_string = $leitura_string;
    }

    // Getter e Setter da leitura_num
    public function getLeituraNum(): float
    {
        return $this->leitura_num;
    }

    public function setLeituraNum(float $leitura_num): void
    {
        $this->leitura_num = $leitura_num;
    }
}
?>