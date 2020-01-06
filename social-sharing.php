<?php

/**
 * Plugin Name: Social Sharing
 * Description: Simple and lightweight social sharing for popular networks
 * Version: 1.0.0
 * Author: Marko Štimac
 * Author URI: https://marko-stimac.github.io/
 */

namespace ms\SocialSharing;

defined('ABSPATH') || exit;

class SocialShares
{

	// Title of a post or website name
	public $title;
	// Twitter tweet
	public $tweet;
	// Clean url
	public $shared_url;

	public function __construct()
	{
		add_action('wp_enqueue_scripts', array($this, 'registerScripts'));
		add_filter('plugin_row_meta', array($this, 'modify_plugin_meta'), 10, 2);
	}

	// Add link to readme file on installed plugin listing
	public function modify_plugin_meta( $links_array, $plugin_file_name){
 
		if( strpos( $plugin_file_name, basename(__FILE__) )) {
			// you can still use array_unshift() to add links at the beginning
			$links_array[] = '<a href="' . plugins_url('readme.md', __FILE__) . '" target="_blank">How to use</a>';
		}
	 
		return $links_array;
	}

	// Registers scripts that can be later enqueued when needed
	public function registerScripts()
	{
		wp_register_style('social-shares', plugins_url('assets/social-shares.css', __FILE__));
		wp_register_script('social-shares', plugins_url('assets/social-shares.js', __FILE__), array('jquery'), false, true);
	}

