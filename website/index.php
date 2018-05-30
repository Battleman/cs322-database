<!DOCTYPE html>
<html lang=en-GB>

<head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1" />
    <title>DB 2018 - Groupe 4</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" media="screen" href="index.css">
    <script src="index.js"></script>
</head>

<body>
    <?php
    require_once '/var/webconfig/dbConfig.php';
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    ?>
    <div class="container">
        <h1>IMDB Evolved - The new interface</h1>
        <div class="tab">
            <button class="tablinks" onclick="openTab(event, 'Welcome')" id="defaultOpen">Welcome</button>
            <button class="tablinks" onclick="openTab(event, 'Search')">Search</button>
            <button class="tablinks" onclick="openTab(event, 'Predefined')">Predefined</button>
            <button class="tablinks" onclick="openTab(event, 'I/D')">Insert/Delete</button>
            <button class="tablinks" onclick="openTab(event, 'Help')">HELP !</button>
            <button class="tablinks" onclick="openTab(event, 'Credits')">Credits</button>
        </div>

        <div id="Welcome" class="tabcontent">
            <h3>Welcome in this wonderful interface !</h3>
        </div>
        <div id="Search" class="tabcontent">
            <form action="search.php" method="GET">
                <fieldset>
                    <legend>Search in the database</legend>
                    <input type="text" name="search">
                    <br>
                    <input type="submit" value="Submit">
                    <input type="reset">
                </fieldset>
            </form>
        </div>
        <div id="Predefined" , class="tabcontent">
            <div class="vert_tab">
                <button class="vert_tablinks" onclick="openVertTab(event, 'predefined1')" id='defaulInsert'>Q 1.a</button>
                <button class="vert_tablinks" onclick="openVertTab(event, 'predefined2')">Q 1.b</button>
                <button class="vert_tablinks" onclick="openVertTab(event, 'predefined3')">Q 1.c</button>
                <button class="vert_tablinks" onclick="openVertTab(event, 'predefined4')">Q 1.d</button>
                <button class="vert_tablinks" onclick="openVertTab(event, 'predefined5')">Q 1.e</button>
                <button class="vert_tablinks" onclick="openVertTab(event, 'predefined6')">Q 1.f</button>
                <button class="vert_tablinks" onclick="openVertTab(event, 'predefined7')">Q 1.g</button>
                <button class="vert_tablinks" onclick="openVertTab(event, 'predefined8')">Q 1.h</button>
            </div>
            <div>
                <div id="predefined1" class="vert_tabcontent">
                    Find the name and length of the 10 longest clips that were released in France.
                    <form action="predefined.php" , method="POST">
                        <input type="submit" value="View" name="predef1">
                    </form>
                </div>
                <div id="predefined2" class="vert_tabcontent">
                    Find the number of clips released per country in 2001.
                    <form action="predefined.php" , method="POST">
                        <input type="submit" value="View" name="predef2">
                    </form>
                </div>
                <div id="predefined3" class="vert_tabcontent">
                    Find the numbers of clips per genre released in the USA after 2013
                    <form action="predefined.php" , method="POST">
                        <input type="submit" value="View" name="predef3">
                    </form>
                </div>
                <div id="predefined4" class="vert_tabcontent">
                    Find the name of actor/actress who has acted in more clips than anyone else.
                    <form action="predefined.php" , method="POST">
                        <input type="submit" value="View" name="predef4">
                    </form>
                </div>
                <div id="predefined5" class="vert_tabcontent">
                    Find the maximum number of clips any director has directed.
                    <form action="predefined.php" , method="POST">
                        <input type="submit" value="View" name="predef5">
                    </form>
                </div>
                <div id="predefined6" class="vert_tabcontent">
                    Find the names of people that had at least 2 different jobs in a single clip
                    <form action="predefined.php" , method="POST">
                        <input type="submit" value="View" name="predef6">
                    </form>
                </div>
                <div id="predefined7" class="vert_tabcontent">
                    Find the 10 most common clip languages.
                    <form action="predefined.php" , method="POST">
                        <input type="submit" value="View" name="predef7">
                    </form>
                </div>
                <div id="predefined8" class="vert_tabcontent">
                    Find the full name of the actor who has performed in the highest number of movies.
                    <form action="predefined.php" , method="POST">
                        <input type="submit" value="View" name="predef8">
                    </form>
                </div>
            </div>
        </div>
        <div id="I/D" class="tabcontent">
            <div class="vert_tab">
                <button class="vert_tablinks" onclick="openVertTab(event, 'insertClip')" id='defaulInsert'>Clips</button>
                <button class="vert_tablinks" onclick="openVertTab(event, 'insertPerson')">People</button>
                <button class="vert_tablinks" onclick="openVertTab(event, 'insertBiography')">Biographies</button>
                <button class="vert_tablinks" onclick="openVertTab(event, 'insertRelation')">Relation</button>
            </div>
            <div>
                <div id="insertClip" class="vert_tabcontent">
                    <form action="insertClip.php" method="POST">
                        <legend>Insert or delete a new clip in the database</legend>
                        Title*:
                        <input type="text" name="input_insertClipTitle" placeholder="Title" required>
                        <br> Year:
                        <input type="number" name="input_insertClipYear" placeholder="Year" min="0">
                        <br> Genre
                        <select name="input_insertClipGenre">
                            <option selected value> -- select an option -- </option>
                            <?php
                            $genreQuery = "SELECT genre FROM Genres;";
                            $resGenre = $conn->query($genreQuery);
                            while ($row = $resGenre->fetch_assoc()) {
                                $genre = $row['genre'];
                                echo "<option value='$genre'>$genre</option>
                                        "; // for line return
                            }
                            ?>
                        </select>
                        <br> Rank:
                        <input type="number" name="input_insertClipRank" placeholder="Rank" min="0">
                        <br> Votes:
                        <input type="number" name="input_insertClipVotes" placeholder="Votes" min="0">
                        <br> Language:
                        <input type="text" name="input_insertClipLang" placeholder="Language">
                        <br> Clip type:
                        <select name='input_insertClipType'>
                            <option selected value> -- select an option -- </option>
                            <option value="TV">TV movie</option>
                            <option value="V">Video movie</option>
                            <option value="VG">Video game movie</option>
                            <option value="SE">Serie</option>
                        </select>
                        <br> Country:
                        <select name='input_insertClipCountry'>
                            <option selected value> -- select an option -- </option>
                            <?php
                            $countriesQuery = "SELECT country FROM Countries;";
                            $resCountries = $conn->query($countriesQuery);
                            while ($row = $resCountries->fetch_assoc()) {
                                $country = $row['country'];
                                echo "<option value='$country'>$country</option>
                                ";
                            }
                            ?>
                        </select>
                        <br>
                        <input type="submit" value="Insert" name='insert'>
                        <input type="submit" value="Delete" name='delete'>
                    </form>
                </div>
                <div id="insertPerson" class="vert_tabcontent">
                    <form id="form_insertPerson" action="insertPerson.php" method="POST">
                        Person Name<span style="color:red">*</span>:
                        <input type="text" name="input_insertPersonName" required>
                        <br> 
                        Film Name<span style="color:red">*</span>:
                        <input type="text" name="input_insertPersonFilmName" required>
                        <select name='clipType'>
                            <option selected value> -- select an option -- </option>
                            <option value="TV">TV movie</option>
                            <option value="V">Video movie</option>
                            <option value="VG">Video game movie</option>
                            <option value="SE">Serie</option>
                        </select>
                        <input type="number" min="1700" placeholder="Year" name="filmYear">
                        <br>
                        <input type="button" value="Add role" onclick="addFields">
                        <br>
                        <div id=role1>
                            Role
                            <span style="color:red">*</span>:
                            <select onchange="roleSelector(event, this.value, 1);", name="roleSelect">
                                <option selected value>--Select an a role--</option>
                                <option value="producer">Producer</option>
                                <option value="writer">Writer</option>
                                <option value="actor">Actor</option>
                                <option value="director">Director</option>
                            </select>
                            <div class="insertPerson_role1" id="producer" style="display:none">
                                <input type="text" placeholder="Exact role" name="input_insertPersonExactRole_Producer">
                                <input type="text" placeholder="Additional information" name="input_insertPersonAddinfo_Producer">
                            </div>
                            <div class="insertPerson_role1" id="actor" style="display:none">
                                
                                <input type="text" placeholder="Character played" name="input_insertPersonCharacter_Actor">
                                <input type="number" min=1 placeholder="Order in credits" name="input_insertPersonOrderCredits_Actor">
                                <input type="text" placeholder="Additional information" name="input_insertPersonAddinfo_Actor">
                                <br>
                            </div>
                            <div class="insertPerson_role1" id="director" style="display:none">
                                <h3>This is the director tab</h3>
                            </div>
                            <div class="insertPerson_role1" id="writer" style="display:none">
                            <input type="text" placeholder="Character played" name="input_insertPersonRole_Writer">
                                <input type="number" min=1 placeholder="Order in credits" name="input_insertPersonWorkType_Writer">
                                <input type="text" placeholder="Additional information" name="input_insertPersonAddinfo_Writer">
                                <br>
                            </div>
                        </div>
                        <br>
                        <input type="submit" value="Insert a new person" name='insertNew'>
                        <input type="submit" value="Insert an existing person" name='insertExist'>
                        <input type="submit" value="Delete" name='delete'>
                    </form>
                </div>
                <div id="insertBiography" class="vert_tabcontent">
                    <form action="insert.php" method="POST">
                        Person Name:
                        <br>
                        <input type="text" name="input_insertPersonNameBio">
                        <br> Biography:
                        <br>
                        <input type="text" name="input_Biography">
                        <br>
                        <input type="submit" value="Insert" name='insert'>
                        <input type="submit" value="Delete" name='delete'>
                    </form>
                </div>
            </div>
        </div>
        <div id='Help' class="tabcontent">
            <h2>Like, dude ? You seriously need some help for an interface as simple as that ?</h2>
            <small>Get some help. Like, medically...</small>
        </div>
        <div id="Credits" class="tabcontent">
            <h3>Credits to the best team ever !</h3>
            Like, yeah, our team work is awesome. They do the dirty work, and I have fun with this website.
        </div>
    </div>

    <?php
    $conn->close();
    ?>
    <script>
        //open the welcome tab
        document.getElementById("defaultOpen").click();
    </script>
</body>

</html>