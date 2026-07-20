<?php

namespace App\Models;

use CodeIgniter\Model;

class OperationModel extends Model
{
    protected $table            = 'operations';
    protected $primaryKey       = 'idOperation';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'reference', 'idTypeOperation', 'expediteur', 'destinataire',
        'montant', 'frais', 'etat', 'description',
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'dateOperation';

    /**
     * Récupère l'historique des opérations d'un client (envoyées ou reçues),
     * avec le nom du type d'opération.
     */
    public function getHistoriqueClient(int $idClient)
    {
        return $this->select('operations.*, typeOperations.nom as typeNom')
            ->join('typeOperations', 'typeOperations.idTypeOperation = operations.idTypeOperation')
            ->groupStart()
                ->where('expediteur', $idClient)
                ->orWhere('destinataire', $idClient)
            ->groupEnd()
            ->orderBy('dateOperation', 'DESC')
            ->findAll();
    }

    public function getGainsParType(): array
    {
        return $this->select('typeOperations.nom AS typeNom, COUNT(operations.idOperation) AS nombreOperations, COALESCE(SUM(operations.frais), 0) AS totalFrais')
            ->join('typeOperations', 'typeOperations.idTypeOperation = operations.idTypeOperation')
            ->whereIn('typeOperations.nom', ['RETRAIT', 'TRANSFERT'])
            ->groupBy('typeOperations.idTypeOperation')
            ->orderBy('typeOperations.nom', 'ASC')
            ->findAll();
    }

    public function getTotalGains(): float
    {
        $result = $this->select('COALESCE(SUM(frais), 0) AS totalFrais')
            ->where('frais >', 0)
            ->first();

        return (float) ($result['totalFrais'] ?? 0);
    }
}
