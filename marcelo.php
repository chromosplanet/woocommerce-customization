<?php
/**
* Plugin Name: Marcelo
* Description: Plugin that adds new status order, send a e-mail notification when it sent
* Version: 1.0
* Author: Marcelo Moreira Velloso 
* Author URI: http://www.chromosplanet.com.br/
**/

add_action( 'init', 'register_custom_post_status', 10 );
    function register_custom_post_status() {
        register_post_status( 'wc-sent', array(
            'label'                     => _x( 'Enviado', 'Order status', 'woocommerce' ),
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop( 'Enviado <span class="count">(%s)</span>', 

'Enviado <span class="count">(%s)</span>', 'woocommerce' )
        ) );
}
add_filter( 'wc_order_statuses', 'custom_wc_order_statuses' );

function custom_wc_order_statuses( $order_statuses ) {
    $order_statuses['wc-sent'] = _x( 'Enviado', 'Order status', 'woocommerce' );
    return $order_statuses;
}

function add_enviado_email( $email_classes ) {

    // include our custom email class
    require( 'includes/class-enviado_email.php' );

    // add the email class to the list of email classes that WooCommerce loads
    $email_classes['enviado_email'] = new enviado_email();

    return $email_classes;
}
add_filter( 'woocommerce_email_classes', 'add_enviado_email' );

function mostra_progresso ($atts) {
	$order_id = shortcode_atts(['numpedido' => 0,], $atts); 
	$order = new WC_Order( $order_id['numpedido'] );
	$output = '  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <div>
    <div style="position:absolute;">
    <img src="http://chromosplanet.com.br/yogh/wp-content/plugins/marcelo/includes/mascara-2.png">    
    </div>
    <div class="progress-bar progress-bar-success" role="progressbar" style="height: 80px; margin: 61px 0 24px 18px; max-width: 639px; width:
';

if ( $order->status === 'on-hold' ) {
  $output = $output.'11.5%';
}
if ( $order->status === 'processing' ) {
  $output = $output.'37.9%';
}
if ( $order->status === 'pending' ) {
  $output = $output.'64%';
}
if ( $order->status === 'sent' ) {
  $output = $output.'92%';
}

$output = $output.'"></div> </div>';

return $output;
}
add_shortcode('mostra-progresso','mostra_progresso');
