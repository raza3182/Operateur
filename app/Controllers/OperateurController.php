<?php

namespace App\Controllers;

use App\Models\BaremeModel;
use App\Models\ClientModel;
use App\Models\OperateurModel;
use App\Models\OperationModel;
use App\Models\PrefixeModel;
use App\Models\TypeOperationModel;

class OperateurController extends BaseController
{
    protected OperateurModel $operateurModel;
    protected PrefixeModel $prefixeModel;
    protected TypeOperationModel $typeOperationModel;
    protected BaremeModel $baremeModel;
    protected OperationModel $operationModel;
    protected ClientModel $clientModel;

    public function __construct()
    {
        $this->operateurModel     = new OperateurModel();
        $this->prefixeModel       = new PrefixeModel();
        $this->typeOperationModel = new TypeOperationModel();
        $this->baremeModel        = new BaremeModel();
        $this->operationModel     = new OperationModel();
        $this->clientModel        = new ClientModel();
    }

    public function index()
    {
        return view('operateur/dashboard', [
            'operateurs'      => $this->operateurModel->getAvecNombrePrefixes(),
            'prefixes'        => $this->prefixeModel->getPrefixesAvecOperateur(),
            'types'           => $this->typeOperationModel->getAvecNombreBaremes(),
            'baremes'         => $this->baremeModel->getAvecTypeOperation(),
            'gains'           => $this->operationModel->getGainsParType(),
            'totalGains'      => $this->operationModel->getTotalGains(),
            'comptesClients'  => $this->clientModel->getSituationComptes(),
        ]);
    }

    public function storeOperateur()
    {
        $nom = trim((string) $this->request->getPost('nom'));

        if ($nom === '') {
            return redirect()->back()->withInput()->with('error', 'Le nom de l\'opérateur est obligatoire.');
        }

        $this->operateurModel->insert(['nom' => $nom]);

        return redirect()->to('operateur')->with('success', 'Opérateur ajouté.');
    }

    public function storePrefixe()
    {
        $prefixe = preg_replace('/\D+/', '', (string) $this->request->getPost('prefixe'));
        $idOperateur = (int) $this->request->getPost('idOperateur');

        if (!preg_match('/^[0-9]{3}$/', $prefixe) || !$this->operateurModel->find($idOperateur)) {
            return redirect()->back()->withInput()->with('error', 'Préfixe ou opérateur invalide.');
        }

        if ($this->prefixeModel->existePrefixe($prefixe)) {
            return redirect()->back()->withInput()->with('error', 'Ce préfixe existe déjà.');
        }

        $this->prefixeModel->insert([
            'prefixe'     => $prefixe,
            'idOperateur' => $idOperateur,
        ]);

        return redirect()->to('operateur')->with('success', 'Préfixe ajouté.');
    }

    public function storeTypeOperation()
    {
        $nom = strtoupper(trim((string) $this->request->getPost('nom')));

        if ($nom === '') {
            return redirect()->back()->withInput()->with('error', 'Le nom du type d\'opération est obligatoire.');
        }

        $existe = $this->typeOperationModel->where('nom', $nom)->first();

        if ($existe) {
            return redirect()->back()->withInput()->with('error', 'Ce type d\'opération existe déjà.');
        }

        $this->typeOperationModel->insert([
            'nom'   => $nom,
            'actif' => 1,
        ]);

        return redirect()->to('operateur')->with('success', 'Type d\'opération ajouté.');
    }

    public function toggleTypeOperation(int $idTypeOperation)
    {
        $type = $this->typeOperationModel->find($idTypeOperation);

        if (!$type) {
            return redirect()->to('operateur')->with('error', 'Type d\'opération introuvable.');
        }

        $this->typeOperationModel->update($idTypeOperation, [
            'actif' => (int) ($type['actif'] ?? 1) === 1 ? 0 : 1,
        ]);

        return redirect()->to('operateur')->with('success', 'Statut du type d\'opération modifié.');
    }

    public function storeBareme()
    {
        $idTypeOperation = (int) $this->request->getPost('idTypeOperation');
        $montantMin = (float) $this->request->getPost('montantMin');
        $montantMax = (float) $this->request->getPost('montantMax');
        $frais = (float) $this->request->getPost('frais');

        if (!$this->typeOperationModel->find($idTypeOperation) || $montantMin <= 0 || $montantMax < $montantMin || $frais < 0) {
            return redirect()->back()->withInput()->with('error', 'Barème invalide.');
        }

        $this->baremeModel->insert([
            'idTypeOperation' => $idTypeOperation,
            'montantMin'      => $montantMin,
            'montantMax'      => $montantMax,
            'frais'           => $frais,
        ]);

        return redirect()->to('operateur')->with('success', 'Barème ajouté.');
    }

    public function updateBareme(int $idBaremeFrais)
    {
        $bareme = $this->baremeModel->find($idBaremeFrais);

        if (!$bareme) {
            return redirect()->to('operateur')->with('error', 'Barème introuvable.');
        }

        $montantMin = (float) $this->request->getPost('montantMin');
        $montantMax = (float) $this->request->getPost('montantMax');
        $frais = (float) $this->request->getPost('frais');

        if ($montantMin <= 0 || $montantMax < $montantMin || $frais < 0) {
            return redirect()->back()->withInput()->with('error', 'Barème invalide.');
        }

        $this->baremeModel->update($idBaremeFrais, [
            'montantMin' => $montantMin,
            'montantMax' => $montantMax,
            'frais'      => $frais,
        ]);

        return redirect()->to('operateur')->with('success', 'Barème modifié.');
    }
}