	// Prepare data needed for components
	public function prepareData()
	{

		// Get title and encode it
		if (empty($this->title)) {
			$this->title = urlencode(urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8')));
		}

		// Get current post permalink and encode it
		if (empty($this->shared_url)) {
			$this->shared_url = get_permalink(get_the_ID());
		}

		// Get excerpt and format it for Twitter
		if (empty($this->tweet)) {
			$tweet = (strlen(get_the_excerpt()) > 140) ? substr(get_the_excerpt(), 0, 140) . '...' : get_the_excerpt();
			$this->tweet =  $tweet . ' ' . $this->shared_url;
		}
	}
		
	// Show social share buttons
	public function showComponent($atts)
	{
		
		$this->prepareData();

		// Default values
		$atts = shortcode_atts(array('show' => 'facebook, twitter, linkedin, email, whatsapp, viber'), $atts);
		wp_enqueue_style('social-shares');
		wp_enqueue_script('social-shares');
		ob_start();
	?>

<div class="ss">

	<?php if (strpos($atts['show'], 'facebook') !== false) : ?>
	<a class="ss__facebook" href="http://www.facebook.com/share.php?u=<?php echo $this->shared_url; ?>" title="Facebook" target="_BLANK">
		<svg width="30" height="30" viewBox="0 0 24 24">
			<path d="M12 2.04C6.5 2.04 2 6.53 2 12.06C2 17.06 5.66 21.21 10.44 21.96V14.96H7.9V12.06H10.44V9.85C10.44 7.34 11.93 5.96 14.22 5.96C15.31 5.96 16.45 6.15 16.45 6.15V8.62H15.19C13.95 8.62 13.56 9.39 13.56 10.18V12.06H16.34L15.89 14.96H13.56V21.96A10 10 0 0 0 22 12.06C22 6.53 17.5 2.04 12 2.04Z" />
		</svg>
	</a>
	<?php endif; ?>

	<?php if (strpos($atts['show'], 'twitter') !== false) : ?>
	<a class="ss__twitter" data-size="large" href="https://twitter.com/intent/tweet?text=<?php echo $this->tweet; ?>" title="Twitter" target="_BLANK">
		<svg width="30" height="30" viewBox="0 0 24 24">
			<path d="M22.46,6C21.69,6.35 20.86,6.58 20,6.69C20.88,6.16 21.56,5.32 21.88,4.31C21.05,4.81 20.13,5.16 19.16,5.36C18.37,4.5 17.26,4 16,4C13.65,4 11.73,5.92 11.73,8.29C11.73,8.63 11.77,8.96 11.84,9.27C8.28,9.09 5.11,7.38 3,4.79C2.63,5.42 2.42,6.16 2.42,6.94C2.42,8.43 3.17,9.75 4.33,10.5C3.62,10.5 2.96,10.3 2.38,10C2.38,10 2.38,10 2.38,10.03C2.38,12.11 3.86,13.85 5.82,14.24C5.46,14.34 5.08,14.39 4.69,14.39C4.42,14.39 4.15,14.36 3.89,14.31C4.43,16 6,17.26 7.89,17.29C6.43,18.45 4.58,19.13 2.56,19.13C2.22,19.13 1.88,19.11 1.54,19.07C3.44,20.29 5.7,21 8.12,21C16,21 20.33,14.46 20.33,8.79C20.33,8.6 20.33,8.42 20.32,8.23C21.16,7.63 21.88,6.87 22.46,6Z" />
		</svg>
	</a>
	<?php endif; ?>

	<?php if (strpos($atts['show'], 'linkedin') !== false) : ?>
	<a class="ss__linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url=&title=<?php echo $this->title; ?>&summary=&source=<?php echo $this->shared_url; ?>" title="LinkedIn" target="_BLANK">
		<svg width="30" height="30" viewBox="0 0 24 24">
			<path d="M21,21H17V14.25C17,13.19 15.81,12.31 14.75,12.31C13.69,12.31 13,13.19 13,14.25V21H9V9H13V11C13.66,9.93 15.36,9.24 16.5,9.24C19,9.24 21,11.28 21,13.75V21M7,21H3V9H7V21M5,3A2,2 0 0,1 7,5A2,2 0 0,1 5,7A2,2 0 0,1 3,5A2,2 0 0,1 5,3Z" />
		</svg>
	</a>
	<?php endif; ?>

	<?php if (strpos($atts['show'], 'email') !== false) : ?>
	<a class="ss__email" href="mailto:?subject=<?php echo $this->title; ?>&body=<?php echo $this->shared_url; ?>" title="E-mail" target="_BLANK">
		<svg width="30" height="30" viewBox="0 0 24 24">
			<path d="M20,8L12,13L4,8V6L12,11L20,6M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4Z" />
		</svg>
	</a>
	<?php endif; ?>

	<?php if (strpos($atts['show'], 'whatsapp') !== false) : ?>
	<a class="ss__whatsapp ss__mobile" href="whatsapp://send?text=<?php echo $this->title; ?>" title="WhatsApp" target="_BLANK" data-action="share/whatsapp/share">
		<svg width="30" height="30" viewBox="0 0 24 24">
			<path d="M16.75,13.96C17,14.09 17.16,14.16 17.21,14.26C17.27,14.37 17.25,14.87 17,15.44C16.8,16 15.76,16.54 15.3,16.56C14.84,16.58 14.83,16.92 12.34,15.83C9.85,14.74 8.35,12.08 8.23,11.91C8.11,11.74 7.27,10.53 7.31,9.3C7.36,8.08 8,7.5 8.26,7.26C8.5,7 8.77,6.97 8.94,7H9.41C9.56,7 9.77,6.94 9.96,7.45L10.65,9.32C10.71,9.45 10.75,9.6 10.66,9.76L10.39,10.17L10,10.59C9.88,10.71 9.74,10.84 9.88,11.09C10,11.35 10.5,12.18 11.2,12.87C12.11,13.75 12.91,14.04 13.15,14.17C13.39,14.31 13.54,14.29 13.69,14.13L14.5,13.19C14.69,12.94 14.85,13 15.08,13.08L16.75,13.96M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22C10.03,22 8.2,21.43 6.65,20.45L2,22L3.55,17.35C2.57,15.8 2,13.97 2,12A10,10 0 0,1 12,2M12,4A8,8 0 0,0 4,12C4,13.72 4.54,15.31 5.46,16.61L4.5,19.5L7.39,18.54C8.69,19.46 10.28,20 12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4Z" />
		</svg>
	</a>
	<?php endif; ?>

	<?php if (strpos($atts['show'], 'viber') !== false) : ?>
	<a class="ss__viber ss__mobile" href="viber://forward?text=<?php echo $this->title; ?> <?php $this->shared_url; ?>" title="Viber" target="_BLANK">
		<svg width="30" height="30" viewBox="0 0 455.731 455.731">
			<path d="M371.996,146.901l-0.09-0.36c-7.28-29.43-40.1-61.01-70.24-67.58l-0.34-0.07
			c-48.75-9.3-98.18-9.3-146.92,0l-0.35,0.07c-30.13,6.57-62.95,38.15-70.24,67.58l-0.08,0.36c-9,41.1-9,82.78,0,123.88l0.08,0.36
			c6.979,28.174,37.355,58.303,66.37,66.589v32.852c0,11.89,14.49,17.73,22.73,9.15l33.285-34.599
			c7.219,0.404,14.442,0.629,21.665,0.629c24.54,0,49.09-2.32,73.46-6.97l0.34-0.07c30.14-6.57,62.96-38.15,70.24-67.58l0.09-0.36
			C380.996,229.681,380.996,188.001,371.996,146.901z M345.656,264.821c-4.86,19.2-29.78,43.07-49.58,47.48
			c-25.921,4.929-52.047,7.036-78.147,6.313c-0.519-0.014-1.018,0.187-1.38,0.559c-3.704,3.802-24.303,24.948-24.303,24.948
			l-25.85,26.53c-1.89,1.97-5.21,0.63-5.21-2.09v-54.422c0-0.899-0.642-1.663-1.525-1.836c-0.005-0.001-0.01-0.002-0.015-0.003
			c-19.8-4.41-44.71-28.28-49.58-47.48c-8.1-37.15-8.1-74.81,0-111.96c4.87-19.2,29.78-43.07,49.58-47.48
			c45.27-8.61,91.17-8.61,136.43,0c19.81,4.41,44.72,28.28,49.58,47.48C353.765,190.011,353.765,227.671,345.656,264.821z" />
			<path d="M270.937,289.942c-3.044-0.924-5.945-1.545-8.639-2.663
			c-27.916-11.582-53.608-26.524-73.959-49.429c-11.573-13.025-20.631-27.73-28.288-43.292c-3.631-7.38-6.691-15.049-9.81-22.668
			c-2.844-6.948,1.345-14.126,5.756-19.361c4.139-4.913,9.465-8.673,15.233-11.444c4.502-2.163,8.943-0.916,12.231,2.9
			c7.108,8.25,13.637,16.922,18.924,26.485c3.251,5.882,2.359,13.072-3.533,17.075c-1.432,0.973-2.737,2.115-4.071,3.214
			c-1.17,0.963-2.271,1.936-3.073,3.24c-1.466,2.386-1.536,5.2-0.592,7.794c7.266,19.968,19.513,35.495,39.611,43.858
			c3.216,1.338,6.446,2.896,10.151,2.464c6.205-0.725,8.214-7.531,12.562-11.087c4.25-3.475,9.681-3.521,14.259-0.624
			c4.579,2.898,9.018,6.009,13.43,9.153c4.331,3.086,8.643,6.105,12.638,9.623c3.841,3.383,5.164,7.821,3.001,12.412
			c-3.96,8.408-9.722,15.403-18.034,19.868C276.387,288.719,273.584,289.127,270.937,289.942
			C267.893,289.017,273.584,289.127,270.937,289.942z" />
			<path d="M227.942,131.471c36.515,1.023,66.506,25.256,72.933,61.356c1.095,6.151,1.485,12.44,1.972,18.683
			c0.205,2.626-1.282,5.121-4.116,5.155c-2.927,0.035-4.244-2.414-4.434-5.039c-0.376-5.196-0.637-10.415-1.353-15.568
			c-3.78-27.201-25.47-49.705-52.545-54.534c-4.074-0.727-8.244-0.918-12.371-1.351c-2.609-0.274-6.026-0.432-6.604-3.675
			c-0.485-2.719,1.81-4.884,4.399-5.023C226.527,131.436,227.235,131.468,227.942,131.471
			C264.457,132.494,227.235,131.468,227.942,131.471z" />
			<path d="M283.434,203.407c-0.06,0.456-0.092,1.528-0.359,2.538c-0.969,3.666-6.527,4.125-7.807,0.425
			c-0.379-1.098-0.436-2.347-0.438-3.529c-0.013-7.734-1.694-15.46-5.594-22.189c-4.009-6.916-10.134-12.73-17.318-16.248
			c-4.344-2.127-9.042-3.449-13.803-4.237c-2.081-0.344-4.184-0.553-6.275-0.844c-2.534-0.352-3.887-1.967-3.767-4.464
			c0.112-2.34,1.822-4.023,4.372-3.879c8.38,0.476,16.474,2.287,23.924,6.232c15.15,8.023,23.804,20.687,26.33,37.597
			c0.114,0.766,0.298,1.525,0.356,2.294C283.198,199.002,283.288,200.903,283.434,203.407
			C283.374,203.863,283.288,200.903,283.434,203.407z" />
			<path d="M260.722,202.523c-3.055,0.055-4.69-1.636-5.005-4.437c-0.219-1.953-0.392-3.932-0.858-5.832
			c-0.918-3.742-2.907-7.21-6.055-9.503c-1.486-1.083-3.17-1.872-4.934-2.381c-2.241-0.647-4.568-0.469-6.804-1.017
			c-2.428-0.595-3.771-2.561-3.389-4.839c0.347-2.073,2.364-3.691,4.629-3.527c14.157,1.022,24.275,8.341,25.719,25.007
			c0.102,1.176,0.222,2.419-0.039,3.544C263.539,201.464,262.113,202.429,260.722,202.523
			C257.667,202.578,262.113,202.429,260.722,202.523z" />
		</svg>

	</a>
	<?php endif; ?>

</div>

<?php
		return ob_get_clean();
	}
}

$social_shares = new SocialShares();
add_shortcode('social-shares', array($social_shares, 'showComponent'));