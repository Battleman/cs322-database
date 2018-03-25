DROP TABLE People;
CREATE TABLE People (
  realname VARCHAR2(100),
  nickname VARCHAR2(100),
  artistname VARCHAR2(100),
  trademark VARCHAR2(100),
  birth VARCHAR2(100),
  death VARCHAR2(100),
  salary INTEGER,
  whereAreTheyNow VARCHAR2(100),
  height VARCHAR2(50),
  spouse VARCHAR2(100),
  biographicalBooks VARCHAR2(1000),
  trivia VARCHAR2(500),
  addInfo VARCHAR2(500),
  personalQuote VARCHAR2(500),
  PRIMARY KEY (artistname)
);
  
DROP TABLE Biography;
CREATE TABLE Biography(
  biography CLOB,
  biographer CHAR(100),
  artistname CHAR(100),
  PRIMARY KEY (artistname),
  FOREIGN KEY (artistname) REFERENCES People 
    ON DELETE CASCADE
);

CREATE TABLE Clips(
  clipid INTEGER,
  rank FLOAT,
  cliptitle CHAR(100),
  votes INTEGER,
  clipyear INTEGER,
  cliptype CHAR(2),
  PRIMARY KEY (clipid)
);

DROP TABLE PlaysIn;
CREATE TABLE PlaysIn (
  artistname VARCHAR2(100),
  clipid INTEGER,
  addinfo VARCHAR2(1000),
  chars VARCHAR2(200),
  orderscredits INTEGER,
  PRIMARY KEY (artistname, clipid),
  FOREIGN KEY (clipid) REFERENCES Clips,
  FOREIGN KEY (artistname) REFERENCES People
);

DROP TABLE Directs;
CREATE TABLE Directs (
  artistname VARCHAR2(100),
  clipid INTEGER,
  addinfo VARCHAR2(1000),
  roles VARCHAR2(200),
  PRIMARY KEY (artistname, clipid),
  FOREIGN KEY (clipid) REFERENCES Clips,
  FOREIGN KEY (artistname) REFERENCES People
);

DROP TABLE Produces;
CREATE TABLE Produces (
  artistname VARCHAR2(100),
  clipid INTEGER,
  addinfo VARCHAR2(1000),
  roles VARCHAR2(200),
  PRIMARY KEY (artistname, clipid),
  FOREIGN KEY (clipid) REFERENCES Clips,
  FOREIGN KEY (artistname) REFERENCES People
);

DROP TABLE Writes;
CREATE TABLE Writes (
  artistname VARCHAR2(100),
  clipid INTEGER,
  addinfo VARCHAR2(1000),
  roles VARCHAR2(200),
  worktype VARCHAR2(100),
  PRIMARY KEY (artistname, clipid),
  FOREIGN KEY (clipid) REFERENCES Clips,
  FOREIGN KEY (artistname) REFERENCES People
);

DROP TABLE Linked;
CREATE TABLE Linked(
  clipto INTEGER,
  clipfrom INTEGER,
  linktype VARCHAR2(50),
  PRIMARY KEY (clipto, clipfrom, linktype),
  FOREIGN KEY (clipto) REFERENCES Clips,
  FOREIGN KEY (clipfrom) REFERENCES Clips
);

DROP TABLE Languages;
CREATE TABLE Languages(
  langid INTEGER,
  language VARCHAR2(50),
  PRIMARY KEY (langid)
);

DROP TABLE HasLang;
CREATE TABLE HasLang(
  clipid INTEGER,
  langid INTEGER,
  PRIMARY KEY (clipid, langid),
  FOREIGN KEY (clipid) REFERENCES Clips,
  FOREIGN KEY (langid) REFERENCES Languages
);

DROP TABLE Genres;
CREATE TABLE Genres(
  genreid INTEGER,
  genre VARCHAR2(20),
  PRIMARY KEY (genreid)
);

DROP TABLE HasGenre;
CREATE TABLE HasGenre(
  clipid INTEGER,
  genreid INTEGER,
  PRIMARY KEY (clipid, genreid),
  FOREIGN KEY (clipid) REFERENCES Clips,
  FOREIGN KEY (genreid) REFERENCES Genres
);

DROP TABLE Countries;
CREATE TABLE Countries(
  countryid INTEGER,
  country VARCHAR2(50),
  PRIMARY KEY (countryid)
);

DROP TABLE Associated;
CREATE TABLE Associated (
  clipid INTEGER,
  countryid INTEGER,
  PRIMARY KEY (clipid, countryid),
  FOREIGN KEY (clipid) REFERENCES Clips,
  FOREIGN KEY (countryid) REFERENCES Countries
);

DROP TABLE Released;
CREATE TABLE Released (
  clipid INTEGER,
  countryid INTEGER,
  releasedate VARCHAR2(20),
  runningtime INTEGER,
  PRIMARY KEY (clipid, countryid),
  FOREIGN KEY (clipid) REFERENCES Clips,
  FOREIGN KEY (countryid) REFERENCES Countries
);