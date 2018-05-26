DROP TABLE IF EXISTS Released;
DROP TABLE IF EXISTS Running;
DROP TABLE IF EXISTS Associated;
DROP TABLE IF EXISTS Bioinfos;
DROP TABLE IF EXISTS Biographies;
DROP TABLE IF EXISTS PlaysIn;
DROP TABLE IF EXISTS Directs;
DROP TABLE IF EXISTS Produces;
DROP TABLE IF EXISTS Writes;
DROP TABLE IF EXISTS Linked;
DROP TABLE IF EXISTS Links;
DROP TABLE IF EXISTS HasLang;
DROP TABLE IF EXISTS HasGenre;
DROP TABLE IF EXISTS Genres;
DROP TABLE IF EXISTS Countries;
DROP TABLE IF EXISTS Clips;
DROP TABLE IF EXISTS Languages;
DROP TABLE IF EXISTS People;

CREATE TABLE People (
  personid INTEGER UNIQUE NOT NULL AUTO_INCREMENT,
  fullname VARCHAR(300),
  PRIMARY KEY (personid)
);

CREATE TABLE Clips(
  clipid INTEGER UNIQUE NOT NULL AUTO_INCREMENT,
  rank FLOAT DEFAULT NULL,
  cliptitle VARCHAR(300) NOT NULL,
  votes INTEGER DEFAULT NULL,
  clipyear INTEGER DEFAULT NULL,
  cliptype CHAR(2) DEFAULT NULL,
  PRIMARY KEY (clipid)
);

CREATE TABLE Countries(
  countryid INTEGER UNIQUE NOT NULL AUTO_INCREMENT,
  country VARCHAR(50) UNIQUE NOT NULL,
  PRIMARY KEY (countryid)
);

CREATE TABLE Links(
  linktype INTEGER UNIQUE NOT NULL AUTO_INCREMENT,
  link VARCHAR(50) UNIQUE NOT NULL,
  PRIMARY KEY (linktype)
);

CREATE TABLE Bioinfos (
  personid INTEGER UNIQUE NOT NULL,
  realname VARCHAR(300),
  nickname VARCHAR(400),
  trademark TEXT,
  birth VARCHAR(200),
  death VARCHAR(200),
  salary TEXT,
  whereAreTheyNow TEXT,
  height VARCHAR(50),
  spouse VARCHAR(750),
  biographicalBooks TEXT,
  trivia TEXT,
  personalQuote MEDIUMTEXT,
  PRIMARY KEY (personid),
  FOREIGN KEY (personid) REFERENCES People (personid)
  ON DELETE CASCADE
);

CREATE TABLE Languages(
  langid INTEGER UNIQUE NOT NULL AUTO_INCREMENT,
  language VARCHAR(50) UNIQUE NOT NULL,
  PRIMARY KEY (langid)
);
  
CREATE TABLE Biographies(
  personid INTEGER UNIQUE NOT NULL,
  biography MEDIUMTEXT,
  biographer VARCHAR(200),
  PRIMARY KEY (personid),
  FOREIGN KEY (personid) REFERENCES People (personid) 
    ON DELETE CASCADE
);

CREATE TABLE PlaysIn (
  personid INTEGER NOT NULL,
  clipid INTEGER,
  addinfo VARCHAR(1000),
  chars VARCHAR(800),
  orderscredits INTEGER,
  PRIMARY KEY (personid, clipid),
  FOREIGN KEY (clipid)
    REFERENCES Clips (clipid),
  FOREIGN KEY (personid)
    REFERENCES People (personid)
);

CREATE TABLE Directs (
  personid INTEGER NOT NULL,
  clipid INTEGER,
  addinfo VARCHAR(1000),
  roles VARCHAR(200),
  PRIMARY KEY (personid, clipid),
  FOREIGN KEY (clipid) 
    REFERENCES Clips (clipid),
  FOREIGN KEY (personid)
    REFERENCES People (personid)
);

CREATE TABLE Produces (
  personid INTEGER NOT NULL,
  clipid INTEGER,
  addinfo VARCHAR(1000),
  roles VARCHAR(200),
  PRIMARY KEY (personid, clipid),
  FOREIGN KEY (clipid)
    REFERENCES Clips (clipid),
  FOREIGN KEY (personid)
    REFERENCES People (personid)
);

CREATE TABLE Writes (
  personid INTEGER NOT NULL,
  clipid INTEGER,
  addinfo VARCHAR(1000),
  role VARCHAR(100),
  worktype VARCHAR(100),
  PRIMARY KEY (personid, clipid, role),
  FOREIGN KEY (clipid)
    REFERENCES Clips (clipid),
  FOREIGN KEY (personid)
    REFERENCES People (personid)
);



CREATE TABLE Linked(
  clipto INTEGER,
  clipfrom INTEGER,
  linktype INTEGER,
  PRIMARY KEY (clipto,clipfrom,linktype),
  FOREIGN KEY (clipto)
    REFERENCES Clips (clipid),
  FOREIGN KEY (clipfrom)
    REFERENCES Clips (clipid),
  FOREIGN KEY (linktype)
    REFERENCES Links(linktype)
);



CREATE TABLE HasLang(
  clipid INTEGER NOT NULL,
  langid INTEGER NOT NULL,
  PRIMARY KEY (clipid, langid),
  FOREIGN KEY (clipid)
    REFERENCES Clips (clipid),
  FOREIGN KEY (langid)
    REFERENCES Languages (langid)
);

CREATE TABLE Genres(
  genreid INTEGER UNIQUE NOT NULL AUTO_INCREMENT,
  genre VARCHAR(20) UNIQUE NOT NULL,
  PRIMARY KEY (genreid)
);

CREATE TABLE HasGenre(
  clipid INTEGER NOT NULL,
  genreid INTEGER,
  PRIMARY KEY (clipid, genreid),
  FOREIGN KEY (clipid)
    REFERENCES Clips (clipid),
  FOREIGN KEY (genreid)
    REFERENCES Genres (genreid)
);

CREATE TABLE Associated (
  clipid INTEGER NOT NULL,
  countryid INTEGER NOT NULL,
  PRIMARY KEY (clipid, countryid),
  FOREIGN KEY (clipid)
    REFERENCES Clips (clipid),
  FOREIGN KEY (countryid)
    REFERENCES Countries (countryid)
);

CREATE TABLE Released (
  clipid INTEGER NOT NULL,
  countryid INTEGER,
  releasedate DATE,
  PRIMARY KEY (clipid, countryid),
  FOREIGN KEY (clipid)
    REFERENCES Clips (clipid),
  FOREIGN KEY (countryid)
    REFERENCES Countries (countryid)
);

