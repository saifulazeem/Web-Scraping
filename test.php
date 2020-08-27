<?php
include("simple_html_dom.php");
include("connection.php");

$q=$con->prepare("SELECT * FROM suburls");
$q->execute();
$result=$q->get_result();
while($row=$result->fetch_assoc())
{
    // echo $row['sub_url']."<br>";
    // echo $row['url_id'];
    // echo $row['sub_url_id'];



$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$row['url_address']);
curl_setopt($ch,CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
curl_close($ch); 


$html= new simple_html_dom();
$html->load($output);

foreach($html->find('h1') as $ex_name)
echo $ex_name;

foreach($html->find('h2') as $filetype)
echo $filetype;

foreach($html->find('td') as $element)
    echo $element.'<br>';


foreach($html->find('h2[class=question]') as $description)
echo $description;


foreach($html->find('div[class=infoBox]') as $description)
echo $description;
// $q=$con->prepare("SELECT sub_url FROM sub_urls WHERE url_id=4");
// $q->execute();
// $result=$q->get_result();
// while($row=$result->fetch_assoc())
// {
//     echo $row['sub_url']."<br>";
//     // echo $row['url_id'];
//     // echo $row['sub_url_id'];
// }

foreach($html->find('div[class=fileinfo]') as $ret)
echo $ret;

// $q=$con->prepare("SELECT sub_url FROM sub_urls WHERE url_id=4");
// $q->execute();
// $result=$q->get_result();
// while($row=$result->fetch_assoc())
// {
//     echo $row['sub_url']."<br>";
//     // echo $row['url_id'];
//     // echo $row['sub_url_id'];
// }

}
?>