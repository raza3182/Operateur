<?php

namespace App\Models;

use CodeIgniter\Model;

class OperateurModel extends Model
{
    protected $table            = 'operateurs';
    protected $primaryKey       = 'idOperateur';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nom'];
    protected $useTimestamps    = false;

    public function getAvecNombrePrefixes(): array
    {
        return $this->select('operateurs.*, COUNT(prefixes.idPrefixe) AS nombrePrefixes')
            ->join('prefixes', 'prefixes.idOperateur = operateurs.idOperateur', 'left')
            ->groupBy('operateurs.idOperateur')
            ->orderBy('operateurs.nom', 'ASC')
            ->findAll();
    }
}
