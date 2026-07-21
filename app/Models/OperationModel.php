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
        'montant', 'frais', 'remisePromotion', 'commissionInteroperateur', 'fraisRetraitInclus', 'idOperateurSource',
        'idOperateurDestinataire', 'etat', 'description',
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

    public function getGainsParType(?int $idOperateur = null): array
    {
        $builder = $this->select('typeOperations.nom AS typeNom, COUNT(operations.idOperation) AS nombreOperations, COALESCE(SUM(operations.frais + operations.fraisRetraitInclus), 0) AS totalFrais')
            ->join('typeOperations', 'typeOperations.idTypeOperation = operations.idTypeOperation');

        if ($idOperateur !== null) {
            $builder
                ->join('clients', 'clients.idClient = operations.expediteur', 'inner')
                ->join('prefixes', 'prefixes.prefixe = SUBSTR(clients.telephone, 1, 3)', 'inner', false)
                ->where('prefixes.idOperateur', $idOperateur);
        }

        return $builder
            ->whereIn('typeOperations.nom', ['RETRAIT', 'TRANSFERT'])
            ->groupBy('typeOperations.idTypeOperation')
            ->orderBy('typeOperations.nom', 'ASC')
            ->findAll();
    }

    public function getTotalGains(?int $idOperateur = null): float
    {
        $builder = $this->select('COALESCE(SUM(operations.frais + operations.fraisRetraitInclus), 0) AS totalFrais');

        if ($idOperateur !== null) {
            $builder
                ->join('clients', 'clients.idClient = operations.expediteur', 'inner')
                ->join('prefixes', 'prefixes.prefixe = SUBSTR(clients.telephone, 1, 3)', 'inner', false)
                ->where('prefixes.idOperateur', $idOperateur);
        }

        $result = $builder->where('operations.frais >', 0)->first();

        return (float) ($result['totalFrais'] ?? 0);
    }

    /** Commissions reçues lorsqu'un autre opérateur envoie de l'argent ici. */
    public function getCommissionsInteroperateursRecues(int $idOperateur): float
    {
        $result = $this->select('COALESCE(SUM(commissionInteroperateur), 0) AS total')
            ->where('idOperateurDestinataire', $idOperateur)
            ->where('idOperateurSource !=', $idOperateur)
            ->first();
        return (float) ($result['total'] ?? 0);
    }

    /** Montants principaux que l'opérateur source doit régler à chaque partenaire. */
    public function getMontantsAEnvoyer(int $idOperateur): array
    {
        return $this->select('operateurs.nom AS operateur, COUNT(operations.idOperation) AS nombreTransferts, COALESCE(SUM(operations.montant), 0) AS montantAEnvoyer, COALESCE(SUM(operations.commissionInteroperateur), 0) AS commissions')
            ->join('operateurs', 'operateurs.idOperateur = operations.idOperateurDestinataire')
            ->where('operations.idOperateurSource', $idOperateur)
            ->where('operations.idOperateurDestinataire !=', $idOperateur)
            ->where('operations.idTypeOperation', 3)
            ->groupBy('operations.idOperateurDestinataire, operateurs.nom')
            ->orderBy('operateurs.nom', 'ASC')
            ->findAll();
    }
}
