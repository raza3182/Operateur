<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index(): string
    {
        $db = \Config\Database::connect();

        // --- KPI: clients actifs et solde total ---
        $clientsRow = $db->table('clients')
            ->select('COUNT(*) as nb_actifs, COALESCE(SUM(solde), 0) as solde_total')
            ->where('est_actif', 1)
            ->get()->getRowArray();

        // --- KPI: gains totaux (somme des frais) ---
        $gainsTotal = (float) ($db->table('transactions')
            ->selectSum('frais', 'total')
            ->get()->getRowArray()['total'] ?? 0);

        // --- Gains par type d'operation ---
        $gainsParType = $db->table('transactions t')
            ->select('top.libelle, top.code, COALESCE(SUM(t.frais), 0) as gains, COUNT(t.id) as nb_operations')
            ->join('types_operations top', 'top.id = t.type_operation_id', 'right')
            ->groupBy('top.id')
            ->orderBy('top.id', 'asc')
            ->get()->getResultArray();

        // --- Barème de frais par type (pour la vue "escalier") ---
        $typesOperations = $db->table('types_operations')->orderBy('id', 'asc')->get()->getResultArray();

        $bareme = [];
        foreach ($typesOperations as $type) {
            $bareme[$type['code']] = [
                'libelle' => $type['libelle'],
                'tranches' => $db->table('tranches_frais')
                    ->where('type_operation_id', $type['id'])
                    ->orderBy('montant_min', 'asc')
                    ->get()->getResultArray(),
            ];
        }

        // --- Dernières transactions ---
        $dernieresTransactions = $db->table('transactions t')
            ->select('t.id, t.montant, t.frais, t.statut, t.date_creation, c.numero_telephone, top.libelle as type_libelle')
            ->join('clients c', 'c.id = t.client_id', 'left')
            ->join('types_operations top', 'top.id = t.type_operation_id', 'left')
            ->orderBy('t.id', 'desc')
            ->limit(8)
            ->get()->getResultArray();

        $data = [
            'nbClientsActifs'       => (int) ($clientsRow['nb_actifs'] ?? 0),
            'soldeTotal'            => (float) ($clientsRow['solde_total'] ?? 0),
            'gainsTotal'            => $gainsTotal,
            'gainsParType'          => $gainsParType,
            'bareme'                => $bareme,
            'dernieresTransactions' => $dernieresTransactions,
        ];

        return view('dashboard', $data);
    }
}
