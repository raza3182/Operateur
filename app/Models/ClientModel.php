<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table            = 'clients';
    protected $primaryKey       = 'idClient';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = ['nom', 'telephone', 'solde'];

    // Pas de timestamps dans cette table
    protected $useTimestamps    = false;

    // Validation
    protected $validationRules = [
        'nom'       => 'required|min_length[2]',
        'telephone' => 'required|min_length[9]|max_length[10]|is_unique[clients.telephone,idClient,{idClient}]',
        'solde'     => 'permit_empty|numeric',
    ];

    protected $validationMessages = [
        'telephone' => [
            'required'  => 'Le numéro de téléphone est obligatoire.',
            'is_unique' => 'Ce numéro est déjà enregistré.',
        ],
        'nom' => [
            'required' => 'Le nom du client est obligatoire.',
        ],
    ];

    /**
     * Recherche un client par son numéro de téléphone.
     */
    public function findByTelephone(string $telephone): ?array
    {
        return $this->where('telephone', $telephone)->first();
    }

    /**
     * Crée un client s'il n'existe pas, ou le retourne s'il existe déjà.
     * Nom temporaire généré automatiquement (pas d'inscription préalable).
     */
    public function findOrCreate(string $telephone): array
    {
        $client = $this->findByTelephone($telephone);

        if (!$client) {
            $this->insert([
                'nom'       => 'Client ' . $telephone,
                'telephone' => $telephone,
                'solde'     => 0,
            ]);
            $client = $this->findByTelephone($telephone);
        }

        return $client;
    }

    /**
     * Récupère uniquement le solde d'un client.
     */
    public function getSolde(int $idClient): float
    {
        $client = $this->find($idClient);
        return $client ? (float) $client['solde'] : 0;
    }

    public function getSituationComptes(): array
    {
        return $this->select('clients.*, COUNT(operations.idOperation) AS nombreOperations')
            ->join(
                'operations',
                'operations.expediteur = clients.idClient OR operations.destinataire = clients.idClient',
                'left',
                false
            )
            ->groupBy('clients.idClient')
            ->orderBy('clients.nom', 'ASC')
            ->findAll();
    }

    public function getSituationComptesParOperateur(int $idOperateur): array
    {
        return $this->select('clients.*, COUNT(operations.idOperation) AS nombreOperations')
            ->join('prefixes', 'prefixes.prefixe = SUBSTR(clients.telephone, 1, 3)', 'inner', false)
            ->join(
                'operations',
                'operations.expediteur = clients.idClient OR operations.destinataire = clients.idClient',
                'left',
                false
            )
            ->where('prefixes.idOperateur', $idOperateur)
            ->groupBy('clients.idClient')
            ->orderBy('clients.nom', 'ASC')
            ->findAll();
    }

    /**
     * Crédite le solde du client (dépôt, ou réception de transfert).
     */
    public function crediter(int $idClient, float $montant): bool
    {
        if ($montant <= 0) {
            return false;
        }

        return $this->db->query(
            'UPDATE clients SET solde = solde + ? WHERE idClient = ?',
            [$montant, $idClient]
        ) !== false && $this->db->affectedRows() === 1;
    }

    /**
     * Débite le solde du client (retrait, ou envoi de transfert).
     * Retourne false si solde insuffisant (respecte aussi le CHECK solde >= 0 en base).
     */
    public function debiter(int $idClient, float $montant): bool
    {
        if ($montant <= 0) {
            return false;
        }

        // La condition sur le solde rend le débit atomique et évite un solde négatif.
        return $this->db->query(
            'UPDATE clients SET solde = solde - ? WHERE idClient = ? AND solde >= ?',
            [$montant, $idClient, $montant]
        ) !== false && $this->db->affectedRows() === 1;
    }
}
