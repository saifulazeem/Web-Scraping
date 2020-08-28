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

$typ_des="";
$typ_num="";
$typ_os="";
$des_head="";

foreach($html->find('h1') as $ex_name){
    echo $ex_name.'<br>';}




foreach($html->find('section[class=ext]') as $a)
{



foreach($a->find('h2[!class]') as $filetype){
    // $ftyp=$filetype;
    echo $typ_num=$filetype->plaintext;
    echo"<br>";}

// foreach($html->find('td') as $element)
//     echo $element.'<br>';


foreach($a->find('h2[class=question]') as $qq){
    echo $des_head=$qq->plaintext;
    echo"<br>";}


foreach($a->find('div[class=infoBox] p') as $element){
    echo $typ_des=$element->plaintext;
    echo"<br>";}

// foreach($html->find('td[class=platform]') as $description)
// echo "OS name : ".$description.'<br>';



foreach($a->find('table[class=programs]') as $description){
// echo "Prgrams Data : ".$description.'<br>';
    echo $typ_os=$description->plaintext;
    echo"<br>";}




    $qrys=$con->prepare("INSERT INTO file_types(sub_url_id,types_name,type_des,typ_dec_head,os_info) VALUES(?,?,?,?,?)");
    $qrys->bind_param("sssss",$row['sub_url_id'],$typ_num,$typ_des,$des_head,$typ_os);
    $qrys->execute();
    $qrys->close();
        
    echo"Data Entered <br>";



}



 

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