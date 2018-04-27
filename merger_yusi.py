import csv
import unicodecsv as unicsv
import pickle as pkl

def biographies():
    with open("namesDictionnary.pkl", "rb") as f:
        namesToId = pkl.load(f)
    
    with open('db2018imdb/biographies.csv', newline='', encoding='utf-8') as bioCSV, \
    open('db2018imdb/ORACLE_biographies.csv', mode='wb') as dstbio,\
    open('db2018imdb/ORACLE_bioinfo.csv', mode='wb') as dstinfo:

        bios = csv.reader(bioCSV, delimiter=',', quotechar='"')
        bioinfos = unicsv.writer(dstinfo, delimiter=",", quotechar='"', encoding='utf-8')
        biographies = unicsv.writer(dstbio, delimiter=",", quotechar='"', encoding='utf-8')

        next(bios)
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

            if bioPhy != "" or bioPher != "":
                biographies.writerow([id, bioPhy, bioPher])
            
            if [realName, nickName, birth, height, death, spouse,\
                trivia, books, quotes, salary, trademark, whereNow] != [""]*12:
                bioinfos.writerow([id, realName, nickName, trademark, birth, death,\
                                    salary, whereNow, height, spouse, books, trivia, quotes])
           
def addPeople(csvSource, namesSet):
    src = csv.reader(csvSource, delimiter=',', quotechar='"')
    next(src)
    for person in src:
        namesSet.add(person[0])
def people():
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
        people = unicsv.writer(dst, delimiter=",", quotechar='"', encoding='utf-8')
        for x,y in couple:
            nameDict[y] = x
            people.writerow([x,y])

    with open("namesDictionnary.pkl", 'wb') as n:
        pkl.dump(nameDict, n, pkl.HIGHEST_PROTOCOL)


def main():
    people()
    biographies()

main()


