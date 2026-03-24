<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DocumentiClass
 *
 * @author xf46260
 */
class DocumentiClass {
    private $id=null;
    private $idtipodoc=null;
    private $nome_doc=null;
    private $nome_file=null;
    
    function getId() {
        return $this->id;
    }
    function getIdtipodoc() {
        return $this->idtipodoc;
    }
    function getNome_doc() {
        return $this->nome_doc;
    }
    function getNome_file() {
        return $this->nome_file;
    }
    
    function setId($id) {
        return $this->id=$id;
    }
    function setIdtipodoc($idtipodoc) {
        return $this->idtipodoc=$idtipodoc;
    }
    function setNome_doc($nome_doc) {
        return $this->nome_doc=$nome_doc;
    }
    function setNome_file($nome_file) {
        return $this->nome_file=$nome_file;
    }
}

?>
