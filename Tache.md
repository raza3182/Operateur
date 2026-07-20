1- SQLite  est embarque via .env
2-Base.db dans writable/database
3-Bootstrap dans le projet /assets/bootstrap
  *Lien pour les vues:<?php
<link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

4- insertion de base:
sqlite3 writable/database/base.db < base.sql

5-Creation de Client Controller 

6-Affichage du solde actuel du client apres login

7-Creation des types d'operation:transfert,retrait et depot