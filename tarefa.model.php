<?php

class Tarefa {
    private $id;
    private $id_status;
    private $tarefa;
    private $data_cadastro;
    private $data_inicio;
    private $data_termino;
    private $prioridade;
    private $id_usuario;
    private $arquivo; 
    private $hora_inicio; 
    private $hora_termino; 
    private $localizacao; 

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
        return $this;
    }

    public function __construct($id = null, $id_status = null, $tarefa = null, $data_cadastro = null, $data_inicio = null, $data_termino = null, $prioridade = null, $id_usuario = null, $arquivo = null, $hora_inicio = null, $hora_termino = null, $localizacao = null) {
        $this->id = $id;
        $this->id_status = $id_status;
        $this->tarefa = $tarefa;
        $this->data_cadastro = $data_cadastro;
        $this->data_inicio = $data_inicio;
        $this->data_termino = $data_termino;
        $this->prioridade = $prioridade;
        $this->id_usuario = $id_usuario;
        $this->arquivo = $arquivo;
        $this->hora_inicio = $hora_inicio;
        $this->hora_termino = $hora_termino;
        $this->localizacao = $localizacao;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getIdStatus() {
        return $this->id_status;
    }

    public function setIdStatus($id_status) {
        $this->id_status = $id_status;
    }

    public function getTarefa() {
        return $this->tarefa;
    }

    public function setTarefa($tarefa) {
        $this->tarefa = $tarefa;
    }

    public function getDataCadastro() {
        return $this->data_cadastro;
    }

    public function setDataCadastro($data_cadastro) {
        $this->data_cadastro = $data_cadastro;
    }

    public function getDataInicio() {
        return $this->data_inicio;
    }

    public function setDataInicio($data_inicio) {
        $this->data_inicio = $data_inicio;
    }

    public function getDataTermino() {
        return $this->data_termino;
    }

    public function setDataTermino($data_termino) {
        $this->data_termino = $data_termino;
    }

    public function getPrioridade() {
        return $this->prioridade;
    }

    public function setPrioridade($prioridade) {
        $this->prioridade = $prioridade;
    }

    public function getIdUsuario() {
        return $this->id_usuario;
    }

    public function setIdUsuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    public function getArquivo() {
        return $this->arquivo;
    }

    public function setArquivo($arquivo) {
        $this->arquivo = $arquivo;
    }

    public function getHoraInicio() {
        return $this->hora_inicio;
    }

    public function setHoraInicio($hora_inicio) {
        $this->hora_inicio = $hora_inicio;
    }

    public function getHoraTermino() {
        return $this->hora_termino;
    }

    public function setHoraTermino($hora_termino) {
        $this->hora_termino = $hora_termino;
    }

    public function getLocalizacao() {
        return $this->localizacao;
    }

    public function setLocalizacao($localizacao) {
        $this->localizacao = $localizacao;
    }
}

