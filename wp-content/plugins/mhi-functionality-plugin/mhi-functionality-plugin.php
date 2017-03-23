<?php
/*
	Plugin Name: MHI Functionality Plugin
	Description: Adds Clinical Studies and Physicians post types and taxonomies to WordPress, along with a template tag and shortcode to query this content.
	Author: Gerry Yumul / Josh Leuze
	Author URI: http://jleuze.com/
	License: GPL2
	Version: 1.1
*/

/*  Copyright 2014 Josh Leuze (email : mail@jleuze.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


	
	// Adds function to load custom post types in theme with a template tag
	function mhi_loop( $cpt='', $tax='', $term='', $quantity='', $tab='',  $grvar='', $ordering='') {

		if($cpt=='grand-rounds-lecture')
			{
				include( 'includes/mhi_loop_grandrounds.php' );
			}else{
				include( 'includes/mhi_loop.php' );
		}
	
	}
		
		/* To load content from the custom post types, add this template tag to your theme and edit the parameters:
	
			<?php if( function_exists( 'mhi_loop' ) ) { mhi_loop( "clinical-studies", "status", "enrolling", "5" ); } ?>
	
		*/
		
	// Adds shortcode to load custom post types in page content from the mhi_loop function
	function mhi_loop_shortcode( $mhi_atts ) {
	
		// Get the attributes from the shortcode
		extract( shortcode_atts( array (
			'cpt'      => '',
			'tax'      => '',
			'term'     => '',
			'quantity' => '',
			'tab' => '',
			'grvar' => '',
			'ordering' => ''
		), $mhi_atts ) );
		
		// Setup the shortcode attributes for the mhi_loop function
		$cpt_att      = $cpt;
		$tax_att      = $tax;
		$term_att     = $term;
		$quantity_att = $quantity;
		$tab_att = $tab;
		$grvar_att = $grvar;
		$ordering_att = $ordering;
	
		// Pause the output of the shortcode
		ob_start();
		
		// Run the mhi_loop function that loads content from CPTs
		mhi_loop( $cpt=$cpt_att, $tax=$tax_att, $term=$term_att, $quantity=$quantity_att, $tab=$tab_att, $grvar=$grvar_att, $ordering=$ordering_att);
		
		// Resume the output of the shortcode with the contents of the mhi_loop function
		$mhi_loop_content = ob_get_clean();
		return $mhi_loop_content;
	
	}
	add_shortcode( 'mhi_loop', 'mhi_loop_shortcode' );
	
		/* To load content from the custom post types, add this shortcode to your page content and edit the parameters:
	
			[mhi_loop cpt="clinical-studies" tax="status" term="open-for-enrollment" quantity="5" tab="" grvar="any string"]
	
		*/


/* Custom Website Roles*/
   function add_roles_on_plugin_activation() {
       	add_role( 'research-coordinator', 'Research Coordinator', $editor->capabilities );
	get_role('research-coordinator')->add_cap( 'edit_others_posts', 'edit_published_posts', 'edit_posts', 'delete_posts', 'delete_published_posts','publish_posts', 'read', 'upload_files' ); 
   }
   register_activation_hook( __FILE__, 'add_roles_on_plugin_activation' );

/* Remove Admin Bar Except for admin*/
add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
  show_admin_bar(false);
}
}

?>
