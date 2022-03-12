<?php


class Movimentacoes{

    // private $id_moviements;
    private $idUser;
    private $idAtivo;

    private $tpOperation;
    
    private $qntAtivo;
    private $vlrAtivo;
    private $vlrTotal;
    private $dtaMoviment;
    private $dtaRecord;
	
	
	public function getIdUser(){
		return $this->idUser;
	}

	public function setIdUser($idUser){
		$this->idUser = $idUser;
	}

	public function getIdAtivo(){
		return $this->idAtivo;
	}

	public function setIdAtivo($idAtivo){
		$this->idAtivo = $idAtivo;
	}

	public function getTpOperation(){
		return $this->tpOperation;
	}

	public function setTpOperation($tpOperation){
		$this->tpOperation = $tpOperation;
	}

	public function getQntAtivo(){
		return $this->qntAtivo;
	}

	public function setQntAtivo($qntAtivo){
		$this->qntAtivo = $qntAtivo;
	}

	public function getVlrAtivo(){
		return $this->vlrAtivo;
	}

	public function setVlrAtivo($vlrAtivo){
		$this->vlrAtivo = $vlrAtivo;
	}

	public function getVlrTotal(){
		return $this->vlrTotal;
	}

	public function setVlrTotal($vlrTotal){
		$this->vlrTotal = $vlrTotal;
	}

	public function getDtaMoviment(){
		return $this->dtaMoviment;
	}

	public function setDtaMoviment($dtaMoviment){
		$this->dtaMoviment = $dtaMoviment;
	}

	public function getDtaRecord(){
		return $this->dtaRecord;
	}

	public function setDtaRecord($dtaRecord){
		$this->dtaRecord = $dtaRecord;
	}

    




}