<?php

namespace App\Models;

use CodeIgniter\Model;

class TypeOperationModel extends Model
{
    protected $table            = 'typeOperations';
    protected $primaryKey       = 'idTypeOperation';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nom', 'actif'];
    protected $useTimestamps    = false;

    public function getAvecNombreBaremes(): array
    {
        return $this->select('typeOperations.*, COUNT(baremeFrais.idBaremeFrais) AS nombreBaremes')
            ->join('baremeFrais', 'baremeFrais.idTypeOperation = typeOperations.idTypeOperation', 'left')
            ->groupBy('typeOperations.idTypeOperation')
            ->orderBy('typeOperations.idTypeOperation', 'ASC')
            ->findAll();
    }

    public function estActif(int $idTypeOperation): bool
    {
        $type = $this->find($idTypeOperation);

        return $type && (int) ($type['actif'] ?? 1) === 1;
    }
}
