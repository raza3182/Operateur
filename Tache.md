1- SQLite  est embarque via .env
2-Base.db dans writable/database
3-Bootstrap dans le projet /assets/bootstrap
  *Lien pour les vues:<?php
<link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
<script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

4- insertion de base:
sqlite3 writable/database/base.db < base.sql