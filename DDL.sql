DROP TABLE Released;
DROP TABLE Running;
DROP TABLE Associated;
DROP TABLE Biographies;
DROP TABLE Bioinfos;
DROP TABLE PlaysIn;
DROP TABLE Directs;
DROP TABLE Produces;
DROP TABLE Writes;
DROP TABLE Linked;
DROP TABLE HasLang;
DROP TABLE HasGenre;
DROP TABLE Genres;
DROP TABLE Countries;
DROP TABLE Clips;
DROP TABLE Languages;
DROP TABLE People;

CREATE TABLE People (
  personid INTEGER UNIQUE NOT NULL,
  fullname VARCHAR(300),
  PRIMARY KEY (personid)
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
  biographicalBooks VARCHAR(1000),
  trivia TEXT,
  personalQuote MEDIUMTEXT,
  PRIMARY KEY (personid),
  FOREIGN KEY (personid) REFERENCES People (personid)
  ON DELETE CASCADE
);
  
CREATE TABLE Biographies(
  personid INTEGER UNIQUE NOT NULL,
  biography MEDIUMTEXT,
  biographer VARCHAR(200),
  PRIMARY KEY (personid),
  FOREIGN KEY (personid) REFERENCES People (personid) 
    ON DELETE CASCADE
);

CREATE TABLE Clips(
  clipid INTEGER UNIQUE NOT NULL,
  rank FLOAT,
  cliptitle VARCHAR(300),
  votes INTEGER,
  clipyear INTEGER,
  cliptype CHAR(2),
  PRIMARY KEY (clipid)
);

CREATE TABLE PlaysIn (
  personid INTEGER NOT NULL,
  clipid INTEGER,
  addinfo VARCHAR(1000),
  chars VARCHAR(200),
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
  roles VARCHAR(200),
  worktype VARCHAR(100),
  PRIMARY KEY (personid, clipid),
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

CREATE TABLE Links(
  linktype INTEGER UNIQUE NOT NULL AUTO_INCREMENT,
  link VARCHAR(50) UNIQUE NOT NULL,
  PRIMARY KEY (linktype)
);

CREATE TABLE Languages(
  langid INTEGER,
  language VARCHAR(50) UNIQUE,
  PRIMARY KEY (langid)
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
  genreid INTEGER UNIQUE NOT NULL,
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

CREATE TABLE Countries(
  countryid INTEGER UNIQUE NOT NULL,
  country VARCHAR(50) UNIQUE NOT NULL,
  PRIMARY KEY (countryid)
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

CREATE TABLE Running (
  clipid INTEGER,
  countryid INTEGER,
  running INTEGER,
  PRIMARY KEY (clipid, countryid,running)
);