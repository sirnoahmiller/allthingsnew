<?php
/**
 * Plugin Name: All Things New – Social Media Generator
 * Plugin URI: https://example.com/
 * Description: Adds a [all_things_new_generator] shortcode with a photo-composition tool (visitors upload their own photo, position/zoom/rotate it, and download it composited with the "All Things New" campaign frame in horizontal, story, and square formats), plus a Tetum/English language switcher.
 * Version: 1.1.0
 * Requires at least: 5.5
 * Requires PHP: 7.0
 * Author: All Things New
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: all-things-new-generator
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No direct access.
}

define( 'ATNG_VERSION', '1.1.0' );
define( 'ATNG_URL', plugin_dir_url( __FILE__ ) );
define( 'ATNG_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Register (but don't force-load) the plugin's CSS/JS. They are only
 * enqueued when the [all_things_new_generator] shortcode actually runs.
 */
function atng_register_assets() {
	wp_register_style( 'atn-generator', ATNG_URL . 'style.css', array(), ATNG_VERSION );
	wp_register_script( 'atn-generator', ATNG_URL . 'assets/js/atn-generator.js', array(), ATNG_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'atng_register_assets' );

/**
 * Output Open Graph / Twitter Card meta tags pointing at the campaign's
 * cover.jpg on any singular page/post that contains the shortcode, so the
 * link gets the right preview image when shared on social media.
 *
 * Skipped when a dedicated SEO plugin (Yoast, Rank Math, All in One SEO) is
 * active, since those already manage og:image and set their own social image
 * per page — set the image there instead to avoid duplicate/conflicting tags.
 */
function atng_output_social_meta() {
	if ( ! is_singular() ) {
		return;
	}

	if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) ) {
		return;
	}

	$post = get_queried_object();
	if ( ! ( $post instanceof WP_Post ) || ! has_shortcode( $post->post_content, 'all_things_new_generator' ) ) {
		return;
	}

	$image_url = ATNG_URL . 'assets/img/cover.jpg';
	$title     = get_the_title( $post );
	$excerpt   = has_excerpt( $post ) ? get_the_excerpt( $post ) : get_bloginfo( 'description' );
	?>
	<meta property="og:type" content="website">
	<meta property="og:title" content="<?php echo esc_attr( $title ); ?>">
	<meta property="og:description" content="<?php echo esc_attr( $excerpt ); ?>">
	<meta property="og:url" content="<?php echo esc_url( get_permalink( $post ) ); ?>">
	<meta property="og:image" content="<?php echo esc_url( $image_url ); ?>">
	<meta property="og:image:width" content="800">
	<meta property="og:image:height" content="450">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="<?php echo esc_attr( $title ); ?>">
	<meta name="twitter:description" content="<?php echo esc_attr( $excerpt ); ?>">
	<meta name="twitter:image" content="<?php echo esc_url( $image_url ); ?>">
	<?php
}
add_action( 'wp_head', 'atng_output_social_meta', 5 );

/**
 * [all_things_new_generator] shortcode output.
 */
