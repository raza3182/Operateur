
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
