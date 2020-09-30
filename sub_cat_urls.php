<?php

require_once("../wp-load.php");
include("connection.php");
include("simple_html_dom.php");



$count=1;


$query=$con->prepare("SELECT * FROM cats_url WHERE url_status=0 ");
        $query->execute();
        $results=$query->get_result();
        if($results->num_rows>0)
        {
            while($row=$results->fetch_assoc())
            {
             
                $category_url=$row['cat_url'];

                $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL,$category_url);
                                    curl_setopt($ch,CURLOPT_POST, false);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                    $output = curl_exec($ch);
                                    curl_close($ch);

                                    $html= new simple_html_dom();
                                    $html->load($output);

                //==============================================================================================================


                                foreach($html->find('div[class=xwY9Zc]') as $sub_cat)
                                {
                                    foreach($sub_cat->find('a') as $sub_cat_links)
                                    {

                                        echo '<br>';
                                        $sub_link=trim($sub_cat_links->href);
                                        $sub_link="https://play.google.com".$sub_link;
                                        // echo $link=$sublink->plaintext;
                                        echo $sub_link;
                                        $sub_link = mysqli_real_escape_string($con, $sub_link);
                                         echo '<br>';



                                        $chk_qry=$con->prepare("SELECT * FROM sub_cats_url WHERE sub_cat_url='$sub_link' AND sub_url_status=0");
                                        $chk_qry->execute();
                                        $reslt=$chk_qry->get_result();

                                        if($reslt->num_rows===0)
                                        {
                                            $status=1;
                                            $idd=$row['url_id'];
                                        $query=$con->prepare("INSERT INTO sub_cats_url(sub_cat_url,url_id,sub_url_status) VALUES(?,?,?)");
                                        $query->bind_param("sss",$sub_link,$idd,$status);
                                        $query->execute();
                                        $query->close();
                                        echo 'Inserted : '.$sub_link."<br>";
                                        }
                                        else
                                        {
                                            echo"Sub_Link Already Available <br>";
                                        }

                        
                                        $chk_qry->close();


                                    }
                                }
                                echo'<h2>Counter'.$row['url_id'].'</h2> ';
                                echo'<h2>Next Category</h2>';

            }// End While Loop
        }// EnD record chk if

        else
        {
            echo "No records in Table";
        }
        


?>
<!-- https://play.google.com/store/apps/collection/cluster?clp=ogoaCA0SDkFSVF9BTkRfREVTSUdOKgIIB1ICCAE%3D:S:ANO1ljI8UeI&gsr=Ch2iChoIDRIOQVJUX0FORF9ERVNJR04qAggHUgIIAQ%3D%3D:S:ANO1ljJ-Slw -->
