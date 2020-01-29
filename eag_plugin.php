<?php
/**
 * Plugin Name: eag_plugin
 * Plugin URI: 
 * Description: 
 * Version: 1.0
 * Author: EAG
 * Author URI: 
 */

add_action('admin_menu', 'eag_settings');

function eag_settings() {

	add_menu_page('EAG settings', 'EAG plugin', 'administrator', __FILE__, 'pagina_ajustes',plugins_url('/images/eag_logo.png', __FILE__),1);

	add_submenu_page(__FILE__, 'Header', 'Ver Header', 'administrator', 'submenu1', 'output_header');
	add_submenu_page(__FILE__, 'Footer', 'Ver Footer', 'administrator', 'submenu2', 'output_footer');
	add_submenu_page(__FILE__, 'Paginas', 'Ver Paginas', 'administrator', 'submenu3', 'output_pages');
	add_submenu_page(__FILE__, 'NuevoPost', 'Nuevo post', 'administrator', 'submenu5', 'insert_post');
	add_submenu_page(__FILE__, 'Buscar', 'Buscar', 'administrator', 'submenu4', 'search');
	add_submenu_page(__FILE__, 'Cambiar Footer', 'Cambiar Footer', 'administrator', 'submenu6', 'crear_menu_pie');

	add_action( 'admin_init', 'registrar_ajustes' );
}

add_filter( 'avada_before_body_content', 'setData');
function setData(){
	?>
	<marquee><h1>NEW HEADER3</h1></marquee>
	<?php
}


function crear_menu_pie() {
  include('form_footer.php');

  if($_POST && $_POST['textopie']) {
    $texto = $_POST['textopie'];
    if(update_option('valor_footer', $texto)) {
      echo '<h2>El valor ha sido almacenado</h2>';
    } else {
      echo '<h2>No se pudo configurar el texto del pie</h2>';
    }
  }

}

add_action('wp_footer', 'agregar_en_footer');

function agregar_en_footer() {
  if($texto_pie = get_option('valor_footer')) {
    echo "<p>{$texto_pie}</p>";
  }
}



function search() {

	?>
	<h1>Buscar</h1>
	<div><form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<div style="padding: 12px">
			<label for="s" class="assistive-text">
				<?php _e( 'Busca posts, entradas, noticias...', 'twentyeleven' ); ?>
			</label>
		</div>
	</form></div>
	<?php
	echo esc_attr(get_search_form());

}

function output_header() {

	?>
	<div class="wrap">
		<h1>Header activo:</h1>
	</div>
	<?php 

	echo esc_attr( get_header());
}

function output_footer() {

	?>
	<div class="wrap">
	<h1>Footer activo:</h1>
	</div>
	<?php
	echo esc_attr( get_footer());
}

function output_pages() {

	?>
	<div class="wrap">
	<h1>Todas las paginas activas:</h1>
	</div>
	<?php

	echo esc_attr( wp_list_pages(array('sort_column' => 'post_date', 'sort_order' => 'desc')));
}

function insert_post() {

	if(isset($_POST['new_post']) == '1') {
	    $post_title = $_POST['post_title'];
	    $post_category = $_POST['cat'];
	    $post_content = $_POST['post_content'];

	    $new_post = array(
	          'ID' => '',
	          'post_author' => '', 
	          'post_category' => array($post_category),
	          'post_content' => $post_content, 
	          'post_title' => $post_title,
	          'post_status' => 'publish'
	        );

	    $post_id = wp_insert_post($new_post);

	    $post = get_post($post_id);
	    wp_redirect($post->guid);
	}

	?>
	<div class="wrap">

		<h1>Insertar nueva entrada</h1>

		<form method="post" action=""> 
			<div>
				<h2>Elige titulo:</h2>
				<input type="text" name="post_title" size="45" id="input-title"/>
			</div>
		    <div>
				<h2>Elige categoria:</h2>
		    	<?php wp_dropdown_categories('orderby=name&hide_empty=0&exclude=1&hierarchical=1'); ?>
		    </div>
		    <div>
				<h2>Escribe el texto:</h2>
		    	<textarea rows="5" name="post_content" cols="100" id="text-desc"></textarea>
		    </div>
		   	<div>
		   		<input type="hidden" name="new_post" value="1"/>
		   	</div>
		    <div>
		    	<input class="subput round" type="submit" name="submit" value="Enviar"/>
		    </div>
		</form>

	</div>
	<?php

	
}


