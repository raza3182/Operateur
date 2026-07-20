<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\PrefixeModel;

class ClientController extends BaseController
{
    protected $clientModel;
    protected $prefixeModel;

    public function __construct()
    {
        $this->clientModel  = new ClientModel();
        $this->prefixeModel = new PrefixeModel();
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



public function retrait()
{
    if (!session()->get('idClient')) {
        return redirect()->to('/');
    }
    $client = $this->clientModel->find(session()->get('idClient'));
    return view('retrait', ['client' => $client]);
}



public function transfert()
{
    if (!session()->get('idClient')) {
        return redirect()->to('/');
    }
    $client = $this->clientModel->find(session()->get('idClient'));
    return view('transfert', ['client' => $client]);
}


public function historique()
{
    if (!session()->get('idClient')) {
        return redirect()->to('/');
    }
    
}
}