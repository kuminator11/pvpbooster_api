<?php 
$servername = 'localhost'; 
$username = 'srv46163_pvpbooster';
$pass = 'Kutas123@@';
$dbname = 'srv46163_pvpbooster';

$GLOBALS['conn'] = mysqli_connect($servername, $username, $pass, $dbname);
if(!$GLOBALS['conn']){
    die("Connection failed." . mysqli_connect_erro());
}
else{
    return 'connected to sql';
}
//sprawdzic czy przez formularz na stronie sa przesylane dane do bazy danych 

//mysqli_close($conn);
?>