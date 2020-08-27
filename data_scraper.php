<?php

include("simple_html_dom.php");
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


        $html= new simple_html_dom();
        $html->load($output)->plaintext;
        foreach($html->find('h1') as $ex_name)
            $extension_name=$ex_name->plaintext;
            echo "Extension Name: ".$extension_name.'<br>';



        $DOM= new DOMDocument(true);
        libxml_use_internal_errors(true);
        @$DOM->loadHTML($output);
        $elements=$DOM->getElementsByTagName('td');
        $ins_chk=0;
        $developer=null;
        $cat=null;
        $format=null;
        $popularity=null;

        
    

        for($i=0; $i<$elements->length; $i++)
        {
            if(preg_match('//',$elements->item($i)->nodeValue,$match))
            {
                $data=$elements->item($i)->nodeValue;
                

                

                if($ins_chk==1)
                {
                    $developer=$data;

                    echo "Developers names : ".$developer.'<br>';

                    $ins_chk=0;
                }

                if($ins_chk==2)
                {
                    $popularity=$data;
                    echo "Ratings names : ".$popularity.'<br>'; //Place insert Query for popularity table in next line.

                    // $qury=$con->prepare("INSERT INTO ")

                    
                    $ins_chk=0;
                }

                if($ins_chk==3)
                {
                    $cat=$data;
                    echo "Categories names : ".$cat.'<br>';
                    $ins_chk=0;
                }

                if($ins_chk==4)
                {
                    $format=$data;
                    echo "Formate names : ".$format.'<br>';
                    $ins_chk=0;
                }

                

                if($data=="Developer")
                {
                    $ins_chk=1;
                }
                if($data=="Popularity")
                {
                    $ins_chk=2;
                }
                if($data=="Category")
                {
                    $ins_chk=3;
                }
                if($data=="Format")
                {
                    $ins_chk=4;
                }


                
                
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
                // echo "Recived Data : ".$data."<br>";
                // }
                // else
                // {
                //     echo"Data Already Available For Sub Url <br>";
                // }
                // $query1->close();


                //[^Developer | Popularity | Category | Format]+
            }
            if($developer!=null && $cat!=null && $format!=null && $popularity!=null)
                {
                    
                    $abt="Null";
                    $qry=$con->prepare("INSERT INTO file_extensions(sub_url_id,exe_name,developer,category,format,about,rating) VALUES(?,?,?,?,?,?,?)");
                    $qry->bind_param("sssssss",$row['sub_url_id'],$extension_name,$developer,$cat,$format,$abt,$popularity);
                    $qry->execute();
                    $qry->close();
                    echo"Row inserted <br>";

                    $developer=null;
                    $format=null;
                    $cat=null;
                    $popularity=null;

                }

            
        }









    }
    
}




?>