<?php

namespace App\Models;

use CodeIgniter\Model;

class BaremeModel extends Model
{
    protected $table            = 'baremeFrais';
    protected $primaryKey       = 'idBaremeFrais';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['idTypeOperation', 'montantMin', 'montantMax', 'frais'];
    protected $useTimestamps    = false;

    /**
     * Trouve le montant des frais applicable pour un type d'opération et un montant donné.
     */
    public function getFrais(int $idTypeOperation, float $montant): ?float
    {
        $bareme = $this->where('idTypeOperation', $idTypeOperation)
                        ->where('montantMin <=', $montant)
                        ->where('montantMax >=', $montant)
                        ->first();

        return $bareme ? (float) $bareme['frais'] : null;
    }

    public function getAvecTypeOperation(): array
    {
        return $this->select('baremeFrais.*, typeOperations.nom AS typeNom')
            ->join('typeOperations', 'typeOperations.idTypeOperation = baremeFrais.idTypeOperation')
            ->orderBy('typeOperations.idTypeOperation', 'ASC')
            ->orderBy('baremeFrais.montantMin', 'ASC')
            ->findAll();
    }
}
