<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table = 'prefixes';

    protected $primaryKey = 'idPrefixe';

    protected $returnType = 'array';

    protected $allowedFields = [
        'prefixe',
        'idOperateur'
    ];


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
}