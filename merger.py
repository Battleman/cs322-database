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
        destination = unicsv.writer(
            dst, delimiter=",", quotechar='"', encoding='utf-8', lineterminator='\n')

        next(clips)
        next(ratings)

        header = [u'clipid', u'rank', u'cliptitle',
                  u'votes', u'clipyear', u'cliptype']
        destination.writerow(header)
        result = dict()
        for clipid, cliptitle, clipyear, cliptype in clips:
            result[clipid] = {'title': cliptitle, 'year': clipyear,
                              'type': cliptype, 'rank': None, 'votes': None}
        for r_clipid, r_votes, r_rank in ratings:
            result[r_clipid]['rank'] = r_rank
            result[r_clipid]['votes'] = r_votes
        for clipid in result:
            destination.writerow([clipid, result[clipid]['rank'], result[clipid]['title'],
                                  result[clipid]['votes'], result[clipid]['year'], result[clipid]['type']])

        # for clipid, cliptitle, clipyear, cliptype in clips:
        #     votes=None
        #     rank=None
        #     # print("#########\n", clipid, cliptitle, clipyear, cliptype)
        #     for r_clipid, r_votes, r_rank in ratings:
        #         if r_clipid == clipid:
        #             if(r_clipid == "1773839"):
        #                 votes = r_votes
        #                 rank = r_rank
        #                 break
        #     ratingCSV.seek(0)
        #     destination.writerow([clipid, rank, cliptitle, votes, clipyear, cliptype])


def lang():
    print('Treating lang')
    """Table 'languages' and 'hasLang'"""
    with open('db2018imdb/languages.csv', mode='r') as languagesCSV,\
            open('db2018imdb/ORACLE_languages.csv', mode='w') as dstLang,\
            open('db2018imdb/ORACLE_hasLang.csv', mode='w') as dstHasLang:
        next(languagesCSV)

        languages = csv.reader(languagesCSV, delimiter=',', quotechar='"')
        destinationLang = csv.writer(
            dstLang, delimiter=",", quotechar='"', lineterminator='\n')
        destinationHasLang = csv.writer(
            dstHasLang, delimiter=",", quotechar='"', lineterminator='\n')

        destinationLang.writerow(['langid', 'language'])
        destinationHasLang.writerow(['clipid', 'langid'])

        allLang = dict()
        uniqTuple = set()
        UID = 1

        for c in languages:
            try:
                cid, lang = c
            except ValueError:
                print(c)
                continue
            if not lang in allLang:
                allLang[lang] = UID
                UID += 1
            if not (cid, lang) in uniqTuple:
                destinationHasLang.writerow([cid, allLang[lang]])
                uniqTuple.add((cid, lang))

        for lang, id in allLang.items():
            destinationLang.writerow([id, lang])


