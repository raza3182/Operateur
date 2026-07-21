<?php

namespace App\Controllers;

use App\Models\BaremeModel;
use App\Models\ClientModel;
use App\Models\ConfigurationModel;
use App\Models\OperationModel;
use App\Models\PrefixeModel;
use App\Models\TypeOperationModel;

class ClientController extends BaseController
{
    private const TYPE_DEPOT = 1;
    private const TYPE_RETRAIT = 2;
    private const TYPE_TRANSFERT = 3;

    protected $clientModel;
    protected $prefixeModel;
    protected $baremeModel;
    protected $operationModel;
    protected $configurationModel;
    protected $typeOperationModel;

    public function __construct()
    {
        $this->clientModel    = new ClientModel();
        $this->prefixeModel   = new PrefixeModel();
        $this->baremeModel    = new BaremeModel();
        $this->operationModel = new OperationModel();
        $this->configurationModel = new ConfigurationModel();
        $this->typeOperationModel = new TypeOperationModel();
    }

    /**
     * Traite l'insertion du numéro de téléphone depuis la page d'accueil.
     */
    public function checkSolde()
    {
        $telephone = trim($this->request->getPost('telephone'));

        if (empty($telephone) || !preg_match('/^[0-9]{9,10}$/', $telephone)) {
            return redirect()->back()->withInput()->with('error', 'Numéro de téléphone invalide.');
        }

        $prefixe = substr($telephone, 0, 3);

        if (!$this->prefixeModel->estValide($prefixe)) {
            return redirect()->back()->withInput()->with('error', "Ce numéro n'appartient pas à notre opérateur.");
        }

        $client = $this->clientModel->findOrCreate($telephone);

        session()->set([
            'idClient'  => $client['idClient'],
            'telephone' => $client['telephone'],
        ]);

        return redirect()->to('client/solde');
    }

    /**
     * Affiche le solde actuel du client connecté (via session).
     */
    public function solde()
    {
        if (!session()->get('idClient')) {
            return redirect()->to('/')->with('error', 'Veuillez saisir votre numéro de téléphone.');
        }

        $client = $this->clientModel->find(session()->get('idClient'));

        if (!$client) {
            session()->remove(['idClient', 'telephone']);
            return redirect()->to('/')->with('error', 'Client introuvable, veuillez réessayer.');
        }

        return view('Solde', [
            'client'     => $client,
            'operations' => $this->operationModel->getHistoriqueClient((int) $client['idClient']),
        ]);
    }

    public function logout()
    {
        session()->remove(['idClient', 'telephone']);
        return redirect()->to('/');
    }

    public function depot()
    {
        if (!session()->get('idClient')) {
            return redirect()->to('/');
        }

        return redirect()->to('client/solde#depot');
    }

