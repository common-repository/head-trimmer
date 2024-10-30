<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Head_Trimmer' ) ) {
	class Head_Trimmer {

		public function __construct() {
			add_action( 'init', array( $this, 'init' ) );
		}

		public function init() {
			add_action( 'plugins_loaded', array( $this, 'load_textdomain_handler' ) );
			add_action( 'wp_loaded', array( $this, 'optimize' ), PHP_INT_MAX );
		}

		public function load_textdomain_handler() {
			load_plugin_textdomain( 'head-trimmer', false, trailingslashit( dirname( plugin_basename( HEAD_TRIMMER_FILE ) ) ) . 'languages/' );
		}

		public function remove_wordpress_version() {
			return '';
		}

		public function remove_wordpress_version_from_scripts_and_styles( $src ) {
			if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) !== false ) {
				$src = remove_query_arg( 'ver', $src );
			}

			return $src;
		}

		public function remove_all_other_versions_from_scripts_and_styles( $src ) {
			if ( strpos( $src, 'ver=' ) !== false ) {
				if ( strpos( $src, 'ver=' . get_bloginfo( 'version' ) ) === false ) {
					$src = remove_query_arg( 'ver', $src );
				}
			}

			return $src;
		}

		public function disable_feeds() {
			wp_die( esc_html__( 'Feeds are not available.', 'head-trimmer' ) );
		}

		public function disable_emojis_tinymce( $plugins ) {
			if ( is_array( $plugins ) ) {
				return array_diff( $plugins, array( 'wpemoji' ) );
			} else {
				return array();
			}
		}

		public function remove_global_styles() {
			wp_dequeue_style( 'global-styles' );
		}

		public function remove_gutenberg_block_styles() {
			wp_dequeue_style( 'wp-block-library' );
			wp_dequeue_style( 'wp-block-library-theme' );
			wp_dequeue_style( 'wc-block-style' );
			wp_dequeue_style( 'storefront-gutenberg-blocks' );
		}

		public function remove_classic_theme_styles() {
			wp_dequeue_style( 'classic-theme-styles' );
		}

		public function remove_dns_prefetch_sworg( $urls, $relation_type ) {
			if ( 'dns-prefetch' == $relation_type ) {
				$urls = $this->remove_dns_prefetch_entry( $urls, 's.w.org' );
			}

			return $urls;
		}

		public function remove_dns_prefetch_gfonts( $urls, $relation_type ) {
			if ( 'dns-prefetch' == $relation_type ) {
				$urls = $this->remove_dns_prefetch_entry( $urls, 'fonts.googleapis.com' );
			}

			return $urls;
		}

		public function remove_jquery() {
			if ( ! is_admin() ) {
				wp_deregister_script( 'jquery' );
			}
		}

		public function remove_dns_prefetch_entry( $urls, $entry_to_remove ) {
			if ( is_array( $urls ) && ! empty( $urls ) ) {
				foreach ( $urls as $key => $value ) {
					if ( strpos( $value, $entry_to_remove ) !== false ) {
						unset( $urls[ $key ] );
					}
				}
			}

			return $urls;
		}

		public function optimize() {
			$options = get_option( 'head_trimmer_settings_db' );

			$b_remove_wordpress_version = ( isset( $options['remove_wordpress_version'] ) ? $options['remove_wordpress_version'] : 0 );
			$b_remove_wordpress_version_from_scripts_and_styles = ( isset( $options['remove_wordpress_version_from_scripts_and_styles'] ) ? $options['remove_wordpress_version_from_scripts_and_styles'] : 0 );
			$b_remove_all_other_versions_from_scripts_and_styles = ( isset( $options['remove_all_other_versions_from_scripts_and_styles'] ) ? $options['remove_all_other_versions_from_scripts_and_styles'] : 0 );
			$b_remove_shortlink = ( isset( $options['remove_shortlink'] ) ? $options['remove_shortlink'] : 0 );
			$b_remove_shortlink_response_header = ( isset( $options['remove_shortlink_response_header'] ) ? $options['remove_shortlink_response_header'] : 0 );
			$b_remove_canonical = ( isset( $options['remove_canonical'] ) ? $options['remove_canonical'] : 0 );
			$b_remove_adjacent_posts_rel_link = ( isset( $options['remove_adjacent_posts_rel_link'] ) ? $options['remove_adjacent_posts_rel_link'] : 0 );
			$b_disable_feeds = ( isset( $options['disable_feeds'] ) ? $options['disable_feeds'] : 0 );
			$b_remove_rsd_link = ( isset( $options['remove_rsd_link'] ) ? $options['remove_rsd_link'] : 0 );
			$b_remove_wlwmanifest_link = ( isset( $options['remove_wlwmanifest_link'] ) ? $options['remove_wlwmanifest_link'] : 0 );
			$b_remove_wp_oembed_add_discovery_links = ( isset( $options['remove_wp_oembed_add_discovery_links'] ) ? $options['remove_wp_oembed_add_discovery_links'] : 0 );
			$b_remove_rest_output_link = ( isset( $options['remove_rest_output_link'] ) ? $options['remove_rest_output_link'] : 0 );
			$b_remove_emoji_support = ( isset( $options['remove_emoji_support'] ) ? $options['remove_emoji_support'] : 0 );
			$b_remove_global_styles_and_svg_filters = ( isset( $options['remove_global_styles_and_svg_filters'] ) ? $options['remove_global_styles_and_svg_filters'] : 0 );
			$b_remove_gutenberg_block_styles = ( isset( $options['remove_gutenberg_block_styles'] ) ? $options['remove_gutenberg_block_styles'] : 0 );
			$b_remove_classic_theme_styles = ( isset( $options['remove_classic_theme_styles'] ) ? $options['remove_classic_theme_styles'] : 0 );
			$b_activate_should_load_separate_core_block_assets = ( isset( $options['activate_should_load_separate_core_block_assets'] ) ? $options['activate_should_load_separate_core_block_assets'] : 0 );
			$b_remove_prefetch_sworg = ( isset( $options['remove_prefetch_sworg'] ) ? $options['remove_prefetch_sworg'] : 0 );
			$b_remove_prefetch_gfonts = ( isset( $options['remove_prefetch_gfonts'] ) ? $options['remove_prefetch_gfonts'] : 0 );
			$b_remove_jquery = ( isset( $options['remove_jquery'] ) ? $options['remove_jquery'] : 0 );

			if ( $b_remove_wordpress_version ) {
				add_filter( 'the_generator', array( $this, 'remove_wordpress_version' ) );
			}

			if ( $b_remove_wordpress_version_from_scripts_and_styles ) {
				add_filter( 'style_loader_src', array( $this, 'remove_wordpress_version_from_scripts_and_styles' ) );
				add_filter( 'script_loader_src', array( $this, 'remove_wordpress_version_from_scripts_and_styles' ) );
			}

			if ( $b_remove_all_other_versions_from_scripts_and_styles ) {
				add_filter( 'style_loader_src', array( $this, 'remove_all_other_versions_from_scripts_and_styles' ) );
				add_filter( 'script_loader_src', array( $this, 'remove_all_other_versions_from_scripts_and_styles' ) );
			}

			if ( $b_remove_shortlink ) {
				remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
			}

			if ( $b_remove_shortlink_response_header ) {
				remove_action( 'template_redirect', 'wp_shortlink_header', 11, 0 );
			}

			if ( $b_remove_canonical ) {
				remove_action( 'wp_head', 'rel_canonical' );
			}

			if ( $b_remove_adjacent_posts_rel_link ) {
				remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
				remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
				remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 );
				remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
			}

			if ( $b_disable_feeds ) {
				remove_action( 'wp_head', 'feed_links', 2 );
				remove_action( 'wp_head', 'feed_links_extra', 3 );

				add_action( 'feed_links_show_posts_feed', '__return_false', -1 );
				add_action( 'feed_links_show_comments_feed', '__return_false', -1 );

				add_action( 'do_feed', array( $this, 'disable_feeds' ), 1 );
				add_action( 'do_feed_rdf', array( $this, 'disable_feeds' ), 1 );
				add_action( 'do_feed_rss', array( $this, 'disable_feeds' ), 1 );
				add_action( 'do_feed_rss2', array( $this, 'disable_feeds' ), 1 );
				add_action( 'do_feed_atom', array( $this, 'disable_feeds' ), 1 );
				add_action( 'do_feed_rss2_comments', array( $this, 'disable_feeds' ), 1 );
				add_action( 'do_feed_atom_comments', array( $this, 'disable_feeds' ), 1 );
			}

			if ( $b_remove_rsd_link ) {
				remove_action( 'wp_head', 'rsd_link' );
			}

			if ( $b_remove_wlwmanifest_link ) {
				remove_action( 'wp_head', 'wlwmanifest_link' );
			}

			if ( $b_remove_wp_oembed_add_discovery_links ) {
				remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
			}

			if ( $b_remove_rest_output_link ) {
				remove_action( 'wp_head', 'rest_output_link_wp_head', 10, 0 );
				remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );
			}

			if ( ! is_admin() ) {
				if ( $b_remove_emoji_support ) {
					remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
					remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
					remove_action( 'wp_print_styles', 'print_emoji_styles' );
					remove_action( 'admin_print_styles', 'print_emoji_styles' );
					remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
					remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
					remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
					add_filter( 'tiny_mce_plugins', array( $this, 'disable_emojis_tinymce' ) );
				}
			}

			if ( ! is_admin() ) {
				if ( $b_remove_global_styles_and_svg_filters ) {
					remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
					remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
					remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
					add_action( 'wp_enqueue_scripts', array( $this, 'remove_global_styles' ), 99 );
				}
			}

			if ( ! is_admin() ) {
				if ( $b_remove_gutenberg_block_styles ) {
					add_action( 'wp_enqueue_scripts', array( $this, 'remove_gutenberg_block_styles' ), 99 );
				}
			}

			if ( ! is_admin() ) {
				if ( $b_remove_classic_theme_styles ) {
					add_action( 'wp_enqueue_scripts', array( $this, 'remove_classic_theme_styles' ), 99 );
				}
			}

			if ( ! is_admin() ) {
				if ( ! ( $b_remove_gutenberg_block_styles ) && $b_activate_should_load_separate_core_block_assets ) {
					add_filter( 'should_load_separate_core_block_assets', '__return_true' );
				}
			}

			if ( $b_remove_prefetch_sworg ) {
				add_filter( 'wp_resource_hints', array( $this, 'remove_dns_prefetch_sworg' ), 99, 2 );
			}

			if ( $b_remove_prefetch_gfonts ) {
				add_filter( 'wp_resource_hints', array( $this, 'remove_dns_prefetch_gfonts' ), 99, 2 );
			}

			if ( ! is_admin() ) {
				if ( $b_remove_jquery ) {
					add_filter( 'wp_enqueue_scripts', array( $this, 'remove_jquery' ), 99, 2 );
				}
			}
		}
	}
}

if ( class_exists( 'Head_Trimmer' ) ) {
	$head_trimmer = new Head_Trimmer();
}
