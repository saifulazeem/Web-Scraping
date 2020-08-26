<?php


include("connection.php");
$query=$con->prepare("SELECT * FROM suburls");
$query->execute();
$results=$query->get_result();

if($results->num_rows>0)
{
    while($row=$results->fetch_assoc())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$row['url_address']);
        curl_setopt($ch,CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch); 

        $DOM= new DOMDocument(true);
        libxml_use_internal_errors(true);
        @$DOM->loadHTML($output);
        $elements=$DOM->getElementsByTagName('td');
        for($i=0; $i<$elements->length; $i++)
        {
            if(preg_match('/[^Developer | Popularity | Category | Format]+/',$elements->item($i)->nodeValue,$match))
            {
                $data=$elements->item($i)->nodeValue;
                
                // $query1=$con->prepare("SELECT * FROM suburls WHERE url_address=?");
                // $query1->bind_param("s",$url);
                // $query1->execute();
                // $reslt=$query1->get_result();
                
                // if($reslt->num_rows===0)
                // {
                // $query=$con->prepare("INSERT INTO suburls(url_id,url_address) VALUES(?,?)");
                // $query->bind_param("ss",$row['url_id'],$url);
                // $query->execute();
                // $query->close();
                echo "Recived Data : ".$data."<br>";
                // }
                // else
                // {
                //     echo"Data Already Available For Sub Url <br>";
                // }
                // $query1->close();
            }

            
        }









    }
    
}




?>