def genres():
    print('Treating genres')
    """Table 'genres' and 'hasGenre'"""
    with open('db2018imdb/genres.csv', mode='r') as genresCSV,\
            open('db2018imdb/ORACLE_genres.csv', mode='w') as dstGenre,\
            open('db2018imdb/ORACLE_hasGenre.csv', mode='w') as dstHasGenre:
        next(genresCSV)

        genres = csv.reader(genresCSV, delimiter=',', quotechar='"')
        destinationGenre = csv.writer(
            dstGenre, delimiter=",", quotechar='"', lineterminator='\n')
        destinationHasGenre = csv.writer(
            dstHasGenre, delimiter=",", quotechar='"', lineterminator='\n')

        destinationGenre.writerow(['genreid', 'genre'])
        destinationHasGenre.writerow(['clipid', 'genreid'])

        allGenres = dict()
        uniqTuple = set()

        UID = 1

        for cid, g in genres:
            if not g in allGenres and g != "Genre":
                allGenres[g] = UID
                UID += 1
            if not (cid, g) in uniqTuple:
                destinationHasGenre.writerow([cid, allGenres[g]])
                uniqTuple.add((cid, g))

        for g, id in allGenres.items():
            destinationGenre.writerow([id, g])


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

        # skip title line
        next(countriesCSV)
        next(releaseCSV)
        next(runningCSV)

        # set CSV writer/reader
        countries = csv.reader(countriesCSV, delimiter=',', quotechar='"')
        running = csv.reader(runningCSV, delimiter=',', quotechar='"')
        releases = csv.reader(releaseCSV, delimiter=',', quotechar='"')
        destinationAssociated = unicsv.writer(
            dstAssociated, delimiter=",", quotechar='"', lineterminator='\n')
        destinationReleased = unicsv.writer(
            dstReleased, delimiter=",", quotechar='"', lineterminator='\n')
        destinationCountries = unicsv.writer(
            dstCountries, delimiter=",", quotechar='"', lineterminator='\n')
        destinationRunning = unicsv.writer(
            dstRunning, delimiter=",", quotechar='"', lineterminator='\n')

        destinationRunning.writerow(['clipid', 'countryid', 'running'])
        destinationCountries.writerow(['countryid', 'country'])
        destinationAssociated.writerow(['clipid', 'countryid'])
        destinationReleased.writerow(['clipid', 'countryid', 'releasedate'])

        uniqTuple = set()
        allCountries = dict()
        UID = 1
        print("in file countries.csv")
        for clipid, c in countries:
            if not c in allCountries:
                allCountries[c] = UID
                destinationCountries.writerow([UID, c])
                UID += 1
            if not (clipid, c) in uniqTuple:
                destinationAssociated.writerow([clipid, allCountries[c]])
                uniqTuple.add((clipid, c))

        print("In file release_dates")
        for clipId, country, releaseDate in releases:
            try:
                parsed = parser.parse(releaseDate)
            except ValueError:
                print("Unparsable date:", releaseDate)

            try:
                countryid = allCountries[country]
            except KeyError:
                print("This country was not found:", country)
                allCountries[country] = UID
                countryid = UID
                destinationCountries.writerow([UID, country])
                UID += 1
            finally:
                destinationReleased.writerow([clipId, countryid, parsed])

        print("In file running_times")
        uniqRuning = set()
        try:
            for clipid, releaseCountry, runningTime in running:
                if (clipid, releaseCountry, runningTime) in uniqRuning:
                    continue
                if(runningTime == ""):
                    runningTime = -1
                if releaseCountry == '':
                    releaseCountry = 'Worldwide'
                # try:
                #     runningTime = float(runningTime)
                # except ValueError:
                #     print("Film {} has strange running time: {}".format(clipid, runningTime))
                #     continue
                try:
                    allCountries[releaseCountry]
                except KeyError:
                    print("This country was not found:", releaseCountry)
                    allCountries[releaseCountry] = UID
                    destinationCountries.writerow([UID, releaseCountry])
                    UID += 1

                uniqRuning.add((clipid, releaseCountry, runningTime))
                destinationRunning.writerow(
                    [clipid, allCountries[releaseCountry], runningTime])
        except ValueError:
            print("Error at film", clipid)


def links():
    print('Treating links')
    """Table 'linked'"""

    with open('db2018imdb/clip_links.csv', mode='r') as linksCSV, open('db2018imdb/ORACLE_links.csv', mode='wb') as dstLinks, open('db2018imdb/ORACLE_linked.csv', mode='wb') as dstLinked:

        links = csv.reader(linksCSV, delimiter=',', quotechar='"')
        destinationLinked = unicsv.writer(
            dstLinked, delimiter=',', quotechar='"', lineterminator='\n')
        destinationLinks = unicsv.writer(
            dstLinks, delimiter=',', quotechar='"', lineterminator='\n')
        next(links)
        allLTypes = dict()
        UID = 1
        for lfrom, lto, ltype in links:
            if ltype not in allLTypes:
                allLTypes[ltype] = UID
                destinationLinked.writerow([lto, lfrom, UID])
                destinationLinks.writerow([UID, ltype])
                UID += 1
            else:
                destinationLinked.writerow([lto, lfrom, allLTypes[ltype]])
        print(allLTypes)


def biographies():
    print("Treating biographies")
    with open("namesDictionnary.pkl", "rb") as f:
        namesToId = pkl.load(f)

    with open('db2018imdb/biographies.csv', newline='', encoding='utf-8') as bioCSV, \
            open('db2018imdb/ORACLE_biographies.csv', mode='wb') as dstbio,\
            open('db2018imdb/ORACLE_bioinfos.csv', mode='wb') as dstinfo:

        bios = csv.reader(bioCSV, delimiter=',', quotechar='"')
        bioinfos = unicsv.writer(
            dstinfo, delimiter=",", quotechar='"', encoding='utf-8', lineterminator='\n')
        biographies = unicsv.writer(
            dstbio, delimiter=",", quotechar='"', encoding='utf-8', lineterminator='\n')
        next(bios)
        length = {'name': 0, 'realName': 0, 'nickName': 0, 'birth': 0, 'height': 0, 'bioPhy': 0, 'bioPher': 0, 'death': 0,
                  'spouse': 0, 'trivia': 0, 'books': 0, 'quotes': 0, 'salary': 0, 'trademark': 0, 'whereNow': 0}
        for x in bios:
            try:
                [name, realName, nickName, birth, height, bioPhy, bioPher, death,
                 spouse, trivia, books, quotes, salary, trademark, whereNow] = x
            except ValueError:
                print('Error at name', x[0],
                      "length is", len(x), "values are ")
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

            if [realName, nickName, birth, height, death, spouse,
                    trivia, books, quotes, salary, trademark, whereNow] != [""]*12:
                bioinfos.writerow([id, realName, nickName, trademark, birth, death,
                                   salary, whereNow, height, spouse, books, trivia, quotes])
    print('Max Lengths:', length)


