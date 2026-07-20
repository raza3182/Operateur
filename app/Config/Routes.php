<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

//$routes->get('/', 'Home::index');
$routes->get('/', 'Accueil::index');

$routes->post(
    '/connexion',
    'Accueil::connexion'
);

$routes->get('/test-form', function () {
    return view('test_form');
});

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

$routes->get('operateur', 'OperateurController::index');
$routes->get('operateur/(:num)', 'OperateurController::show/$1');
$routes->post('operateur/operateurs', 'OperateurController::storeOperateur');
$routes->post('operateur/(:num)/prefixes', 'OperateurController::storePrefixe/$1');
$routes->post('operateur/(:num)/types', 'OperateurController::storeTypeOperation/$1');
$routes->post('operateur/(:num)/types/(:num)/toggle', 'OperateurController::toggleTypeOperation/$1/$2');
$routes->post('operateur/(:num)/baremes', 'OperateurController::storeBareme/$1');
$routes->post('operateur/(:num)/baremes/(:num)', 'OperateurController::updateBareme/$1/$2');
$routes->post('operateur/(:num)/commission-interoperateur', 'OperateurController::updateCommissionInteroperateur/$1');
