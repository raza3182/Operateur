PRAGMA foreign_keys = OFF;

DROP VIEW IF EXISTS vue_gains_frais;
DROP VIEW IF EXISTS vue_comptes_clients;
DROP VIEW IF EXISTS promotions;
DROP TABLE IF EXISTS operations;
DROP TABLE IF EXISTS configurations;
DROP TABLE IF EXISTS clients;
DROP TABLE IF EXISTS baremeFrais;
DROP TABLE IF EXISTS typeOperations;
DROP TABLE IF EXISTS prefixes;
DROP TABLE IF EXISTS operateurs;

PRAGMA foreign_keys = ON;

CREATE TABLE operateurs (
    idOperateur INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL
);

CREATE TABLE prefixes (
    idPrefixe INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe TEXT NOT NULL UNIQUE,
    idOperateur INTEGER NOT NULL,

    FOREIGN KEY (idOperateur)
        REFERENCES operateurs(idOperateur)
);

CREATE TABLE typeOperations (
    idTypeOperation INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL UNIQUE,
    actif INTEGER NOT NULL DEFAULT 1 CHECK (actif IN (0, 1))
);

CREATE TABLE baremeFrais (
    idBaremeFrais INTEGER PRIMARY KEY AUTOINCREMENT,
    idTypeOperation INTEGER NOT NULL,
    montantMin REAL NOT NULL,
    montantMax REAL NOT NULL,
    frais REAL NOT NULL,

    FOREIGN KEY (idTypeOperation)
        REFERENCES typeOperations(idTypeOperation)
);

CREATE TABLE clients (
    idClient INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    telephone TEXT NOT NULL UNIQUE,
    solde REAL NOT NULL DEFAULT 0 CHECK (solde >= 0)
);

CREATE TABLE operations (
    idOperation INTEGER PRIMARY KEY AUTOINCREMENT,

    reference TEXT NOT NULL UNIQUE,

    idTypeOperation INTEGER NOT NULL,

    expediteur INTEGER,
    destinataire INTEGER,

    montant REAL NOT NULL CHECK (montant > 0),

    frais REAL NOT NULL DEFAULT 0,

    remisePromotion REAL NOT NULL DEFAULT 0,

    commissionInteroperateur REAL NOT NULL DEFAULT 0,

    fraisRetraitInclus REAL NOT NULL DEFAULT 0,

    idOperateurSource INTEGER,
    idOperateurDestinataire INTEGER,

    etat TEXT NOT NULL DEFAULT 'SUCCES',

    description TEXT,

    dateOperation DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (idTypeOperation)
        REFERENCES typeOperations(idTypeOperation),

    FOREIGN KEY (expediteur)
        REFERENCES clients(idClient),

    FOREIGN KEY (destinataire)
        REFERENCES clients(idClient),

    FOREIGN KEY (idOperateurSource)
        REFERENCES operateurs(idOperateur),

    FOREIGN KEY (idOperateurDestinataire)
        REFERENCES operateurs(idOperateur)
);

CREATE TABLE configurations (
    cle TEXT PRIMARY KEY,
    valeur TEXT NOT NULL
);

-- ======================================
-- VUE : Situation des comptes clients
-- ======================================
CREATE VIEW vue_comptes_clients AS
SELECT
    operateurs.idOperateur,
    operateurs.nom AS operateur,
    clients.idClient,
    clients.nom,
    clients.telephone,
    clients.solde,
    COUNT(operations.idOperation) AS nombreOperations
FROM operateurs
JOIN prefixes
    ON prefixes.idOperateur = operateurs.idOperateur
JOIN clients
    ON SUBSTR(clients.telephone, 1, 3) = prefixes.prefixe
LEFT JOIN operations
    ON operations.expediteur = clients.idClient
    OR operations.destinataire = clients.idClient
GROUP BY
    operateurs.idOperateur,
    operateurs.nom,
    clients.idClient,
    clients.nom,
    clients.telephone,
    clients.solde;

-- ======================================
-- VUE : Gains via les frais
-- ======================================
CREATE VIEW vue_gains_frais AS
SELECT
    operateurs.idOperateur,
    operateurs.nom AS operateur,
    typeOperations.nom AS typeOperation,
    COUNT(operations.idOperation) AS nombreOperations,
    COALESCE(SUM(operations.frais), 0) AS totalFrais
FROM operateurs
LEFT JOIN prefixes
    ON prefixes.idOperateur = operateurs.idOperateur
LEFT JOIN clients
    ON SUBSTR(clients.telephone, 1, 3) = prefixes.prefixe
LEFT JOIN operations
    ON operations.expediteur = clients.idClient
LEFT JOIN typeOperations
    ON operations.idTypeOperation = typeOperations.idTypeOperation
WHERE typeOperations.nom IN ('RETRAIT', 'TRANSFERT')
GROUP BY
    operateurs.idOperateur,
    operateurs.nom,
    typeOperations.idTypeOperation,
    typeOperations.nom;


-- ======================================
-- VUE : Promotions de transfert interne
-- ======================================
-- La promotion réduit les frais, sans modifier le solde directement.
CREATE VIEW promotions AS
SELECT
    operations.idOperation,
    operations.idTypeOperation,
    operations.expediteur AS idClient,
    operations.destinataire AS idClientDestinataire,
    operations.idOperateurSource,
    operations.idOperateurDestinataire,
    operations.frais + operations.remisePromotion AS fraisAvantPromotion,
    operations.remisePromotion AS montantRemise,
    operations.frais AS fraisAppliques,
    operations.dateOperation
