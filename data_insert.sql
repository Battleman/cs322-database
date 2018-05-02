DELETE FROM Countries;
LOAD DATA LOCAL INFILE '/media/battleman/DATA/Documents/EPFL/semestre6/Database/cs322-database/db2018imdb/ORACLE_countries.csv'
INTO TABLE Countries 
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n' 
IGNORE 1 ROWS 
(countryid, country);

DELETE FROM Clips;
LOAD DATA LOCAL INFILE '/media/battleman/DATA/Documents/EPFL/semestre6/Database/cs322-database/db2018imdb/ORACLE_clips.csv' 
INTO TABLE Clips  
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
IGNORE 1 ROWS 
(@vclipid, @vrank, @vcliptitle, @vvotes, @vclipyear, @vcliptype)
SET 
clipid = nullif(@vclipid,''), 
rank = nullif(@vrank,''), 
cliptitle = nullif(@vcliptitle,''), 
votes = nullif(@vvotes,''), 
clipyear = nullif(@vclipyear,''), 
cliptype = nullif(@vcliptype,'');

DELETE FROM People;
LOAD DATA LOCAL INFILE '/media/battleman/DATA/Documents/EPFL/semestre6/Database/cs322-database/db2018imdb/ORACLE_people.csv'
INTO TABLE People  
FIELDS TERMINATED BY ','  
LINES TERMINATED BY '\n'
(personid, fullname);

DELETE FROM Genres;
LOAD DATA LOCAL INFILE '/media/battleman/DATA/Documents/EPFL/semestre6/Database/cs322-database/db2018imdb/ORACLE_genres.csv'
INTO TABLE Genres
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n'
IGNORE 1 ROWS (genreid, genre);

DELETE FROM HasGenre;
LOAD DATA LOCAL INFILE '/media/battleman/DATA/Documents/EPFL/semestre6/Database/cs322-database/db2018imdb/ORACLE_hasGenre.csv'
INTO TABLE HasGenre
FIELDS TERMINATED BY ','
LINES TERMINATED BY '\n' 
IGNORE 1 ROWS (clipid, genreid);

DELETE FROM Running;
LOAD DATA LOCAL INFILE '/media/battleman/DATA/Documents/EPFL/semestre6/Database/cs322-database/db2018imdb/ORACLE_running.csv'
INTO TABLE Running
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n' 
IGNORE 1 ROWS 
(clipid, countryid, running);

DELETE FROM Released;
LOAD DATA LOCAL INFILE '/media/battleman/DATA/Documents/EPFL/semestre6/Database/cs322-database/db2018imdb/ORACLE_released.csv' 
INTO TABLE Released
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
IGNORE 1 ROWS 
(clipid, countryid, releasedate);

DELETE FROM Associated; LOAD DATA LOCAL INFILE '/media/battleman/DATA/Documents/EPFL/semestre6/Database/cs322-database/db2018imdb/ORACLE_associated.csv' INTO TABLE Associated FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 ROWS (clipid, countryid);

DELETE FROM Biographies; LOAD DATA LOCAL INFILE '/media/battleman/DATA/Documents/EPFL/semestre6/Database/cs322-database/db2018imdb/ORACLE_biographies.csv' INTO TABLE Biographies FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' (personid, @vbio, @vbiographer) SET biography = nullif(@vbio, ''), biographer = nullif(@vbiographer, '');

DELETE FROM Bioinfos; LOAD DATA LOCAL INFILE '/media/battleman/DATA/Documents/EPFL/semestre6/Database/cs322-database/db2018imdb/ORACLE_bioinfos.csv' INTO TABLE Bioinfos FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' (personid, @vRealName, @vNick, @vtrademark,@vbirth, @vdeath, @vsalary, @vwhereAreTheyNow, @vheight, @vspouse, @vbiographicalBooks, @vtrivia, @vpersonalQuote) SET realname = nullif(@vRealName,''), nickname = nullif(@vNick,''), trademark = nullif(@vtrademark,''), birth = nullif(@vbirth,''), death = nullif(@vdeath,''), salary = nullif(@vsalary,''), whereAreTheyNow = nullif(@vwhereAreTheyNow,''),height = nullif(@vheight,''), spouse = nullif(@vspouse,''), biographicalBooks = nullif(@vbiographicalBooks,''), trivia = nullif(@vtrivia,''), personalQuote = nullif(@vpersonalQuote,'');

DELETE FROM Directs; LOAD DATA LOCAL INFILE '/media/battleman/DATA/Documents/EPFL/semestre6/Database/cs322-database/db2018imdb/tables/ORACLE_directs.csv' INTO TABLE Directs FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 ROWS (personid, clipid, @vaddinfo, @vroles) SET addinfo = nullif(@vaddinfo, ''), roles = nullif(@vroles, '');

DELETE FROM Writes; LOAD DATA LOCAL INFILE '/media/battleman/DATA/Documents/EPFL/semestre6/Database/cs322-database/db2018imdb/tables/ORACLE_writes.csv' INTO TABLE WritesFIELDS TERMINATED BY ',' LINES TERMINATED BY '\n'IGNORE 1 ROWS(personid, clipid, @vaddinfo, @vroles, @vwork)SETaddinfo = nullif(@vaddinfo, ''),roles = nullif(@vroles, ''), worktype = nullif(@vwork, '';

DELETE FROM Produces; LOAD DATA LOCAL INFILE '/media/battleman/DATA/Documents/EPFL/semestre6/Database/cs322-database/db2018imdb/tables/ORACLE_produces.csv' INTO TABLE Produces FIELDS TERMINATED BY ','  LINES TERMINATED BY '\n'  IGNORE 1 ROWS (personid, clipid, @vaddinfo, @vroles) SET addinfo = nullif(@vaddinfo, ''), roles = nullif(@vroles, '');

DELETE FROM PlaysIn; LOAD DATA LOCAL INFILE '/media/battleman/DATA/Documents/EPFL/semestre6/Database/cs322-database/db2018imdb/tables/ORACLE_playsin.csv'  INTO TABLE PlaysIn FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n'  IGNORE 1  ROWS (personid, clipid, @vaddinfo, @vchars, @vorderscredits) SET addinfo = nullif(@vaddinfo, ''), chars = nullif(@vchars, ''), orderscredits = nullif(@vorderscredits, '');

DELETE FROM Links; LOAD DATA LOCAL INFILE '/media/battleman/DATA/Documents/EPFL/semestre6/Database/cs322-database/db2018imdb/ORACLE_links.csv' INTO TABLE Links FIELDS TERMINATED BY ',' LINES TERMINATED BY '\n' IGNORE 1 ROWS (linktype, link);