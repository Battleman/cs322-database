<html>
<head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1"/>
    <title>DB 2018 - Query results</title>
</head>
<body>
    <div>
        <?php
        $servername = "localhost";
        $username = "olivier";
        $password = "olivier";
        $dbname = "db2018";

            // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if (isset($_POST['predef1'])) {
            $query = "SELECT DISTINCT Cl.cliptitle, R.running
                    FROM Clips Cl, Running R, Countries Co 
                    WHERE Cl.clipid = R.clipid AND R.countryid = Co.countryid AND Co.country = 'France' 
                    ORDER BY R.running DESC LIMIT 10;";
            $columns = array("cliptitle", "running");
            $humanCols = array("Clip Title", "Running");
        }
        if (isset($_POST['predef2'])) {
            $query = "SELECT COUNT(*), C.country
            FROM Released R, Countries C
            WHERE R.countryid = C.countryid
               AND YEAR(R.releasedate) = 2001
            GROUP BY C.countryid
            ORDER BY COUNT(*) DESC;";

            $columns = array("COUNT(*)", "country");
            $humanCols = array("Total", "Country");
        }
        if (isset($_POST['predef3'])) {
            $query = 'SELECT COUNT(*), G.genre
            FROM Genres G, HasGenre H, Released R, Countries C
            WHERE C.countryid=R.countryid
               AND R.clipid = H.clipid
               AND H.genreid=G.genreid
               AND C.country = "USA"
               AND YEAR(R.releasedate) > 2013
            GROUP BY G.genreid;';

            $columns = array("COUNT(*)", "genre");
            $humanCols = array("Total", "Genre");
        }
        if (isset($_POST['predef4'])) {
            $query = 'SELECT M.fullname
            FROM People M, PlaysIn Pl
            WHERE Pe.personid = Pl.personid
            GROUP BY Pl.personid
            ORDER BY COUNT(Pl.clipid) DESC LIMIT 1;';

            $columns = array("fullname");
            $humanCols = array("Name");
        }
        if (isset($_POST['predef5'])) {
            $query = 'SELECT COUNT(*)
            FROM Directs D
            GROUP BY D.personid
            ORDER BY COUNT(*) DESC LIMIT 1;';
            $columns = array("COUNT(*)");
            $humanCols = array("Total");
        }
        if (isset($_POST['predef6'])) {
            $query = 'SELECT M.fullname
            FROM Directs D, PlaysIn A, Writes W, Produces P, People M
             WHERE D.personid=A.personid AND D.clipid=A.clipid AND M.personid = D.personid
                OR D.personid=W.personid AND D.clipid=W.clipid AND M.personid = D.personid
                OR D.personid=P.personid AND D.clipid=P.clipid AND M.personid = D.personid
                OR W.personid=A.personid AND W.clipid=A.clipid AND M.personid = A.personid
                OR P.personid=A.personid AND P.clipid=A.clipid AND M.personid = A.personid
                OR W.personid=P.personid AND W.clipid=P.clipid AND M.personid = W.personid;';

            $columns = array("Fullname");
            $humanCols = array("Name");
        }
        if (isset($_POST['predef7'])) {
            $query = 'SELECT L.language
            FROM HasLang H, Languages L
            WHERE H.langid = L.langid
            GROUP BY H.langid
            ORDER BY COUNT(H.clipid) DESC LIMIT 10;';

            $columns = array("language");
            $humanCols = array("Language");
        }
        if (isset($_POST['predef8'])) {
            $query = 'SELECT M.fullname, B.realname, B.nickname
            FROM People M, Bioinfos B, PlaysIn A, Clips C
            WHERE C.cliptype = ‘user-specified’
                AND A.clipid = C.clipid
                AND A.personid = B.personid
                AND A.personid = M.personid
            GROUP BY A.personid
            ORDER BY COUNT(A.clipid) DESC LIMIT 1
            ';
            $columns = array("language");
            $humanCols = array("Language");
        }
       
        $result = $conn->query($query);
        if ($result === false) {
            die("Result failed, check your query");
        }
        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                for ($x = 0; $x < count($columns); $x++) {
                    echo '<b>' . $humanCols[$x] . "</b>: " . $row[$columns[$x]];
                    if ($x < count($columns) - 1) {
                        echo ' - ';
                    }
                }
                echo '<br>';
            }
        } else {
            print_r($result);
            echo "0 results";
        }
        $conn->close();
        ?> 
    </div>
</body>
</html>