<?php


class Movimentacoes{

    // private $id_moviements;
    private $id_user;
    private $id_ativo;
    private $tp_operation;
    private $compra_venda;
    private $qnt_ativo;
    private $vlr_ativo;
    private $vlr_total;
    private $dt_moviment;
    private $dt_registro;
	/*
    public function getId_moviements(){
		return $this->id_moviements;
	}

	public function setId_moviements($id_moviements){
		$this->id_moviements = $id_moviements;
	}
	*/

	public function getId_user(){
		return $this->id_user;
	}

	public function setId_user($id_user){
		$this->id_user = $id_user;
	}

	public function getId_ativo(){
		return $this->id_ativo;
	}

	public function setId_ativo($id_ativo){
		$this->id_ativo = $id_ativo;
	}

	public function getTp_operation(){
		return $this->tp_operation;
	}

	public function setTp_operation($tp_operation){
		$this->tp_operation = $tp_operation;
	}

	public function getCompra_venda(){
		return $this->compra_venda;
	}

	public function setCompra_venda($compra_venda){
		$this->compra_venda = $compra_venda;
	}

	public function getQnt_ativo(){
		return $this->qnt_ativo;
	}

	public function setQnt_ativo($qnt_ativo){
		$this->qnt_ativo = $qnt_ativo;
	}

	public function getVlr_ativo(){
		return $this->vlr_ativo;
	}

	public function setVlr_ativo($vlr_ativo){
		$this->vlr_ativo = $vlr_ativo;
	}

	public function getVlr_total(){
		return $this->vlr_total;
	}

	public function setVlr_total($vlr_total){
		$this->vlr_total = $vlr_total;
	}

	public function getDt_moviment(){
		return $this->dt_moviment;
	}

	public function setDt_moviment($dt_moviment){
		$this->dt_moviment = $dt_moviment;
	}

	public function getDt_registro(){
		return $this->dt_registro;
	}

	public function setDt_registro($dt_registro){
		$this->dt_registro = $dt_registro;
	}

    
   

    




}