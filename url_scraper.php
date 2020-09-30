<?php

require_once("../wp-load.php");
include("connection.php");
include("simple_html_dom.php");

 $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,"https://play.google.com/store/apps");
                    curl_setopt($ch,CURLOPT_POST, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $output = curl_exec($ch);
                    curl_close($ch);

                    $html= new simple_html_dom();
                    $html->load($output);

//==============================================================================================================


                foreach($html->find('div[class=LNKfBf]') as $cat)
                {
                    foreach($cat->find('ul') as $ul)
                    {
                        
                        foreach($ul->find('li') as $li)
                        {
                            
                            $link=$li->find('a',0);
   
                            echo '<br>';
                            $link=trim($link->href);
                            $link="https://play.google.com".$link;
                            // echo $link=$link->plaintext;
                             echo '<br>';
                             
                            

                            if($link=="https://play.google.com" || $link=="https://play.google.com/store/apps/category/GAME")
                            {
                                echo"Link Already Available <br>";
                            }

                            else
                            {
                            
                                $chk_qry=$con->prepare("SELECT * FROM cats_url WHERE cat_url='$link'");
                                $chk_qry->execute();
                                $reslt=$chk_qry->get_result();

                                if($reslt->num_rows===0)
                                {
                                    $status=1;
                                $query=$con->prepare("INSERT INTO cats_url(cat_url,url_status) VALUES(?,?)");
                                $query->bind_param("ss",$link,$status);
                                $query->execute();
                                $query->close();
                                echo 'Inserted : '.$link."<br>";
                                }
                                else
                                {
                                    echo"Link Already Available <br>";
                                }

                
                                $chk_qry->close();
                            }

                            if($link=="https://play.google.com/store/apps/category/GAME_WORD")
                                {
                                break;
                                }

                            
                            
                        }
                    break;
        
                    
                    }
                
                break;

                }



            
                    

                //****************************************************************************************************** */

              
                









      
                    // $DOM= new DOMDocument(true);
                    // libxml_use_internal_errors(true);
                    // @$DOM->loadHTML($output);
                    // $elements=$DOM->getElementsByTagName('a');
                    
            
                    // for($i=0; $i<$elements->length; $i++)
                    // {
                    //     if(preg_match('/apps/',$elements->item($i)->getAttribute('href'),$match))
                    //     {
                    //         $url="https://play.google.com/".$elements->item($i)->getAttribute('href');
                    //         echo $url;
                    //         echo '<br>';

                    //     }
                    // }

               



        // $cat_url=$html->find('div[class=zxErU]',0);
        // echo '<br>';
        // echo $cat_url=trim($cat_url->plaintext);
        // echo '<br>';

        // foreach ($html->find('div[class=LNKfBf]') as $cat)
        // {
        //     foreach($cat->find('li[class=CRHL7b eZdLre]') as $mcat)
        //     {
        //     echo '<br>';
        //     echo $link=trim($mcat->plaintext);
        //     echo '<br>';
        //     }
        // }








?>