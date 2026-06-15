<?php

class Lugares{
    private $id;
    private $id_p_estacionamento;
    private $id_tipo_lugares;
    private $identificação;
    private $ocupado;

    public function __construct($id, $id_p_estacionamento, $id_tipo_lugares, $identificação, $ocupado)
    {
        $this->id = $id;
        $this->id_p_estacionamento = $id_p_estacionamento;
        $this->id_tipo_lugares = $id_tipo_lugares;
        $this->identificação = $identificação;
        $this->ocupado = $ocupado;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getIdPEstacionamento()
    {
        return $this->id_p_estacionamento;
    }

    public function setIdPEstacionamento($id_p_estacionamento)
    {
        $this->id_p_estacionamento = $id_p_estacionamento;
    }

    public function getIdTipoLugares()
    {
        return $this->id_tipo_lugares;
    }

    public function setIdTipoLugares($id_tipo_lugares)
    {
        $this->id_tipo_lugares = $id_tipo_lugares;
    }

    public function getIdentificacao()
    {
        return $this->identificação;
    }

    public function setIdentificacao($identificação)
    {
        $this->identificação = $identificação;
    }

    public function getOcupado()
    {
        return $this->ocupado;
    }

    public function setOcupado($ocupado)
    {
        $this->ocupado = $ocupado;
    }
}