import numpy as np
import csv
import codecs
import unicodecsv as unicsv
import time
from dateutil import parser
import pickle as pkl

def clips():
    print('Treating clips')
    with open('db2018imdb/clips.csv', newline='', encoding='utf-8') as clipsCSV,\
         open('db2018imdb/ratings.csv', newline='') as ratingCSV,\
         open('db2018imdb/ORACLE_clips.csv', mode='wb') as dst:
        
        clips = csv.reader(clipsCSV, delimiter=',', quotechar='"')
        ratings = csv.reader(ratingCSV, delimiter=',', quotechar='"')
        destination = unicsv.writer(dst, delimiter=",", quotechar='"', encoding='utf-8', lineterminator='\n')
        
        next(clips)
        next(ratings)


        header = [u'clipid',u'rank',u'cliptitle',u'votes',u'clipyear',u'cliptype']
        destination.writerow(header)
        for c in clips:
            id = c[0]
            title = c[1]
            year = c[2]
            typ = c[3]
            for r in ratings:
                rid = r[0]
                vote=""
                rank=""
                if rid == id:
                    vote = r[1]
                    rank = r[2]
                    break
                
            destination.writerow([id, rank, title, vote, year, typ])
def lang():
    print('Treating lang')
    """Table 'languages' and 'hasLang'"""
    with open('db2018imdb/languages.csv', mode='r') as languagesCSV,\
        open('db2018imdb/ORACLE_languages.csv', mode='w') as dstLang,\
        open('db2018imdb/ORACLE_hasLang.csv', mode='w') as dstHasLang:
        next(languagesCSV)

        languages = csv.reader(languagesCSV, delimiter=',',quotechar='"')
        destinationLang = csv.writer(dstLang, delimiter=",", quotechar='"', lineterminator='\n')
        destinationHasLang = csv.writer(dstHasLang, delimiter=",", quotechar='"', lineterminator='\n')


        destinationLang.writerow(['langid','language'])
        destinationHasLang.writerow(['clipid','langid'])
        

        allLang=dict()
        uniqTuple = set()
        UID=1

        for cid, lang in languages:
            if not lang in allLang:
                allLang[lang] = UID
                UID += 1
            if not (cid,lang) in uniqTuple:
                destinationHasLang.writerow([cid,allLang[lang]])
                uniqTuple.add((cid,lang))
        
        for lang, id in allLang.items():
            destinationLang.writerow([id,lang])         
def genres():
    print('Treating genres')
    """Table 'genres' and 'hasGenre'"""
    with open('db2018imdb/genres.csv', mode='r') as genresCSV,\
         open('db2018imdb/ORACLE_genres.csv', mode='w') as dstGenre,\
         open('db2018imdb/ORACLE_hasGenre.csv', mode='w') as dstHasGenre:
        next(genresCSV)
        
        genres = csv.reader(genresCSV, delimiter=',',quotechar='"')
        destinationGenre = csv.writer(dstGenre, delimiter=",", quotechar='"', lineterminator='\n')
        destinationHasGenre = csv.writer(dstHasGenre, delimiter=",", quotechar='"', lineterminator='\n')
        
        destinationGenre.writerow(['genreid', 'genre'])
        destinationHasGenre.writerow(['clipid','genreid'])

        allGenres=dict()
        uniqTuple = set()

        UID=1

        for cid, g in genres:
            if not g in allGenres and g != "Genre":
                allGenres[g] = UID
                UID += 1
            if not (cid, g) in uniqTuple:
                destinationHasGenre.writerow([cid,allGenres[g]])
                uniqTuple.add((cid,g))


        for g, id in allGenres.items():
            destinationGenre.writerow([id,g])
