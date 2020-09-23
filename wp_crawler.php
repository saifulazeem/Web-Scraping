<?php
/**
 * WordPress Cron Implementation for hosts, which do not offer CRON or for which
 * the user has not set up a CRON job pointing to this file.
 *
 * The HTTP request to this file will not slow down the visitor who happens to
 * visit when the cron job is needed to run.
 *
 * @package WordPress
 */

ignore_user_abort(true);

if ( !empty($_POST) || defined('DOING_AJAX') || defined('DOING_CRON') )
	die();

/**
 * Tell WordPress we are doing the CRON task.
 *
 * @var bool
 */
define('DOING_CRON', true);

if ( !defined('ABSPATH') ) {
	/** Set up WordPress environment */
	require_once( dirname( __FILE__ ) . '/wp-load.php' );
	require_once( dirname( __FILE__ ) . '/simple_html_dom.php' );
}

global $wpdb;

// Get Links From Table Wp_crawl
$blog_title = get_bloginfo('name');
$site_url = site_url();
$posturl = the_permalink();





    $row_data = $wpdb->get_row("SELECT * FROM `wp_crawl` WHERE `status` = 0");
    
    if (!$row_data) {
    	echo "Crawler is Hungry !! Feed me the Links :) ";
    	return;
    }
    
    $url = $row_data->url;
    
    $g_id_1 = explode("=", $url);
    $g_id = $g_id_1[1];
    //var_dump("Play ID: ".$g_id);
    
     $old_data = $wpdb->get_row("SELECT * FROM `app_data` WHERE `play_store_id` = '$g_id'");
     $slug = $old_data->post_name;
     $post_publish_date = $old_data->post_publish_date;
    
     
    
    // Create DOM from URL or file
    $html = file_get_html($url);
    if($html){
    	$title_dom1 = $html->find('h1.AHFaub span', 0);
    	$title_dom1=trim($title_dom1->plaintext);
    	$title = $title_dom1.' For PC / Windows 7/8/10 / Mac';
    	//var_dump("Name: ".$title);
    
        $dev_name = $html->find('a[class=hrTbp R8zArc]', 0);
    	$dev_name=trim($dev_name->plaintext);
    	//var_dump("Developer: ".$dev_name);
    
    
    	$images_dom = $html->find('img[alt=Cover art]', 0); $image = false;
    	if($images_dom){
    		$image1=trim($images_dom->src);
    		}
    	$image_par= explode('=', $image1);
    	$image2= "http:".$image_par[0]."=w300";
    	$image_par2= explode('/', $image2);
    	$image = "http://".$image_par2[2]."/". $image_par2[3];
    	//var_dump("Image URL: ".$image);
    	
    	
    	//============================Image Upload================================================================
    
    	// Add Featured Image to Post
    	$image_url        = $image; // Define the image URL here
    	$image_name       = $title_dom1.' for pc.png';
    	$upload_dir       = wp_upload_dir(); // Set upload folder
    	$image_data       = file_get_contents($image_url); // Get image data
    	$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
    	$filename         = basename( $unique_file_name ); // Create image file name
    
    	// Check folder permission and define file location
    	if( wp_mkdir_p( $upload_dir['path'] ) ) {
    	    $file = $upload_dir['path'] . '/' . $filename;
    	} else {
    	    $file = $upload_dir['basedir'] . '/' . $filename;
    	}
    
    	// Create the image  file on the server
    	file_put_contents( $file, $image_data );
    
    	// Check image file type
    	$wp_filetype = wp_check_filetype( $filename, null );
    
    	// Set attachment data
    	$attachment = array(
    	    'post_mime_type' => $wp_filetype['type'],
    	    'post_title'     => sanitize_file_name( $filename ),
    	    'post_content'   => '',
    	    'post_status'    => 'inherit'
    	);
    
    	// Create the attachment
    	$attach_id = wp_insert_attachment( $attachment, $file, $post_id );
    
    	// Include image.php
    	require_once(ABSPATH . 'wp-admin/includes/image.php');
    
    	// Define attachment metadata
    	$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
    
    	// Assign metadata to attachment
    	wp_update_attachment_metadata( $attach_id, $attach_data );
    
    	$image_url = wp_get_attachment_url($attach_id);
    	
    	
    	//============================Image Upload End=============================================================
    
    	$category_name = $html->find('a[itemprop=genre]', 0);
    	$category_name=trim($category_name->plaintext);
    	//var_dump("Category: ".$category_name);
    
    
    	$rating = $html->find('div[class=BHMmbe]', 0);
    	$rating=trim($rating->innertext);
    	//var_dump("Rating: ".$rating);
    
    	$total_ratings = $html->find('span[class=EymY4b] span', 1);
    	$total_ratings=trim($total_ratings->plaintext);
    	//var_dump("Total Ratings: ".$total_ratings);
    
    	$last_updated = $html->find('span[class=htlgb]', 0);
    	$last_updated=trim($last_updated->plaintext);
    	//var_dump("Last updated: ".$last_updated);
    
    	$size = $html->find('span[class=htlgb]', 2);
    	$size=trim($size->plaintext);
    	//var_dump("size: ".$size);
    
    	$Installs = $html->find('span[class=htlgb]', 4);
    	$Installs = trim($Installs->plaintext);
    	//var_dump("Installs: ".$Installs);
    
    	$Current_Version = $html->find('span[class=htlgb]', 6);
    	$Current_Version= trim($Current_Version->plaintext);
    	//var_dump("Current Version: ".$Current_Version);
    
    	$Req_Android = $html->find('span[class=htlgb]', 8);
    	$Req_Android=trim($Req_Android->plaintext);
    	//var_dump("Req Android: ".$Req_Android);
    
    	$Content_Rating = $html->find('span[class=htlgb]', 6);
    	$Content_Rating=trim($Content_Rating->plaintext);
    	//var_dump("Content Rating: ".$Content_Rating);
    /*
    
    	$screenshot = $html->find('img[alt=Screenshot Image] ', 0);
    	$screenshot = trim($screenshot->src);
    	$screenshot_array =  explode('=', $screenshot);
    	$screenshot = $screenshot_array[0];
    	var_dump("screenshot 1: ".$screenshot);
    
    	$screenshot1 = $html->find('img[alt=Screenshot Image] ', 1);
    	$screenshot1 = trim($screenshot1->src);
    	$screenshot_array =  explode('=', $screenshot1);
    	$screenshot1 = $screenshot_array[0];
    	var_dump("screenshot 2: ".$screenshot1);
    
    	$screenshot2 = $html->find('img[alt=Screenshot Image] ', 2);
    	$screenshot2 = trim($screenshot2->getAttribute("data-src"));
    	$screenshot_array =  explode('=', $screenshot2);
    	$screenshot2 = $screenshot_array[0];
    	var_dump("screenshot 3: ".$screenshot2);
    
    	$screenshot3 = $html->find('img[alt=Screenshot Image] ', 3);
    	$screenshot3 = trim($screenshot3->getAttribute("data-src"));
    	$screenshot_array =  explode('=', $screenshot3);
    	$screenshot3 = $screenshot_array[0];
    	var_dump("screenshot 4: ".$screenshot3);
    */
    
    
        if ($category_name == 'Action'||$category_name == 'Adventure'||$category_name == 'Arcade'||$category_name == 'Board'||$category_name == 'Card'||$category_name == 'Casino'||$category_name == 'Casual'||$category_name == 'Educational'||$category_name == 'Music'||$category_name == 'Puzzle'||$category_name == 'Racing'||$category_name == 'Role Playing'||$category_name == 'Simulation'||$category_name == 'Sports'||$category_name == 'Strategy'||$category_name == 'Trivia'||$category_name == 'Word'){
    	    $category = 'Game';
    	}
    
    	else{
    	    $category ='App';
    	}
    
    
    
    	$content_dom = $html->find('div[jsname=sngebd]', 0);
    	$content = $content_dom->plaintext;
    
    	//var_dump("Description: ".$content);
    
    
    	$whats_new = $html->find('div[itemprop=description] span', 1);
    	$whats_new=trim($whats_new->innertext);
    	//var_dump("Whats new: ".$whats_new);
    
    
    
       $content_final= "<img class='alignright wp-image-69' src='$image_url' alt='$title_dom1 for PC' width='165' height='165' /><em><strong>$title_dom1 For PC</strong>:</em> Download $title_dom1 for PC/Mac/Windows 7,8,10 and have the fun experience of using the smartphone Apps on Desktop or personal computers. New and rising $category_name $category, $title_dom1 developed by $dev_name for Android is available for free in the Play Store. Before we move toward the installation guide of $title_dom1 on PC using Emulators, here is the official Google play link for $title_dom1, You can read the Complete Features and Description of the App there.
    
    <h3>About $title_dom1</h3>
    <table>
    <tbody>
    <tr>
    <td>File size:</td>
    <td>$size</td>
    </tr>
    <tr>
    <td>Category:</td>
    <td>$category_name</td>
    </tr>
    <tr>
    <td>App Title:</td>
    <td><strong>$title_dom1</strong></td>
    </tr>
    <tr>
    <td>Developed By:</td>
    <td>$dev_name</td>
    </tr>
    <tr>
    <td>Installations:</td>
    <td>$Installs</td>
    </tr>
    <tr>
    <td>Current Version:</td>
    <td>$Current_Version</td>
    </tr>
    <tr>
    <td>Req. Android:</td>
    <td>$Req_Android</td>
    </tr>
    <tr>
    <td>Last Updated:</td>
    <td>$last_updated</td>
    </tr>
    <tr>
    <td>Rating:</td>
    <td>$rating / 5.0</td>
    </tr>
    </table>
    </tbody>
    
    [appbox googleplay $g_id]
    
    We helps you to install any App/Game available on Google Play Store/iTunes Store on your PC running Windows or Mac OS. You can download apps/games to the desktop or your PC with Windows 7,8,10 OS, Mac OS X, or you can use an Emulator for Android or iOS to play the game directly on your personal computer. Here we will show you how can you download and install your fav. $category $title_dom1 on PC using the emulator, all you need to do is just follow the steps given below.
    
    <h2><strong>How to Download $title_dom1 for PC Windows 8.1/10/8/7 64-Bit &amp; 32-Bit Free?</strong></h2>
    if you are a PC user using any of the OS available like Windows or Mac you can follow this step to step guide below to get $title_dom1 on your  PC. without further ado lets more towards the guide:
    <ul>
     	<li>For the starters Download and Install the Android Emulator of your Choice. Take a look at the list we provide here: <a href='https://appsnet.us/best-android-emulators-for-pc/' target='_blank'>Best Android Emulators For PC</a></li>
     	<li>Upon the Completion of download and install, open the Android Emulator.</li>
     	<li>In the next step click on the Search Button on home screen.</li>
     	<li>Now in the search box type '$title_dom1' and get the manager in Google Play Search.</li>
     	<li>Click on the app icon and install it.</li>
     	<li>Once installed, find <em>$title_dom1</em> in all apps in drawer, click to open it.</li>
     	<li>Use your mouse’s right button/click and WASD keys to use this application.</li>
     	<li>Follow on-screen instructions to learn about $title_dom1 and use the App properly</li>
     	<li>That’s all.</li>
    </ul>
    [appbox googleplay $g_id screenshots-only]
    <h3><strong>Features of $title_dom1 for PC:</strong></h3>
    <p class='dev-content-read'>$content</p>
    <h2><strong>$title_dom1 PC FAQs</strong></h2>
    Here are some quick FAQs which you may like to go through:
    
    <strong>How do I install $title_dom1 on my PC?</strong>
    
    <strong>Ans.</strong> You can not directly install this app on your pc but with the help of the android emulator, you can do that.
    
    <strong>Is $title_dom1 available for pc?</strong>
    
    <strong>Ans.</strong> No officially not, but with this article steps, you can use it on pc.
    
    <strong>How do I install $title_dom1 on Windows 8,7 or 10?</strong>
    
    <strong>Ans.</strong> This is the same process as we install the app on our pc that is the same process for windows also.
    
    <strong>How do I install $title_dom1 on Mac OS X?</strong>
    
    <strong>Ans.</strong> This is the same process as we install the app on our pc that is the same process for windows also
    
    Also, make sure you share these with your friends on social media. Please check it out our more content like ";
    $args = array( 'posts_per_page' => 1, 'orderby' => 'rand' );
    $rand_posts = get_posts( $args );
    foreach ( $rand_posts as $post ) : 
     setup_postdata( $post );?>
    <?php $id = $post->id ?>
    <?php $id = $post->id?>
     <?php $content_final .="<a href='".post_permalink($id)."'> ".$post->post_title." </a></b><br>"; ?>
     <?php endforeach;
      
    wp_reset_postdata();
    
    $content_final.="
    <h3><strong>Conclusion</strong></h3>
    We have discussed here <strong>$title_dom1</strong> an App from <strong>Video Editors</strong> category which is not yet available on Mac or Windows store, or there is no other version of it available on PC; So we have used an Android emulator to help us in this regard and let us use the App on our PC using the Android Emulators.
    
    If you are facing any issue with this app or in the installation let me know in the comment box I will help you to fix your problem. Thanks!";
        
        
    
    } //-------------------------------If Ends one post type if URL is live on play store---------------------
    
    else{
        $app_data = $wpdb->get_row("SELECT * FROM `app_data` WHERE `play_store_id` = '$g_id'");
        
        $title_dom1 = trim($app_data->app_name);
        $image = trim($app_data->app_img);
        $category_name = trim($app_data->app_category);
        $dev_name = trim($app_data->dev_name);
        
        if ($category_name == 'Action'||$category_name == 'Adventure'||$category_name == 'Arcade'||$category_name == 'Board'||$category_name == 'Card'||$category_name == 'Casino'||$category_name == 'Casual'||$category_name == 'Educational'||$category_name == 'Music'||$category_name == 'Puzzle'||$category_name == 'Racing'||$category_name == 'Role Playing'||$category_name == 'Simulation'||$category_name == 'Sports'||$category_name == 'Strategy'||$category_name == 'Trivia'||$category_name == 'Word'){
    	    $category = 'Game';
    	}
    
    	else{
    	    $category ='App';
    	}
    
        
        //============================Image Upload================================================================
    
    	// Add Featured Image to Post
    	$image_url        = $image; // Define the image URL here
    	$image_name       = $title_dom1.' for pc.png';
    	$upload_dir       = wp_upload_dir(); // Set upload folder
    	$image_data       = file_get_contents($image_url); // Get image data
    	$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
    	$filename         = basename( $unique_file_name ); // Create image file name
    
    	// Check folder permission and define file location
    	if( wp_mkdir_p( $upload_dir['path'] ) ) {
    	    $file = $upload_dir['path'] . '/' . $filename;
    	} else {
    	    $file = $upload_dir['basedir'] . '/' . $filename;
    	}
    
    	// Create the image  file on the server
    	file_put_contents( $file, $image_data );
    
    	// Check image file type
    	$wp_filetype = wp_check_filetype( $filename, null );
    
    	// Set attachment data
    	$attachment = array(
    	    'post_mime_type' => $wp_filetype['type'],
    	    'post_title'     => sanitize_file_name( $filename ),
    	    'post_content'   => '',
    	    'post_status'    => 'inherit'
    	);
    
    	// Create the attachment
    	$attach_id = wp_insert_attachment( $attachment, $file, $post_id );
    
    	// Include image.php
    	require_once(ABSPATH . 'wp-admin/includes/image.php');
    
    	// Define attachment metadata
    	$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
    
    	// Assign metadata to attachment
    	wp_update_attachment_metadata( $attach_id, $attach_data );
    
    	$image_url = wp_get_attachment_url($attach_id);
    	
    
    	
    	
    	//============================Image Upload End=============================================================
        
        
          $content_final= "<img class='alignright wp-image-69' src='$image_url' alt='$title_dom1 for PC' width='165' height='165' /><em><strong>$title_dom1 For PC</strong>:</em> Download $title_dom1 for PC/Mac/Windows 7,8,10 and have the fun experience of using the smartphone Apps on Desktop or personal computers. New and rising $category_name $category, $title_dom1 developed by $dev_name for Android is available for free in the Play Store. Before we move toward the installation guide of $title_dom1 on PC using Emulators, here is the official Google play link for $title_dom1, You can read the Complete Features and Description of the App there.
    
    <h3>About $title_dom1</h3>
    <table>
    <tbody>
    <tr>
    <td>File size:</td>
    <td>Varies with the Device</td>
    </tr>
    <tr>
    <td>Category:</td>
    <td>$category_name</td>
    </tr>
    <tr>
    <td>App Title:</td>
    <td><strong>$title_dom1</strong></td>
    </tr>
    <tr>
    <td>Developed By:</td>
    <td>$title_dom1</td>
    </tr>
    <tr>
    <td>Installations:</td>
    <td>1,00,000</td>
    </tr>
    <tr>
    <td>Current Version:</td>
    <td>$Current_Version</td>
    </tr>
    <tr>
    <td>Req. Android:</td>
    <td>Android 4.4 and up</td>
    </tr>
    <tr>
    <td>Last Updated:</td>
    <td>$last_updated</td>
    </tr>
    <tr>
    <td>Rating:</td>
    <td>4.1 / 5.0</td>
    </tr>
    </table>
    </tbody>
    
    [appbox googleplay $g_id]
    
    We helps you to install any App/Game available on Google Play Store/iTunes Store on your PC running Windows or Mac OS. You can download apps/games to the desktop or your PC with Windows 7,8,10 OS, Mac OS X, or you can use an Emulator for Android or iOS to play the game directly on your personal computer. Here we will show you how can you download and install your fav. $category $title_dom1 on PC using the emulator, all you need to do is just follow the steps given below.
    
    <h2><strong>How to Download $title_dom1 for PC Windows 8.1/10/8/7 64-Bit &amp; 32-Bit Free?</strong></h2>
    if you are a PC user using any of the OS available like Windows or Mac you can follow this step to step guide below to get $title_dom1 on your  PC. without further ado lets more towards the guide:
    <ul>
     	<li>For the starters Download and Install the Android Emulator of your Choice. Take a look at the list we provide here: <a href='https://appsnet.us/best-android-emulators-for-pc/' target='_blank'>Best Android Emulators For PC</a></li>
     	<li>Upon the Completion of download and install, open the Android Emulator.</li>
     	<li>In the next step click on the Search Button on home screen.</li>
     	<li>Now in the search box type <strong>$title_dom1</strong> and get the manager in Google Play Search.</li>
     	<li>Click on the app icon and install it.</li>
     	<li>Once installed, find <em>$title_dom1</em> in all apps in drawer, click to open it.</li>
     	<li>Use your mouse’s right button/click and WASD keys to use this application.</li>
     	<li>Follow on-screen instructions to learn about $title_dom1 and use the App properly</li>
     	<li>That’s all.</li>
    </ul>
    [appbox googleplay $g_id screenshots-only]
    <h2><strong>$title_dom1 PC FAQs</strong></h2>
    Here are some quick FAQs which you may like to go through:
    
    <strong>How do I install $title_dom1 on my PC?</strong>
    
    <strong>Ans.</strong> You can not directly install this app on your pc but with the help of the android emulator, you can do that.
    
    <strong>Is $title_dom1 available for pc?</strong>
    
    <strong>Ans.</strong> No officially not, but with this article steps, you can use it on pc.
    
    <strong>How do I install $title_dom1 on Windows 8,7 or 10?</strong>
    
    <strong>Ans.</strong> This is the same process as we install the app on our pc that is the same process for windows also.
    
    <strong>How do I install $title_dom1 on Mac OS X?</strong>
    
    <strong>Ans.</strong> This is the same process as we install the app on our pc that is the same process for windows also
    
    Also, make sure you share these with your friends on social media. Please check it out our more content like ";
    $args = array( 'posts_per_page' => 1, 'orderby' => 'rand' );
    $rand_posts = get_posts( $args );
    foreach ( $rand_posts as $post ) : 
     setup_postdata( $post );?>
    <?php $id = $post->id ?>
    <?php $id = $post->id?>
     <?php $content_final .="<a href='".post_permalink($id)."'> ".$post->post_title." </a></b><br>"; ?>
     <?php endforeach;
      
    wp_reset_postdata();
    
    $content_final.="
    <h3><strong>Conclusion</strong></h3>
    We have discussed here <strong>$title_dom1</strong> an App from <strong>Video Editors</strong> category which is not yet available on Mac or Windows store, or there is no other version of it available on PC; So we have used an Android emulator to help us in this regard and let us use the App on our PC using the Android Emulators.
    
    If you are facing any issue with this app or in the installation let me know in the comment box I will help you to fix your problem. Thanks!";
        
        
        
        
    }  // ------------------Else End 2nd post type if URL is dead on play store-----------------------
    
    
     // Get Category
        if ($category_name == 'Action'){
        	$category_id [] =3;
        }
        else if ($category_name == 'Adventure') {
        	$category_id [] =4;
        }
        else if ($category_name == 'Arcade') {
        	$category_id [] =5;
        }
        else if ($category_name == 'Card') {
        	$category_id [] =6;
        }
        else if ($category_name == 'Casino') {
        	$category_id [] =7;
        }
        else if ($category_name == 'Casual') {
        	$category_id [] =8;
        }
        else if ($category_name == 'Educational') {
        	$category_id [] =9;
        }
        else if ($category_name == 'Music') {
        	$category_id [] =10;
        }
        else if ($category_name == 'Puzzle') {
        	$category_id [] =11;
        }
        else if ($category_name == 'Racing') {
        	$category_id [] =12;
        }
        else if ($category_name == 'Role Playing') {
        	$category_id [] =13;
        }
        else if ($category_name == 'Simulation') {
        	$category_id [] =14;
        }
        else if ($category_name == 'Sports') {
        	$category_id [] =15;
        }
        else if ($category_name == 'Strategy') {
        	$category_id [] =16;
        }
        else if ($category_name == 'Trivia') {
        	$category_id [] =17;
        }
        else if ($category_name == 'Word') {
        	$category_id [] =18;
        }
        else{
        	$category_id [] =1;
        }
    
    $tags_post = array(
    	    'Tag1'   => $title_dom1,
    	    'Tag2'   => $title_dom1.' for pc',
    	    'Tag3'   => $title_dom1.' on pc',
    	    'Tag4'   => $title_dom1.' online',
    	    'Tag5'   => $title_dom1.' pc',
    	    'Tag6'   => $title_dom1.' for windows',
    	    'Tag7'   => $title_dom1.' for mac',
    	    'Tag8'   => 'download '.$title_dom1,
    	); 
    
    $my_post = array(
    	  'post_title'    => $title,
    	  'post_content'  => $content_final,
    	  'post_name'	  => $slug,
    	  'post_status'   => 'publish',
    	  'post_author'   => 1,
    	  'post_category' => $category_id,
    	  'post_date'     => $post_publish_date,
    	  'post_date_gmt' => $post_publish_date,
    	  'tags_input'	  => $tags_post
    	);
    
    	// Insert the post into the database
    	$post_id = wp_insert_post( $my_post );
    	// And finally assign featured image to post
    	set_post_thumbnail( $post_id, $attach_id );
    
    
    if ($post_id) {
    		echo "Successsssss :)  ".$title;
    	}
    
       
       $wpdb->update( 
    		'wp_crawl', 
    		array( 
    			'status' => 1,
    			'post_id' => $post_id,
    			'post_title' => $title	// string
    		), 
    		array( 'ID' => $row_data->id ), 
    		array( 
    			'%d',
    			'%d',
    			'%s'	// value2
    		), 
    		array( '%d' ) 
    	);

die;


?>