FROM operations
WHERE operations.idTypeOperation = 3
    AND operations.idOperateurSource = operations.idOperateurDestinataire
    AND operations.remisePromotion > 0;
-- ======================================
-- TABLE : CONFIGURATIONS
-- ======================================
CREATE TABLE configurations (
    cle TEXT PRIMARY KEY,
    valeur TEXT NOT NULL
);





-- ============================================
-- Script de données de test
-- ============================================

-- 1. Opérateurs
INSERT INTO operateurs (nom) VALUES ('Airtel Money');
INSERT INTO operateurs (nom) VALUES ('Orange Money');
INSERT INTO operateurs (nom) VALUES ('MVola');

-- Commission prélevée lorsque le destinataire appartient à un autre opérateur.
INSERT INTO configurations (cle, valeur) VALUES ('commission_transfert_interoperateur', '2');

-- 2. Préfixes valables
INSERT INTO prefixes (prefixe, idOperateur) VALUES ('033', 1); -- Airtel Money
INSERT INTO prefixes (prefixe, idOperateur) VALUES ('032', 2); -- Orange Money
INSERT INTO prefixes (prefixe, idOperateur) VALUES ('037', 2); -- Orange Money
INSERT INTO prefixes (prefixe, idOperateur) VALUES ('034', 3); -- MVola
INSERT INTO prefixes (prefixe, idOperateur) VALUES ('038', 3); -- MVola

-- 3. Types d'opérations
INSERT INTO typeOperations (nom, actif) VALUES ('DEPOT', 1);
INSERT INTO typeOperations (nom, actif) VALUES ('RETRAIT', 1);
INSERT INTO typeOperations (nom, actif) VALUES ('TRANSFERT', 1);

-- 4. Barèmes de frais (exemple du sujet, appliqué au RETRAIT idTypeOperation=2)
INSERT INTO baremeFrais (idTypeOperation, montantMin, montantMax, frais) VALUES (2, 100, 1000, 50);
INSERT INTO baremeFrais (idTypeOperation, montantMin, montantMax, frais) VALUES (2, 1001, 5000, 50);
INSERT INTO baremeFrais (idTypeOperation, montantMin, montantMax, frais) VALUES (2, 5001, 10000, 100);
INSERT INTO baremeFrais (idTypeOperation, montantMin, montantMax, frais) VALUES (2, 10001, 25000, 200);
INSERT INTO baremeFrais (idTypeOperation, montantMin, montantMax, frais) VALUES (2, 25001, 50000, 400);
INSERT INTO baremeFrais (idTypeOperation, montantMin, montantMax, frais) VALUES (2, 50001, 100000, 800);
INSERT INTO baremeFrais (idTypeOperation, montantMin, montantMax, frais) VALUES (2, 100001, 250000, 1500);
INSERT INTO baremeFrais (idTypeOperation, montantMin, montantMax, frais) VALUES (2, 250001, 500000, 1500);
INSERT INTO baremeFrais (idTypeOperation, montantMin, montantMax, frais) VALUES (2, 500001, 1000000, 2500);
INSERT INTO baremeFrais (idTypeOperation, montantMin, montantMax, frais) VALUES (2, 1000001, 2000000, 3000);

-- Barèmes pour TRANSFERT (idTypeOperation=3) — mêmes tranches, à ajuster si besoin
INSERT INTO baremeFrais (idTypeOperation, montantMin, montantMax, frais) VALUES (3, 100, 1000, 50);
INSERT INTO baremeFrais (idTypeOperation, montantMin, montantMax, frais) VALUES (3, 1001, 5000, 50);
INSERT INTO baremeFrais (idTypeOperation, montantMin, montantMax, frais) VALUES (3, 5001, 10000, 100);
INSERT INTO baremeFrais (idTypeOperation, montantMin, montantMax, frais) VALUES (3, 10001, 25000, 200);
INSERT INTO baremeFrais (idTypeOperation, montantMin, montantMax, frais) VALUES (3, 25001, 50000, 400);
INSERT INTO baremeFrais (idTypeOperation, montantMin, montantMax, frais) VALUES (3, 50001, 100000, 800);
INSERT INTO baremeFrais (idTypeOperation, montantMin, montantMax, frais) VALUES (3, 100001, 250000, 1500);
INSERT INTO baremeFrais (idTypeOperation, montantMin, montantMax, frais) VALUES (3, 250001, 500000, 1500);
INSERT INTO baremeFrais (idTypeOperation, montantMin, montantMax, frais) VALUES (3, 500001, 1000000, 2500);
INSERT INTO baremeFrais (idTypeOperation, montantMin, montantMax, frais) VALUES (3, 1000001, 2000000, 3000);

-- 5. Clients de test
INSERT INTO clients (nom, telephone, solde) VALUES ('Rakoto Jean', '0331234567', 50000);
INSERT INTO clients (nom, telephone, solde) VALUES ('Rasoa Marie', '0381234567', 15000);
INSERT INTO clients (nom, telephone, solde) VALUES ('Randria Paul', '0371234567', 0);

-- 6. Quelques opérations d'exemple (historique)
INSERT INTO operations (reference, idTypeOperation, expediteur, destinataire, montant, frais, etat, description)
VALUES ('OP-TEST-001', 1, NULL, 1, 50000, 0, 'SUCCES', 'Dépôt initial');

INSERT INTO operations (reference, idTypeOperation, expediteur, destinataire, montant, frais, etat, description)
VALUES ('OP-TEST-002', 3, 1, 2, 5000, 50, 'SUCCES', 'Transfert vers Rasoa Marie');
