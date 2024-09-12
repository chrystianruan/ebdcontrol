<?php

namespace App\Http\DTOs;

class PresencaPessoaDTO
{
    private int $pessoaId;
    private int $funcaoId;
    private int $salaId;
    private bool $presente;
    private string $createdAt;
    private string $updatedAt;
    private int $tipoPresencaId;
    private string $dataInicio;
    private string $dataFim;
    private array $orderBy;
    private string $groupBy;
    private string $count;

    public function __construct() {

    }

    public function getPessoaId(): int {
        return $this->pessoaId;
    }
    public function setPessoaId(int $pessoaId): void {
        $this->pessoaId = $pessoaId;
    }
    public function getFuncaoId(): int {
        return $this->funcaoId;
    }
    public function setFuncaoId(int $funcaoId): void {
        $this->funcaoId = $funcaoId;
    }
    public function getSalaId(): int {
        return $this->salaId;
    }
    public function setSalaId(int $salaId): void {
        $this->salaId = $salaId;
    }
    public function isPresente(): bool {
        return $this->presente;
    }
    public function setPresente(bool $presente): void {
        $this->presente = $presente;
    }
    public function getCreatedAt(): string {
        return $this->createdAt;
    }
    public function setCreatedAt(string $createdAt): void {
        $this->createdAt = $createdAt;
    }
    public function getUpdatedAt(): string {
        return $this->updatedAt;
    }
    public function setUpdatedAt(string $updatedAt): void {
        $this->updatedAt = $updatedAt;
    }
    public function getTipoPresencaId(): int {
        return $this->tipoPresencaId;
    }
    public function setTipoPresencaId(int $tipoPresencaId): void {
        $this->tipoPresencaId = $tipoPresencaId;
    }
    public function getOrderBy(): array {
        return $this->orderBy;
    }
    public function setOrderBy(array $orderBy): void {
        $this->orderBy = $orderBy;
    }
    public function getGroupBy(): string {
        return $this->groupBy;
    }
    public function setGroupBy(string $groupBy): void {
        $this->groupBy = $groupBy;
    }
    public function getCount(): string {
        return $this->count;
    }
    public function setCount(string $count): void {
        $this->count = $count;
    }
    public function getDataInicio(): string {
        return $this->dataInicio;
    }
    public function setDataInicio(string $dataInicio): void {
        $this->dataInicio = $dataInicio;
    }
    public function getDataFim(): string {
        return $this->dataFim;
    }
    public function setDataFim(string $dataFim): void {
        $this->dataFim = $dataFim;
    }
}
