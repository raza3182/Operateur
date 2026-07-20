<?php

namespace App\Controllers;

use App\Models\BaremeModel;
use App\Models\ClientModel;
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
    protected $typeOperationModel;

    public function __construct()
    {
        $this->clientModel    = new ClientModel();
        $this->prefixeModel   = new PrefixeModel();
        $this->baremeModel    = new BaremeModel();
        $this->operationModel = new OperationModel();
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

        return view('Solde', ['client' => $client]);
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

        $client = $this->clientModel->find(session()->get('idClient'));
        return view('depot', ['client' => $client]);
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

        $this->clientModel->crediter((int) $idClient, $montant);
        $this->operationModel->insert([
            'reference'       => $this->genererReference(),
            'idTypeOperation' => self::TYPE_DEPOT,
            'expediteur'      => null,
            'destinataire'    => $idClient,
            'montant'         => $montant,
            'frais'           => 0,
            'etat'            => 'SUCCES',
            'description'     => 'Depot',
        ]);

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

        $db = db_connect();
        $db->transStart();

        if (!$this->clientModel->debiter((int) $idClient, $montant + $frais)) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Solde insuffisant.');
        }

        $this->operationModel->insert([
            'reference'       => $this->genererReference(),
            'idTypeOperation' => self::TYPE_RETRAIT,
            'expediteur'      => $idClient,
            'destinataire'    => null,
            'montant'         => $montant,
            'frais'           => $frais,
            'etat'            => 'SUCCES',
            'description'     => 'Retrait',
        ]);

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

        if (!$idClient) {
            return redirect()->to('/');
        }

        $telephoneDest = preg_replace('/\D+/', '', (string) $this->request->getPost('telephone_dest'));
        $montant = (float) $this->request->getPost('montant');

        if ($montant <= 0 || !preg_match('/^[0-9]{9,10}$/', $telephoneDest)) {
            return redirect()->back()->withInput()->with('error', 'Informations de transfert invalides.');
        }

        if (!$this->typeOperationModel->estActif(self::TYPE_TRANSFERT)) {
            return redirect()->back()->withInput()->with('error', 'Le transfert est temporairement desactive.');
        }

        if ($telephoneDest === session()->get('telephone')) {
            return redirect()->back()->withInput()->with('error', 'Le destinataire doit etre different du compte courant.');
        }

        if (!$this->prefixeModel->estValide(substr($telephoneDest, 0, 3))) {
            return redirect()->back()->withInput()->with('error', "Ce numero n'appartient pas a notre operateur.");
        }

        $frais = $this->baremeModel->getFrais(self::TYPE_TRANSFERT, $montant);

        if ($frais === null) {
            return redirect()->back()->withInput()->with('error', 'Aucun bareme trouve pour ce montant.');
        }

        $destinataire = $this->clientModel->findOrCreate($telephoneDest);
        $db = db_connect();
        $db->transStart();

        if (!$this->clientModel->debiter((int) $idClient, $montant + $frais)) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Solde insuffisant.');
        }

        $this->clientModel->crediter((int) $destinataire['idClient'], $montant);
        $this->operationModel->insert([
            'reference'       => $this->genererReference(),
            'idTypeOperation' => self::TYPE_TRANSFERT,
            'expediteur'      => $idClient,
            'destinataire'    => $destinataire['idClient'],
            'montant'         => $montant,
            'frais'           => $frais,
            'etat'            => 'SUCCES',
            'description'     => 'Transfert',
        ]);

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'Le transfert a echoue.');
        }

        return redirect()->to('client/solde')->with('success', 'Transfert effectue avec succes.');
    }

    public function historique()
    {
        if (!session()->get('idClient')) {
            return redirect()->to('/');
        }

        $idClient = session()->get('idClient');
        $client   = $this->clientModel->find($idClient);
        $operations = $this->operationModel->getHistoriqueClient($idClient);

        return view('historique', [
            'client'     => $client,
            'operations' => $operations,
        ]);
    }

    private function genererReference(): string
    {
        return 'OP-' . date('YmdHis') . '-' . random_int(1000, 9999);
    }
}
