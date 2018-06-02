<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1"/>
    <title>DB 2018 - Query results</title>

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
    <script>
        function showMore(toHide, toShow){
            document.getElementById(toShow).style.display = 'inline';
            document.getElementById(toHide).style.display = 'none';
        }

        function toggleFilmoShow(cat){
            var i, targetsInline;
            targetsInline = document.getElementsByClassName("SH-"+cat)
            targetsBlock = document.getElementsByClassName("filmo-body-"+cat)
            for(i=0; i<targetsInline.length; i++){
                if(targetsInline[i].style.display == 'none'){
                    targetsInline[i].style.display = 'inline';
                } else {
                    targetsInline[i].style.display = 'none';
                }
            }

            for(i=0; i<targetsBlock.length; i++){
                if(targetsBlock[i].style.display == 'none'){
                    targetsBlock[i].style.display = 'block  ';
                } else {
                    targetsBlock[i].style.display = 'none';
                }
            }
        }
    </script>
    <div class='container'>
        <?php
        require_once '/var/webconfig/dbConfig.php';
        require_once 'phpSnippets.php';
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        if (isset($_GET['pid'])) {
            //Searching for a person
            $pid = $_GET['pid'];
            if (!intval($pid)) {
                echo ('<h2>This id is not valid</h2>');
                echo ('<button onclick="window.location.href=\'index.php\'">Go back to home page</button>');
                die();
            }
            // get fullname
            $query = "SELECT fullname FROM People WHERE personid = $pid";
            if (!$res = $conn->query($query)) {
                die("Error querying this person: " . $conn->error);
            }
            $fullname = $res->fetch_assoc()['fullname'];

            // get biographies
            $biographies = array();
            $biographers = array();
            $query = "SELECT biography, biographer FROM Biographies WHERE personid = $pid;";
            if (!$res = $conn->query($query)) {
                echo ("Error querying biography: " . $conn->error);
            } else {
                if ($res->num_rows > 0) {
                    $biographies = array();
                    $biographers = array();
                    while ($row = $res->fetch_assoc()) {
                        $cleaned = cleanText($conn, $row['biography']);
                        array_push($biographies, $cleaned);
                        array_push($biographers, '(By ' . $row['biographer'] . ')');
                    }
                } else {
                    array_push($biographies, "No bio");
                    array_push($biographers, "");
                }
            }

            //get bioinfos
            $query = "SELECT * FROM Bioinfos WHERE Bioinfos.personid = $pid;";
            $bioinfos = getRowsQuery($conn, $query, "Error querying bioinfos", "No personnal information for $fullname.");

            ##########
            #Filmography
            ############

            //Director
            $query = "SELECT Clips.clipid, role, addinfo, cliptitle, clipyear 
                        FROM People, Directs, DirectsRoles, Clips 
                        WHERE People.personid = $pid 
                        AND Directs.personid = People.personid 
                        AND DirectsRoles.directsroleid = Directs.directsroleid 
                        AND Clips.clipid = Directs.clipid
                        ORDER BY clipyear DESC;";
            $directoring = getRowsQuery($conn, $query, "Error querying directors", "No work of director for " . $fullname);

            //Actor
            $query = "SELECT Clips.clipid as clipid, cliptitle, chars, orderscredit, addinfo, clipyear
                        FROM Clips, People, PlaysIn, PlaysInRoles
                        WHERE People.personid = $pid
                        AND People.personid = PlaysIn.personid
                        AND PlaysInRoles.playsinroleid = PlaysIn.playsinroleid
                        AND Clips.clipid = PlaysIn.clipid
                        AND PlaysInRoles.chars NOT LIKE '%himself%'
                        ORDER BY clipyear DESC;";
            $actoring = getRowsQuery($conn, $query, "Error querying actors", "No work of actor for " . $fullname);

            //Self
            $query = "SELECT Clips.clipid, cliptitle, chars, orderscredit, addinfo, clipyear
                        FROM Clips, People, PlaysIn, PlaysInRoles
                        WHERE People.personid = $pid
                        AND People.personid = PlaysIn.personid
                        AND PlaysInRoles.playsinroleid = PlaysIn.playsinroleid
                        AND Clips.clipid = PlaysIn.clipid
                        AND PlaysInRoles.chars LIKE '%himself%'
                        ORDER BY clipyear DESC;";
            $self = getRowsQuery($conn, $query, "Error querying actors", "No work of self for " . $fullname);



            //Producer
            $query = "SELECT Clips.clipid, role, addinfo , cliptitle , clipyear 
                        FROM Clips, People, Produces, ProducesRoles
                        WHERE Clips.clipid = Produces.clipid 
                        AND People.personid = Produces.personid
                        AND ProducesRoles.producesroleid = Produces.producesroleid
                        AND People.personid = $pid
                        ORDER BY clipyear DESC;";
            $productoring = getRowsQuery($conn, $query, "Error querying producers", "No work of producer for " . $fullname);

            //Writer
            $query = "SELECT Clips.clipid, role, addinfo , cliptitle , clipyear 
                        FROM Clips, People, Writes, WritesRoles
                        WHERE Clips.clipid = Writes.clipid 
                        AND People.personid = Writes.personid
                        AND WritesRoles.writesroleid = Writes.writesroleid
                        AND People.personid = $pid
                        ORDER BY clipyear DESC;";
            $writering = getRowsQuery($conn, $query, "Error querying writers", "No work of actor writer " . $fullname);

            ?>

            <h2><?php echo $fullname ?></h2>
            <button onclick="window.location.href='index.php'">Go back to home page</button>
            <div class="bioraphies">
            <h3>Biography:</h3>
            <?php 
            if (isset($biographies['error'])) {
                die($biographies['error']);
            }
            if (isset($biographies['empty'])) {
            } else {
                for ($i = 0; $i < count($biographies); $i++) {
                    echo "<div class='biography'>" . preg_replace('/""/', "'", $biographies[$i]) . "</div>" . "$biographers[$i]<br><br>";
                }
            }
            ?>
            </div>
            <?php
            if (!empty($bioinfos[0]['birth'])) {
                echo "<strong>Born</strong>: " . $bioinfos[0]['birth'];
            }
            if (!empty($bioinfos[0]['death'])) {
                echo "<br><strong>Died</strong>: " . $bioinfos[0]['death'];
            }
            ?>
            
            <div id='filmography'>
                <h3>Filmography</h3>
                <div id='filmo-head-actor'>
                    <?php 
                    if (isset($actoring['error'])) {
                        die($actoring['error']);
                    }
                    if (isset($actoring['empty'])) {;
                    } else { ?>
                            <big>Actor</big> (<?php echo count($actoring) ?> credits) 
                            <span class="SH-actor" onclick="toggleFilmoShow('actor')" style='display:inline; cursor:pointer'><a>Show</a></span>
                            <span class='SH-actor' onclick="toggleFilmoShow('actor')" style="display:none; cursor:pointer"><a>Hide</a></span>
                            <div class='filmo-body-actor' style='display:none'>
                                <table class="filmoTable">
                                    <thead>
                                        <tr>
                                        <td>Title<br><small>character</small></td><td>Year</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                <?php
                                for ($i = 0; $i < count($actoring); $i++) {
                                    $cid = $actoring[$i]['clipid'];
                                    $title = $actoring[$i]['cliptitle'];
                                    $year = $actoring[$i]['clipyear'];
                                    $char = $actoring[$i]['chars'];
                                    echo "<tr>";
                                    echo "<td><a href='?cid=$cid'>$title</a><br><small>$char </small></td><td>$year</td>";
                                    echo '</tr>';
                                }
                                ?>
                                </tbody>
                                </table>
                            </div>
                    <?php 
                    } 
                    ?>
                </div>
                <div id='filmo-head-director'>
                    <?php 
                    if (isset($actoring['error'])) {
                        die($actoring['error']);
                    }
                    if (isset($actoring['empty'])) {;
                    } else { ?>
                        <big>Director</big> (<?php echo count($directoring) ?> credits) 
                        <span class="SH-director" onclick="toggleFilmoShow('director')" style='display:inline; cursor:pointer'><a>Show</a></span> 
                        <span class='SH-director' onclick="toggleFilmoShow('director')" style="display:none; cursor:pointer"><a>Hide</a></span>
                        <div class='filmo-body-director' style='display:none'>
                            <table class="filmoTable">
                                <thead>
                                    <tr>
                                    <td>Title<br><small>role</small></td><td>Year</td>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php
                            for ($i = 0; $i < count($directoring); $i++) {
                                $cid = $directoring[$i]['clipid'];
                                $title = $directoring[$i]['cliptitle'];
                                $year = $directoring[$i]['clipyear'];
                                $role = $directoring[$i]['role'];
                                echo "<tr>";
                                echo "<td><a href='?cid=$cid'>$title</a><br><small>$role </small></td><td>$year</td>";
                                echo '</tr>';
                            }
                            ?>
                            </tbody>
                            </table>
                        </div>
                    <?php 
                    } 
                    ?>
                </div>
                <div id='filmo-head-producer'>
                    <?php 
                    if (isset($productoring['error'])) {
                        die($productoring['error']);
                    }
                    if (isset($productoring['empty'])) {;
                    } else { ?>
                        <big>Producer</big> (<?php echo count($productoring) ?> credits) 
                        <span class="SH-producer" onclick="toggleFilmoShow('producer')" style='display:inline; cursor:pointer'><a>Show</a></span>
                        <span class='SH-producer' onclick="toggleFilmoShow('producer')" style="display:none; cursor:pointer"><a>Hide</a></span>
                        <div class='filmo-body-producer' style='display:none'>
                            <table class="filmoTable">
                                <thead>
                                    <tr>
                                    <td>Title<br><small>character</small></td><td>Year</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    for ($i = 0; $i < count($productoring); $i++) {
                                        $cid = $productoring[$i]['clipid'];
                                        $title = $productoring[$i]['cliptitle'];
                                        $year = $productoring[$i]['clipyear'];
                                        $role = $productoring[$i]['role'];
                                        echo "<tr>";
                                        echo "<td><a href='?cid=$cid'>$title</a><br><small>$role </small></td><td>$year</td>";
                                        echo '</tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <?php 
                    } 
                    ?>
                </div>
                <div id='filmo-head-writer'>
                    <?php 
                    if (isset($writering['error'])) {
                        die($writering['error']);
                    }
                    if (isset($writering['empty'])) {;
                    } else { 
                    ?>
                        <big>Writer</big> (<?php echo count($writering) ?> credits) 
                        <span class="SH-writer" onclick="toggleFilmoShow('writer')" style='display:inline; cursor:pointer'><a>Show</a></span>
                        <span class='SH-writer' onclick="toggleFilmoShow('writer')" style="display:none; cursor:pointer"><a>Hide</a></span>
                        <div class='filmo-body-writer' style='display:none'>
                            <table class="filmoTable">
                                <thead>
                                    <tr>
                                    <td>Title<br><small>character</small></td><td>Year</td>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php
                            for ($i = 0; $i < count($writering); $i++) {
                                $cid = $writering[$i]['clipid'];
                                $title = $writering[$i]['cliptitle'];
                                $year = $writering[$i]['clipyear'];
                                $role = $writering[$i]['role'];
                                echo "<tr>";
                                echo "<td><a href='?cid=$cid'>$title</a><br><small>$role </small></td><td>$year</td>";
                                echo '</tr>';
                            }
                            ?>
                            </body>
                            </table>
                        </div>
                    <?php 
                    } 
                    ?>
                </div>
                <div id='filmo-head-self'>
                    <?php 
                        if(isset($self['error'])){
                            die($self['error']);
                        }
                        if(isset($self['empty'])){
                            ;
                        } else { 
                    ?>
                        <big>Self</big> (<?php echo count($self) ?> credits) 
                        <span class="SH-self" onclick="toggleFilmoShow('self')" style='display:inline; cursor:pointer'><a>Show</a></span>
                        <span class='SH-self' onclick="toggleFilmoShow('self')" style="display:none; cursor:pointer"><a>Hide</a></span>
                        <div class='filmo-body-self' style='display:none'>
                            <table class="filmoTable">
                                <thead>
                                    <tr>
                                    <td>Title<br><small>character</small></td><td>Year</td>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php
                            for ($i = 0; $i < count($self); $i++) {
                                $cid = $self[$i]['clipid'];
                                $title = $self[$i]['cliptitle'];
                                $year = $self[$i]['clipyear'];
                                $char = $self[$i]['chars'];
                                echo "<tr>";
                                echo "<td><a href='?cid=$cid'>$title</a><br><small>$char </small></td><td>$year</td>";
                                echo '</tr>';
                            }
                            ?>
                            </tbody>
                            </table>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div id="persoInfos">
                <h3>Personal Informations</h3>
                <?php
                if(isset($bioinfos['error'])) {
                    die($bioinfos['error']);
                }
                if(isset($bioinfos['empty'])){
                    echo "Nothing to display here...";
                } else{
                    echo "<ul>";
                    if(isset($bioinfos[0]['realname'])){echo "<li>Real Name: ". $bioinfos[0]['realname'] . "</li>";}
                    if(isset($bioinfos[0]['nickname'])){echo "<li>Nickname: ". $bioinfos[0]['nickname'] . "</li>";}
                    if(isset($bioinfos[0]['trademark'])){echo "<li>Trademark: ". $bioinfos[0]['trademark'] . "</li>";}
                    if(isset($bioinfos[0]['whereAreTheyNow'])){echo "<li>Where are they now ?: ". cleanText($conn, $bioinfos[0]['whereAreTheyNow']) . "</li>";}
                    if(isset($bioinfos[0]['height'])){echo "<li>Height: ". $bioinfos[0]['height'] . "</li>";}
                    if(isset($bioinfos[0]['spouse'])){echo "<li>Spouse: ". $bioinfos[0]['spouse'] . "</li>";}
                    if(isset($bioinfos[0]['trivia'])){echo "<li>Trivia: ". cleanText($conn, $bioinfos[0]['trivia']) . "</li>";}
                    if(isset($bioinfos[0]['personalQuote'])){echo "<li>Personal quote: ". cleanText($conn, $bioinfos[0]['personalQuote']) . "</li>";}
                    echo "</ul>";
                }?>
            </div>
        <?php
        } elseif (isset($_GET['cid'])) {

            $cid = $_GET['cid'];
            $genre = array();
            //Searching for a clip

            //select clip infos
            $query = "SELECT cliptitle, cliptype, clipyear, rank, votes 
            FROM Clips 
            WHERE Clips.clipid = $cid;";
            if (!$res = $conn->query($query)) {
                echo ("Error with title/genre query:" . $conn->error);
            } else {
                if ($res->num_rows > 0) {
                    $row = $res->fetch_assoc();
                    $title = $row['cliptitle'];
                    $year = $row['clipyear'];
                    $votes = $row['votes'];
                    $rank = $row['rank'];
                    $type = $row['cliptype'];
                } else {
                    die("Whoups, this film does not exist.");
                }
            } 
            //Genre
            $query = "SELECT cliptitle, genre 
                        FROM Clips, HasGenre, Genres 
                         Genres.genreid = HasGenre.genreid 
                        AND HasGenre.clipid = Clips.clipid 
                        AND Clips.clipid = $cid;";
            if (!$res = $conn->query($query)) {
                echo ("Error with title/genre query:" . $conn->error);
            } else {
                if ($res->num_rows > 0) {
                    while ($row = $res->fetch_assoc()) {
                        array_push($genre, $row['genre']);
                    }
                } else {
                    echo "No genre";
                }
            } 
            //running
            $query = "SELECT running, country 
                        FROM Clips, Running, Countries 
                        WHERE Clips.clipid = Running.clipid 
                        AND Running.countryid = Countries.countryid
                        AND Clips.clipid = $cid;";

            //actor CREDITED cast
            $query = "SELECT clipyear fullname, chars, orderscredit, addinfo, People.personid 
                        FROM People, PlaysIn, PlaysInRoles, Clips 
                        WHERE Clips.clipid = $cid 
                        AND PlaysIn.clipid = Clips.clipid
                        AND PlaysInRoles.playsinroleid = PlaysIn.playsinroleid 
                        AND People.personid = PlaysIn.personid 
                        AND PlaysInRoles.orderscredit IS NOT NULL
                        ORDER BY orderscredit ASC;";
            $actorsCredited = array();
            if (!$res = $conn->query($query)) {
                die("Check your Credited Actors query: " . $conn->error);
            } else {
                if ($res->num_rows > 0) {
                    while ($row = $res->fetch_assoc()) {
                        array_push($actorsCredited, $row);
                    }
                } else {
                    echo "No actor";
                }
            }

            //actor UN-CREDITED cast
            $query = "SELECT People.fullname, PlaysInRoles.chars, PlaysInRoles.orderscredit, PlaysInRoles.addinfo, PlaysIn.personid 
                        FROM People, PlaysIn, PlaysInRoles, Clips 
                        WHERE Clips.clipid = $cid 
                        AND PlaysIn.clipid = Clips.clipid
                        AND PlaysInRoles.playsinroleid = PlaysIn.playsinroleid 
                        AND People.personid = PlaysIn.personid 
                        AND PlaysInRoles.orderscredit IS NULL
                        ORDER BY orderscredit ASC;";
            $actorsUncredit = array();
            if (!$res = $conn->query($query)) {
                die("Check your UnCredited Actors query: " . $conn->error);
            } else {
                if ($res->num_rows > 0) {
                    while ($row = $res->fetch_assoc()) {
                        array_push($actorsUncredit, $row);
                    }
                } else {
                    echo "No actor";
                }
            }

            //producers cast
            $producers = array();
            $query = "SELECT fullname, role, addinfo 
                        FROM Clips, People, Produces, ProducesRoles 
                        WHERE People.personid = Produces.personid 
                        AND ProducesRoles.producesroleid = Produces.producesroleid 
                        AND Clips.clipid = Produces.clipid AND Clips.clipid = $cid;";
            if (!$res = $conn->query($query)) {
                die("Check your Producers query: " . $conn->error);
            } else {
                if ($res->num_rows > 0) {

                } else {

                }
            }
            //writers cast
            $query = "SELECT People.personid, fullname, role, worktype, addinfo 
                        FROM Clips, People, Writes, WritesRoles 
                        WHERE People.personid = Writes.personid 
                        AND WritesRoles.writesroleid = Writes.writesroleid 
                        AND Clips.clipid = Writes.clipid 
                        AND Clips.clipid = $cid;";
            $writers = array();
            if (!$res = $conn->query($query)) {
                die("Check your Writers query: " . $res->error);
            } else {
                if ($res->num_rows > 0) {
                    while ($row = $res->fetch_assoc()) {
                        array_push($writers, $row);
                    }
                } else {

                }
            }

            //director cast
            $query = "SELECT People.fullname, People.personid, DirectsRoles.role FROM Clips, Directs, DirectsRoles, People 
                        WHERE Clips.clipid = $cid 
                        AND Directs.clipid = Clips.clipid 
                        AND DirectsRoles.directsroleid = Directs.directsroleid 
                        AND People.personid = Directs.personid ;";
            $directors = array();
            if (!$res = $conn->query($query)) {
                die("Check your directors query");
            } else {
                if ($res->num_rows > 0) {
                    while ($row = $res->fetch_assoc()) {
                        array_push($directors, $row);
                    }
                } else {

                }
            }

            ?>
            <h2><?php echo "$title ($year)" ?></h2>
            Director: <?php for ($i = 0; $i < count($directors); $i++) {
                            echo ("<a href='?pid=" . $directors[$i]['personid'] . "'>" . $directors[$i]['fullname'] . "</a>");
                        } ?>

            <p>Writers:<?php for ($i = 0; $i < count($writers); $i++) {
                            echo ("<a href='?pid=" . $writers[$i]['personid'] . "'>" . $writers[$i]['fullname'] . "</a> <small>(" . $writers[$i]['worktype'] . ")</small>, ");
                        } ?>
            </p>
            <p>Stars: <span id='shortStarsID'>
                            <?php for ($i = 0; $i < min(3, count($actorsCredited)); $i++) {
                                echo ("<a href='?pid=" . $actorsCredited[$i]['personid'] . "'>" . $actorsCredited[$i]['fullname'] . "</a>, ");
                            }
                            for ($i = 0; $i < min(count($actorsUncredit), 3 - count($actorsCredited)); $i++) {
                                echo ("<a href='?pid=" . $actorsUncredit[$i]['personid'] . "'>" . $actorsUncredit[$i]['fullname'] . "</a>(" . $actorsUncredit[$i]['addinfo'] . "), ");
                            }
                            ?>
                            ...
                            <a class="moreLessButton" href="javascript:showMore('shortStarsID', 'longStarsID')">Show More...</a>
                        </span>
                        <div id='longStarsID' style='display:none'>
                            <a class='moreLessButton' href="javascript:showMore('longStarsID', 'shortStarsID')">Show Less...</a>
                            <table>
                                <tbody>
                                <?php 
                                for ($i = 0; $i < count($actorsCredited); $i++) {
                                    echo "<tr>";
                                    echo ("<td><a href='?pid=" . $actorsCredited[$i]['personid'] . "'>" . $actorsCredited[$i]['fullname'] . "</a></td>");
                                    echo ("<td>" . $actorsCredited[$i]['chars'] . "</td>");
                                    echo "</tr>";
                                }
                                for ($i = 0; $i < count($actorsUncredit); $i++) {
                                    echo "<tr>";
                                    echo ("<td><a href='?pid=" . $actorsUncredit[$i]['personid'] . "'>" . $actorsUncredit[$i]['fullname'] . "</a></td>");
                                    echo ("<td>" . $actorsUncredit[$i]['addinfo'] . "</td>");
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                            </table>
                        </div>
            </p>
            <?php

        } else {
            //Display search result
            $search = $_GET['search'];
            $sql_clips = "SELECT * FROM Clips WHERE cliptitle = '" . addslashes($search) . "' OR cliptitle LIKE '%$search%' OR cliptitle LIKE '" . preg_replace("/ /", "%", $search) . "';";
            $sql_people = "SELECT * FROM People WHERE fullname = '" . addslashes($search) . "' OR fullname LIKE '%" . addslashes($search) . "%';";

            $resultPeople = $conn->query($sql_people);
            if ($resultPeople === false) {
                die("Result failed, check your query");
            }
            echo "<h3>People</h3><br />";
            if ($resultPeople->num_rows > 0) {
                // output data of each row
                while ($row = $resultPeople->fetch_assoc()) {
                    echo "<strong>Name</strong> : <a href='?pid=" . $row['personid'] . "'>" . $row['fullname'] . "</a><br>
                ";
                }
            } else {
                echo "0 results";
            }

            $resultClips = $conn->query($sql_clips);
            if ($resultClips === false) {
                die("Result failed, check your query");
            }
            if ($resultClips->num_rows > 0) {
                echo "<h3>Clips</h3><br />";
                // output data of each row
                while ($row = $resultClips->fetch_assoc()) {
                    echo "<strong>Clip title</strong> : <a href='?cid=" . $row['clipid'] . "'>" . $row['cliptitle'] . "</a>" . (!empty($row['clipyear']) ? " (" . $row['clipyear'] . ")" : "") . "<br>
                ";
                }
            } else {
                echo "0 results";
            }

        }
        $conn->close();
        ?> 
    </div>
</body>
</html>