function atng_shortcode( $atts ) {
	wp_enqueue_style( 'atn-generator' );
	wp_enqueue_script( 'atn-generator' );

	$img = trailingslashit( ATNG_URL . 'assets/img' );

	ob_start();
	?>
	<div class="atn-wrap">

	  <div class="atn-top-banner">
	    <img src="<?php echo esc_url( $img . 'logo/Logo_Kor_Horizontal.png' ); ?>" alt="All Things New">
	  </div>
	  <main class="atn-container">
	    <header>
	      <div>
	        <h1><span class="atn-i18n" data-tet="#AllThingsNew — Materiais ba Redes Sosiál." data-en="#AllThingsNew — Materials for Social Media.">#AllThingsNew — Materiais ba Redes Sosiál.</span><svg viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" preserveAspectRatio="xMidYMid meet" fill="#000000" style="width:0.9em;height:0.9em;vertical-align:-0.1em;margin-left:8px"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path fill="#DC241F" d="M32 5H4a4 4 0 0 0-4 4v18a4 4 0 0 0 4 4h28a4 4 0 0 0 4-4V9a4 4 0 0 0-4-4z"></path><path fill="#FFC726" d="M16 18L1.296 29.947c.079.072.16.141.244.207L23.667 18L1.54 5.846a4.037 4.037 0 0 0-.244.207L16 18z"></path><path fill="#141414" d="M1.296 6.053l-.002.001A3.99 3.99 0 0 0 0 9v18c0 1.166.499 2.216 1.296 2.947L16 18L1.296 6.053z"></path><path fill="#FFF" d="M4.761 19.01l.492 3.269l1.523-2.934l3.262.542l-2.32-2.355l1.523-2.934l-2.957 1.478l-2.32-2.355l.493 3.269L1.5 18.468z"></path></g></svg></h1>
	        <p class="atn-lead atn-i18n" data-tet="Selesiona bo-nia foto rasik atu halo kompozisaun ho design &quot;All Things New&quot;, depois deskarrega imajen ne'ebé kompletu ona hodi partilha iha redes sosiál." data-en="Select your own photo to create a composition with the &quot;All Things New&quot; design, then download the finished image to share on social media.">Selesiona bo-nia foto rasik atu halo kompozisaun ho design "All Things New", depois deskarrega imajen ne'ebé kompletu ona hodi partilha iha redes sosiál.</p>
	      </div>
	      <div class="atn-lang-switch" role="group" aria-label="Language switcher / Troka lingua">
	        <button type="button" class="atn-lang-btn is-active" data-lang="tet">TET</button>
	        <button type="button" class="atn-lang-btn" data-lang="en">EN</button>
	      </div>
	    </header>

	    <section class="atn-grid atn-i18n-aria" data-tet-aria="Jerador imajen personalizadu" data-en-aria="Custom image generator" aria-label="Jerador imajen personalizadu">
	      <article class="atn-card atn-generator" data-width="1200" data-height="630" data-frame="<?php echo esc_url( $img . 'frame/horizontal_socialmedia_1200x630.png' ); ?>" data-placeholder="<?php echo esc_url( $img . 'horizontal_socialmedia_1200x630.jpg' ); ?>">
	        <div class="atn-canvas-wrap">
	          <canvas class="atn-canvas" width="1200" height="630"></canvas>
	        </div>
	        <p class="atn-adjust-hint atn-i18n" data-tet="<b>Adjusta:</b> Drag ba pozisiona, uza scroll ka botaun sira atu halo zoom, no rotasiona se presiza." data-en="<b>Adjust:</b> Drag to position, scroll or use buttons to zoom, and rotate if needed."><b>Adjusta:</b> Drag ba pozisiona, uza scroll ka botaun sira atu halo zoom, no rotasiona se presiza.</p>
	        <div class="atn-adjust-toolbar">
	          <button type="button" class="atn-icon-btn atn-zoom-out atn-i18n-attr" data-tet-title="Hamenus Zoom" data-en-title="Zoom out" title="Hamenus Zoom" aria-label="Hamenus Zoom" disabled>−</button>
	          <button type="button" class="atn-icon-btn atn-zoom-in atn-i18n-attr" data-tet-title="Aumenta Zoom" data-en-title="Zoom in" title="Aumenta Zoom" aria-label="Aumenta Zoom" disabled>+</button>
	          <button type="button" class="atn-icon-btn atn-rotate-left atn-i18n-attr" data-tet-title="Vira ba Karuk" data-en-title="Rotate left" title="Vira ba Karuk" aria-label="Vira ba Karuk" disabled>⟲</button>
	          <button type="button" class="atn-icon-btn atn-rotate-right atn-i18n-attr" data-tet-title="Vira ba Loos" data-en-title="Rotate right" title="Vira ba Loos" aria-label="Vira ba Loos" disabled>⟳</button>
	        </div>
	        <div class="atn-meta atn-i18n" data-tet="Imajen Horizontál • 1200×630" data-en="Horizontal Image • 1200×630">Imajen Horizontál • 1200×630</div>
	        <div class="atn-actions">
	          <label class="atn-btn atn-btn-ghost atn-upload-label">
	            <svg class="atn-btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
	            <span class="atn-i18n" data-tet="Tira Foto" data-en="Take Photo">Tira Foto</span>
	            <input type="file" accept="image/*" capture="user" class="atn-file-input">
	          </label>
	          <label class="atn-btn atn-btn-ghost atn-upload-label">
	            <svg class="atn-btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
	            <span class="atn-i18n" data-tet="Selesiona husi Galeria" data-en="Select from Gallery">Selesiona husi Galeria</span>
	            <input type="file" accept="image/*" class="atn-file-input">
	          </label>
	        </div>
	        <div class="atn-actions">
	          <button type="button" class="atn-btn atn-btn-download atn-download-btn atn-i18n" data-tet="Deskarrega Imajen" data-en="Download Image" disabled>Deskarrega Imajen</button>
	        </div>
	        <p class="atn-generator-note atn-i18n" data-tet="Design frame seidauk disponivel — imajen download sei uza foto de'it, sem frame." data-en="Design frame not available yet — the downloaded image will use just the photo, without the frame.">Design frame seidauk disponivel — imajen download sei uza foto de'it, sem frame.</p>
	        <p class="atn-i18n" data-tet="<b>Uza hashtags:</b> #AllThingsNew" data-en="<b>Use hashtags:</b> #AllThingsNew"><b>Uza hashtags:</b> #AllThingsNew</p>
	      </article>

	      <article class="atn-card atn-generator" data-width="1080" data-height="1920" data-frame="<?php echo esc_url( $img . 'frame/vertical_socialmedia_1080x1920.png' ); ?>" data-placeholder="<?php echo esc_url( $img . 'vertical_socialmedia_1080x1920.jpg' ); ?>">
	        <div class="atn-canvas-wrap">
	          <canvas class="atn-canvas" width="1080" height="1920"></canvas>
	        </div>
	        <p class="atn-adjust-hint atn-i18n" data-tet="<b>Adjusta:</b> Drag ba pozisiona, uza scroll ka botaun sira atu halo zoom, no rotasiona se presiza." data-en="<b>Adjust:</b> Drag to position, scroll or use buttons to zoom, and rotate if needed."><b>Adjusta:</b> Drag ba pozisiona, uza scroll ka botaun sira atu halo zoom, no rotasiona se presiza.</p>
	        <div class="atn-adjust-toolbar">
	          <button type="button" class="atn-icon-btn atn-zoom-out atn-i18n-attr" data-tet-title="Hamenus Zoom" data-en-title="Zoom out" title="Hamenus Zoom" aria-label="Hamenus Zoom" disabled>−</button>
	          <button type="button" class="atn-icon-btn atn-zoom-in atn-i18n-attr" data-tet-title="Aumenta Zoom" data-en-title="Zoom in" title="Aumenta Zoom" aria-label="Aumenta Zoom" disabled>+</button>
	          <button type="button" class="atn-icon-btn atn-rotate-left atn-i18n-attr" data-tet-title="Vira ba Karuk" data-en-title="Rotate left" title="Vira ba Karuk" aria-label="Vira ba Karuk" disabled>⟲</button>
	          <button type="button" class="atn-icon-btn atn-rotate-right atn-i18n-attr" data-tet-title="Vira ba Loos" data-en-title="Rotate right" title="Vira ba Loos" aria-label="Vira ba Loos" disabled>⟳</button>
	        </div>
	        <div class="atn-meta atn-i18n" data-tet="Vertikal Story • 1080×1920" data-en="Vertical Story • 1080×1920">Vertikal Story • 1080×1920</div>
	        <div class="atn-actions">
	          <label class="atn-btn atn-btn-ghost atn-upload-label">
	            <svg class="atn-btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
	            <span class="atn-i18n" data-tet="Tira Foto" data-en="Take Photo">Tira Foto</span>
	            <input type="file" accept="image/*" capture="user" class="atn-file-input">
	          </label>
	          <label class="atn-btn atn-btn-ghost atn-upload-label">
	            <svg class="atn-btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
	            <span class="atn-i18n" data-tet="Selesiona husi Galeria" data-en="Select from Gallery">Selesiona husi Galeria</span>
	            <input type="file" accept="image/*" class="atn-file-input">
	          </label>
	        </div>
	        <div class="atn-actions">
	          <button type="button" class="atn-btn atn-btn-download atn-download-btn atn-i18n" data-tet="Deskarrega Imajen" data-en="Download Image" disabled>Deskarrega Imajen</button>
	        </div>
	        <p class="atn-generator-note atn-i18n" data-tet="Design frame seidauk disponivel — imajen download sei uza foto de'it, sem frame." data-en="Design frame not available yet — the downloaded image will use just the photo, without the frame.">Design frame seidauk disponivel — imajen download sei uza foto de'it, sem frame.</p>
	        <p class="atn-i18n" data-tet="<b>Uza hashtags:</b> #AllThingsNew" data-en="<b>Use hashtags:</b> #AllThingsNew"><b>Uza hashtags:</b> #AllThingsNew</p>
	      </article>

	      <article class="atn-card atn-generator" data-width="1080" data-height="1080" data-frame="<?php echo esc_url( $img . 'frame/square_socialmedia_1080x1080.png' ); ?>" data-placeholder="<?php echo esc_url( $img . 'square_socialmedia_1080x1080.jpg' ); ?>">
	        <div class="atn-canvas-wrap">
	          <canvas class="atn-canvas" width="1080" height="1080"></canvas>
	        </div>
	        <p class="atn-adjust-hint atn-i18n" data-tet="<b>Adjusta:</b> Drag ba pozisiona, uza scroll ka botaun sira atu halo zoom, no rotasiona se presiza." data-en="<b>Adjust:</b> Drag to position, scroll or use buttons to zoom, and rotate if needed."><b>Adjusta:</b> Drag ba pozisiona, uza scroll ka botaun sira atu halo zoom, no rotasiona se presiza.</p>
	        <div class="atn-adjust-toolbar">
	          <button type="button" class="atn-icon-btn atn-zoom-out atn-i18n-attr" data-tet-title="Hamenus Zoom" data-en-title="Zoom out" title="Hamenus Zoom" aria-label="Hamenus Zoom" disabled>−</button>
	          <button type="button" class="atn-icon-btn atn-zoom-in atn-i18n-attr" data-tet-title="Aumenta Zoom" data-en-title="Zoom in" title="Aumenta Zoom" aria-label="Aumenta Zoom" disabled>+</button>
	          <button type="button" class="atn-icon-btn atn-rotate-left atn-i18n-attr" data-tet-title="Vira ba Karuk" data-en-title="Rotate left" title="Vira ba Karuk" aria-label="Vira ba Karuk" disabled>⟲</button>
	          <button type="button" class="atn-icon-btn atn-rotate-right atn-i18n-attr" data-tet-title="Vira ba Loos" data-en-title="Rotate right" title="Vira ba Loos" aria-label="Vira ba Loos" disabled>⟳</button>
	        </div>
	        <div class="atn-meta atn-i18n" data-tet="Quadradu • 1080×1080" data-en="Square • 1080×1080">Quadradu • 1080×1080</div>
	        <div class="atn-actions">
	          <label class="atn-btn atn-btn-ghost atn-upload-label">
	            <svg class="atn-btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>
	            <span class="atn-i18n" data-tet="Tira Foto" data-en="Take Photo">Tira Foto</span>
	            <input type="file" accept="image/*" capture="user" class="atn-file-input">
	          </label>
	          <label class="atn-btn atn-btn-ghost atn-upload-label">
	            <svg class="atn-btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
	            <span class="atn-i18n" data-tet="Selesiona husi Galeria" data-en="Select from Gallery">Selesiona husi Galeria</span>
	            <input type="file" accept="image/*" class="atn-file-input">
	          </label>
	        </div>
	        <div class="atn-actions">
	          <button type="button" class="atn-btn atn-btn-download atn-download-btn atn-i18n" data-tet="Deskarrega Imajen" data-en="Download Image" disabled>Deskarrega Imajen</button>
	        </div>
	        <p class="atn-generator-note atn-i18n" data-tet="Design frame seidauk disponivel — imajen download sei uza foto de'it, sem frame." data-en="Design frame not available yet — the downloaded image will use just the photo, without the frame.">Design frame seidauk disponivel — imajen download sei uza foto de'it, sem frame.</p>
	        <p class="atn-i18n" data-tet="<b>Uza hashtags:</b> #AllThingsNew" data-en="<b>Use hashtags:</b> #AllThingsNew"><b>Uza hashtags:</b> #AllThingsNew</p>
	      </article>

	    </section>

	    <section style="margin-top:22px">
	      <h2 style="margin:0 0 8px 0" class="atn-i18n" data-tet="Uza Hashtags: #AllThingsNew" data-en="Use Hashtags: #AllThingsNew">Uza Hashtags: #AllThingsNew</h2>
	    </section>

	    <footer>
	      <div class="atn-muted atn-i18n" data-tet="Modo hodi uza: Selesiona bo-nia foto rasik, depois deskarrega imajen kompostu no partilha iha redes sosiál ninian. Uza Hashtags: <b>#AllThingsNew</b>" data-en="How to use: Select your own photo, then download the composed image and share it on your social media. Use Hashtags: <b>#AllThingsNew</b>">Modo hodi uza: Selesiona bo-nia foto rasik, depois deskarrega imajen kompostu no partilha iha redes sosiál ninian. Uza Hashtags: <b>#AllThingsNew</b></div>
	    </footer>
	  </main>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'all_things_new_generator', 'atng_shortcode' );
