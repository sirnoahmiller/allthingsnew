<?php
/**
 * Plugin Name: All Things New – Social Media Generator
 * Plugin URI: https://example.com/
 * Description: Adds a [all_things_new_generator] shortcode with a photo-composition tool (visitors upload their own photo, position/zoom/rotate it, and download it composited with the "All Things New" campaign frame in horizontal, story, and square formats) plus official logo downloads.
 * Version: 1.0.0
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

define( 'ATNG_VERSION', '1.0.0' );
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
	    <img src="<?php echo esc_url( $img . 'AllThingsNew-Colored.png' ); ?>" alt="All Things New">
	  </div>
	  <main class="atn-container">
	    <header>
	      <div>
	        <h1>#AllThingsNew — Materiais ba Redes Sosiál.<svg viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" role="img" preserveAspectRatio="xMidYMid meet" fill="#000000" style="width:0.9em;height:0.9em;vertical-align:-0.1em;margin-left:8px"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path fill="#DC241F" d="M32 5H4a4 4 0 0 0-4 4v18a4 4 0 0 0 4 4h28a4 4 0 0 0 4-4V9a4 4 0 0 0-4-4z"></path><path fill="#FFC726" d="M16 18L1.296 29.947c.079.072.16.141.244.207L23.667 18L1.54 5.846a4.037 4.037 0 0 0-.244.207L16 18z"></path><path fill="#141414" d="M1.296 6.053l-.002.001A3.99 3.99 0 0 0 0 9v18c0 1.166.499 2.216 1.296 2.947L16 18L1.296 6.053z"></path><path fill="#FFF" d="M4.761 19.01l.492 3.269l1.523-2.934l3.262.542l-2.32-2.355l1.523-2.934l-2.957 1.478l-2.32-2.355l.493 3.269L1.5 18.468z"></path></g></svg></h1>
	        <p class="atn-lead">Selesiona bo-nia foto rasik atu halo kompozisaun ho design "All Things New", depois deskarrega imajen ne'ebé kompletu ona hodi partilha iha redes sosiál.</p>
	      </div>
	    </header>

	    <section class="atn-grid" aria-label="Jerador imajen personalizadu">
	      <article class="atn-card atn-generator" data-width="1200" data-height="630" data-frame="<?php echo esc_url( $img . 'frame/horizontal_socialmedia_1200x630.png' ); ?>" data-placeholder="<?php echo esc_url( $img . 'horizontal_socialmedia_1200x630.jpg' ); ?>">
	        <div class="atn-canvas-wrap">
	          <canvas class="atn-canvas" width="1200" height="630"></canvas>
	        </div>
	        <p class="atn-adjust-hint"><b>Adjust:</b> Drag to position, scroll or use buttons to zoom, and rotate if needed.</p>
	        <div class="atn-adjust-toolbar">
	          <button type="button" class="atn-icon-btn atn-zoom-out" title="Zoom out" aria-label="Zoom out" disabled>−</button>
	          <button type="button" class="atn-icon-btn atn-zoom-in" title="Zoom in" aria-label="Zoom in" disabled>+</button>
	          <button type="button" class="atn-icon-btn atn-rotate-left" title="Rotate left" aria-label="Rotate left" disabled>⟲</button>
	          <button type="button" class="atn-icon-btn atn-rotate-right" title="Rotate right" aria-label="Rotate right" disabled>⟳</button>
	        </div>
	        <div class="atn-meta">Imajen Horizontál • 1200×630</div>
	        <div class="atn-actions">
	          <label class="atn-btn atn-btn-ghost atn-upload-label">
	            Selesiona Foto
	            <input type="file" accept="image/*" class="atn-file-input">
	          </label>
	          <button type="button" class="atn-btn atn-btn-download atn-download-btn" disabled>Deskarrega Imajen</button>
	        </div>
	        <p class="atn-generator-note">Design frame seidauk disponivel — imajen download sei uza foto de'it, sem frame.</p>
	        <p><b>Uza hashtags:</b> #AllThingsNew #HopeStartsHere #EsperansaHahuIhaNee</p>
	      </article>

	      <article class="atn-card atn-generator" data-width="1080" data-height="1920" data-frame="<?php echo esc_url( $img . 'frame/vertical_socialmedia_1080x1920.png' ); ?>" data-placeholder="<?php echo esc_url( $img . 'vertical_socialmedia_1080x1920.jpg' ); ?>">
	        <div class="atn-canvas-wrap">
	          <canvas class="atn-canvas" width="1080" height="1920"></canvas>
	        </div>
	        <p class="atn-adjust-hint"><b>Adjust:</b> Drag to position, scroll or use buttons to zoom, and rotate if needed.</p>
	        <div class="atn-adjust-toolbar">
	          <button type="button" class="atn-icon-btn atn-zoom-out" title="Zoom out" aria-label="Zoom out" disabled>−</button>
	          <button type="button" class="atn-icon-btn atn-zoom-in" title="Zoom in" aria-label="Zoom in" disabled>+</button>
	          <button type="button" class="atn-icon-btn atn-rotate-left" title="Rotate left" aria-label="Rotate left" disabled>⟲</button>
	          <button type="button" class="atn-icon-btn atn-rotate-right" title="Rotate right" aria-label="Rotate right" disabled>⟳</button>
	        </div>
	        <div class="atn-meta">Vertikal Story • 1080×1920</div>
	        <div class="atn-actions">
	          <label class="atn-btn atn-btn-ghost atn-upload-label">
	            Selesiona Foto
	            <input type="file" accept="image/*" class="atn-file-input">
	          </label>
	          <button type="button" class="atn-btn atn-btn-download atn-download-btn" disabled>Deskarrega Imajen</button>
	        </div>
	        <p class="atn-generator-note">Design frame seidauk disponivel — imajen download sei uza foto de'it, sem frame.</p>
	        <p><b>Uza hashtags:</b> #AllThingsNew #HopeStartsHere #EsperansaHahuIhaNee</p>
	      </article>

	      <article class="atn-card atn-generator" data-width="1080" data-height="1080" data-frame="<?php echo esc_url( $img . 'frame/square_socialmedia_1080x1080.png' ); ?>" data-placeholder="<?php echo esc_url( $img . 'square_socialmedia_1080x1080.jpg' ); ?>">
	        <div class="atn-canvas-wrap">
	          <canvas class="atn-canvas" width="1080" height="1080"></canvas>
	        </div>
	        <p class="atn-adjust-hint"><b>Adjust:</b> Drag to position, scroll or use buttons to zoom, and rotate if needed.</p>
	        <div class="atn-adjust-toolbar">
	          <button type="button" class="atn-icon-btn atn-zoom-out" title="Zoom out" aria-label="Zoom out" disabled>−</button>
	          <button type="button" class="atn-icon-btn atn-zoom-in" title="Zoom in" aria-label="Zoom in" disabled>+</button>
	          <button type="button" class="atn-icon-btn atn-rotate-left" title="Rotate left" aria-label="Rotate left" disabled>⟲</button>
	          <button type="button" class="atn-icon-btn atn-rotate-right" title="Rotate right" aria-label="Rotate right" disabled>⟳</button>
	        </div>
	        <div class="atn-meta">Quadradu • 1080×1080</div>
	        <div class="atn-actions">
	          <label class="atn-btn atn-btn-ghost atn-upload-label">
	            Selesiona Foto
	            <input type="file" accept="image/*" class="atn-file-input">
	          </label>
	          <button type="button" class="atn-btn atn-btn-download atn-download-btn" disabled>Deskarrega Imajen</button>
	        </div>
	        <p class="atn-generator-note">Design frame seidauk disponivel — imajen download sei uza foto de'it, sem frame.</p>
	        <p><b>Uza hashtags:</b> #AllThingsNew #HopeStartsHere #EsperansaHahuIhaNee</p>
	      </article>

	      <article class="atn-card">
	        <h3 class="atn-card-title">Logo Ofisiál</h3>
	        <div class="atn-thumb"><img src="<?php echo esc_url( $img . 'logo-ATN.jpg' ); ?>" alt="Logo All Things New"></div>
	        <div class="atn-actions-grid">
	          <a class="atn-btn atn-btn-download atn-btn-kor" href="<?php echo esc_url( $img . 'logo/Logo_Kor_Horizontal.png' ); ?>" download target="_blank" rel="noopener">Deskarrega PNG - Kor (Horizontál)</a>
	          <a class="atn-btn atn-btn-download atn-btn-kor" href="<?php echo esc_url( $img . 'logo/Logo_Kor_Vertical.png' ); ?>" download target="_blank" rel="noopener">Deskarrega PNG - Kor (Vertikál)</a>
	          <a class="atn-btn atn-btn-download atn-btn-metan" href="<?php echo esc_url( $img . 'logo/Logo_Metan_Horizontal.png' ); ?>" download target="_blank" rel="noopener">Deskarrega PNG - Metan (Horizontál)</a>
	          <a class="atn-btn atn-btn-download atn-btn-metan" href="<?php echo esc_url( $img . 'logo/Logo_Metan_Vertical.png' ); ?>" download target="_blank" rel="noopener">Deskarrega PNG - Metan (Vertikál)</a>
	          <a class="atn-btn atn-btn-download atn-btn-mutin" href="<?php echo esc_url( $img . 'logo/Logo_Mutin_Horizontal.png' ); ?>" download target="_blank" rel="noopener">Deskarrega PNG - Mutin (Horizontál)</a>
	          <a class="atn-btn atn-btn-download atn-btn-mutin" href="<?php echo esc_url( $img . 'logo/Logo_Mutin_Vertical.png' ); ?>" download target="_blank" rel="noopener">Deskarrega PNG - Mutin (Vertikál)</a>
	        </div>
	        <div class="atn-muted" style="margin-top:10px">Naran arkivu: Logo_Kor_Horizontal.png, Logo_Kor_Vertical.png, Logo_Metan_Horizontal.png, Logo_Metan_Vertical.png, Logo_Mutin_Horizontal.png, Logo_Mutin_Vertical.png</div>
	      </article>
	    </section>

	    <section style="margin-top:22px">
	      <h2 style="margin:0 0 8px 0">Legenda rekomendadu no hashtags</h2>
	      <div class="atn-share-text">Ami kontenti tebes atu partilha kampanha "All Things New" — mak hamutuk ho ami hodi buka dalan foun no reinu foun. #AllThingsNew #HopeStartsHere #EsperansaHahuIhaNee</div>
	      <p class="atn-muted" style="margin-top:10px">Dika: kopi legenda iha leten no paseta ba postu. Ajusta tama imajen iha lokál se presiza.</p>
	    </section>

	    <footer>
	      <div class="atn-muted">Modo hodi uza: Selesiona bo-nia foto rasik, depois deskarrega imajen kompostu no partilha iha redes sosiál ninian. Uza Hashtags: #AllThingsNew #HopeStartsHere #EsperansaHahuIhaNee.</div>
	    </footer>
	  </main>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'all_things_new_generator', 'atng_shortcode' );
