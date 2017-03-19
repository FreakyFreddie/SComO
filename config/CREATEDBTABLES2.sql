DROP TABLE IF EXISTS webstoredb.gebruiker;
DROP TABLE IF EXISTS webstoredb.project;
DROP TABLE IF EXISTS webstoredb.gebruikerproject;
DROP TABLE IF EXISTS webstoredb.bestelling;
DROP TABLE IF EXISTS webstoredb.product;
DROP TABLE IF EXISTS webstoredb.bestellingproduct ;
DROP TABLE IF EXISTS webstoredb.definitiefbesteld;

/*Tabel gebruiker*/
CREATE TABLE IF NOT EXISTS webstoredb.gebruiker
(
  rnummer VARCHAR(8) NOT NULL,
  voornaam CHAR(45) NOT NULL,
  achternaam CHAR(45) NOT NULL,
  email VARCHAR(45) NOT NULL,
  wachtwoord VARCHAR(45) NOT NULL,
  machtigingsniveau INT NOT NULL,
  aanmaakdatum DATE NOT NULL,
  PRIMARY KEY (rnummer)
);

/*Tabel project*/
CREATE TABLE IF NOT EXISTS webstoredb.project
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
CREATE TABLE IF NOT EXISTS webstoredb.gebruikerproject
(
  rnummer VARCHAR(8) NOT NULL,
  idproject VARCHAR(25) NOT NULL,
  is_beheerder INT NOT NULL,
  FOREIGN KEY (rnummer)
	REFERENCES gebruiker (rnummer),
  FOREIGN KEY (idproject)
	REFERENCES project (idproject)
);

/*Tabel bestelling*/
CREATE TABLE IF NOT EXISTS webstoredb.bestelling
(
  bestelnummer VARCHAR(25) NOT NULL,
  status INT NOT NULL,
  besteldatum DATE NOT NULL,
  idproject VARCHAR(25),
  rnummer VARCHAR(8) NOT NULL,
  persoonlijk INT NOT NULL,
  defbestelnummer VARCHAR(25) NOT NULL,
  PRIMARY KEY (bestelnummer),
  FOREIGN KEY (idproject)
	REFERENCES project (idproject),
  FOREIGN KEY (rnummer)
	REFERENCES gebruiker (rnummer),
	FOREIGN KEY (defbestelnummer)
	REFERENCES definitiefbesteld (defbestelnummer)
);

/*Tabel product*/
CREATE TABLE IF NOT EXISTS webstoredb.product
(
  idproduct VARCHAR(25) NOT NULL,
  leverancier VARCHAR(25) NOT NULL,
  productnaam VARCHAR(30) NOT NULL,
  PRIMARY KEY (idproduct,leverancier));

/*Relatie gebruiker heeft bepaalde producten in winkelwagen*/
CREATE TABLE IF NOT EXISTS webstoredb.winkelwagen
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
  defbesteldatum DATE NOT NULL,
  PRIMARY KEY (defbestelnummer)
);

/*Relatie bestelling bevat producten*/
CREATE TABLE IF NOT EXISTS webstoredb.bestellingproduct
(
  bestelnummer VARCHAR(25) NOT NULL,
  idproduct VARCHAR(25) NOT NULL,
  leverancier VARCHAR(25) NOT NULL,
  aantal INT NOT NULL,
  prijs DECIMAL(10,2) NOT NULL,
  verzamelnaam VARCHAR(25),
  FOREIGN KEY (bestelnummer)
	REFERENCES bestelling (bestelnummer),
  FOREIGN KEY (idproduct,leverancier)
	REFERENCES product (idproduct,leverancier),
);