def countries():
    print('Treating countries')
    """Tables 'Countries', 'associated' and 'Released'"""

    with open('db2018imdb/countries.csv', mode='r') as countriesCSV,\
         open('db2018imdb/release_dates.csv', mode='r') as releaseCSV,\
         open('db2018imdb/running_times.csv', mode='r') as runningCSV,\
         open('db2018imdb/ORACLE_associated.csv', mode='wb') as dstAssociated,\
         open('db2018imdb/ORACLE_released.csv', mode='wb') as dstReleased,\
         open('db2018imdb/ORACLE_countries.csv', mode='wb') as dstCountries,\
         open('db2018imdb/ORACLE_running.csv', mode='wb') as dstRunning:
        
        #skip title line
        next(countriesCSV)
        next(releaseCSV)
        next(runningCSV)
        
        #set CSV writer/reader
        countries = csv.reader(countriesCSV, delimiter=',',quotechar='"')
        running = csv.reader(runningCSV, delimiter=',',quotechar='"')
        releases = csv.reader(releaseCSV, delimiter=',',quotechar='"')
        destinationAssociated = unicsv.writer(dstAssociated, delimiter=",", quotechar='"', lineterminator='\n')
        destinationReleased = unicsv.writer(dstReleased, delimiter=",", quotechar='"', lineterminator='\n')
        destinationCountries = unicsv.writer(dstCountries, delimiter=",", quotechar='"', lineterminator='\n')
        destinationRunning = unicsv.writer(dstRunning, delimiter=",", quotechar='"', lineterminator='\n')

        destinationRunning.writerow(['clipid','countryid','running'])
        destinationCountries.writerow(['countryid','country'])
        destinationAssociated.writerow(['clipid','countryid'])
        destinationReleased.writerow(['clipid','countryid','releasedate'])

        uniqTuple = set()
        allCountries = dict()
        UID = 0
        for cid, c in countries:
            if not c in allCountries:
                allCountries[c] = UID
                destinationCountries.writerow([UID, c])
                UID += 1
            if not (cid, c) in uniqTuple:
                destinationAssociated.writerow([cid, allCountries[c]])
                uniqTuple.add((cid,c))
        
        for clipId, country, releaseDate in releases:
            parsed = parser.parse(releaseDate)
            try:
                destinationReleased.writerow([clipId, allCountries[country], parsed])
            except KeyError:
                print("This country was not found:",country)
                allCountries[country] = UID
                destinationCountries.writerow([UID, country])
                UID += 1
                destinationReleased.writerow([clipId, allCountries[country], parsed])
        
        
        uniqRuning = set()
        try:
            for clipid, releaseCountry, runningTime in running:
                if (clipid, releaseCountry, runningTime) in uniqRuning:
                    continue
                if(runningTime == ""):
                    runningTime = -1
                if releaseCountry == '':
                    releaseCountry = 'Worldwide'
                try:
                    runningTime = float(runningTime)
                except ValueError:
                    print("Film {} has strange running time: {}".format(clipid, runningTime))
                    continue

                try:
                    cid = allCountries[releaseCountry]
                except KeyError:
                    print("This country was not found:",releaseCountry)
                    allCountries[releaseCountry] = UID
                    destinationCountries.writerow([UID, releaseCountry])
                    UID += 1

                uniqRuning.add((clipid, releaseCountry, runningTime))
                destinationRunning.writerow([clipid, allCountries[releaseCountry], runningTime])
        except ValueError:
            print("Error at film", clipid)
def links():
    print('Treating links')
    """Table 'linked'"""
    
    with open('db2018imdb/clip_links.csv', mode='r') as linksCSV\
        ,open('db2018imdb/ORACLE_linked.csv', mode='wb') as dstLinked:

        links=csv.reader(linksCSV, delimiter=',', quotechar='"')
        destinationLinked = csv.writer(dstLinked, delimiter=',', quotechar='"', lineterminator='\n')
        allLTypes = set()
        for lfrom, lto, ltype in links:
            allLTypes.add(ltype)
        # print(allLTypes)
