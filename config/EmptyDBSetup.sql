DROP TABLE IF EXISTS webstoredb.gebruikerproject;
DROP TABLE IF EXISTS webstoredb.bestellingproduct ;
DROP TABLE IF EXISTS webstoredb.bestelling;
DROP TABLE IF EXISTS webstoredb.winkelwagen;
DROP TABLE IF EXISTS webstoredb.gebruiker;
DROP TABLE IF EXISTS webstoredb.project;
DROP TABLE IF EXISTS webstoredb.product;
DROP TABLE IF EXISTS webstoredb.definitiefbesteld;

/*Tabel gebruiker*/
CREATE TABLE IF NOT EXISTS webstoredb.gebruiker
(
  rnummer VARCHAR(8) NOT NULL,
  voornaam CHAR(45) NOT NULL,
  achternaam CHAR(45) NOT NULL,
  email VARCHAR(45) NOT NULL,
  wachtwoord VARCHAR(100) NOT NULL,
  machtigingsniveau INT NOT NULL,
  aanmaakdatum DATE NOT NULL,
  activatiesleutel VARCHAR(32),
  PRIMARY KEY (rnummer)
);

/*Tabel project*/
CREATE TABLE IF NOT EXISTS webstoredb.project
(
  idproject INT NOT NULL AUTO_INCREMENT,
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
  idproject INT NOT NULL,
  is_beheerder INT NOT NULL,
  FOREIGN KEY (rnummer)
	REFERENCES gebruiker (rnummer),
  FOREIGN KEY (idproject)
	REFERENCES project (idproject)
);

/*Tabel definitief besteld*/
CREATE TABLE IF NOT EXISTS webstoredb.definitiefbesteld
(
  defbestelnummer VARCHAR(25) NOT NULL,
  defbesteldatum DATE NOT NULL,
  PRIMARY KEY (defbestelnummer)
);

/*Tabel bestelling*/
CREATE TABLE IF NOT EXISTS webstoredb.bestelling
(
  bestelnummer INT NOT NULL AUTO_INCREMENT,
  status INT NOT NULL,
  besteldatum DATE NOT NULL,
  idproject INT,
  rnummer VARCHAR(8) NOT NULL,
  persoonlijk INT NOT NULL,
  defbestelnummer VARCHAR(25),
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
  productnaam VARCHAR(70) NOT NULL,
  productverkoper VARCHAR(40) NOT NULL,
  productafbeelding VARCHAR(70) NOT NULL,
  productdatasheet VARCHAR(70) NOT NULL,
  PRIMARY KEY (idproduct, leverancier)
);

/*relatie verzamelingproduct*/
CREATE TABLE IF NOT EXISTS webstoredb.verzameling
(
  idverzameling INT NOT NULL AUTO_INCREMENT,
  titel VARCHAR(25),
  PRIMARY KEY (idverzameling)
);

/*relatie verzamelingproduct*/
CREATE TABLE IF NOT EXISTS webstoredb.verzamelingproduct
(
  idverzameling INT NOT NULL,
  idproduct VARCHAR(25) NOT NULL,
  leverancier VARCHAR(25) NOT NULL,
  FOREIGN KEY (idverzameling)
	REFERENCES gebruiker (idverzameling),
  FOREIGN KEY (idproduct,leverancier)
	REFERENCES product (idproduct,leverancier)
);

/*Relatie gebruiker heeft bepaalde producten in winkelwagen*/
CREATE TABLE IF NOT EXISTS webstoredb.winkelwagen
(
  rnummer VARCHAR(8) NOT NULL,
  idproduct VARCHAR(25) NOT NULL,
  leverancier VARCHAR(25) NOT NULL,
  aantal INT NOT NULL,
  prijs DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (rnummer)
	REFERENCES gebruiker (rnummer),
  FOREIGN KEY (idproduct,leverancier)
	REFERENCES product (idproduct,leverancier)
);

/*Relatie bestelling bevat producten*/
CREATE TABLE IF NOT EXISTS webstoredb.bestellingproduct
(
  bestelnummer INT NOT NULL,
  idproduct VARCHAR(25) NOT NULL,
  leverancier VARCHAR(25) NOT NULL,
  aantal INT NOT NULL,
  prijs DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (bestelnummer)
	REFERENCES bestelling (bestelnummer),
  FOREIGN KEY (idproduct,leverancier)
	REFERENCES product (idproduct,leverancier)
);