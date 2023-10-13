<?php
/**
 * Plugin Name: Self-affirmations
 * Plugin URI: http://www.teochewthunder.com
 * Description: This plugin implements self-affirmations
 * Version: 1.0.0
 * Author: TeochewThunder
 * Author URI: http://www.teochewthunder.com
 * License: GPL2
 */

function wc_admin_menu() 
{
    add_submenu_page("index.php", "Test Readytoreceive", "Test Readytoreceive", "manage_options", "tt_get_readytoreceive", "tt_get_readytoreceive");
	add_submenu_page("index.php", "Test Terms", "Test Terms", "manage_options", "tt_get_terms", "tt_get_terms");
	add_submenu_page("index.php", "Test Lastsent", "Test Lastsent", "manage_options", "tt_set_lastsent", "tt_set_lastsent");
	add_submenu_page("index.php", "Test Generate Email", "Test Generate Email", "manage_options", "tt_generate_mail", "tt_generate_mail");
	add_submenu_page("index.php", "Test Job", "Test Job", "manage_options", "tt_selfaffirmations", "tt_selfaffirmations");
}

add_action("admin_menu", "wc_admin_menu", 11);

function tt_get_readytoreceive() 
{
	$cURLConnection = curl_init();

	curl_setopt($cURLConnection, CURLOPT_URL, "https://apex.oracle.com/pls/apex/teochewthunder/mailinglist/readytoreceive");
	curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

	$json_list = curl_exec($cURLConnection);
	curl_close($cURLConnection);

	echo $json_list;
	
	$list = json_decode($json_list);
	
	return $list->items;
}

function tt_get_terms($id) 
{
	$id = ($id ? $id : "teochewthunder@gmail.com");
	$cURLConnection = curl_init();

	curl_setopt($cURLConnection, CURLOPT_URL, "https://apex.oracle.com/pls/apex/teochewthunder/mailinglist/terms/" . $id);
	curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

	$json_list = curl_exec($cURLConnection);
	curl_close($cURLConnection);

	echo $json_list;
	
	$list = json_decode($json_list);
	
	$interests = [];
	$descriptions = [];
	foreach($list->items as $i) {
		if ($i->type == "INTERESTS" && rand(0, 1) == 1) $interests[] = $i->term;
		if ($i->type == "DESCRIPTIONS" && rand(0, 1) == 1) $descriptions[] = $i->term;
	}
	
	return ["interests" => $interests, "descriptions" => $descriptions];
}

function tt_set_lastsent($id) 
{
	$id = ($id ? $id : "teochewthunder@gmail.com");
	$cURLConnection = curl_init();

	curl_setopt($cURLConnection, CURLOPT_URL, "https://apex.oracle.com/pls/apex/teochewthunder/mailinglist/setreceived/" . $id);
	curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

	$json_list = curl_exec($cURLConnection);
	curl_close($cURLConnection);
	
	return;
}

