<?php

namespace App\Controllers;

use App\Models\BaremeModel;
use App\Models\ClientModel;
use App\Models\ConfigurationModel;
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
    protected ConfigurationModel $configurationModel;

    public function __construct()
    {
        $this->operateurModel     = new OperateurModel();
        $this->prefixeModel       = new PrefixeModel();
        $this->typeOperationModel = new TypeOperationModel();
        $this->baremeModel        = new BaremeModel();
        $this->operationModel     = new OperationModel();
        $this->clientModel        = new ClientModel();
        $this->configurationModel = new ConfigurationModel();
    }

    public function index()
    {
        return view('operateur/choix', [
            'operateurs' => $this->operateurModel->getAvecNombrePrefixes(),
        ]);
    }

    public function show(int $idOperateur)
    {
        return view('operateur/dashboard', [
            'operateurActuel' => $this->getOperateurOuRetour($idOperateur),
            'operateurs'      => $this->operateurModel->getAvecNombrePrefixes(),
            'prefixes'        => $this->prefixeModel->getPrefixesAvecOperateur($idOperateur),
            'types'           => $this->typeOperationModel->getAvecNombreBaremes(),
            'baremes'         => $this->baremeModel->getAvecTypeOperation(),
            'gains'           => $this->operationModel->getGainsParType($idOperateur),
            'totalGains'      => $this->operationModel->getTotalGains($idOperateur),
            'commissionInteroperateur' => $this->configurationModel->commissionInteroperateur(),
            'commissionsAutresOperateurs' => $this->operationModel->getCommissionsInteroperateursRecues($idOperateur),
            'montantsAEnvoyer' => $this->operationModel->getMontantsAEnvoyer($idOperateur),
            'comptesClients'  => $this->clientModel->getSituationComptesParOperateur($idOperateur),
        ]);
    }

    public function storeOperateur()
    {
        $nom = trim((string) $this->request->getPost('nom'));

        if ($nom === '') {
            return redirect()->back()->withInput()->with('error', 'Le nom de l\'opérateur est obligatoire.');
        }

        $idOperateur = $this->operateurModel->insert(['nom' => $nom]);

        return redirect()->to('operateur/' . $idOperateur)->with('success', 'Opérateur ajouté.');
    }

    public function storePrefixe(int $idOperateur)
    {
        $prefixe = preg_replace('/\D+/', '', (string) $this->request->getPost('prefixe'));

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

        return redirect()->to('operateur/' . $idOperateur)->with('success', 'Préfixe ajouté.');
    }

    public function storeTypeOperation(int $idOperateur)
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

        return redirect()->to('operateur/' . $idOperateur)->with('success', 'Type d\'opération ajouté.');
    }

    public function toggleTypeOperation(int $idOperateur, int $idTypeOperation)
    {
        $type = $this->typeOperationModel->find($idTypeOperation);

        if (!$type) {
            return redirect()->to('operateur/' . $idOperateur)->with('error', 'Type d\'opération introuvable.');
        }

        $this->typeOperationModel->update($idTypeOperation, [
            'actif' => (int) ($type['actif'] ?? 1) === 1 ? 0 : 1,
        ]);

        return redirect()->to('operateur/' . $idOperateur)->with('success', 'Statut du type d\'opération modifié.');
    }

    public function storeBareme(int $idOperateur)
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

        return redirect()->to('operateur/' . $idOperateur)->with('success', 'Barème ajouté.');
    }

    public function updateBareme(int $idOperateur, int $idBaremeFrais)
    {
        $bareme = $this->baremeModel->find($idBaremeFrais);

        if (!$bareme) {
            return redirect()->to('operateur/' . $idOperateur)->with('error', 'Barème introuvable.');
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

        return redirect()->to('operateur/' . $idOperateur)->with('success', 'Barème modifié.');
    }

    public function updateCommissionInteroperateur(int $idOperateur)
    {
        $pourcentage = (float) $this->request->getPost('pourcentage');
        if ($pourcentage < 0 || $pourcentage > 100) {
            return redirect()->back()->withInput()->with('error', 'Le pourcentage doit être compris entre 0 et 100.');
        }
        $this->configurationModel->definirCommissionInteroperateur($pourcentage);
        return redirect()->to('operateur/' . $idOperateur)->with('success', 'Commission interopérateur mise à jour.');
    }

    private function getOperateurOuRetour(int $idOperateur): array
    {
        $operateur = $this->operateurModel->find($idOperateur);

        if (!$operateur) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Opérateur introuvable.');
        }

        return $operateur;
    }
}