def people():
    print("Treating people")

    def addPeople(csvSource, namesSet, debug=False):
        src = csv.reader(csvSource, delimiter=',', quotechar='"')
        next(src)
        # row=1
        for person in src:
            #     if debug and row > 52715 and row < 52725:
                # print(person, person[0], "Central Saint Martin" in person[0])
            namesSet.add(person[0])
            # row += 1
    uniqNames = set()

    with open('db2018imdb/actors.csv', newline='', encoding='utf-8') as actCSV:
        addPeople(actCSV, uniqNames)

    with open('db2018imdb/directors.csv', newline='', encoding='utf-8') as dirCSV:
        addPeople(dirCSV, uniqNames)

    with open('db2018imdb/producers.csv', newline='', encoding='utf-8') as proCSV:
        addPeople(proCSV, uniqNames, True)

    with open('db2018imdb/writers.csv', newline='', encoding='utf-8') as wriCSV:
        addPeople(wriCSV, uniqNames)

    with open('db2018imdb/biographies.csv', newline='', encoding='utf-8') as bioCSV:
        addPeople(bioCSV, uniqNames)

    sortedNames = sorted(uniqNames)
    couple = enumerate(sortedNames, start=1)
    nameDict = dict()

    with open('db2018imdb/ORACLE_people.csv', mode='wb') as dst:
        people = unicsv.writer(
            dst, delimiter=",", quotechar='"', encoding='utf-8', lineterminator='\n')
        for x, y in couple:
            nameDict[y] = x
            people.writerow([x, y])

    with open("namesDictionnary.pkl", 'wb') as n:
        pkl.dump(nameDict, n, pkl.HIGHEST_PROTOCOL)


def produces():
    with open("namesDictionnary.pkl", 'rb') as f:
        namesToId = pkl.load(f)
    with open('db2018imdb/producers.csv', 'r', newline='', encoding='utf-8') as prodCSV,\
            open('db2018imdb/ORACLE_produces.csv', 'wb') as prodDstCSV,\
            open('db2018imdb/ORACLE_producesroles.csv', 'wb') as roleDstCSV:
        next(prodCSV)
        src = csv.reader(prodCSV, delimiter=',', quotechar='"')
        dstProd = unicsv.writer(prodDstCSV, delimiter=',',
                                quotechar='"', lineterminator="\n")
        dstRole = unicsv.writer(roleDstCSV, delimiter=',',
                                quotechar='"', lineterminator="\n")
        roleId = 1
        for name, clipsIds, roles, addinfos in src:
            clipsList = clipsIds[1:-1].split('|')
            rolesList = roles[1:-1].split('|')
            addinfosList = addinfos[1:-1].split('|')
            pid = namesToId[name]
            numRoles = len(clipsList)
            zippedRoles = zip(range(roleId, roleId+numRoles),
                              rolesList, addinfosList)
            zippedProduces = zip([pid]*numRoles, clipsList,
                                 range(roleId, roleId+numRoles))
            roleId += numRoles
            for role in zippedRoles:
                dstRole.writerow([role[0], role[1], role[2]])
            for prod in zippedProduces:
                # print(prod)
                dstProd.writerow([prod[0], prod[1], prod[2]])


