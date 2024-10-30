<?php
/*
Plugin Name: CoronaWP – A Corona virus/COVID-19 custom info banner
Plugin URI: https://www.coronawp.com/
Description: A simple plugin which will display a banner, where you can add your own content, in the header of your site.
Author: Stein H
Version: 1.1.4
Author URI: https://www.coronawp.com/
Text Domain: corona-wp
*/


/* Options page */
require_once('RationalOptionPages.php');
$pages = array(
	'cwp_options_page'	=> array(
		'page_title'	=> __( 'CoronaWP', 'corona-wp' ),
		'position'		=> '2',
		'icon_url'		=> 'dashicons-sticky',
		'sections'		=> array(
			'section-one'	=> array(
				'title'			=> __( 'Basic settings', 'corona-wp' ),
				'text' 			=> __( 'Here you can change your banner settings. For more info about the plugin, visit the <a target="_blank" href="https://www.coronawp.com/">CoronaWP</a> site.', 'corona-wp' ),
				'fields'		=> array(
					
					'status'		=> array(
						'title'			=> __( 'Status', 'corona-wp' ),
						'type'			=> 'checkbox',
						'text'			=> __( 'Active' ),
					),
					'title	'		=> array(
						'title'			=> __( 'Title', 'corona-wp' ),
					),
					'excerpt'		=> array(
						'title'			=> __( 'Excerpt', 'corona-wp' ),
						'type'			=> 'textarea',
						'placeholder'		=> 'Keep it short and to the point.',
					),
					'button_text'	=> array(
						'title'			=> __( 'Button text', 'corona-wp' ),
						'placeholder'	=> __( 'Read more', 'corona-wp' )
					),
					'button_url'	=> array(
						'title'			=> __( 'Button URL', 'corona-wp' ),
						'type'			=> 'url',
						'placeholder'	=> 'https://',
					),
					'button_target'	=> array(
						'title'			=> __( 'Button target', 'corona-wp' ),
						'type'			=> 'checkbox',
						'text'			=> __( 'Open link in new window' ),
					),
				),
			),
			'section-two'	=> array(
				'title'			=> __( 'Design settings', 'corona-wp' ),
				'text'			=> __( 'In this section you can change the look and feel of your banner.', 'corna-wp' ),
				'fields'		=> array(
					/*'color'			=> array(
						'title'			=> __( 'Color', 'corona-wp' ),
						'type'			=> 'color',
						'value'			=> '#cc0000',
					),*/
					'alignment'		=> array(
						'title'			=> __( 'Text alignment', 'corona-wp' ),
						'type'			=> 'radio',
						'value'			=> 'align_left',
						'choices'		=> array(
							'align_left'	=> __( 'Left', 'corona-wp' ),
							'align_center'	=> __( 'Center', 'corona-wp' ),
							/*'align_right'	=> __( 'Right', 'corona-wp' ),*/
						),
					),
					'sticky'		=> array(
						'title'			=> __( 'Sticky', 'corona-wp' ),
						'type'			=> 'checkbox',
						'text'			=> __( 'Yes' ),
					),
					'poistion'			=> array(
						'title'			=> __( 'Position', 'corona-wp' ),
						'type'			=> 'radio',
						'value'			=> 'position_top',
						'choices'		=> array(
							'position_top'		=> __( 'Position top', 'corona-wp' ),
							'position_bottom'	=> __( 'Position bottom', 'corona-wp' ),
						),
					),
					'banner_size'			=> array(
						'title'			=> __( 'Banner size', 'corona-wp' ),
						'type'			=> 'radio',
						'value'			=> 'slim',
						'choices'		=> array(
							'slim'	=> __( 'Slim', 'corona-wp' ),
							'extended'	=> __( 'Extended', 'corona-wp' ),
						),
					),
					'colortheme'	=> array(
						'title'			=> __( 'Color theme', 'corona-wp' ),
						'type'			=> 'select',
						'value'			=> '#fff3cd',
						'choices'		=> array(
							'#fff3cd'	=> __( 'Yellow', 'corona-wp' ),
							'#bee5eb'	=> __( 'Blue', 'corona-wp' ),
							'#ffffff'	=> __( 'White', 'corona-wp' ),
							'#f8d7da'	=> __( 'Red', 'corona-wp' ),
							'#d4edda'	=> __( 'Green', 'corona-wp' ),
							'#e2e3e5'	=> __( 'Grey', 'corona-wp' ),
							'#333333'	=> __( 'Dark grey', 'corona-wp' ),
							/*'custom'	=> __( 'Custom', 'corona-wp' ),*/
							
						),
					),
					
				),
			),
		),
	),
);
$option_page = new RationalOptionPages( $pages );


/* Escape all HTML, JavaScript, and CSS */
function noHTML($input, $encoding = 'UTF-8') {
	return htmlentities($input, ENT_QUOTES | ENT_HTML5, $encoding);
}


