<?php

include("connection.php");


$q=$con->prepare("SELECT sub_url FROM sub_urls WHERE url_id=4");
$q->execute();
$result=$q->get_result();
while($row=$result->fetch_assoc())
{
    echo $row['sub_url']."<br>";
    // echo $row['url_id'];
    // echo $row['sub_url_id'];
}

?>