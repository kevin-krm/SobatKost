<?php

class AturanKost {
    private $id_aturan;
    private $judul_aturan;
    private $deskripsi_aturan;
    private $created_at;
    private $updated_at;

    public function getIdAturan() { return $this->id_aturan; }
    public function setIdAturan($id_aturan) { $this->id_aturan = $id_aturan; }

    public function getJudulAturan() { return $this->judul_aturan; }
    public function setJudulAturan($judul_aturan) { $this->judul_aturan = $judul_aturan; }

    public function getDeskripsiAturan() { return $this->deskripsi_aturan; }
    public function setDeskripsiAturan($deskripsi_aturan) { $this->deskripsi_aturan = $deskripsi_aturan; }

    public function getCreatedAt() { return $this->created_at; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }

    public function getUpdatedAt() { return $this->updated_at; }
    public function setUpdatedAt($updated_at) { $this->updated_at = $updated_at; }
}
?>