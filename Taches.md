# Gestion des tâches

## Livraison v1

### ETU004143

- Mise en place du projet CodeIgniter 4 avec SQLite embarqué.
- Création du fichier unique `base.sql` avec tables, vues et données de test.
- Création des tables opérateurs, préfixes, types d'opérations, barèmes, clients et opérations.
- Mise en place de la page d'accueil client.
- Login automatique avec numéro de téléphone et création automatique du client si le numéro est valide.
- Affichage du solde client.
- Fonctionnalité de dépôt automatique.

### ETU004059

- Création des types d'opérations dépôt, retrait et transfert.
- Gestion des barèmes de frais par tranche de montant pour retrait et transfert.
- Fonctionnalité de retrait avec calcul automatique des frais.
- Fonctionnalité de transfert avec calcul automatique des frais et création automatique du destinataire.
- Affichage de l'historique des opérations client.
- Création de l'espace opérateur pour configurer les préfixes, types d'opérations et barèmes.
- Ajout du choix de l'opérateur avant l'accès à l'espace opérateur.
- Création d'un espace dédié par opérateur avec ses préfixes, ses comptes clients et ses gains filtrés.
- Ajout des situations opérateur : gains via frais et comptes clients.

## Livraison v2

### ETU004143
- [ ] **Configuration %** en plus de commissions pour les transferts vers les autres opérateurs 
    - Creation des view 
        . vue_comptes_clients
        . vue_gains_frais
- [ ] Situation gain via les différents frais” , séparer opérateur et autres opérateurs



### ETU004059
- [ ] Situation des montants à envoyer à chaque opérateur
- [ ] Option inclure frais de retrait lors de l’envoi
il n’y a pas de frais de retrait pour les autres opérateurs
- [ ] Envoi multiple vers plusieurs numéros ( divisé le montant pour chaque numéro)
même opérateur uniquement
