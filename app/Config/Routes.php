<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', function() {
    return view('test_form');
});


// Page d'accueil (déjà faite par votre binôme) — saisie du numéro
//$routes->get('/', 'Home::index');

// Traitement de la saisie du numéro de téléphone
$routes->post('client/check', 'ClientController::checkSolde');

// Affichage du solde après connexion
$routes->get('client/solde', 'ClientController::solde');

// Déconnexion (vide la session)
$routes->get('client/logout', 'ClientController::logout');

$routes->get('client/depot', 'ClientController::depot');
$routes->post('client/depot', 'ClientController::storeDepot');

$routes->get('client/retrait', 'ClientController::retrait');
$routes->post('client/retrait', 'ClientController::storeRetrait');

$routes->get('client/transfert', 'ClientController::transfert');
$routes->post('client/transfert', 'ClientController::storeTransfert');

$routes->get('client/historique', 'ClientController::historique');