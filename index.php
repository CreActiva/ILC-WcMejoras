<?php
defined ('ABSPATH') or die ('¡No HACKS Man!');
/*
 * Plugin Name: Remover Campos Woo
 * Description: Remueve los campos de más en woocommerce.
 * Version: 0.2
 * Author: Miguel Peña 
*/
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {
   unset($fields['billing']['billing_company']);
   unset($fields['billing']['billing_address_1']);
   unset($fields['billing']['billing_address_2']);
   unset($fields['billing']['billing_city']);
   unset($fields['billing']['billing_state']);
   unset($fields['billing']['billing_postcode']);
   unset($fields['order']['order_comments']);
   return $fields;
}

/*No repetir compras en el carrito*/
add_filter( 'woocommerce_add_cart_item_data', 'woo_custom_add_to_cart' );
function woo_custom_add_to_cart( $cart_item_data ) {
    global $woocommerce;
    $woocommerce->cart->empty_cart();
    return $cart_item_data;
}
/*================================*/

/*Desactivar carrito*/
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
remove_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );

/*Cambiar texto de Añadir a carrito por COMPRAR AHORA*/
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woocommerce_custom_single_add_to_cart_text' ); 
function woocommerce_custom_single_add_to_cart_text() {
    return __( 'COMPRAR AHORA', 'woocommerce' ); 
}
add_filter( 'woocommerce_product_add_to_cart_text', 'woocommerce_custom_product_add_to_cart_text' );  
function woocommerce_custom_product_add_to_cart_text() {
    return __( 'COMPRAR AHORA', 'woocommerce' );
}
/*==================*/

/*Ir a checkout al darle clic a "añadir al carrito"*/
function bbloomer_redirect_checkout_add_cart( $url ) {
   $url = get_permalink( get_option( 'woocommerce_checkout_page_id' ) ); 
   return $url;
}
 
add_filter( 'woocommerce_add_to_cart_redirect', 'bbloomer_redirect_checkout_add_cart' );
/*===============================================*/

/*Eliminar mensaje de "agregado a carrito"*/
add_filter( 'wc_add_to_cart_message_html', '__return_null' );
/*=====================================*/

/* Mostrar imagen de producto en el checkout */
add_action('woocommerce_before_checkout_form', 'displays_cart_products_feature_image');
function displays_cart_products_feature_image() {
    foreach ( WC()->cart->get_cart() as $cart_item ) {
        $item = $cart_item['data'];
        //print_r($item);
        if(!empty($item)){
            $product = new WC_product($item->id);
            // $image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->ID ), 'single-post-thumbnail' );
            echo $product->get_image();
            echo $product->name;
            // to display only the first product image uncomment the line bellow
            // break;
        }
    }
}
/* ========================================= */