function tt_generate_mail($id, $name = "Teochew Thunder", $gender = "M", $dob = "01-01-1980") 
{
	$terms = tt_get_terms($id);

	$interests = "";
	if (count($terms["interests"]) > 0) 
	{
		$interests = ($gender == "M" ? "He" : "She") . " is interested in ";
		for ($i = 0; $i < sizeof($terms["interests"]); $i++) 
		{
			$interests .= $terms["interests"][$i];
			if ($i == sizeof($terms["interests"]) - 1) 
			{
				$interests .= ". ";
			} 
			else 
			{
				$interests .= " and ";
			}
		}		
	}

	$descriptions = "";
	if (count($terms["descriptions"]) > 0) 
	{
		$descriptions = ($gender == "M" ? "He" : "She") . " is described as ";		
		for ($i = 0; $i < sizeof($terms["descriptions"]); $i++) 
		{
			$descriptions .= $terms["descriptions"][$i];
			if ($i == sizeof($terms["descriptions"]) - 1) 
			{
				$descriptions .= ". ";
			} 
			else 
			{
				$descriptions .= " and ";
			}
		}		
	}
	
	$about = ($gender == "M" ? "man" : "woman");
	$about .= " named '" . $name . "'";
	$about .= " born on " . explode("T", $dob)[0];

	$prompt_type = rand(0, 20);
	$prompt = "";
	$tokens = 50;
	$title = "";
	
	switch($prompt_type)
	{
		case 0: $prompt = "Generate a complimentary poem about"; $title = "A poem for you!"; $tokens = 3000; break;
		case 1: $prompt = "Generate some positive life advice for"; $title = "Some life advice"; $tokens = 1000; break;
		case 2: $prompt = "Generate a sample horoscope for"; $title = "Your Zodiac advice"; $tokens = 3000; break;
		case 3: $prompt = "Generate a Chinese zodiac horoscope for"; $title = "Your Zodiac advice"; $tokens = 3000; break;
		case 4: $prompt = "Generate an encouraging two paragraph letter to self for"; $title = "Your self-affirmation"; $tokens = 2000; break;
		case 5: $prompt = "Generate an encouraging one paragraph letter to self for"; $title = "Your self-affirmation"; $tokens = 1000; break;
		case 6: $prompt = "Generate an encouraging three paragraph letter to self for"; $title = "Your self-affirmation"; $tokens = 3000; break;			
		case 7: $prompt = "Generate a funny and uplifting short story about"; $title = "The Story of You"; $tokens = 3000; break;
		case 8: $prompt = "Generate five inspirational quotes from famous people for"; $title = "Five Quotes to make your day"; $tokens = 2500; break;
		case 9: $prompt = "Generate five fictitious short reviews from fictitious publications about"; $title = "Your reviews from public media"; $tokens = 2500; break;
		case 10: $prompt = "Generate five fictitious one-sentence reviews from fictitious people from diverse races and their occupations about"; $title = "Public Opinion About You"; $tokens = 2500; break;
		case 11: $prompt = "Generate five fictitious one-sentence reviews from fictitious people from diverse races complimenting the personality of"; $title = "Public Opinion About You"; $tokens = 2500; break;				
		case 12: $prompt = "Generate five corny pickup lines from random people for"; $title = "Pickup Lines For You"; $tokens = 2500; break;
		case 13: $prompt = "Generate a character testimonial (from self) for"; $title = "Your testimonial!"; $tokens = 3000; break;
		case 14: $prompt = "Write a love letter (from self) to"; $title = "Some self-love"; $tokens = 3000; break;
		case 15: $prompt = "Create a short movie synopsis with famous actors about"; $title = "A movie was made about you!"; $tokens = 3000; break;			
		case 16: $prompt = "Create a movie role, with famous co-stars, for"; $title = "A movie role for you"; $tokens = 3000; break;			
		case 17: $prompt = "Write a welcoming letter from the President of a Fan Club centered around"; $title = "Welcome Address From " . $name . " Fan Club"; $tokens = 1000; break;
		case 18: $prompt = "Generate a sensational and funny article from a fictitious publication about"; $title = "An article About You"; $tokens = 1000; break;			
		default: $prompt = "Generate a complimentary poem about"; $title = "A poem for you!"; $tokens = 3000; break;			
	}
	
	$tokens += (100 * count($terms["interests"]));
	$tokens += (100 * count($terms["descriptions"]));
	
	$final_prompt = $prompt . " " . $about . ". " . $interests . $descriptions;
		
	$key = "sk-xxx";
	$org = "org-FUOhDblZb1pxvaY6YylF54gl";
	$url = "https://api.openai.com/v1/chat/completions";
	
	$headers = array(
		"Authorization: Bearer " . $key,
		"OpenAI-Organization: " . $org, 
		"Content-Type: application/json"
	);
    
    // Define messages
    $messages = [];
	$obj = [];
    $obj["role"] = "user";
    $obj["content"] = $final_prompt;
	$messages[] = $obj;
    	
    // Define data
    $data = array();
    $data["model"] = "gpt-3.5-turbo";
    $data["messages"] = $messages;
    $data["max_tokens"] = $tokens;

    // init curl
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	$result = curl_exec($curl);
    if (curl_errno($curl)) 
	{
        echo "Error:" . curl_error($curl);
    } 
	else 
	{
        echo print_r($result);
    }
    
    curl_close($curl);	
	
	$result = json_decode($result);
	$sanitized_content = $result->choices[0]->message->content;
	$sanitized_content = str_replace("[Your Name]", $name, $sanitized_content);
	$sanitized_content = str_replace("[President's Name]", $name, $sanitized_content);
	$sanitized_content = str_replace("[Fan Club Name]", $name . " Fan Club", $sanitized_content);
	$sanitized_content = str_replace("[Date]", date("j F Y"), $sanitized_content);
	$sanitized_content = str_replace("[Insert Date]", date("j F Y"), $sanitized_content);
	$sanitized_content = str_replace("[President's Logo]", "", $sanitized_content);
	$sanitized_content = str_replace("[P.O Box]", "", $sanitized_content);
	$sanitized_content = str_replace("[City, State, Zip Code]", "", $sanitized_content);
	$sanitized_content = str_replace("[Email Address]", "", $sanitized_content);
	$sanitized_content = str_replace("[Website]", "", $sanitized_content);
	$sanitized_content = str_replace("[Social Media Handles]", "", $sanitized_content);	
	
	return ["title" => $title, "body" => $sanitized_content];
}

function tt_selfaffirmations() 
{
	$list = tt_get_readytoreceive();

	foreach($list as $l) 
	{
		$name = $l->first_name . " " . $l->last_name;
		$email = tt_generate_mail($l->email, $name, $l->gender, $l->dob);

		if (wp_mail($l->email, $email["title"], $email["body"] . "\n\nTo unsubscribe to the Self-affirmations Mailing List, please reply to this email with the subject 'UNSUBSCRIBE'.", "", [] )) 
		{
			tt_set_lastsent($l->email);
		}
	}
}

add_action("cron_selfaffirmations", "tt_selfaffirmations");