function registrar_ajustes() {
	register_setting( 'eag_settings_db', 'blogname' );
	register_setting( 'eag_settings_db', 'blogdescription' );
	register_setting( 'eag_settings_db', 'admin_email' );
	register_setting( 'eag_settings_db', 'current_theme' );
	register_setting( 'eag_settings_db', 'woocommerce_store_address' );
	register_setting( 'eag_settings_db', 'woocommerce_store_address_2' );
	register_setting( 'eag_settings_db', 'woocommerce_store_city' );
	register_setting( 'eag_settings_db', 'woocommerce_default_country' );
	register_setting( 'eag_settings_db', 'woocommerce_email_from_address' );
	register_setting( 'eag_settings_db', 'woocommerce_enable_coupons' );
	register_setting( 'eag_settings_db', 'woocommerce_email_footer_text' );
}



function pagina_ajustes() {
		?>
		<div class="wrap">
		<h1>EAG Plugin</h1>

		<form method="post" action="options.php">
		    <?php settings_fields( 'eag_settings_db' ); ?>
		    <?php do_settings_sections( 'eag_settings_db' ); ?>
		    <table class="form-table">

		        <tr valign="top">

		        <th scope="row">Nombre de la Web</th>
		        <td><input type="text" name="blogname" value="<?php echo esc_attr( get_option('blogname') ); ?>" /></td>


		        <th scope="row">Descripcion de la Web</th>
		        <td><input type="text" name="blogdescription" value="<?php echo esc_attr( get_option('blogdescription') ); ?>" /></td>


				<th scope="row">Email del admin</th>
		        <td><input type="text" name="admin_email" value="<?php echo esc_attr( get_option('admin_email') ); ?>" /></td>

		        </tr>
		         
		        		        
		        <tr valign="top">
		        <th scope="row">Theme actual</th>
		        <td><input type="text" name="current_theme" value="<?php echo esc_attr( get_option('current_theme') ); ?>" /></td>


		        <th scope="row">Direccion de la tienda</th>
		        <td><input type="text" name="woocommerce_store_address" value="<?php echo esc_attr( get_option('woocommerce_store_address') ); ?>" /></td>


		        <th scope="row">Direccion 2</th>
		        <td><input type="text" name="woocommerce_store_address_2" value="<?php echo esc_attr( get_option('woocommerce_store_address_2') ); ?>" /></td>

		        </tr>

		        
		        <tr valign="top">
		        <th scope="row">Ciudad de la tienda</th>
		        <td><input type="text" name="woocommerce_store_city" value="<?php echo esc_attr( get_option('woocommerce_store_city') ); ?>" /></td>


		        <th scope="row">Pais de la tienda</th>
		        <td><input type="text" name="woocommerce_default_country" value="<?php echo esc_attr( get_option('woocommerce_default_country') ); ?>" /></td>


		        <th scope="row">Email de la tienda</th>
		        <td><input type="text" name="woocommerce_email_from_address" value="<?php echo esc_attr( get_option('woocommerce_email_from_address') ); ?>" /></td>
		        </tr>

		        
		        <tr valign="top">
		        <th scope="row">Activar cupones</th>
		        <td><input type="text" name="woocommerce_enable_coupons" value="<?php echo esc_attr( get_option('woocommerce_enable_coupons') ); ?>" /></td>


		        <th scope="row">Footer de emails ventas</th>
		        <td><input type="text" name="woocommerce_email_footer_text" value="<?php echo esc_attr( get_option('woocommerce_email_footer_text') ); ?>" /></td>
		        </tr>

		    </table>
		    
		    <?php submit_button(); ?>

		</form>
		</div>
		<?php
	}
?>