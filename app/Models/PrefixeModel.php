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
     * Récupérer tous les préfixes avec leur opérateur
     */
    public function getPrefixesAvecOperateur()
    {
        return $this->select([
                        'prefixes.idPrefixe',
                        'prefixes.prefixe',
                        'operateurs.nom AS operateur'
                    ])
                    ->join(
                        'operateurs',
                        'operateurs.idOperateur = prefixes.idOperateur',
                        'INNER'
                    )
                    ->orderBy('prefixes.prefixe', 'ASC')
                    ->findAll();
    }


    /**
     * Vérifier si un préfixe existe
     */
    public function existePrefixe($prefixe)
    {
        return $this->where('prefixe', $prefixe)
                    ->first();
    }

    /**
     * Vérifie si un préfixe (3 premiers chiffres du numéro) est valide.
     */
    public function estValide(string $prefixe): bool
    {
        return (bool) $this->where('prefixe', $prefixe)->first();
    }
}
