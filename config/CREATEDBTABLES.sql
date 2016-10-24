/*
DROP TABLE `webstoredb`.`gebruiker`;

DROP TABLE `webstoredb`.`project`;

DROP TABLE `webstoredb`.`gebruikerproject`;

DROP TABLE `webstoredb`.`bestelling`;

DROP TABLE `webstoredb`.`gebruikerbestelling`;

DROP TABLE `webstoredb`.`bestellingproject`;

DROP TABLE `webstoredb`.`product`;

DROP TABLE `webstoredb`.`bestellingproduct` ;

DROP TABLE `webstoredb`.`definitiefbesteld`;

DROP TABLE `webstoredb`.`bestellingdefinitiefbesteld`;
*/

/*Tabel gebruiker*/
CREATE TABLE `webstoredb`.`gebruiker` (
  `rnummer` VARCHAR(8) NOT NULL,
  `voornaam` CHAR(45) NOT NULL,
  `achternaam` CHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `wachtwoord` VARCHAR(45) NOT NULL,
  `machtigingsniveau` INT NOT NULL,
  `aanmaakdatum` DATE NOT NULL,
  PRIMARY KEY (`rnummer`)
);

/*Tabel project*/
CREATE TABLE `webstoredb`.`project` (
  `idproject` VARCHAR(25) NOT NULL,
  `titel` VARCHAR(25) NOT NULL,
  `budget` DECIMAL(10,2) NOT NULL,
  `rekeningnr` VARCHAR(25) NOT NULL,
  `startdatum` DATE NOT NULL,
  `vervaldatum` DATE NOT NULL,
  PRIMARY KEY (`idproject`));

/*Relatie gebruiker behoort tot project*/
CREATE TABLE `webstoredb`.`gebruikerproject` (
  `rnummer` VARCHAR(8) NOT NULL,
  `idproject` VARCHAR(25) NOT NULL,
  FOREIGN KEY (`rnummer`)
	REFERENCES gebruiker (`rnummer`),
  FOREIGN KEY (`idproject`)
	REFERENCES project (`idproject`)
);

/*Tabel bestelling*/
CREATE TABLE `webstoredb`.`bestelling` (
  `bestelnummer` VARCHAR(25) NOT NULL,
  `status` INT NOT NULL,
  `besteldatum` DATE NOT NULL,
  PRIMARY KEY (`bestelnummer`)
);
  
/*Relatie gebruiker plaatst bestelling*/
CREATE TABLE `webstoredb`.`gebruikerbestelling` (
  `rnummer` VARCHAR(8) NOT NULL,
  `bestelnummer` VARCHAR(25) NOT NULL,
  `persoonlijk` INT NOT NULL,
  FOREIGN KEY (`rnummer`)
	REFERENCES gebruiker (`rnummer`),
  FOREIGN KEY (`bestelnummer`)
	REFERENCES bestelling (`bestelnummer`)
);
  
/*Relatie bestelling hoort bij project*/
CREATE TABLE `webstoredb`.`bestellingproject` (
  `bestelnummer` VARCHAR(25) NOT NULL,
  `idproject` VARCHAR(25) NOT NULL,
  FOREIGN KEY (`bestelnummer`)
	REFERENCES bestelling (`bestelnummer`),
  FOREIGN KEY (`idproject`)
	REFERENCES project (`idproject`)
);
  
/*Tabel product*/
CREATE TABLE `webstoredb`.`product` (
  `idproduct` VARCHAR(25) NOT NULL,
  `leverancier` VARCHAR(25) NOT NULL,
  `productnaam` VARCHAR(30) NOT NULL,
  PRIMARY KEY (`idproduct`,`leverancier`));
  
/*Relatie bestelling bevat producten*/
CREATE TABLE `webstoredb`.`bestellingproduct` (
  `bestelnummer` VARCHAR(25) NOT NULL,
  `idproduct` VARCHAR(25) NOT NULL,
  `leverancier` VARCHAR(25) NOT NULL,
  `aantal` INT NOT NULL,
  `prijs` DECIMAL(10,2) NOT NULL,
  `verzamelnaam` VARCHAR(25),
  FOREIGN KEY (`bestelnummer`)
	REFERENCES bestelling (`bestelnummer`),
  FOREIGN KEY (`idproduct`,`leverancier`)
	REFERENCES product (`idproduct`,`leverancier`)
);
  
/*Tabel definitief besteld*/
CREATE TABLE `webstoredb`.`definitiefbesteld` (
  `defbestelnummer` VARCHAR(25) NOT NULL,
  `leverancier` VARCHAR(25) NOT NULL,
  `defbesteldatum` DATE NOT NULL,
  PRIMARY KEY (`defbestelnummer`,`leverancier`));
  
/*Relatie definitieve bestelling bevat bestellingen*/
CREATE TABLE `webstoredb`.`bestellingdefinitiefbesteld` (
  `bestelnummer` VARCHAR(25) NOT NULL,
  `defbestelnummer` VARCHAR(25) NOT NULL,
  `leverancier` VARCHAR(25) NOT NULL,
  FOREIGN KEY (`bestelnummer`)
	REFERENCES bestelling (`bestelnummer`),
  FOREIGN KEY (`defbestelnummer`,`leverancier`)
	REFERENCES definitiefbesteld (`defbestelnummer`,`leverancier`)
);