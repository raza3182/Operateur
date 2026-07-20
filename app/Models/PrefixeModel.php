<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table            = 'prefixes';
    protected $primaryKey       = 'idPrefixe';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['prefixe', 'idOperateur'];
    protected $useTimestamps    = false;

    /**
     * Vérifie si un préfixe (3 premiers chiffres du numéro) est valide.
     */
    public function estValide(string $prefixe): bool
    {
        return (bool) $this->where('prefixe', $prefixe)->first();
    }
}