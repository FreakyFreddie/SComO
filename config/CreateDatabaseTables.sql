/*
DROP TABLE webstoredb.gebruiker;

DROP TABLE webstoredb.project;

DROP TABLE webstoredb.gebruikerproject;

DROP TABLE webstoredb.bestelling;

DROP TABLE webstoredb.product;

DROP TABLE webstoredb.winkelwagen;

DROP TABLE webstoredb.bestellingproduct ;

DROP TABLE webstoredb.definitiefbesteld;

CREATE VIEW bestellingen AS
	  SELECT bestelling.bestelnummer as bestelnr, bestelling.besteldatum as datum, bestelling.idproject as projectid,
    bestelling.rnummer as rnummer, (bestellingproduct.aantal * bestellingproduct.prijs) as totaalkost
    FROM bestelling INNER JOIN bestellingproduct
    ON bestelling.bestelnummer=bestellingproduct.bestelnummer
    WHERE bestelling.status=1 AND bestelling.persoonlijk=0
    GROUP BY bestelling.bestelnummer;
*/

/*Tabel gebruiker*/
CREATE TABLE webstoredb.gebruiker
(
  rnummer VARCHAR(8) NOT NULL,
  voornaam CHAR(45) NOT NULL,
  achternaam CHAR(45) NOT NULL,
  email VARCHAR(45) NOT NULL,
  wachtwoord VARCHAR(100) NOT NULL,
  machtigingsniveau INT(1) DEFAULT '0',
  aanmaakdatum DATETIME NOT NULL,
  activatiesleutel VARCHAR(32),
  PRIMARY KEY (rnummer)
);

/*Tabel project*/
CREATE TABLE webstoredb.project
(
  idproject INT NOT NULL AUTO_INCREMENT,
  titel VARCHAR(25) NOT NULL,
  budget DECIMAL(10,2) NOT NULL,
  rekeningnr VARCHAR(25) NOT NULL,
  startdatum DATETIME NOT NULL,
  vervaldatum DATETIME NOT NULL,
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
  besteldatum DATETIME NOT NULL,
  idproject VARCHAR(25),
  rnummer VARCHAR(8) NOT NULL,
  persoonlijk INT NOT NULL,
  PRIMARY KEY (bestelnummer),
  FOREIGN KEY (idproject)
	REFERENCES project (idproject),
  FOREIGN KEY (rnummer)
	REFERENCES gebruiker (rnummer)
);

/*Tabel product, url kan max 2000 characters hebben*/
CREATE TABLE webstoredb.product
(
  idproduct VARCHAR(25) NOT NULL,
  leverancier VARCHAR(25) NOT NULL,
  productnaam VARCHAR(100) NOT NULL,
  productverkoper VARCHAR(30) NOT NULL,
  productafbeelding VARCHAR(2000) NOT NULL,
  productdatasheet VARCHAR(2000) NOT NULL,
  PRIMARY KEY (idproduct,leverancier));

/*Relatie gebruiker heeft bepaalde producten in winkelwagen*/
CREATE TABLE webstoredb.winkelwagen
(
  rnummer VARCHAR(8) NOT NULL,
  idproduct VARCHAR(25) NOT NULL,
  leverancier VARCHAR(25) NOT NULL,
  aantal INT(10) NOT NULL,
  prijs DEC(14,4) NOT NULL,
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
  defbesteldatum DATETIME NOT NULL,
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