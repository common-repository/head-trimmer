<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Head_Trimmer_Settings' ) ) {
	class Head_Trimmer_Settings {
		private $options;

		public function __construct() {
			add_action( 'init', array( $this, 'init' ) );
		}

		public function init() {
			add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
			add_action( 'admin_init', array( $this, 'page_init' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( HEAD_TRIMMER_FILE ), array( $this, 'plugin_action_links_handler' ) );
		}

		public function add_plugin_page() {
			add_options_page( __( 'Head Trimmer', 'head-trimmer' ), __( 'Head Trimmer', 'head-trimmer' ), 'manage_options', 'head-trimmer-settings', array( $this, 'create_admin_page' ) );
		}

		public function create_admin_page() {
			$this->options = get_option( 'head_trimmer_settings_db' );

			echo( '<div class="wrap"><h1>' . esc_html__( 'Head Trimmer', 'head-trimmer' ) . '</h1><form method="post" action="options.php">' );

			settings_fields( 'head-trimmer-settings-group' );
			do_settings_sections( 'head-trimmer-settings' );
			submit_button();

			echo( '</form></div>' );
		}

		public function page_init() {
			register_setting( 'head-trimmer-settings-group', 'head_trimmer_settings_db', array( $this, 'sanitize' ) );

			add_settings_section( 'head-trimmer-settings-section-general', __( 'Settings', 'head-trimmer' ), array( $this, 'print_section_info_general' ), 'head-trimmer-settings' );

			add_settings_field( 'remove_wordpress_version', __( 'Remove WordPress Version?', 'head-trimmer' ), array( $this, 'remove_wordpress_version_callback' ), 'head-trimmer-settings', 'head-trimmer-settings-section-general' );
			add_settings_field( 'remove_wordpress_version_from_scripts_and_styles', __( 'Remove WordPress Version from Scripts and Styles?', 'head-trimmer' ), array( $this, 'remove_wordpress_version_from_scripts_and_styles_callback' ), 'head-trimmer-settings', 'head-trimmer-settings-section-general' );
			add_settings_field( 'remove_all_other_versions_from_scripts_and_styles', __( 'Remove All Other Version Signatures (Excluding WordPress Version) from Scripts and Styles?', 'head-trimmer' ), array( $this, 'remove_all_other_versions_from_scripts_and_styles_callback' ), 'head-trimmer-settings', 'head-trimmer-settings-section-general' );
			add_settings_field( 'remove_shortlink', __( 'Remove Shortlink?', 'head-trimmer' ), array( $this, 'remove_shortlink_callback' ), 'head-trimmer-settings', 'head-trimmer-settings-section-general' );
			add_settings_field( 'remove_shortlink_response_header', __( 'Remove Shortlink Response Header?', 'head-trimmer' ), array( $this, 'remove_shortlink_response_header_callback' ), 'head-trimmer-settings', 'head-trimmer-settings-section-general' );
			add_settings_field( 'remove_canonical', __( 'Remove Canonical?', 'head-trimmer' ), array( $this, 'remove_canonical_callback' ), 'head-trimmer-settings', 'head-trimmer-settings-section-general' );
			add_settings_field( 'remove_adjacent_posts_rel_link', __( 'Remove Relational Links for Posts Adjacent to Current Post?', 'head-trimmer' ), array( $this, 'remove_adjacent_posts_rel_link_callback' ), 'head-trimmer-settings', 'head-trimmer-settings-section-general' );
			add_settings_field( 'disable_feeds', __( 'Disable Feeds?', 'head-trimmer' ), array( $this, 'disable_feeds_callback' ), 'head-trimmer-settings', 'head-trimmer-settings-section-general' );
			add_settings_field( 'remove_rsd_link', __( 'Remove Really Simple Discovery Link (rsd_link)?', 'head-trimmer' ), array( $this, 'remove_rsd_link_callback' ), 'head-trimmer-settings', 'head-trimmer-settings-section-general' );
			add_settings_field( 'remove_wlwmanifest_link', __( 'Remove Windows Live Writer Manifest File Link (wlwmanifest_link)?', 'head-trimmer' ), array( $this, 'remove_wlwmanifest_link_callback' ), 'head-trimmer-settings', 'head-trimmer-settings-section-general' );
			add_settings_field( 'remove_wp_oembed_add_discovery_links', __( 'Remove oEmbed Discovery Links?', 'head-trimmer' ), array( $this, 'remove_wp_oembed_add_discovery_links_callback' ), 'head-trimmer-settings', 'head-trimmer-settings-section-general' );
			add_settings_field( 'remove_rest_output_link', __( 'Remove REST API Links?', 'head-trimmer' ), array( $this, 'remove_rest_output_link_callback' ), 'head-trimmer-settings', 'head-trimmer-settings-section-general' );
			add_settings_field( 'remove_emoji_support', __( 'Remove Emoji Support?', 'head-trimmer' ), array( $this, 'remove_emoji_support_callback' ), 'head-trimmer-settings', 'head-trimmer-settings-section-general' );
			add_settings_field( 'remove_global_styles_and_svg_filters', __( 'Remove Global Styles and SVG Filters?', 'head-trimmer' ), array( $this, 'remove_global_styles_and_svg_filters_callback' ), 'head-trimmer-settings', 'head-trimmer-settings-section-general' );
			add_settings_field( 'remove_gutenberg_block_styles', __( 'Remove Gutenberg Block CSS Styles?', 'head-trimmer' ), array( $this, 'remove_gutenberg_block_styles_callback' ), 'head-trimmer-settings', 'head-trimmer-settings-section-general' );
			add_settings_field( 'activate_should_load_separate_core_block_assets', __( 'Load Core Gutenberg Block CSS Styles Only When Rendered?', 'head-trimmer' ), array( $this, 'activate_should_load_separate_core_block_assets_callback' ), 'head-trimmer-settings', 'head-trimmer-settings-section-general' );
			add_settings_field( 'remove_classic_theme_styles', __( 'Remove Classic Theme CSS Styles?', 'head-trimmer' ), array( $this, 'remove_classic_theme_styles_callback' ), 'head-trimmer-settings', 'head-trimmer-settings-section-general' );
			add_settings_field( 'remove_prefetch_sworg', __( 'Remove DNS-Prefetch for //s.w.org?', 'head-trimmer' ), array( $this, 'remove_prefetch_sworg_callback' ), 'head-trimmer-settings', 'head-trimmer-settings-section-general' );
			add_settings_field( 'remove_prefetch_gfonts', __( 'Remove DNS-Prefetch for Google Fonts?', 'head-trimmer' ), array( $this, 'remove_prefetch_gfonts_callback' ), 'head-trimmer-settings', 'head-trimmer-settings-section-general' );
			add_settings_field( 'remove_jquery', __( 'Remove jQuery?', 'head-trimmer' ), array( $this, 'remove_jquery_callback' ), 'head-trimmer-settings', 'head-trimmer-settings-section-general' );
		}

		public function plugin_action_links_handler( $links ) {
			$settings_link = '<a href="' . admin_url( 'options-general.php?page=head-trimmer-settings' ) . '">' . esc_html__( 'Settings', 'head-trimmer' ) . '</a>';
			array_unshift( $links, $settings_link );

			return $links;
		}

		public function print_section_info_general() {
			esc_html_e( 'Configure general settings:', 'head-trimmer' );
		}

		public function remove_wordpress_version_callback() {
			echo( '<input type="checkbox" id="remove_wordpress_version" name="head_trimmer_settings_db[remove_wordpress_version]" value="1"' . checked( 1, ( isset( $this->options['remove_wordpress_version'] ) ? esc_attr( $this->options['remove_wordpress_version'] ) : 0 ), false ) . '/>' );
			echo( '<label for="remove_wordpress_version">' . esc_html__( 'Removes WordPress generator meta, e.g., <meta name="generator" content="WordPress 6.0">.', 'head-trimmer' ) . '</label>' );
		}

		public function remove_wordpress_version_from_scripts_and_styles_callback() {
			echo( '<input type="checkbox" id="remove_wordpress_version_from_scripts_and_styles" name="head_trimmer_settings_db[remove_wordpress_version_from_scripts_and_styles]" value="1"' . checked( 1, ( isset( $this->options['remove_wordpress_version_from_scripts_and_styles'] ) ? esc_attr( $this->options['remove_wordpress_version_from_scripts_and_styles'] ) : 0 ), false ) . '/>' );
			echo( '<label for="remove_wordpress_version_from_scripts_and_styles">' . esc_html__( 'Removes WordPress version information, such as ?ver=X.X.X, from resource query strings, e.g., <link href="https://localhost/wp-includes/css/example.css?ver=6.0" />.', 'head-trimmer' ) . '</label>' );
		}

		public function remove_all_other_versions_from_scripts_and_styles_callback() {
			echo( '<input type="checkbox" id="remove_all_other_versions_from_scripts_and_styles" name="head_trimmer_settings_db[remove_all_other_versions_from_scripts_and_styles]" value="1"' . checked( 1, ( isset( $this->options['remove_all_other_versions_from_scripts_and_styles'] ) ? esc_attr( $this->options['remove_all_other_versions_from_scripts_and_styles'] ) : 0 ), false ) . '/>' );
			echo( '<label for="remove_all_other_versions_from_scripts_and_styles">' . esc_html__( 'Removes all non-WordPress version information, such as ?ver=Y.Y.Y, from resource query strings, e.g., <link href="https://localhost/wp-includes/css/other-example.css?ver=3.1.0" />.', 'head-trimmer' ) . '</label>' );
		}

		public function remove_shortlink_callback() {
			echo( '<input type="checkbox" id="remove_shortlink" name="head_trimmer_settings_db[remove_shortlink]" value="1"' . checked( 1, ( isset( $this->options['remove_shortlink'] ) ? esc_attr( $this->options['remove_shortlink'] ) : 0 ), false ) . '/>' );
			echo( '<label for="remove_shortlink">' . esc_html__( 'Removes shortlinks from source, e.g., <link rel="shortlink" href="https://localhost/?p=1">.', 'head-trimmer' ) . '</label>' );
		}

		public function remove_shortlink_response_header_callback() {
			echo( '<input type="checkbox" id="remove_shortlink_response_header" name="head_trimmer_settings_db[remove_shortlink_response_header]" value="1"' . checked( 1, ( isset( $this->options['remove_shortlink_response_header'] ) ? esc_attr( $this->options['remove_shortlink_response_header'] ) : 0 ), false ) . '/>' );
			echo( '<label for="remove_shortlink_response_header">' . esc_html__( 'Removes shortlinks from HTTP response headers when viewing network requests, e.g., "Link: <https://localhost/?p=1>; rel=shortlink".', 'head-trimmer' ) . '</label>' );
		}

		public function remove_canonical_callback() {
			echo( '<input type="checkbox" id="remove_canonical" name="head_trimmer_settings_db[remove_canonical]" value="1"' . checked( 1, ( isset( $this->options['remove_canonical'] ) ? esc_attr( $this->options['remove_canonical'] ) : 0 ), false ) . '/>' );
			echo( '<label for="remove_canonical">' . esc_html__( 'Removes canonicals from source, e.g., <link rel="canonical" href="https://localhost/hello-world/">.', 'head-trimmer' ) . '</label>' );
		}

		public function remove_adjacent_posts_rel_link_callback() {
			echo( '<input type="checkbox" id="remove_adjacent_posts_rel_link" name="head_trimmer_settings_db[remove_adjacent_posts_rel_link]" value="1"' . checked( 1, ( isset( $this->options['remove_adjacent_posts_rel_link'] ) ? esc_attr( $this->options['remove_adjacent_posts_rel_link'] ) : 0 ), false ) . '/>' );
			echo( '<label for="remove_adjacent_posts_rel_link">' . esc_html__( 'Removes adjacent post links from source, e.g., <link rel="prev" href="https://localhost/page/2/" /> and <link rel="next" href="https://localhost/page/4/" />.', 'head-trimmer' ) . '</label>' );
		}

		public function disable_feeds_callback() {
			echo( '<input type="checkbox" id="disable_feeds" name="head_trimmer_settings_db[disable_feeds]" value="1"' . checked( 1, ( isset( $this->options['disable_feeds'] ) ? esc_attr( $this->options['disable_feeds'] ) : 0 ), false ) . '/>' );
			echo( '<label for="disable_feeds">' . esc_html__( 'Removes feed links from source, e.g., <link rel="alternate" type="application/rss+xml" title="WordPress » Feed" href="https://localhost/feed/">.', 'head-trimmer' ) . '</label>' );
		}

		public function remove_rsd_link_callback() {
			echo( '<input type="checkbox" id="remove_rsd_link" name="head_trimmer_settings_db[remove_rsd_link]" value="1"' . checked( 1, ( isset( $this->options['remove_rsd_link'] ) ? esc_attr( $this->options['remove_rsd_link'] ) : 0 ), false ) . '/>' );
			echo( '<label for="remove_rsd_link">' . esc_html__( 'Removes RSD xmlrpc.php link from source, e.g., <link rel="EditURI" type="application/rsd+xml" title="RSD" href="https://localhost/xmlrpc.php?rsd">.', 'head-trimmer' ) . '</label>' );
		}

		public function remove_wlwmanifest_link_callback() {
			echo( '<input type="checkbox" id="remove_wlwmanifest_link" name="head_trimmer_settings_db[remove_wlwmanifest_link]" value="1"' . checked( 1, ( isset( $this->options['remove_wlwmanifest_link'] ) ? esc_attr( $this->options['remove_wlwmanifest_link'] ) : 0 ), false ) . '/>' );
			echo( '<label for="remove_wlwmanifest_link">' . esc_html__( 'Removes wlwmanifest.xml link from source, e.g., <link rel="wlwmanifest" type="application/wlwmanifest+xml" href="https://localhost/wp-includes/wlwmanifest.xml">.', 'head-trimmer' ) . '</label>' );
		}

		public function remove_wp_oembed_add_discovery_links_callback() {
			echo( '<input type="checkbox" id="remove_wp_oembed_add_discovery_links" name="head_trimmer_settings_db[remove_wp_oembed_add_discovery_links]" value="1"' . checked( 1, ( isset( $this->options['remove_wp_oembed_add_discovery_links'] ) ? esc_attr( $this->options['remove_wp_oembed_add_discovery_links'] ) : 0 ), false ) . '/>' );
			echo( '<label for="remove_wp_oembed_add_discovery_links">' . esc_html__( 'Removes oEmbed links from source, e.g., <link rel="alternate" type="application/json+oembed" href="https://localhost/wp-json/oembed/1.0/embed?url="> and <link rel="alternate" type="text/xml+oembed" href="https://localhost/wp-json/oembed/1.0/embed?url=">.', 'head-trimmer' ) . '</label>' );
		}

		public function remove_rest_output_link_callback() {
			echo( '<input type="checkbox" id="remove_rest_output_link" name="head_trimmer_settings_db[remove_rest_output_link]" value="1"' . checked( 1, ( isset( $this->options['remove_rest_output_link'] ) ? esc_attr( $this->options['remove_rest_output_link'] ) : 0 ), false ) . '/>' );
			echo( '<label for="remove_rest_output_link">' . esc_html__( 'Removes REST API links from source, e.g., <link rel="https://api.w.org/" href="https://localhost/wp-json/">.', 'head-trimmer' ) . '</label>' );
		}

		public function remove_emoji_support_callback() {
			echo( '<input type="checkbox" id="remove_emoji_support" name="head_trimmer_settings_db[remove_emoji_support]" value="1"' . checked( 1, ( isset( $this->options['remove_emoji_support'] ) ? esc_attr( $this->options['remove_emoji_support'] ) : 0 ), false ) . '/>' );
			echo( '<label for="remove_emoji_support">' . esc_html__( 'Removes emoji scripts and styles. This setting does not affect administrative screens.', 'head-trimmer' ) . '</label>' );
		}

		public function remove_global_styles_and_svg_filters_callback() {
			echo( '<input type="checkbox" id="remove_global_styles_and_svg_filters" name="head_trimmer_settings_db[remove_global_styles_and_svg_filters]" value="1"' . checked( 1, ( isset( $this->options['remove_global_styles_and_svg_filters'] ) ? esc_attr( $this->options['remove_global_styles_and_svg_filters'] ) : 0 ), false ) . '/>' );
			echo( '<label for="remove_global_styles_and_svg_filters">' . esc_html__( 'Removes global styles and SVG filters, e.g., duotone filters. This setting does not affect administrative screens.', 'head-trimmer' ) . '</label>' );
		}

		public function remove_gutenberg_block_styles_callback() {
			echo( '<input type="checkbox" id="remove_gutenberg_block_styles" name="head_trimmer_settings_db[remove_gutenberg_block_styles]" value="1"' . checked( 1, ( isset( $this->options['remove_gutenberg_block_styles'] ) ? esc_attr( $this->options['remove_gutenberg_block_styles'] ) : 0 ), false ) . '/>' );
			echo( '<label for="remove_gutenberg_block_styles">' . esc_html__( 'Removes Gutenberg block CSS styles. This setting does not affect administrative screens.', 'head-trimmer' ) . '</label>' );
		}

		public function activate_should_load_separate_core_block_assets_callback() {
			echo( '<input type="checkbox" id="activate_should_load_separate_core_block_assets" name="head_trimmer_settings_db[activate_should_load_separate_core_block_assets]" value="1"' . checked( 1, ( isset( $this->options['activate_should_load_separate_core_block_assets'] ) ? esc_attr( $this->options['activate_should_load_separate_core_block_assets'] ) : 0 ), false ) . '/>' );
			echo( '<label for="activate_should_load_separate_core_block_assets">' . esc_html__( 'Only loads core Gutenberg block CSS styles if needed on a particular page or post. Instead of loading the entire block library, this reduces the amount of CSS downloaded by the client. This setting is ignored if "Remove Gutenberg Block CSS Styles" is active. This setting does not affect administrative screens.', 'head-trimmer' ) . '</label>' );
		}

		public function remove_classic_theme_styles_callback() {
			echo( '<input type="checkbox" id="remove_classic_theme_styles" name="head_trimmer_settings_db[remove_classic_theme_styles]" value="1"' . checked( 1, ( isset( $this->options['remove_classic_theme_styles'] ) ? esc_attr( $this->options['remove_classic_theme_styles'] ) : 0 ), false ) . '/>' );
			echo( '<label for="remove_classic_theme_styles">' . esc_html__( 'Removes classic theme CSS styles. This setting does not affect administrative screens.', 'head-trimmer' ) . '</label>' );
		}

		public function remove_prefetch_sworg_callback() {
			echo( '<input type="checkbox" id="remove_prefetch_sworg" name="head_trimmer_settings_db[remove_prefetch_sworg]" value="1"' . checked( 1, ( isset( $this->options['remove_prefetch_sworg'] ) ? esc_attr( $this->options['remove_prefetch_sworg'] ) : 0 ), false ) . '/>' );
			echo( '<label for="remove_prefetch_sworg">' . esc_html__( 'Removes dns-prefetch for //s.w.org, e.g., <link rel="dns-prefetch" href="//s.w.org">.', 'head-trimmer' ) . '</label>' );
		}

		public function remove_prefetch_gfonts_callback() {
			echo( '<input type="checkbox" id="remove_prefetch_gfonts" name="head_trimmer_settings_db[remove_prefetch_gfonts]" value="1"' . checked( 1, ( isset( $this->options['remove_prefetch_gfonts'] ) ? esc_attr( $this->options['remove_prefetch_gfonts'] ) : 0 ), false ) . '/>' );
			echo( '<label for="remove_prefetch_gfonts">' . esc_html__( 'Removes dns-prefetch for //fonts.googleapis.com, e.g., <link rel="dns-prefetch" href="//fonts.googleapis.com">.', 'head-trimmer' ) . '</label>' );
		}

		public function remove_jquery_callback() {
			echo( '<input type="checkbox" id="remove_jquery" name="head_trimmer_settings_db[remove_jquery]" value="1"' . checked( 1, ( isset( $this->options['remove_jquery'] ) ? esc_attr( $this->options['remove_jquery'] ) : 0 ), false ) . '/>' );
			echo( '<label for="remove_jquery">' . esc_html__( 'Prevents jQuery script library from loading. This setting does not affect administrative screens.', 'head-trimmer' ) . '</label>' );
		}

		public function sanitize( $input ) {
			$b_remove_wordpress_version = 0;
			$b_remove_wordpress_version_from_scripts_and_styles = 0;
			$b_remove_all_other_versions_from_scripts_and_styles = 0;
			$b_remove_shortlink = 0;
			$b_remove_shortlink_response_header = 0;
			$b_remove_canonical = 0;
			$b_disable_feeds = 0;
			$b_remove_rsd_link = 0;
			$b_remove_wlwmanifest_link = 0;
			$b_remove_wp_oembed_add_discovery_links = 0;
			$b_remove_rest_output_link = 0;
			$b_remove_emoji_support = 0;
			$b_remove_global_styles_and_svg_filters = 0;
			$b_remove_gutenberg_block_styles = 0;
			$b_activate_should_load_separate_core_block_assets = 0;
			$b_remove_classic_theme_styles = 0;
			$b_remove_prefetch_sworg = 0;
			$b_remove_prefetch_gfonts = 0;
			$b_remove_jquery = 0;
			$sanitized_input = array();

			if ( isset( $input['remove_wordpress_version'] ) ) {
				if ( 1 == intval( $input['remove_wordpress_version'] ) ) {
					$b_remove_wordpress_version = 1;
				} else {
					$b_remove_wordpress_version = 0;
				}
			} else {
				$b_remove_wordpress_version = 0;
			}
			$sanitized_input['remove_wordpress_version'] = $b_remove_wordpress_version;

			if ( isset( $input['remove_wordpress_version_from_scripts_and_styles'] ) ) {
				if ( 1 == intval( $input['remove_wordpress_version_from_scripts_and_styles'] ) ) {
					$b_remove_wordpress_version_from_scripts_and_styles = 1;
				} else {
					$b_remove_wordpress_version_from_scripts_and_styles = 0;
				}
			} else {
				$b_remove_wordpress_version_from_scripts_and_styles = 0;
			}
			$sanitized_input['remove_wordpress_version_from_scripts_and_styles'] = $b_remove_wordpress_version_from_scripts_and_styles;

			if ( isset( $input['remove_all_other_versions_from_scripts_and_styles'] ) ) {
				if ( 1 == intval( $input['remove_all_other_versions_from_scripts_and_styles'] ) ) {
					$b_remove_all_other_versions_from_scripts_and_styles = 1;
				} else {
					$b_remove_all_other_versions_from_scripts_and_styles = 0;
				}
			} else {
				$b_remove_all_other_versions_from_scripts_and_styles = 0;
			}
			$sanitized_input['remove_all_other_versions_from_scripts_and_styles'] = $b_remove_all_other_versions_from_scripts_and_styles;

			if ( isset( $input['remove_shortlink'] ) ) {
				if ( 1 == intval( $input['remove_shortlink'] ) ) {
					$b_remove_shortlink = 1;
				} else {
					$b_remove_shortlink = 0;
				}
			} else {
				$b_remove_shortlink = 0;
			}
			$sanitized_input['remove_shortlink'] = $b_remove_shortlink;

			if ( isset( $input['remove_shortlink_response_header'] ) ) {
				if ( 1 == intval( $input['remove_shortlink_response_header'] ) ) {
					$b_remove_shortlink_response_header = 1;
				} else {
					$b_remove_shortlink_response_header = 0;
				}
			} else {
				$b_remove_shortlink_response_header = 0;
			}
			$sanitized_input['remove_shortlink_response_header'] = $b_remove_shortlink_response_header;

			if ( isset( $input['remove_canonical'] ) ) {
				if ( 1 == intval( $input['remove_canonical'] ) ) {
					$b_remove_canonical = 1;
				} else {
					$b_remove_canonical = 0;
				}
			} else {
				$b_remove_canonical = 0;
			}
			$sanitized_input['remove_canonical'] = $b_remove_canonical;

			if ( isset( $input['remove_adjacent_posts_rel_link'] ) ) {
				if ( 1 == intval( $input['remove_adjacent_posts_rel_link'] ) ) {
					$b_remove_adjacent_posts_rel_link = 1;
				} else {
					$b_remove_adjacent_posts_rel_link = 0;
				}
			} else {
				$b_remove_adjacent_posts_rel_link = 0;
			}
			$sanitized_input['remove_adjacent_posts_rel_link'] = $b_remove_adjacent_posts_rel_link;

			if ( isset( $input['disable_feeds'] ) ) {
				if ( 1 == intval( $input['disable_feeds'] ) ) {
					$b_disable_feeds = 1;
				} else {
					$b_disable_feeds = 0;
				}
			} else {
				$b_disable_feeds = 0;
			}
			$sanitized_input['disable_feeds'] = $b_disable_feeds;

			if ( isset( $input['remove_rsd_link'] ) ) {
				if ( 1 == intval( $input['remove_rsd_link'] ) ) {
					$b_remove_rsd_link = 1;
				} else {
					$b_remove_rsd_link = 0;
				}
			} else {
				$b_remove_rsd_link = 0;
			}
			$sanitized_input['remove_rsd_link'] = $b_remove_rsd_link;

			if ( isset( $input['remove_wlwmanifest_link'] ) ) {
				if ( 1 == intval( $input['remove_wlwmanifest_link'] ) ) {
					$b_remove_wlwmanifest_link = 1;
				} else {
					$b_remove_wlwmanifest_link = 0;
				}
			} else {
				$b_remove_wlwmanifest_link = 0;
			}
			$sanitized_input['remove_wlwmanifest_link'] = $b_remove_wlwmanifest_link;

			if ( isset( $input['remove_wp_oembed_add_discovery_links'] ) ) {
				if ( 1 == intval( $input['remove_wp_oembed_add_discovery_links'] ) ) {
					$b_remove_wp_oembed_add_discovery_links = 1;
				} else {
					$b_remove_wp_oembed_add_discovery_links = 0;
				}
			} else {
				$b_remove_wp_oembed_add_discovery_links = 0;
			}
			$sanitized_input['remove_wp_oembed_add_discovery_links'] = $b_remove_wp_oembed_add_discovery_links;

			if ( isset( $input['remove_rest_output_link'] ) ) {
				if ( 1 == intval( $input['remove_rest_output_link'] ) ) {
					$b_remove_rest_output_link = 1;
				} else {
					$b_remove_rest_output_link = 0;
				}
			} else {
				$b_remove_rest_output_link = 0;
			}
			$sanitized_input['remove_rest_output_link'] = $b_remove_rest_output_link;

			if ( isset( $input['remove_emoji_support'] ) ) {
				if ( 1 == intval( $input['remove_emoji_support'] ) ) {
					$b_remove_emoji_support = 1;
				} else {
					$b_remove_emoji_support = 0;
				}
			} else {
				$b_remove_emoji_support = 0;
			}
			$sanitized_input['remove_emoji_support'] = $b_remove_emoji_support;

			if ( isset( $input['remove_global_styles_and_svg_filters'] ) ) {
				if ( 1 == intval( $input['remove_global_styles_and_svg_filters'] ) ) {
					$b_remove_global_styles_and_svg_filters = 1;
				} else {
					$b_remove_global_styles_and_svg_filters = 0;
				}
			} else {
				$b_remove_global_styles_and_svg_filters = 0;
			}
			$sanitized_input['remove_global_styles_and_svg_filters'] = $b_remove_global_styles_and_svg_filters;

			if ( isset( $input['remove_gutenberg_block_styles'] ) ) {
				if ( 1 == intval( $input['remove_gutenberg_block_styles'] ) ) {
					$b_remove_gutenberg_block_styles = 1;
				} else {
					$b_remove_gutenberg_block_styles = 0;
				}
			} else {
				$b_remove_gutenberg_block_styles = 0;
			}
			$sanitized_input['remove_gutenberg_block_styles'] = $b_remove_gutenberg_block_styles;

			if ( isset( $input['activate_should_load_separate_core_block_assets'] ) ) {
				if ( 1 == intval( $input['activate_should_load_separate_core_block_assets'] ) ) {
					$b_activate_should_load_separate_core_block_assets = 1;
				} else {
					$b_activate_should_load_separate_core_block_assets = 0;
				}
			} else {
				$b_activate_should_load_separate_core_block_assets = 0;
			}
			$sanitized_input['activate_should_load_separate_core_block_assets'] = $b_activate_should_load_separate_core_block_assets;

			if ( isset( $input['remove_classic_theme_styles'] ) ) {
				if ( 1 == intval( $input['remove_classic_theme_styles'] ) ) {
					$b_remove_classic_theme_styles = 1;
				} else {
					$b_remove_classic_theme_styles = 0;
				}
			} else {
				$b_remove_classic_theme_styles = 0;
			}
			$sanitized_input['remove_classic_theme_styles'] = $b_remove_classic_theme_styles;

			if ( isset( $input['remove_prefetch_sworg'] ) ) {
				if ( 1 == intval( $input['remove_prefetch_sworg'] ) ) {
					$b_remove_prefetch_sworg = 1;
				} else {
					$b_remove_prefetch_sworg = 0;
				}
			} else {
				$b_remove_prefetch_sworg = 0;
			}
			$sanitized_input['remove_prefetch_sworg'] = $b_remove_prefetch_sworg;

			if ( isset( $input['remove_prefetch_gfonts'] ) ) {
				if ( 1 == intval( $input['remove_prefetch_gfonts'] ) ) {
					$b_remove_prefetch_gfonts = 1;
				} else {
					$b_remove_prefetch_gfonts = 0;
				}
			} else {
				$b_remove_prefetch_gfonts = 0;
			}
			$sanitized_input['remove_prefetch_gfonts'] = $b_remove_prefetch_gfonts;

			if ( isset( $input['remove_jquery'] ) ) {
				if ( 1 == intval( $input['remove_jquery'] ) ) {
					$b_remove_jquery = 1;
				} else {
					$b_remove_jquery = 0;
				}
			} else {
				$b_remove_jquery = 0;
			}
			$sanitized_input['remove_jquery'] = $b_remove_jquery;

			return $sanitized_input;
		}
	}
}

if ( class_exists( 'Head_Trimmer_Settings' ) ) {
	if ( is_admin() ) {
		$head_trimmer_settings = new Head_Trimmer_Settings();
	}
}
