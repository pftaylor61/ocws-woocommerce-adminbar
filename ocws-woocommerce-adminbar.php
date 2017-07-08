<?php

/*

Plugin Name: OCWS WooCommerce Admin Bar
Plugin URI: http://oldcastleweb.com/pws/plugins

Description: This plugin will check to see if the WooCommerce plugin exists. If it does, it will look at the orders, and publish the number of pending orders on the admin bar.

Version: 0.2.1
Author: Paul Taylor
Author URI: http://oldcastleweb.com/pws/about
License: GPL2
GitHub Plugin URI: https://github.com/pftaylor61/ocws-woocommerce-adminbar
GitHub Branch:     master

*/

/*  Copyright 2017  Paul Taylor  (email : info@oldcastleweb.com)



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

        global $wp_admin_bar;
	global $wp_version;
	global $post;
        
        add_action( 'wp_enqueue_scripts', 'ocws_wcab_scripts' );
 
        /**
         * Enqueue Dashicons style for frontend use when enqueuing your theme's style sheet
         */
        function ocws_wcab_scripts() {
                wp_enqueue_style( 'ocws_wcab-style', get_stylesheet_uri(), 'dashicons' );
        }
        
        if ( is_plugin_active('woocommerce/woocommerce.php') ) {
            add_action( 'admin_bar_menu', 'ocws_wcab_newmenu',20 );
        } // end of testing for WooCommerce
        
        function ocws_wcab_newmenu() {
                global $wp_admin_bar;
                global $wp_version;
                global $post;
                
                if ((is_admin())||(current_user_can('editor'))) { // to test to see if the user is an admin; otherwise do nothing
                
                    $ocws_wcab_countposts = wp_count_posts('shop_order');
                    $ocws_wcab_orders = $ocws_wcab_countposts->wc-completed;

                    $menupt_title = "";
                    $plugin_url = plugins_url( plugin_basename( dirname( __FILE__ ) ) ) ;
                    $ocws_wcab_title = "<img src=\"".$plugin_url."/images/cart_16x16.png\"";
                    $ocws_wcab_title .= " style=\"vertical-align:middle;margin-right:5px\" width=\"16\" height=\"16\" alt=\"OCWS\" />";
                    $ocws_wcab_title .= " Orders to Process ";

                    $wp_admin_bar->add_menu(array(
                                    'id' => 'ocws_wcab_newmenu',
                                    'title' => $ocws_wcab_title." ".(ocws_wbac_count_orders('pending') + ocws_wbac_count_orders('processing')),
                                    'href' => site_url('/wp-admin/edit.php?post_type=shop_order'),
                                    ));
                } // the plugin does nothing if the user is not an admin
        } // end ocws_wcab_newmenu
        
        function ocws_wbac_count_orders( $status ) {
            // to count the number of orders
            // code obtained from 
            // https://github.com/bekarice/woocommerce-display-order-count/blob/master/woocommerce-display-order-count.php and edited
            
            
            $order_count = 0;
            
                    // if we didn't get a wc- prefix, add one
                    if ( 0 !== strpos( $status, 'wc-' ) ) {
                            $status = str_replace( $status, 'wc-' . $status, $status );
                    }
                    $total_orders = wp_count_posts( 'shop_order' )->$status;
                    $order_count += $total_orders;
            
            
            return $total_orders;
        } // end function ocws_wbac_count_orders




?>