def directs():
    with open("namesDictionnary.pkl", 'rb') as f:
        namesToId = pkl.load(f)
    with open('db2018imdb/directors.csv', 'r', newline='', encoding='utf-8') as dirCSV,\
            open('db2018imdb/ORACLE_directs.csv', 'wb') as directsCSV,\
            open('db2018imdb/ORACLE_directsroles.csv', 'wb') as roleDstCSV:

        next(dirCSV)
        src = csv.reader(dirCSV, delimiter=',', quotechar='"')
        dstDirec = unicsv.writer(
            directsCSV, delimiter=',', quotechar='"', lineterminator="\n")
        dstRole = unicsv.writer(roleDstCSV, delimiter=',',
                                quotechar='"', lineterminator="\n")
        roleId = 1
        for name, clipsIds, roles, addinfos in src:
            pid = namesToId[name]

            clipsList = clipsIds[1:-1].split('|')
            rolesList = roles[1:-1].split('|')
            addinfosList = addinfos[1:-1].split('|')

            numRoles = len(rolesList)

            zippedRoles = zip(range(roleId, roleId+numRoles),
                              rolesList, addinfosList)
            zippedProduces = zip([pid]*numRoles, clipsList,
                                 range(roleId, roleId+numRoles))

            roleId += numRoles

            for role in zippedRoles:
                # print(role)
                dstRole.writerow([role[0], role[1], role[2]])
            for prod in zippedProduces:
                dstDirec.writerow([prod[0], prod[1], prod[2]])


def plays():
    with open("namesDictionnary.pkl", 'rb') as f:
        namesToId = pkl.load(f)
    with open('db2018imdb/actors.csv', 'r', newline='', encoding='utf-8') as playsCSV,\
            open('db2018imdb/ORACLE_playsin.csv', 'wb') as playsDstCSV,\
            open('db2018imdb/ORACLE_playsinroles.csv', 'wb') as playroleDstCSV:

        next(playsCSV)
        src = csv.reader(playsCSV, delimiter=',', quotechar='"')
        dstDirec = unicsv.writer(
            playsDstCSV, delimiter=',', quotechar='"', lineterminator="\n")
        dstRole = unicsv.writer(
            playroleDstCSV, delimiter=',', quotechar='"', lineterminator="\n")
        roleId = 1
        for name, clipsIds, chars, orderCredits, addinfos in src:
            pid = namesToId[name]

            clipsList = clipsIds[1:-1].split('|')
            ordCredList = orderCredits[1:-1].split('|')
            addinfosList = addinfos[1:-1].split('|')
            charsList = chars[1:-1].split('|')
            numRoles = len(clipsList)
            zippedRoles = zip(range(roleId, roleId+numRoles),
                              charsList, ordCredList, addinfosList)
            zippedProduces = zip([pid]*numRoles, clipsList,
                                 range(roleId, roleId+numRoles))

            roleId += numRoles

            for role in zippedRoles:
                dstRole.writerow([role[0], role[1], role[2], role[3]])
            for prod in zippedProduces:
                dstDirec.writerow([prod[0], prod[1], prod[2]])


def writes():
    with open("namesDictionnary.pkl", 'rb') as f:
        namesToId = pkl.load(f)
    with open('db2018imdb/writers.csv', 'r', encoding='utf8') as writesCSV,\
            open('db2018imdb/ORACLE_writes.csv', 'wb') as writesDstCSV,\
            open('db2018imdb/ORACLE_writesroles.csv', 'wb') as writesRoleCSV:

        next(writesCSV)
        src = csv.reader(writesCSV, delimiter=',', quotechar='"')
        dstWrites = unicsv.writer(
            writesDstCSV, delimiter=',', quotechar='"', lineterminator='\n')
        dstRole = unicsv.writer(
            writesRoleCSV, delimiter=',', quotechar='"', lineterminator='\n')
        roleId = 1

        for name, clipsIds, worktype, roles, addinfos in src:
            pid = namesToId[name]

            clipsList = clipsIds[1:-1].split('|')
            rolesList = roles[1:-1].split('|')
            addinfosList = addinfos[1:-1].split('|')
            workTypeList = worktype[1:-1].split('|')
            numRoles = len(rolesList)

            zippedWrites = zip([pid]*numRoles, clipsList,
                               range(roleId, roleId+numRoles))
            zippedRoles = zip(range(roleId, roleId+numRoles),
                              rolesList, workTypeList, addinfosList)
            roleId += numRoles
            for writer in zippedWrites:
                dstWrites.writerow([writer[0], writer[1], writer[2]])
            for role in zippedRoles:
                dstRole.writerow([role[0], role[1], role[2], role[3]])


def main():
    # genres()
    # lang()
    # clips()
    # links()
    # countries()
    people()
    # biographies()

    write_time = time.time()
    writes()
    print("Time for writer: ", time.time()-write_time)

    actors_time = time.time()
    plays()
    print("Time for actors: ", time.time()-actors_time)

    prod_time = time.time()
    produces()
    print("Time for produces: ", time.time()-prod_time)

    directs_time = time.time()
    directs()
    print("Time for directs: ", time.time()-directs_time)


if __name__ == "__main__":
    main()
