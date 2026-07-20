<?php

namespace App\Controllers;

use App\Models\PrefixeModel;
use App\Models\ClientModel;


class Accueil extends BaseController
{

    public function index()
    {
        $prefixeModel = new PrefixeModel();
        $data = [
            'titre' => 'Mobile Money',

            'prefixes' => $prefixeModel
                         ->getPrefixesAvecOperateur()

        ];
        return view('accueil', $data);

    }

    public function connexion()
    {
        $prefixe = preg_replace('/\D+/', '', (string) $this->request->getPost('prefixe'));
        $numero = preg_replace('/\D+/', '', (string) $this->request->getPost('numero'));
        $prefixeModel = new PrefixeModel();

        if ($prefixe === '' || $numero === '' || !$prefixeModel->estValide($prefixe)) {
            return redirect()->back()->with('erreur', 'Veuillez choisir un opérateur valide et saisir un numéro.');
        }

        // Construction du numéro complet, sans espaces ni caractères spéciaux.
        $telephone = $prefixe . $numero;

        if (!preg_match('/^[0-9]{9,10}$/', $telephone)) {
            return redirect()->back()->with('erreur', 'Numéro de téléphone invalide.');
        }

        $clientModel = new ClientModel();
        $client = $clientModel
                  ->findOrCreate($telephone);

        if($client)
        {
            session()->set([
                'idClient'  => $client['idClient'],
                'telephone' => $client['telephone'],
            ]);

            return redirect()->to('client/solde');
        }

        return redirect()
               ->back()
               ->with('erreur', 'Connexion impossible. Veuillez réessayer.');
    }

}