def biographies():
    print("Treating biographies")
    with open("namesDictionnary.pkl", "rb") as f:
        namesToId = pkl.load(f)
    
    with open('db2018imdb/biographies.csv', newline='', encoding='utf-8') as bioCSV, \
    open('db2018imdb/ORACLE_biographies.csv', mode='wb') as dstbio,\
    open('db2018imdb/ORACLE_bioinfos.csv', mode='wb') as dstinfo:

        bios = csv.reader(bioCSV, delimiter=',', quotechar='"')
        bioinfos = unicsv.writer(dstinfo, delimiter=",", quotechar='"', encoding='utf-8', lineterminator='\n')
        biographies = unicsv.writer(dstbio, delimiter=",", quotechar='"', encoding='utf-8', lineterminator='\n')
        next(bios)
        length = {'name':0, 'realName':0, 'nickName':0, 'birth':0, 'height':0, 'bioPhy':0, 'bioPher':0, 'death':0,\
                'spouse':0, 'trivia':0, 'books':0, 'quotes':0, 'salary':0, 'trademark':0, 'whereNow':0}
        for x in bios:
            try :
                [name, realName, nickName, birth, height, bioPhy, bioPher, death,\
                spouse, trivia, books, quotes, salary, trademark, whereNow] = x
            except ValueError:
                print('Error at name', x[0], "length is", len(x), "values are ")
                print("\n".join(v for v in x))
                continue 
            id = namesToId[name]
            # print("Inspecting {}, with id {}".format(name, id))
            length['name'] = max(length['name'], len(name))
            length['realName'] = max(length['realName'], len(realName))
            length['nickName'] = max(length['nickName'], len(nickName))
            length['birth'] = max(length['birth'], len(birth))
            length['height'] = max(length['height'], len(height))
            length['bioPhy'] = max(length['bioPhy'], len(bioPhy))
            length['bioPher'] = max(length['bioPher'], len(bioPher))
            length['death'] = max(length['death'], len(death))
            length['spouse'] = max(length['spouse'], len(spouse))
            length['trivia'] = max(length['trivia'], len(trivia))
            length['books'] = max(length['books'], len(books))
            length['quotes'] = max(length['quotes'], len(quotes))
            length['salary'] = max(length['salary'], len(salary))
            length['trademark'] = max(length['trademark'], len(trademark))
            length['whereNow'] = max(length['whereNow'], len(whereNow))
            if bioPhy != "" or bioPher != "":
                biographies.writerow([id, bioPhy, bioPher])
            
            if [realName, nickName, birth, height, death, spouse,\
                trivia, books, quotes, salary, trademark, whereNow] != [""]*12:
                bioinfos.writerow([id, realName, nickName, trademark, birth, death,\
                                    salary, whereNow, height, spouse, books, trivia, quotes])
    print('Max Lengths:', length)
def people():
    print("Treating people")
    def addPeople(csvSource, namesSet):
        src = csv.reader(csvSource, delimiter=',', quotechar='"')
        next(src)
        for person in src:
            namesSet.add(person[0])
    uniqNames = set()

    with open('db2018imdb/actors.csv', newline='', encoding='utf-8') as actCSV:
        addPeople(actCSV, uniqNames)

    with open('db2018imdb/directors.csv', newline='', encoding='utf-8') as dirCSV:
        addPeople(dirCSV, uniqNames)

    with open('db2018imdb/producers.csv', newline='', encoding='utf-8') as proCSV:
        addPeople(proCSV, uniqNames)

    with open('db2018imdb/writers.csv', newline='', encoding='utf-8') as wriCSV:
        addPeople(wriCSV, uniqNames)

    with open('db2018imdb/biographies.csv', newline='', encoding='utf-8') as wriCSV:
        addPeople(wriCSV, uniqNames)

    sortedNames = sorted(uniqNames)
    couple = enumerate(sortedNames, start=1)
    nameDict = dict()
    
    with open('db2018imdb/ORACLE_people.csv', mode='wb') as dst:
        people = unicsv.writer(dst, delimiter=",", quotechar='"', encoding='utf-8', lineterminator='\n')
        for x,y in couple:
            nameDict[y] = x
            people.writerow([x,y])

    with open("namesDictionnary.pkl", 'wb') as n:
        pkl.dump(nameDict, n, pkl.HIGHEST_PROTOCOL)

def main():
    genres()
    lang()
    clips()
    links()
    countries()
    people()
    biographies()
if __name__ == "__main__":
    main()