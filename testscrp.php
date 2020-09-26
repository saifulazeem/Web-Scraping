<?php


require_once("../wp-load.php");
include("connection.php");
include("simple_html_dom.php");

 $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,"https://play.google.com/store/apps/details?id=com.eline.neveralonemobile");
                    curl_setopt($ch,CURLOPT_POST, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $output = curl_exec($ch);
                    curl_close($ch);


                    $html= new simple_html_dom();
                    $html->load($output);


                    // $lasts_updated = $html->find('span[class=htlgb]', 0);
                    // $lasts_updated=trim($lasts_updated->plaintext);

                    // if($lasts_updated=="Learn More")
                    // {
                    // $last_updated = $html->find('span[class=htlgb]', 2);
                    // $last_updated=trim($last_updated->plaintext);
                    // echo 'If Running '.$last_updated;
                    // }

                    // else
                    // {
                    //     echo 'Else Runing ';

                    //     $last_updated=$lasts_updated;

                    //     echo $last_updated;
                    // }


                    $chk = $html->find('div[class=BgcNfc]', 0);
                    $mychk=trim($chk->plaintext);

                    if($mychk=="Updated")
                    {
                        $lasts_updated = $html->find('span[class=htlgb]', 0);
                        $last_updated=trim($lasts_updated->plaintext);

                        echo "IF Last date = ".$last_updated;
                        echo'<br>';
                    }

                    else
                    {
                        // $chks = $html->find('div[class=IxB2fe]', 0);
                        // $mychks=trim($chks->plaintext);
                        // echo '<br>';

                        // echo "Elsee My Check =  ".$mychk;
                        // echo '<br>';



                        // foreach($html->find('div[class=BgcNfc]') as $a)
                        // {
                        //     echo $chkks=trim($a->plaintext);
                        //     if($chkks=="Updated")
                        //     {
                                $lasts_updated = $html->find('span[class=htlgb]',2);
                                $last_updated=trim($lasts_updated->plaintext);

                                echo "else Last date = ".$last_updated;
                                echo'<br>';
                        //     }
                        //     echo '<br>';
                            
                        
                        // }


                    }







            // foreach($html->find('span[class=htlgb]') as $ldate)
            //             {
            //                 // $ftyp=$filetype;
            //                 echo $typ_error=$ldate->plaintext;
            //                 echo"<br>";
            //              }



?>