
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
    nom TEXT NOT NULL UNIQUE
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

    etat TEXT NOT NULL DEFAULT 'SUCCES',

    description TEXT,

    dateOperation DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (idTypeOperation)
        REFERENCES typeOperations(idTypeOperation),

    FOREIGN KEY (expediteur)
        REFERENCES clients(idClient),

    FOREIGN KEY (destinataire)
        REFERENCES clients(idClient)
);

-- ============================================
-- Script de données de test
-- ============================================

-- 1. Opérateurs
INSERT INTO operateurs (nom) VALUES ('Telma');
INSERT INTO operateurs (nom) VALUES ('Orange');

-- 2. Préfixes valables
INSERT INTO prefixes (prefixe, idOperateur) VALUES ('033', 1); -- Telma
INSERT INTO prefixes (prefixe, idOperateur) VALUES ('038', 1); -- Telma
INSERT INTO prefixes (prefixe, idOperateur) VALUES ('037', 2); -- Orange
INSERT INTO prefixes (prefixe, idOperateur) VALUES ('032', 2); -- Orange

-- 3. Types d'opérations
INSERT INTO typeOperations (nom) VALUES ('DEPOT');
INSERT INTO typeOperations (nom) VALUES ('RETRAIT');
INSERT INTO typeOperations (nom) VALUES ('TRANSFERT');

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
