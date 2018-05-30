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
?>