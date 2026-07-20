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

        if ($prefixe === '' || $numero === '') {
            return redirect()->back()->with('erreur', 'Veuillez choisir un opérateur et saisir un numéro.');
        }

        // Construction du numéro complet, sans espaces ni caractères spéciaux.
        $telephone = $prefixe . $numero;
        $clientModel = new ClientModel();
        $client = $clientModel
                  ->chercherParTelephone($telephone);
        // CAS A : Client trouvé

        if($client)
        {
            return view(
                'client',
                [
                    'client'=>$client
                ]
            );
        }

        // CAS B : Client inexistant
        else
        {
            return redirect()
                   ->back()
                   ->with(
                       'erreur',
                       'Ce numéro n\'est pas encore enregistré. Veuillez contacter votre opérateur.'
                   );
        }

    }

}
