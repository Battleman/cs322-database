<?php
$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = diassrv2.epfl.ch)(PORT = 1521)))(CONNECT_DATA=(SID=orcldias)))" ;

if($c = OCILogon("DB_2018_G04", "DB_2018_G04", $db))
{
    echo "Successfully connected to Oracle.\n";
    OCILogoff($c);
}
else
{
    $err = OCIError();
    echo "Connection failed." . $err[text];
}

?>


<?php

//create table users (userid varchar2(10), password varchar2(20), constraint pk_users primary key (userid));
//insert into users values('kharis', 'pass123');

// $nis = isset($_POST['nis']) == true ? $_POST['nis'] : '';
// $password= isset($_POST['password']) == true ? $_POST['password'] : '';



// if(empty($nis) or empty($password)){
//     echo "UserID or Password empty";}
// else
// {
    $db = "(DESCRIPTION =
        (ADDRESS = (PROTOCOL = TCP)(HOST = diassrv2.epfl.ch)(PORT = 1521))
        (CONNECT_DATA =
          (SERVER = DEDICATED)
          (SERVICE_NAME = XE)
        )
      )" ;
    $connect = oci_connect("HR", "hr", "XE");
    $query = "SELECT * from 'Genres'";
    $result = oci_parse($connect, $query);
    oci_execute($result);
    $tmpcount = oci_fetch($result);
    if ($tmpcount==1) {
        echo "Login Success";}
    else
    {
        echo "Login Failed";
    }
// }
?>