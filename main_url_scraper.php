<?php
include("connection.php");
        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, "https://fileinfo.com/browse/");

        curl_setopt($ch,CURLOPT_POST, false);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch);   
        $DOM= new DOMDocument(true);
        libxml_use_internal_errors(true);
        @$DOM->loadHTML($output);
        $elements=$DOM->getElementsByTagName('a');
        

        for($i=0; $i<$elements->length; $i++)
        {
            if(preg_match('/list/',$elements->item($i)->getAttribute('href'),$match))
            {
                $url="https://fileinfo.com".$elements->item($i)->getAttribute('href');

                $query1=$con->prepare("SELECT * FROM main_urls WHERE url=?");
                $query1->bind_param("s",$url);
                $query1->execute();
                $reslt=$query1->get_result();

                if($reslt->num_rows===0)
                {
                $query=$con->prepare("INSERT INTO main_urls(url) VALUES(?)");
                $query->bind_param("s",$url);
                $query->execute();
                $query->close();
                echo $url."<br>";
                }
                else
                {
                    echo"Data Already Available <br>";
                }

                $query1->close();
                

                
            }

            
        }
?>