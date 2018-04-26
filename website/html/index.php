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
    <div class="container">
        <h1>IMDB Evolved - The new interface</h1>
        <div <div class="tab">
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
            <form action="query.php" method="POST">
                <fieldset>
                    <legend>Your first query</legend>
                    Search the whole database:
                    <input type="text" name="input_search">
                    <br>
                    <input type="submit" value="Submit">
                    <input type="reset">
                </fieldset>
            </form>
        </div>
        <div id="Predefined" , class="tabcontent">
            <h3>Predefined queries</h3>
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
                    <form action="insert.php" method="POST">
                        Title:
                        <br>
                        <input type="text" name="input_insertClipTitle">
                        <br> Year:
                        <br>
                        <input type="text" name="input_insertClipYear">
                        <br>
                        <!--Select the genre-->
                        Genre:
                        <ul class="checkbox-grid">
                            <li>
                                <input type="checkbox" name="genre[]" value="horror">Horror</input>
                            </li>
                            <li>
                                <input type="checkbox" name="genre[]" value="romantic">Romantic</input>
                            </li>
                            <li>
                                <input type="checkbox" name="genre[]" value="sad">Sad</input>
                            </li>
                            <li>
                                <input type="checkbox" name="genre[]" value="thriller">Thriller</input>
                            </li>
                            <?php
                            ?>
                        </ul>
                        <input type="submit" value="Insert" name='insert'>
                        <input type="submit" value="Delete" name='delete'>
                    </form>
                </div>
                <div id="insertPerson" class="vert_tabcontent">
                    <form action="insert.php" method="POST">
                        Person Name:
                        <br>
                        <input type="text" name="input_insertPersonName">
                        <br> Person Age:
                        <br>
                        <input type="text" name="input_insertPersonAge">
                        <br>
                        <input type="submit" value="Insert" name='insert'>
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
    <script>
        //open the welcome tab
        document.getElementById("defaultOpen").click();
    </script>
</body>

</html>