function cwp_add_banner_head() {
	
	// Get all options for the page
	$options = get_option( 'cwp_options_page', array() );
	
	// Each field id is a key in the options array
	$status = $options['status'];
	$title = $options['title'];
	$excerpt = $options['excerpt'];
	$button_text = $options['button_text'];
	$button_url = $options['button_url'];
	$button_target = $options['button_target'];
	$alignment = $options['text_alignment'];
	$sticky = $options['sticky'];
	$position = $options['position'];
	$color_theme = $options['color_theme'];
	$banner_size = $options['banner_size'];
	
	// Set banner colors
	$cwp_color_bg = (($color_theme) ? $color_theme : '#dcf6f1');
	$cwp_color_txt = (($color_theme == '#333333') ? '#fff' : '#333');
	
	//print_r($options);
?>
	<style type='text/css'>
		#coronawp {
			font-size: 16px;
			line-height: 1.4em;
			color: <?php echo $cwp_color_txt; ?>;
			padding: 20px 20px;
			background: <?php echo $cwp_color_bg; ?>;
			align-items: center;
			display: flex;
			flex-wrap: wrap;
			
		<?php if($alignment == 'align_center') { ?>
			justify-content: center;
			text-align: center;
		<?php } else { ?>
			justify-content: flex-start;
		<?php } ?>
			
		<?php if($sticky) { ?>
			z-index: 9999;
			position: -webkit-sticky; /* Safari */
			position: sticky;
			
			<?php if($position == 'position_top') { ?>
				top: 0px;
			<?php } else { ?>
				bottom: 0px;
			<?php } ?>
			
			width: 100%;
		<?php } ?>
		}
		#coronawp strong {
			font-weight: 700;
			margin-right: 3px;
		}
		#coronawp h3 {
			font-weight: 700;
			font-size: 22px;
			margin: 0 0 6px 0;
			padding: 0;
		}
		#coronawp .cwp_button.cwp_extended {
			font-weight: 500;
			margin: 14px 0 6px 0;
			padding-bottom: 3px;
			display: inline-block;
			border-bottom: 3px solid;
		}
		#coronawp .cwp_button {
			color: <?php echo $cwp_color_txt; ?>;
			border-bottom: 1px solid;
			text-decoration: none;
			margin: 0;
			padding: 0;
			transition: border 300ms;
		}
		#coronawp .cwp_button:hover {
			border-color: transparent;
		}
		@media screen and (max-width: 768px) {
			#coronawp {
				font-size: 14px;
				padding: 16px 20px;
			}
			#coronawp strong {
				margin-right: 4px;
			}
			#coronawp h3 {
				font-size: 18px;
			}
		}
	</style>

	<div id="coronawp">
		<div class="cwp_content">
			
			<?php if($banner_size == 'slim') { ?>
				
				<?php echo '<strong>' . noHTML($title) . '</strong> ' . noHTML($excerpt); ?> 
				<?php if(!empty($button_text)) { ?>
					<a <?php if($button_target == true) { echo 'target="_blank"'; } ?> title="<?php echo noHTML($button_text); ?>" class="cwp_button" href="<?php echo noHTML($button_url); ?>" class="cwp_button"><?php echo noHTML($button_text); ?></a>
				<?php } ?>
				
			<?php } elseif($banner_size == 'extended') { ?>
	
				<?php echo '<h3>' . noHTML($title) . '</h3><div>' . noHTML($excerpt) . '</div>'; ?> 
				<?php if(!empty($button_text)) { ?>
					<a <?php if($button_target == true) { echo 'target="_blank"'; } ?> title="<?php echo noHTML($button_text); ?>" class="cwp_extended cwp_button" href="<?php echo noHTML($button_url); ?>" class="cwp_button"><?php echo noHTML($button_text); ?></a>
				<?php } ?>
			
			<?php } ?>
			
		</div>
	</div>	
	
<?php }



// Check if status is active, if so; add_action
add_action('init', 'cwp_check_status');
function cwp_check_status() {
	
	// Get options
	$options = get_option( 'cwp_options_page', array() );
	$status = $options['status'];
	$position = $options['position'];
	
	if($status && $position == 'position_top' ) {
		add_action( 'wp_head', 'cwp_add_banner_head' );
	} 
	elseif($status) {
		add_action( 'wp_footer', 'cwp_add_banner_head' );
	} 
	return;
}


// Add links in plugin admin
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'salcode_add_plugin_page_settings_link');
function salcode_add_plugin_page_settings_link( $links ) {
	$links[] = '<a href="' .
		admin_url( '/admin.php?page=coronawp' ) .
		'">' . __('Settings') . '</a>';
	$links[] = '<a taget="_blank" href="https://www.coronawp.com/">Plugin website</a>';
	return $links;
}



