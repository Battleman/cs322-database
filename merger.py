import numpy as np
import csv
import codecs
import unicodecsv as unicsv

def clips():
    with open('db2018imdb/clips.csv', newline='', encoding='utf-8') as clipsCSV,\
         open('db2018imdb/ratings.csv', newline='') as ratingCSV,\
         open('db2018imdb/ORACLE_clips.csv', mode='wb') as dst:
        
        clips = csv.reader(clipsCSV, delimiter=',', quotechar='"')
        ratings = csv.reader(ratingCSV, delimiter=',', quotechar='"')
        destination = unicsv.writer(dst, delimiter=",", quotechar='"', encoding='utf-8')
        
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
    """Table 'languages' and 'hasLang'"""
    with open('db2018imdb/languages.csv', mode='r') as languagesCSV,\
        open('db2018imdb/ORACLE_languages.csv', mode='w') as dstLang,\
        open('db2018imdb/ORACLE_hasLang.csv', mode='w') as dstHasLang:
        next(languagesCSV)

        languages = csv.reader(languagesCSV, delimiter=',',quotechar='"')
        destinationLang = csv.writer(dstLang, delimiter=",", quotechar='"')
        destinationHasLang = csv.writer(dstHasLang, delimiter=",", quotechar='"')


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
    """Table 'genres' and 'hasGenre'"""
    with open('db2018imdb/genres.csv', mode='r') as genresCSV,\
         open('db2018imdb/ORACLE_genres.csv', mode='w') as dstGenre,\
         open('db2018imdb/ORACLE_hasGenre.csv', mode='w') as dstHasGenre:
        next(genresCSV)
        
        genres = csv.reader(genresCSV, delimiter=',',quotechar='"')
        destinationGenre = csv.writer(dstGenre, delimiter=",", quotechar='"')
        destinationHasGenre = csv.writer(dstHasGenre, delimiter=",", quotechar='"')
        
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
    """Tables 'Countries', 'associated' and 'Released'"""

    with open('db2018imdb/countries.csv', mode='r') as countriesCSV,\
         open('db2018imdb/release_dates.csv', mode='r') as releaseCSV,\
         open('db2018imdb/running_times.csv', mode='r') as runningCSV,\
         open('db2018imdb/ORACLE_associated.csv', mode='wb') as dstAssociated,\
         open('db2018imdb/ORACLE_released.csv', mode='wb') as dstReleased,\
         open('db2018imdb/ORACLE_countries.csv', mode='wb') as dstCountries:
        
        #skip title line
        next(countriesCSV)
        next(releaseCSV)
        next(runningCSV)
        
        #set CSV writer/reader
        countries = csv.reader(countriesCSV, delimiter=',',quotechar='"')
        running = csv.reader(runningCSV, delimiter=',',quotechar='"')
        releases = csv.reader(releaseCSV, delimiter=',',quotechar='"')
        destinationAssociated = unicsv.writer(dstAssociated, delimiter=",", quotechar='"')
        destinationReleased = csv.writer(dstReleased, delimiter=",", quotechar='"')
        destinationCountries = unicsv.writer(dstCountries, delimiter=",", quotechar='"')

        destinationCountries.writerow(['country','countryid'])
        destinationAssociated.writerow(['clipid','countryid'])

        uniqTuple = set()
        allCountries = dict()
        UID = 0
        for cid, c in countries:
            if not c in allCountries:
                allCountries[c] = UID
                destinationCountries.writerow([c, UID])
                UID += 1
            if not (cid, c) in uniqTuple:
                destinationAssociated.writerow([cid, allCountries[c]])
                uniqTuple.add((cid,c))
                

def links():
    """Table 'linked'"""
    
    with open('db2018imdb/clip_links.csv', mode='r') as linksCSV:
        # ,open('db2018imdb/ORACLE_linked.csv', mode='w') as dstLinked\

        links=csv.reader(linksCSV, delimiter=',', quotechar='"')
        # destinationLinked = csv.writer(dstLinked, delimiter=',', quotechar='"')
        allLTypes = set()
        for lfrom, lto, ltype in links:
            allLTypes.add(ltype)
        # print(allLTypes)

def main():
    # genres()
    # lang()
    # clips()
    # links()
    countries()
if __name__ == "__main__":
    main()