<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
//$routes->get('/', 'Home::index');
$routes->get('/', 'Accueil::index');

$routes->post(
    '/connexion',
    'Accueil::connexion'
);