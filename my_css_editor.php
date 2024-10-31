<?php

/*
Plugin Name: My CSS Editor
Plugin URI: http://blog.4sure.jp/yokoshima/
Description: Style Sheet edit only for you.
Version: 0.2
Author: yokoshima
Author URI:  http://blog.4sure.jp/yokoshima/
*/
/*  Copyright 2009 yokoshima (email : k.yokoshima@4sure.co.jp:)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/ 
 class My_CSS_Editor {

	
	var $version = "0.2";
	var $option_name = "mse_option.css.1";
	
	function My_CSS_Editor(){

	}

	function wp_head() {		
		echo "\n<!-- My CSS Editor $this->version -->\n";
		if( get_option($this->option_name) ){
			echo '<style type="text/css">';
			echo '<--';
			echo get_option($this->option_name);
			echo '-->';
			echo '</style>';
		}
	}
	
	function edit_setting() {
		if ( isset($_POST['action']) && $_POST['action'] == 'update' ){
			update_option($this->option_name, $_POST['css_value']);
			echo '<div id="message" class="updated fade"><p><strong>Your Style Sheet was preserved. </strong></p></div>';
		}elseif(isset($_POST['action']) && $_POST['action'] == 'delete'){
			delete_option($this->option_name);
			echo '<div id="message" class="updated fade"><p><strong>Your Style Sheet setting was deleted. </strong></p></div>';
		}
		echo '<div class="wrap">';
		echo '<h2>Edit of your Style Sheet</h2>';
		echo '<form id=', "\"{$this->option_name}\"",  ' action="'.$_SERVER['REQUEST_URI'].'" method="post">';
		wp_nonce_field('update-options');
		echo '<table class="form-table">';
		
		echo '<p>Your Style Sheet setting can be made by editing the text area. <br />';
		echo 'The setting can be deleted with the delete button. </p>';
		echo '<tr valign="top">';
		echo '<th scope="row">';
		echo $this->option_name;
		echo '</th>';
		echo '<td>';
		echo '<textarea name="css_value" style="width:100%;height:400px;">';
		
		if($settings = get_option($this->option_name)){
			echo $settings;
		}else{
			;
		}
		
		echo '</textarea>';

		echo '</td>';
		echo '</tr>';
		echo '</table>';
		echo "<script type='text/javascript'>function delete_setting(){alert('delete?');document.getElementById('action').value='delete';document.getElementById('{$this->option_name}').submit();}</script>";
		echo '<input type="hidden" id="action" name="action" value="update">';
		echo '<p><input type="submit" value="update"> <input type="button" onclick="delete_setting();" value="delete"></p>';
		echo '</form>';
		echo '</div>';
	}
	
	function my_css_editor_admin() {
	  if (function_exists('add_options_page')) {
	    add_options_page('My CSS Editor' /* page title */, 
	                     'My CSS Editor' /* menu title */, 
	                     8 /* min. user level */, 
	                     basename(__FILE__) /* php file */ , 
	                     array($this, 'edit_setting') /* function for subpanel */);
	  }
	}
	
}

$_my_css_editor = new My_CSS_Editor();
add_action('wp_head', array($_my_css_editor, 'wp_head'));
add_action('admin_menu',  array($_my_css_editor, 'my_css_editor_admin'));
?>
