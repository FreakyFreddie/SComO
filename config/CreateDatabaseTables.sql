/*
DROP TABLE webstoredb.gebruiker;

DROP TABLE webstoredb.project;

DROP TABLE webstoredb.gebruikerproject;

DROP TABLE webstoredb.bestelling;

DROP TABLE webstoredb.product;

DROP TABLE webstoredb.winkelwagen;

DROP TABLE webstoredb.bestellingproduct ;

DROP TABLE webstoredb.definitiefbesteld;
*/

/*Tabel gebruiker*/
CREATE TABLE webstoredb.gebruiker
(
  rnummer VARCHAR(8) NOT NULL,
  voornaam CHAR(45) NOT NULL,
  achternaam CHAR(45) NOT NULL,
  email VARCHAR(45) NOT NULL,
  wachtwoord VARCHAR(45) NOT NULL,
  machtigingsniveau INT DEFAULT '0',
  aanmaakdatum DATE NOT NULL,
  activatiesleutel VARCHAR(),
  PRIMARY KEY (rnummer)
);

/*Tabel project*/
CREATE TABLE webstoredb.project
(
  idproject VARCHAR(25) NOT NULL,
  titel VARCHAR(25) NOT NULL,
  budget DECIMAL(10,2) NOT NULL,
  rekeningnr VARCHAR(25) NOT NULL,
  startdatum DATE NOT NULL,
  vervaldatum DATE NOT NULL,
  PRIMARY KEY (idproject)
);

/*Relatie gebruiker behoort tot project*/
CREATE TABLE webstoredb.gebruikerproject
(
  rnummer VARCHAR(8) NOT NULL,
  idproject VARCHAR(25) NOT NULL,
  FOREIGN KEY (rnummer)
	REFERENCES gebruiker (rnummer),
  FOREIGN KEY (idproject)
	REFERENCES project (idproject)
);

/*Tabel bestelling*/
CREATE TABLE webstoredb.bestelling
(
  bestelnummer VARCHAR(25) NOT NULL,
  status INT NOT NULL,
  besteldatum DATE NOT NULL,
  idproject VARCHAR(25),
  rnummer VARCHAR(8) NOT NULL,
  persoonlijk INT NOT NULL,
  PRIMARY KEY (bestelnummer),
  FOREIGN KEY (idproject)
	REFERENCES project (idproject),
  FOREIGN KEY (rnummer)
	REFERENCES gebruiker (rnummer)
);

/*Tabel product*/
CREATE TABLE webstoredb.product
(
  idproduct VARCHAR(25) NOT NULL,
  leverancier VARCHAR(25) NOT NULL,
  productnaam VARCHAR(30) NOT NULL,
  PRIMARY KEY (idproduct,leverancier));

/*Relatie gebruiker heeft bepaalde producten in winkelwagen*/
CREATE TABLE webstoredb.winkelwagen
(
  rnummer VARCHAR(8) NOT NULL,
  idproduct VARCHAR(25) NOT NULL,
  leverancier VARCHAR(25) NOT NULL,
  FOREIGN KEY (rnummer)
	REFERENCES gebruiker (rnummer),
  FOREIGN KEY (idproduct)
	REFERENCES product (idproduct),
  FOREIGN KEY (leverancier)
	REFERENCES product (leverancier)
);

/*Tabel definitief besteld*/
CREATE TABLE webstoredb.definitiefbesteld
(
  defbestelnummer VARCHAR(25) NOT NULL,
  leverancier VARCHAR(25) NOT NULL,
  defbesteldatum DATE NOT NULL,
  PRIMARY KEY (defbestelnummer,leverancier));

/*Relatie bestelling bevat producten*/
CREATE TABLE webstoredb.bestellingproduct
(
  bestelnummer VARCHAR(25) NOT NULL,
  idproduct VARCHAR(25) NOT NULL,
  leverancier VARCHAR(25) NOT NULL,
  aantal INT NOT NULL,
  prijs DECIMAL(10,2) NOT NULL,
  verzamelnaam VARCHAR(25),
  defbestelnummer VARCHAR(25),
  FOREIGN KEY (bestelnummer)
	REFERENCES bestelling (bestelnummer),
  FOREIGN KEY (idproduct,leverancier)
	REFERENCES product (idproduct,leverancier),
  FOREIGN KEY (defbestelnummer)
	REFERENCES definitiefbesteld (defbestelnummer)
);