<?php 

require_once("../wp-load.php");
include("connection.php");
include("simple_html_dom.php");


$my_qry=$con->prepare("SELECT * FROM app_data WHERE availability_status=0");
$my_qry->execute();
$result=$my_qry->get_result();

if($result->num_rows>0) // Start if for Record Check

{

        while($row=$result->fetch_assoc())
        {

            //Start Making Urls
            $app_url=$row['play_store_id'];

            $my_app_url='https://play.google.com/store/apps/details?id='.$app_url;

            echo $my_app_url;
            echo '<br>';

            // End Making Urls
            

            $app_id=$row['ID'];

            $app_post_slug=$row['post_name'];

            $app_cat=$row['app_category'];

            $app_imgs=$row['app_img'];

            $app_dev=$row['dev_name'];

            $app_name=$row['app_name'];



            $app_store_status=$row['playstore'];

            $app_post_id=$row['post_id'];

            $app_post_date=$row['post_publish_date'];



            // Creating Dom for HTML

            $html = file_get_html($my_app_url);

                //*important check if Html Found then Scrap Data Accorind to Pattern 1
            if($html)
            {
                $title_dom1 = $html->find('h1.AHFaub span', 0);
                $title_dom1=trim($title_dom1->plaintext);
                // $title = $title_dom1.' For PC / Windows 7/8/10 / Mac';
                $title = $title_dom1;
                echo '<h3>Application Title: '.$title.'</h3>';
                //var_dump("Name: ".$title);
            
                $dev_name = $html->find('a[class=hrTbp R8zArc]', 0);
                $dev_name=trim($dev_name->plaintext);

                echo '<h3>Developer Name: '.$dev_name.'</h3>';
                echo '<br>';

                $images_dom = $html->find('img[alt=Cover art]', 0);
                $image = false;
                if($images_dom)
                {
    		        $image1=trim($images_dom->src);
    		    }
                $image_par= explode('=', $image1);
                $image2= "http:".$image_par[0]."=w300";
                $image_par2= explode('/', $image2);
                $image = "http://".$image_par2[2]."/". $image_par2[3];

                echo '<h3>Image URL: '.$image.'</h3>';
                
                echo '<br>';

                $category_name = $html->find('a[itemprop=genre]', 0);
                $category_name=trim($category_name->plaintext);
                echo '<h3>Category: '.$category_name.'</h3>';
                
                echo '<br>';
                //var_dump("Category: ".$category_name);
            
            
                $rating = $html->find('div[class=BHMmbe]', 0);
                $rating=trim($rating->innertext);
                echo '<h3>Rating: '.$rating.'</h3>';
                
                echo '<br>';
                //var_dump("Rating: ".$rating);
            
                $total_ratings = $html->find('span[class=EymY4b] span', 1);
                $total_ratings=trim($total_ratings->plaintext);
                echo '<h3>Total Rating: '.$total_ratings.'</h3>';
                
                echo '<br>';
                //var_dump("Total Ratings: ".$total_ratings);
            
                $last_updated = $html->find('span[class=htlgb]', 0);
                $last_updated=trim($last_updated->plaintext);
                echo '<h3>Last Updated On : '.$last_updated.'</h3>';
                
                echo '<br>';
                //var_dump("Last updated: ".$last_updated);
            
                $size = $html->find('span[class=htlgb]', 2);
                $size=trim($size->plaintext);
                echo '<h3>App Size: '.$size.'</h3>';
                
                echo '<br>';
                //var_dump("size: ".$size);
            
                $Installs = $html->find('span[class=htlgb]', 4);
                $Installs = trim($Installs->plaintext);
                echo '<h3> Installs : '.$Installs.'</h3>';
                
                echo '<br>';
                //var_dump("Installs: ".$Installs);
            
                $Current_Version = $html->find('span[class=htlgb]', 6);
                $Current_Version= trim($Current_Version->plaintext);
                echo '<h3> Version: '.$Current_Version.'</h3>';
                
                echo '<br>';
                //var_dump("Current Version: ".$Current_Version);
            
                $Req_Android = $html->find('span[class=htlgb]', 8);
                $Req_Android=trim($Req_Android->plaintext);
                echo '<h3>Req Android: '.$Req_Android.'</h3>';
                
                echo '<br>';
                //var_dump("Req Android: ".$Req_Android);
            
                $Content_Rating = $html->find('span[class=htlgb]', 10);
                $Content_Rating=trim($Content_Rating->plaintext);
                echo '<h3>Content Rating: '.$Content_Rating.'</h3>';

                if ($category_name == 'Action'||$category_name == 'Adventure'||$category_name == 'Arcade'||$category_name == 'Board'||$category_name == 'Card'||$category_name == 'Casino'||$category_name == 'Casual'||$category_name == 'Educational'||$category_name == 'Music'||$category_name == 'Puzzle'||$category_name == 'Racing'||$category_name == 'Role Playing'||$category_name == 'Simulation'||$category_name == 'Sports'||$category_name == 'Strategy'||$category_name == 'Trivia'||$category_name == 'Word')
                {
                    $category = 'Game';
                }
            
                else
                {
                    $category ='App';
                }


                $content_dom = $html->find('div[jsname=sngebd]', 0);
    	        $content = $content_dom->plaintext;
    
    	        //var_dump("Description: ".$content);
    
    
    	        $whats_new = $html->find('div[itemprop=description] span', 1);
    	        $whats_new=trim($whats_new->innertext);
                //var_dump("Whats new: ".$whats_new);
                echo '<h3> Description</h3> ';
                echo $content.'<br>'.$whats_new;




                /// Start makinking Post for here ============================================================

                $post_content="";






                
                echo '<br>';
                echo '<br>';







                echo '<br>';


            }

                //*important check if Html Not Found then Display Data Accorind to Pattern 0
            else
            {
                
                echo 'no Html Found';
            }











        }


} //End If for Records Check



else
{
    echo 'No Records Found';
}

$my_qry->close();


?>