    public function storeDepot()
    {
        $idClient = session()->get('idClient');

        if (!$idClient) {
            return redirect()->to('/');
        }

        $montant = (float) $this->request->getPost('montant');

        if ($montant <= 0) {
            return redirect()->back()->withInput()->with('error', 'Montant invalide.');
        }

        if (!$this->typeOperationModel->estActif(self::TYPE_DEPOT)) {
            return redirect()->back()->withInput()->with('error', 'Le depot est temporairement desactive.');
        }

        $db = db_connect();
        $db->transStart();

        $operationCreee = $this->operationModel->insert([
            'reference'       => $this->genererReference(),
            'idTypeOperation' => self::TYPE_DEPOT,
            'expediteur'      => null,
            'destinataire'    => $idClient,
            'montant'         => $montant,
            'frais'           => 0,
            'etat'            => 'SUCCES',
            'description'     => 'Depot',
        ]);

        if (!$operationCreee || !$this->clientModel->crediter((int) $idClient, $montant)) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Le depot a echoue.');
        }

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'Le depot a echoue.');
        }

        return redirect()->to('client/solde')->with('success', 'Depot effectue avec succes.');
    }

    public function retrait()
    {
        if (!session()->get('idClient')) {
            return redirect()->to('/');
        }

        $client = $this->clientModel->find(session()->get('idClient'));
        return view('retrait', ['client' => $client]);
    }

    public function storeRetrait()
    {
        $idClient = session()->get('idClient');

        if (!$idClient) {
            return redirect()->to('/');
        }

        $montant = (float) $this->request->getPost('montant');

        if ($montant <= 0) {
            return redirect()->back()->withInput()->with('error', 'Montant invalide.');
        }

        if (!$this->typeOperationModel->estActif(self::TYPE_RETRAIT)) {
            return redirect()->back()->withInput()->with('error', 'Le retrait est temporairement desactive.');
        }

        $frais = $this->baremeModel->getFrais(self::TYPE_RETRAIT, $montant);

        if ($frais === null) {
            return redirect()->back()->withInput()->with('error', 'Aucun bareme trouve pour ce montant.');
        }

        $client = $this->clientModel->find((int) $idClient);
        if (!$client || (float) $client['solde'] < ($montant + $frais)) {
            return redirect()->back()->withInput()->with('error', 'Solde insuffisant.');
        }

        $db = db_connect();
        $db->transStart();

        $operationCreee = $this->operationModel->insert([
            'reference'       => $this->genererReference(),
            'idTypeOperation' => self::TYPE_RETRAIT,
            'expediteur'      => $idClient,
            'destinataire'    => null,
            'montant'         => $montant,
            'frais'           => $frais,
            'etat'            => 'SUCCES',
            'description'     => 'Retrait',
        ]);

        if (!$operationCreee || !$this->clientModel->debiter((int) $idClient, $montant + $frais)) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Le retrait a echoue.');
        }

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'Le retrait a echoue.');
        }

        return redirect()->to('client/solde')->with('success', 'Retrait effectue avec succes.');
    }

    public function transfert()
    {
        if (!session()->get('idClient')) {
            return redirect()->to('/');
        }

        $client = $this->clientModel->find(session()->get('idClient'));
        return view('transfert', ['client' => $client]);
    }

    public function storeTransfert()
    {
        $idClient = session()->get('idClient');

        if (!$idClient) return redirect()->to('/');

        $saisieDestinataires = (string) ($this->request->getPost('destinataires') ?: $this->request->getPost('telephone_dest'));
        $destinataires = array_values(array_unique(array_filter(array_map(
            static fn (string $numero): string => preg_replace('/\D+/', '', $numero),
            preg_split('/[,;\n\r]+/', $saisieDestinataires) ?: []
        ))));
        $montantTotal = (float) $this->request->getPost('montant');
        $inclureFraisRetrait = (bool) $this->request->getPost('inclure_frais_retrait');

        if ($montantTotal <= 0 || empty($destinataires) || count($destinataires) > 20) {
            return redirect()->back()->withInput()->with('error', 'Saisissez un montant et entre 1 et 20 numéros destinataires.');
        }
        if (!$this->typeOperationModel->estActif(self::TYPE_TRANSFERT)) {
            return redirect()->back()->withInput()->with('error', 'Le transfert est temporairement désactivé.');
        }

        $expediteur = $this->clientModel->find((int) $idClient);
        $operateurSource = $expediteur ? $this->prefixeModel->getOperateurParTelephone($expediteur['telephone']) : null;
        if (!$expediteur || !$operateurSource) return redirect()->to('/')->with('error', 'Compte ou opérateur introuvable.');

        $nombreDestinataires = count($destinataires);
        $part = round($montantTotal / $nombreDestinataires, 2);
        $parts = array_fill(0, $nombreDestinataires, $part);
        $parts[$nombreDestinataires - 1] = round($montantTotal - array_sum(array_slice($parts, 0, -1)), 2);
        $transferts = [];
        $totalADebiter = 0.0;

        foreach ($destinataires as $index => $telephoneDest) {
            if (!preg_match('/^[0-9]{9,10}$/', $telephoneDest) || $telephoneDest === $expediteur['telephone']) {
                return redirect()->back()->withInput()->with('error', 'Chaque destinataire doit avoir un numéro valide et différent du vôtre.');
            }
            $operateurDestinataire = $this->prefixeModel->getOperateurParTelephone($telephoneDest);
            if (!$operateurDestinataire) return redirect()->back()->withInput()->with('error', 'Un préfixe destinataire n’est pas configuré.');

            $montantPart = $parts[$index];
            $fraisTransfert = $this->baremeModel->getFrais(self::TYPE_TRANSFERT, $montantPart);
            if ($fraisTransfert === null) return redirect()->back()->withInput()->with('error', 'Aucun barème de transfert ne couvre une des parts.');

            $estInteroperateur = (int) $operateurSource['idOperateur'] !== (int) $operateurDestinataire['idOperateur'];
            // Promotion : -10 % sur les frais de transfert au sein d'un même opérateur.
            // Le montant de la remise est conservé avec l'opération pour assurer sa traçabilité.
            $remisePromotion = !$estInteroperateur ? round($fraisTransfert * 0.10, 2) : 0.0;
            $fraisTransfert = round($fraisTransfert - $remisePromotion, 2);
            $commission = $estInteroperateur ? round($montantPart * $this->configurationModel->commissionInteroperateur() / 100, 2) : 0.0;
            // Les frais de retrait prépayés ne concernent jamais les autres opérateurs.
            $fraisRetrait = (!$estInteroperateur && $inclureFraisRetrait)
                ? $this->baremeModel->getFrais(self::TYPE_RETRAIT, $montantPart)
                : 0.0;
            if ($fraisRetrait === null) return redirect()->back()->withInput()->with('error', 'Aucun barème de retrait ne couvre une des parts.');

            $transferts[] = compact('telephoneDest', 'operateurDestinataire', 'montantPart', 'fraisTransfert', 'remisePromotion', 'commission', 'fraisRetrait');
            $totalADebiter += $montantPart + $fraisTransfert + $commission + $fraisRetrait;
        }

        $client = $this->clientModel->find((int) $idClient);
        if (!$client || (float) $client['solde'] < $totalADebiter) {
            return redirect()->back()->withInput()->with('error', 'Solde insuffisant pour le montant et tous les frais.');
        }

        $db = db_connect();
        $db->transStart();
        foreach ($transferts as $transfert) {
            $destinataire = $this->clientModel->findOrCreate($transfert['telephoneDest']);
            $operationCreee = $this->operationModel->insert([
                'reference' => $this->genererReference(), 'idTypeOperation' => self::TYPE_TRANSFERT,
                'expediteur' => $idClient, 'destinataire' => $destinataire['idClient'],
                'montant' => $transfert['montantPart'], 'frais' => $transfert['fraisTransfert'],
                'remisePromotion' => $transfert['remisePromotion'],
                'commissionInteroperateur' => $transfert['commission'], 'fraisRetraitInclus' => $transfert['fraisRetrait'],
                'idOperateurSource' => $operateurSource['idOperateur'], 'idOperateurDestinataire' => $transfert['operateurDestinataire']['idOperateur'],
                'etat' => 'SUCCES', 'description' => $nombreDestinataires > 1 ? 'Transfert multiple' : ($transfert['remisePromotion'] > 0 ? 'Transfert — promotion -10 % sur frais' : 'Transfert'),
            ]);

            if (!$operationCreee || !$this->clientModel->crediter((int) $destinataire['idClient'], $transfert['montantPart'])) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Le transfert a échoué.');
            }
        }

        if (!$this->clientModel->debiter((int) $idClient, $totalADebiter)) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Solde insuffisant pour le montant et tous les frais.');
        }
        $db->transComplete();
        if (!$db->transStatus()) return redirect()->back()->withInput()->with('error', 'Le transfert a échoué.');

        return redirect()->to('client/solde')->with('success', $nombreDestinataires . ' transfert(s) effectué(s). Total débité : ' . number_format($totalADebiter, 0, ',', ' ') . ' Ar.');
    }

    public function historique()
    {
        if (!session()->get('idClient')) {
            return redirect()->to('/');
        }

        return redirect()->to('client/solde#historique');
    }

    private function genererReference(): string
    {
        return 'OP-' . date('YmdHis') . '-' . random_int(1000, 9999);
    }
}
