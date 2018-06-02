<?php
    $clipTypePattern = array();
    $clipTypePattern[0] = "/\(V\)/";
    $clipTypePattern[1] = "/\(VG\)/";
    $clipTypePattern[2] = "/\(TV\)/";
    $clipTypePattern[3] = "/\(SE\)/";

    $clipTypeReplacement = array();
    $clipTypeReplacement[0] = "(Video)";
    $clipTypeReplacement[1] = "(Video Game)";
    $clipTypeReplacement[2] = "(TV Movie)";
    $clipTypeReplacement[3] = "(TV Episode)";


function getRowsQuery($conn, $query, $errQuery, $emptyQuery){
    $result = array();
    if(!$res = $conn->query($query)){
        $result = ['error' => $errQuery . ": " . $conn->error];
    } else{
        if($res->num_rows > 0){
            while($row = $res->fetch_assoc()){
                array_push($result, $row);
            }
        } else {
            $result = ['empty' => $emptyQuery];
        }
    }

    return $result;
}

function cleanText($conn, $text){
    $result = $text;
    $match = array();
    $allSubCids = array('"');
    $toReplace = array('""');
    preg_match_all("/_([^_]*) \((\d*)\)_/", $result, $match);
    $num_matches = count($match[0]);
    for ($i = 0; $i < $num_matches; $i++) {
        $title = $match[1][$i];
        $year = $match[2][$i];
        $subquery = "SELECT clipid FROM Clips WHERE cliptitle LIKE '" . str_replace(" ", "%", addslashes($title)) . "' AND clipyear = $year;";
        // echo $subquery . "<br><br>";
        $subres = $conn->query($subquery);
        if($subres->num_rows==0){
            continue;
        } else {
            $subcid = $subres->fetch_assoc()['clipid'];
            $linkedName = "<a href='?cid=$subcid'>$title</a> ($year)";
            array_push($allSubCids, $linkedName);
            array_push($toReplace, "_$title ($year)_");
        }
    }

    for ($i = 0; $i < count($toReplace); $i++) {
        $result = str_replace($toReplace[$i], $allSubCids[$i], $result);
    }
    return $result;

}
?>