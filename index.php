<?php
	/*
		Plugin Name: Eralha Register Form
		Plugin URI: 
		Description: A Register form for your themes, with costumizable CSS. Place [er-register-form] in your page or post.
		Version: 0.0.0.1
		Author: Emanuel Ralha
		Author URI: 
	*/

// No direct access to this file
defined('ABSPATH') or die('Restricted access');

if (!class_exists("eralha_register_form")){
	class eralha_register_form{

		var $optionsName = "eralha_register_form";
		var $dbVersion = "0.1";

		function eralha_register_form(){
			
		}

		function init(){
			//Do plugin loaded actions
		}
		function activationHandler(){
			//Do activation stuff here
		}
		function deactivationHandler(){
			//Do deactivation stuff here.
		}

		function register(){
			$pluginDir = str_replace("", "", plugin_dir_url(__FILE__));
			set_include_path($pluginDir);

			$inserted = false;
			//require_once(ABSPATH . WPINC . '/registration.php');

			if(isset($_POST["wp-submit-register"])){
				$vResult = $this->validate();
				$errCount = $vResult[1];

				if($errCount > 0){
					return false;
				}else{
					$userID = wp_insert_user( 
						array (
							'first_name' => $_POST["first_name"],
							'last_name' => $_POST["last_name"],
							'nickname' => $_POST["nickname"],
							'user_email' => $_POST["user_email"],
							'user_login' => $_POST["user_login_register"],
							'user_pass' => $_POST["user_pass_register"]
					));
					$inserted = true;

					/*
						Add a custom capability to the user
							$user = new WP_User($userID);
							$user->add_cap("edit_posts");
							$user->add_cap("delete_posts");
					*/

					//Add USER INFO
						add_user_meta($userID, "adress", $_POST["adress"], true);
						add_user_meta($userID, "localidade", $_POST["localidade"], true);
						add_user_meta($userID, "codPostal", $_POST["codPostal"], true);
				}
			}

			return $inserted;
		}

		function validate(){
			$errorMSG = "";
			$errCount = 0;

			if(!isset($_POST["wp-submit-register"]) && !isset($_POST["wp-submit-update"])){
				return;
			}

			if(is_user_logged_in()){ 
				$uinfo = get_userdata(get_current_user_id());

				if(email_exists($_POST["user_email"]) && $uinfo->data->user_email != $_POST["user_email"]){
					$errorMSG .= "» O Email que escolheu já existe<br />";
					$errCount ++;
				}
			}else{
				if(email_exists($_POST["user_email"])){
					$errorMSG .= "» O Email que escolheu já existe<br />";
					$errCount ++;
				}
			}

			if($_POST["first_name"] == ""){
				$errorMSG .= "» Primeiro Nome<br />";
				$errCount ++;
			}
			if($_POST["last_name"] == ""){
				$errorMSG .= "» Ultimo Nome<br />";
				$errCount ++;
			}
			if($_POST["nickname"] == ""){
				$errorMSG .= "» Nickname<br />";
				$errCount ++;
			}
			if($_POST["user_email"] == ""){
				$errorMSG .= "» Email<br />";
				$errCount ++;
			}
			if($_POST["adress"] == ""){
				$errorMSG .= "» Morada<br />";
				$errCount ++;
			}
			if($_POST["localidade"] == ""){
				$errorMSG .= "» Localidade<br />";
				$errCount ++;
			}
			if($_POST["codPostal"] == ""){
				$errorMSG .= "» Código Postal<br />";
				$errCount ++;
			}

			if(!is_user_logged_in()){ 
				if($_POST["user_login_register"] == ""){
					$errorMSG .= "» Username<br />";
					$errCount ++;
				}
				if($_POST["user_pass_register"] == ""){
					$errorMSG .= "» Password<br />";
					$errCount ++;
				}
			}
			$errorMSG = "<strong>Please Check the Following Fields:</strong><blockquote>".$errorMSG."</blockquote>";

			return array($errorMSG, $errCount);
		}

		function addContent($content=''){
			global $wpdb;
			$pluginDir = str_replace("", "", plugin_dir_url(__FILE__));
			set_include_path($pluginDir);

			if(strpos($content, "[er-register-form]") !== false){

				$inserted = $this->register();
				$vResult = $this->validate();

				if(is_user_logged_in()){ 
					$responseHTML = file_get_contents($pluginDir."templates/user_logged_in.php");
					$uinfo = get_userdata(get_current_user_id());

					if(isset($_POST["wp-submit-update"])){
						//IF WE ARE HERE USER TRY TO UPDATE USER INFO

						if($vResult[1] == 0){
							//IF EVERY THINK IS VALIDATED, UPDATE USER INFO IN TABLE USERS
								wp_update_user(array(
									'ID' => get_current_user_id(), 
									'first_name' => $_POST["first_name"],
									'last_name' => $_POST["last_name"],
									'nickname' => $_POST["nickname"],
									'user_email' => $_POST["user_email"]
								));

							//UPDATE USER META INFO
								update_user_meta(get_current_user_id(), "adress", $_POST["adress"]);
								update_user_meta(get_current_user_id(), "localidade", $_POST["localidade"]);
								update_user_meta(get_current_user_id(), "codPostal", $_POST["codPostal"]);

							$responseHTML = str_replace("{error_message}", "<strong>Dados actualizados!</strong>", $responseHTML);

							$uinfo = get_userdata(get_current_user_id());
						}else{
							$errorMSG = "<strong>Please Check the Following Fields:</strong><blockquote>".$errorMSG."</blockquote>";
							$responseHTML = str_replace("{error_message}", $vResult[0], $responseHTML);
						}
					}

					//PARSE VIEW
					$responseHTML = str_replace("{error_message}", "", $responseHTML);
					$responseHTML = str_replace("{REQUEST_URI}", $_SERVER['REQUEST_URI'], $responseHTML);
					$responseHTML = str_replace("{first_name}", $uinfo->first_name, $responseHTML);
					$responseHTML = str_replace("{last_name}", $uinfo->last_name, $responseHTML);
					$responseHTML = str_replace("{nickname}", get_user_meta(get_current_user_id(), "nickname", true), $responseHTML);
					$responseHTML = str_replace("{user_email}", $uinfo->data->user_email, $responseHTML);
					$responseHTML = str_replace("{adress}", get_user_meta(get_current_user_id(), "adress", true), $responseHTML);
					$responseHTML = str_replace("{localidade}", get_user_meta(get_current_user_id(), "localidade", true), $responseHTML);
					$responseHTML = str_replace("{codPostal}", get_user_meta(get_current_user_id(), "codPostal", true), $responseHTML);

					$content = str_replace("[er-register-form]", $responseHTML, $content);

					return $content;
				}

				if($inserted === false){
					$formHTML = file_get_contents($pluginDir."templates/register_form.php");
					$formHTML = str_replace("{REQUEST_URI}", $_SERVER['REQUEST_URI'], $formHTML);
					$formHTML = str_replace("{error_message}", $vResult[0], $formHTML);

						$formHTML = str_replace("{first_name}", $_POST["first_name"], $formHTML);
						$formHTML = str_replace("{last_name}", $_POST["last_name"], $formHTML);
						$formHTML = str_replace("{nickname}", $_POST["nickname"], $formHTML);
						$formHTML = str_replace("{user_email}", $_POST["user_email"], $formHTML);
						$formHTML = str_replace("{adress}", $_POST["adress"], $formHTML);
						$formHTML = str_replace("{localidade}", $_POST["localidade"], $formHTML);
						$formHTML = str_replace("{codPostal}", $_POST["codPostal"], $formHTML);
						$formHTML = str_replace("{user_login_register}", $_POST["user_login_register"], $formHTML);
						$formHTML = str_replace("{user_pass_register}", $_POST["user_pass_register"], $formHTML);

					$content = str_replace("[er-register-form]", $formHTML, $content);
				}

				if($inserted === true){
					$responseHTML = file_get_contents($pluginDir."templates/register_form_response.php");
					$responseHTML = str_replace("{user_login}", $_POST["user_login_register"], $responseHTML);
					$responseHTML = str_replace("{password}", $_POST["user_pass_register"], $responseHTML);
					$content = str_replace("[er-register-form]", $responseHTML, $content);
				}
			}

			return $content;
		}
	}
}
if (class_exists("eralha_register_form")) {
	$eralha_register_form_obj = new eralha_register_form();
}

//Actions and Filters
if (isset($eralha_register_form_obj)) {
	//VARS
		$plugindir = plugin_dir_url( __FILE__ );

	//Actions
		register_activation_hook(__FILE__, array($eralha_register_form_obj, 'activationHandler'));
		register_deactivation_hook(__FILE__, array($eralha_register_form_obj, 'deactivationHandler'));
		add_action('plugins_loaded', array($eralha_register_form_obj, 'init'));

	//Filters
		//Search the content for galery matches
		add_filter('the_content', array($eralha_register_form_obj, 'addContent'));

}
?>