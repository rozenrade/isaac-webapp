CREATE TABLE utilisateur(
    u_id VARCHAR(50),
    username VARCHAR(50),
    password VARCHAR(50),
    role LOGICAL,
    email VARCHAR(50),
    PRIMARY KEY(u_id)
);

CREATE TABLE synergie(
    synergy_id VARCHAR(50),
    item_id VARCHAR(50),
    syngergy_name VARCHAR(50),
    item_name VARCHAR(50),
    PRIMARY KEY(synergy_id, item_id)
);

CREATE TABLE item(
    item_id VARCHAR(50),
    name VARCHAR(50),
    filepath VARCHAR(50),
    PRIMARY KEY(item_id)
);

CREATE TABLE collection(
    item_id VARCHAR(50),
    col_id VARCHAR(50),
    item_name VARCHAR(50),
    collection_name VARCHAR(50),
    u_id VARCHAR(50) NOT NULL,
    PRIMARY KEY(item_id, col_id),
    FOREIGN KEY(u_id) REFERENCES utilisateur(u_id)
);

CREATE TABLE boss(
    boss_id VARCHAR(50),
    boss_name VARCHAR(50),
    filepath VARCHAR(50),
    PRIMARY KEY(boss_id)
);

CREATE TABLE personnage(
    character__id VARCHAR(50),
    character_name VARCHAR(50),
    filepath VARCHAR(50),
    PRIMARY KEY(character__id)
);

CREATE TABLE build(
    build_id VARCHAR(50),
    item_id VARCHAR(50),
    item_name VARCHAR(50),
    character__id VARCHAR(50) NOT NULL,
    boss_id VARCHAR(50) NOT NULL,
    u_id VARCHAR(50) NOT NULL,
    PRIMARY KEY(bui_id, item_id),
    FOREIGN KEY(character__id) REFERENCES personnage(character__id),
    FOREIGN KEY(boss_id) REFERENCES boss(boss_id),
    FOREIGN KEY(u_id) REFERENCES utilisateur(u_id)
);

CREATE TABLE Dispose(
    u_id VARCHAR(50),
    synergy_id VARCHAR(50),
    item_id VARCHAR(50),
    PRIMARY KEY(u_id, synergy_id, item_id),
    FOREIGN KEY(u_id) REFERENCES utilisateur(u_id),
    FOREIGN KEY(synergy_id, item_id) REFERENCES synergie(synergy_id, item_id)
);

CREATE TABLE comprends(
    item_id VARCHAR(50),
    item_id_1 VARCHAR(50),
    col_id VARCHAR(50),
    PRIMARY KEY(item_id, item_id_1, col_id),
    FOREIGN KEY(item_id) REFERENCES item(item_id),
    FOREIGN KEY(item_id_1, col_id) REFERENCES collection(item_id, col_id)
);

CREATE TABLE contient(
    synergy_id VARCHAR(50),
    item_id VARCHAR(50),
    item_id_1 VARCHAR(50),
    PRIMARY KEY(synergy_id, item_id, item_id_1),
    FOREIGN KEY(synergy_id, item_id) REFERENCES synergie(synergy_id, item_id),
    FOREIGN KEY(item_id_1) REFERENCES item(item_id)
);

CREATE TABLE appartient(
    item_id VARCHAR(50),
    build_id VARCHAR(50),
    item_id_1 VARCHAR(50),
    PRIMARY KEY(item_id, bui_id, item_id_1),
    FOREIGN KEY(item_id) REFERENCES item(item_id),
    FOREIGN KEY(bui_id, item_id_1) REFERENCES build(bui_id, item_id)
);


