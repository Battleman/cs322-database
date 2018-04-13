import numpy as np
import csv
import codecs

def clips():
    with open('db2018imdb/clips.csv', newline='') as clipsCSV, open('db2018imdb/ratings.csv', newline='') as ratingCSV, open('db2018imdb/ORACLE_clips.csv', mode='w') as dst:
        clips = csv.reader(clipsCSV, delimiter=',', quotechar='"')
        ratings = csv.reader(ratingCSV, delimiter=',', quotechar='"')
        destination = csv.writer(dst, delimiter=",", quotechar='"')
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
        languages = csv.reader(languagesCSV, delimiter=',',quotechar='"')
        destinationLang = csv.writer(dstLang, delimiter=",", quotechar='"')
        destinationHasLang = csv.writer(dstHasLang, delimiter=",", quotechar='"')
        allLang=dict()
        UID=1

        for l in languages:
            if not l[1] in allLang and l[1] != "Language":
                allLang[l[1]] = UID
                UID += 1
        
        print('Done creating set')

        for lang, id in allLang.items():
            destinationLang.writerow([id,lang])
        
        print('Done creating Language table')
        languagesCSV.seek(0)
        for cid,lang in languages:
            if(lang != "Language"):
                destinationHasLang.writerow([cid,allLang[lang]])

def genres():
    """Table 'genres' and 'hasGenre'"""
    with open('db2018imdb/genres.csv', mode='r') as genresCSV,\
         open('db2018imdb/ORACLE_genres.csv', mode='w') as dstGenre,\
         open('db2018imdb/ORACLE_hasGenre.csv', mode='w') as dstHasGenre:
        
        genres = csv.reader(genresCSV, delimiter=',',quotechar='"')
        destinationGenre = csv.writer(dstGenre, delimiter=",", quotechar='"')
        destinationHasGenre = csv.writer(dstHasGenre, delimiter=",", quotechar='"')
        allGenres=dict()
        UID=1

        for g in genres:
            if not g[1] in allGenres and g[1] != "Genre":
                allGenres[g[1]] = UID
                UID += 1
        
        print('Done creating set')

        for g, id in allGenres.items():
            destinationGenre.writerow([id,g])
        
        print('Done creating Language table')
        genresCSV.seek(0)
        for cid,g in genres:
            if(g != "Genre"):
                destinationHasGenre.writerow([cid,allGenres[g]])

def countries():
    """Tables 'Countries', 'associated' and 'Released'"""

    with open('db2018imdb/countries.csv', mode='r') as countriesCSV\
         open('db2018imdb/release_dates.csv', mode='r') as releaseCSV\
         open('db2018imdb/running_times.csv', mode='r') as runningCSV\
         open('db2018imdb/ORACLE_associated.csv', mode='w') as dstAssociated\
         open('db2018imdb/ORACLE_released.csv', mode='w') as dstReleased\
         open('db2018imdb/ORACLE_countries.csv', mode='w') as dstCountries:

        pass

def links():
    """Table 'linked'"""
    pass


def main():
    genres()
    lang()

if __name__ == "__main__":
    main()