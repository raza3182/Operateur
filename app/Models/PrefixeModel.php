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
    public function getPrefixesAvecOperateur(?int $idOperateur = null)
    {
        $builder = $this->select([
                        'prefixes.idPrefixe',
                        'prefixes.prefixe',
                        'prefixes.idOperateur',
                        'operateurs.nom AS operateur'
                    ])
                    ->join(
                        'operateurs',
                        'operateurs.idOperateur = prefixes.idOperateur',
                        'INNER'
                    );

        if ($idOperateur !== null) {
            $builder->where('prefixes.idOperateur', $idOperateur);
        }

        return $builder
            ->orderBy("CASE prefixes.prefixe WHEN '033' THEN 1 WHEN '032' THEN 2 WHEN '037' THEN 3 WHEN '034' THEN 4 WHEN '038' THEN 5 ELSE 6 END", '', false)
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
