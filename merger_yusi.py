import csv
import unicodecsv as unicsv
import pickle as pkl

def biographies():
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
        people = unicsv.writer(dst, delimiter=",", quotechar='"', encoding='utf-8', lineterminator='\n')
        for x,y in couple:
            nameDict[y] = x
            people.writerow([x,y])

    with open("namesDictionnary.pkl", 'wb') as n:
        pkl.dump(nameDict, n, pkl.HIGHEST_PROTOCOL)


def main():
    people()
    biographies()

main()


