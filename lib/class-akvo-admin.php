<?php

	class AKVO_ADMIN{

		var $meta_boxes;
		var $taxonomies;
		var $post_types;

		function __construct(){

			add_action( 'init', array( $this, 'create') );			// REGISTERING POST TYPES AND TAXONOMIES

			$this->set_post_types();

			$this->set_taxonomies();

			$this->set_meta_boxes();

			/*
			 * FILTER TO UPDATE THE POST TYPES DROPDOWN IN THE SITE ORIGIN WIDGETS
			 *
			*/
			add_filter( 'akvo-sow-post-types', function( $post_types ){

				$post_types = array();

				foreach( $this->get_post_types() as $slug => $post_type ){
					$post_types[ $slug ] = $post_type['name'];
				}

				return $post_types;
			} );


			/*
			 * FILTER TO UPDATE THE TAXONOMIES DROPDOWN IN THE NESTED FILTERS (SITE ORIGIN WIDGETS)
			 *
			*/
			add_filter( 'akvo-sow-taxonomies', function( $taxonomies ){

				$taxonomies = array();

				foreach( $this->get_taxonomies() as $slug => $tax ){
					$taxonomies[ $slug ] = $tax['labels']['name'];
				}

				return $taxonomies;
			} );

			/*
			* OVERRIDE TEMPLATES FOR EACH POST TYPE THROUGH AKVO_CUSTOM_POSTS IN THE PLUGIN FOLDER OF AKVO_SITEORIGIN_WIDGETS
			*/
			foreach( $this->get_post_types() as $slug => $post_type ){

				add_filter( 'akvo-custom-posts-'.$slug.'-item-template', function( $slug ){

					$template = get_template_directory()."/templates/".$slug.".php";

					return $template;
				});

			}


			/* SAVE POST - FOR SAVING META FIELDS */
			add_action( 'save_post', array( $this, 'save_meta_fields' ), 10, 2 );

			/* change permalinks */
			add_filter('post_type_link', function( $permalink, $post_id, $leavename ){

				$post = get_post( $post_id );

				if( $post->post_type == 'microstory' ){

					$rewritecode = array(
						$leavename? '' : '%postname%',
						'%post_id%',
						'%category%',
						$leavename? '' : '%pagename%',
					);

					$category = "akvo-hub";
					$hubs = get_the_terms( $post, 'staff_hub' );
					if( is_array( $hubs ) ){
						$category = $hubs[0]->slug;
					}

					$rewritereplace = array(
						$post->post_name,
						$post->ID,
						$category,
						$post->post_name,
					);

					$permalink = str_replace($rewritecode, $rewritereplace, $permalink);

				}

				return $permalink;
			}, 10, 3);


		}

		/* SET POST TYPES */
		function set_post_types(){

			$this->post_types = array(
				/* FUNNEL ELEMENTS */
				'funnel'	=> array(
					'name'			=> 'Funnels',
					'singular_name'	=> 'Funnel',
					'menu_icon' 	=> 'dashicons-format-aside',
					'has_archive' 	=> false,
					'supports'		=> array( 'title', 'author' )
				),
				/* STAFF */
				'new_staffs'	=> array(
					'name' 			=> 'Akvo Staff',
					'singular_name' => 'Akvo Staff',
					'supports' 		=> array( 'title', 'thumbnail', 'revisions'),
					'menu_icon' 	=> get_bloginfo('template_url').'/images/icons/akvoStaff_icn.png',
					'has_archive' 	=> true
				),
				/* PARTNERS */
				'new_partners'	=> array(
					'name' 			=> 'Akvo Partner',
					'singular_name' => 'Akvo Partner',
					'supports' 		=> array( 'title', 'thumbnail', 'revisions'),
					'menu_icon' 	=> get_bloginfo('template_url').'/images/icons/akvoPartner_icn.png',
					'has_archive' 	=> true
				),
				/* MICROSTORY */
				'microstory'	=> array(
					'name' 			=> 'Akvo Microstories',
					'singular_name' => 'Akvo Microstory',
					'supports' 		=> array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
					'menu_icon' 	=> 'dashicons-admin-page',
					'rewrite'		=> false,
					'has_archive' 	=> true
				),
				/* FOUNDATION MEMBERS */
				'foundation_member'	=> array(
					'name' 			=> 'Akvo Foundation Members',
					'singular_name' => 'Akvo Foundation Member',
					'supports' 		=> array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
					'menu_icon' 	=> get_bloginfo('template_url').'/images/icons/akvoStaff_icn.png',
					'has_archive' 	=> true
				),
				/* ADVERTS */
				'advert'	=> array(
					'name' 					=> 'Akvo Adverts',
					'singular_name' 		=> 'Akvo Advert',
					'supports' 				=> array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
					'menu_icon' 			=> 'dashicons-format-gallery',
					'exclude_from_search' 	=> true,
					'rewrite'				=> false,
					'has_archive' 			=> true
				),
				/* PRODUCT UPDATES */
				'product_update'	=> array(
					'name' 			=> 'Akvo Product Updates',
					'singular_name' => 'Akvo Advert Update',
					'supports' 		=> array( 'title', 'author', 'editor', 'excerpt', 'thumbnail', 'revisions' ),
					'menu_icon' 	=> 'dashicons-edit',
					'rewrite'		=> false,
					'has_archive' 	=> true
				),
			);
		}
		function get_post_types(){ return $this->post_types; }

		/* TAXONOMIES */
		function set_taxonomies(){

			$this->taxonomies = array(
				'product_category'	=> array(
					'post_type'	=> 'product_update',
					'labels'	=> array(
						'name' 			=> 'Product Category',
						'add_new_item' 	=> 'New Product Category',
						'new_item_name' => 'New Product Category'
					)
				),
				'product_tag'	=> array(
					'post_type'	=> 'product_update',
					'labels'	=> array(
						'name' 			=> 'Product Tag',
						'add_new_item' 	=> 'New Product Tag',
						'new_item_name' => 'New Product Tag'
					),
					'hierarchical'	=> false
				),
				'new_staffs_team'	=> array(
					'post_type'	=> 'new_staffs',
					'labels'	=> array(
						'name' 			=> 'Staff Team',
						'add_new_item' 	=> 'New Akvo team',
						'new_item_name' => 'New Akvo team'
					)
				),
				'staff_hub'		=> array(
					'post_type'	=> array( 'new_staffs', 'new_partners', 'microstory' ),
					'labels'	=> array(
						'name' 			=> 'Staff Hub',
						'add_new_item' 	=> 'Add New Hub',
						'new_item_name' => 'New Hub'
					)
				),
				'new_partners_category'	=> array(
					'post_type'	=> 'new_partners',
					'labels'	=> array(
						'name' 			=> 'Akvo Partner Category',
						'add_new_item' 	=> 'New Akvo Category',
						'new_item_name' => "New Akvo Category"
					)
				),
				'akvo_sector' 	=> array(
					'post_type'	=> array( 'microstory' ),
					'labels'	=> array(
						'name' 			=> 'Akvo Sector',
						'add_new_item' 	=> 'New Akvo Sector',
						'new_item_name' => "New Akvo Sector"
					)
				),
				'new_foundation_team' 	=> array(
					'post_type'	=> array( 'foundation_member' ),
					'labels'	=> array(
						'name' 			=> 'Akvo Foundation Group',
						'add_new_item' 	=> 'New Akvo Foundation Group',
						'new_item_name' => "New Akvo Foundation Group"
					)
				),
				'foundation_type' 	=> array(
					'post_type'	=> array( 'foundation_member' ),
					'labels'	=> array(
						'name' 			=> 'Akvo Foundation Type',
						'add_new_item' 	=> 'New Akvo Foundation Type',
						'new_item_name' => "New Akvo Foundation Type"
					)
				),
			);
		}

		function get_taxonomies(){ return $this->taxonomies; }

		function set_meta_boxes(){
			$this->meta_boxes = array(
				'new_partners'	=> array(
					'title'		=> 'Partner Details',
					'fields'	=> array(
						'partners_link'	=> array(
							'label'		=> 'Link to a microstory or a partners page',
							'type'		=> 'text',
							'default'	=> ''
						),
					),
					'post_type'	=> 'new_partners',
				),
				'staff'	=> array(
					'title'		=> 'New Staff Details',
					'fields'	=> array(
						'staff_title'		=> 'Job Title',
						'staff_twitter'		=> 'Twitter Link',
						'staff_linkedin'	=> 'LinkedIn Link',
						'staff_blog'		=> 'Blog Link'
					),
					'post_type'	=> 'new_staffs',
				),
				'settings_box'	=> array(
					'title'		=> 'Page Settings',
					'fields'	=> array(
						'disable_header'	=> array(
							'label'		=> 'Hide Header Image Section',
							'type'		=> 'boolean',
							'default'	=> false
						),
						'disable_header_title'	=> array(
							'label'		=> 'Hide Header Title',
							'type'		=> 'boolean',
							'default'	=> false
						),
						'header_title'	=> array(
							'label'		=> 'Header Title',
							'type'		=> 'text',
							'default'	=> ''
						),
						'disable_clocks'	=> array(
							'label'		=> 'Hide Regional Clocks',
							'type'		=> 'boolean',
							'default'	=> false
						),
						'clocks_title'	=> array(
							'label'		=> 'Clocks Title',
							'type'		=> 'text',
							'default'	=> '',
							'desc'		=> 'Leave empty for default text'
						),
					),
					'post_type'	=> array('page', 'microstory'),
					'context'	=> 'side',
					'priority'	=> 'default'
				),
				'post_settings_box'	=> array(
					'title'		=> 'Blog Settings',
					'fields'	=> array(
						'disable_featured_image'	=> array(
							'label'		=> 'Hide Featured Image within the post content',
							'type'		=> 'boolean',
							'default'	=> false
						),
					),
					'post_type'	=> array('post'),
					'context'	=> 'side',
					'priority'	=> 'default'
				),
				'foundation_member'	=> array(
					'title'		=> 'Settings',
					'fields'	=> array(
						'member_title'	=> array(
							'label'	=> 'Job Title',
							'type'	=> 'text'
						),
						'staff_twitter'	=> array(
							'label'	=> 'Twitter Link',
							'type'	=> 'text'
						),
						'staff_linkedin' => array(
							'label'	=> 'LinkedIn Link',
							'type'	=> 'text'
						),
						'staff_blog' => array(
							'label'	=> 'Blog Link',
							'type'	=> 'text'
						)
					),
					'post_type'	=> 'foundation_member',
				),
				'microstory'	=> array(
					'title'		=> 'Story Settings',
					'fields'	=> array(
						'featured'	=> array(
							'label'		=> 'Featured Story',
							'type'		=> 'boolean',
							'default'	=> false
						),
					),
					'post_type'	=> 'microstory',
					'context'	=> 'side',
				),
				'advert'	=> array(
					'title'		=> 'Settings',
					'fields'	=> array(
						'url'	=> array(
							'label'	=> 'URL',
							'type'	=> 'text'
						),
					),
					'post_type'	=> 'advert',
					'context'	=> 'side',
				)
			);

		}

		function get_meta_boxes(){ return $this->meta_boxes; }

		function create(){

			/* rewrite urls for microstory links */
			global $wp_rewrite;
			$microstory_structure = '/stories/%category%/%microstory%/';
			$wp_rewrite->add_rewrite_tag( "%microstory%", '([^/]+)', "microstory=" );
			$wp_rewrite->add_permastruct( 'microstory', $microstory_structure, false );

			/* registering post types */
			foreach( $this->get_post_types() as $slug => $post_type ){
				register_post_type( $slug,
					array(
						'labels' => array(
							'name' 			=> $post_type['name'],
							'singular_name' => $post_type['singular_name'],
							'add_new'	 	=> 'Add New Item',
							'add_new_item' 	=> 'Add New Item',
							'edit' 			=> 'Edit',
							'edit_item' 	=> 'Edit',
							'new_item' 		=> 'New',
							'view' 			=> 'View',
							'view_item' 	=> 'View',
						),
						'public' 				=> true,
						'supports' 				=> $post_type['supports'],
						'menu_icon' 			=> $post_type['menu_icon'],
						'has_archive' 			=> $post_type['has_archive'],
						'exclude_from_search' 	=> isset( $post_type['exclude_from_search'] ) ? $post_type['exclude_from_search'] : false,
						'rewrite' 				=> isset( $post_type['rewrite'] ) ? $post_type['rewrite'] : array( 'slug' => $slug, 'with_front'=> false, 'feed' => true, 'pages' => true )
					)
				);
			}

			/* registering taxonomies */
			foreach( $this->get_taxonomies() as $slug => $tax ){
				register_taxonomy(
					$slug,
					$tax['post_type'],
					array(
						'labels' 		=> $tax['labels'],
						'show_ui' 		=> true,
						'show_tagcloud' => false,
						'hierarchical' 	=> isset( $tax['hierarchical'] ) ? $tax['hierarchical'] : true,
						'query_var' 	=> true,
						'rewrite' 		=> array('slug' => $slug )
					)
				);
			}

			/* META BOXES */
			add_action( 'admin_init', function(){

				foreach( $this->get_meta_boxes() as $slug => $metabox ){

					$metabox['context'] = isset( $metabox['context'] ) ? $metabox['context'] :  'normal';

					$metabox['priority'] = isset( $metabox['priority'] ) ? $metabox['priority'] :  'default';

					/* ADD META BOX */
					add_meta_box( $slug, $metabox[ 'title' ], array( $this, 'meta_box' ), $metabox['post_type'], $metabox['context'], $metabox['priority']);
				}
			} );

		}

		/* META BOXES */
		function meta_box( $post, $metabox ) {

			// GET THE METABOX ID, IF NOT THEN RETURN
			if( !is_array( $metabox ) || !isset( $metabox['id'] ) ){ return; }
			$slug = $metabox['id'];

			// GET THE REGISTERED META BOX FIELDS
			$metaboxes = $this->get_meta_boxes();
			if( !is_array( $metaboxes ) || !isset( $metaboxes[ $slug ] ) ){ return ;}
			$fields = $metaboxes[ $slug ][ 'fields' ];



			// ITERATING THROUGH EACH FIELD
			foreach( $fields as $slug => $field ){

				// GETTING VALUE FROM THE DB
				$value = esc_html( get_post_meta( $post->ID, $slug, true ) );

				// CHECKING IF THE FIELDS IS AN ARRAY OR NEEDS TO INVOKE THE LEGACY CODE
				if( is_array( $field ) && isset( $field[ 'type' ] ) ){
					$template_file = false;
					switch( $field[ 'type' ] ){
						case 'boolean':
							$template_file = "metafield_boolean.php";
							break;
						case 'text':
							$template_file = "metafield_text.php";
							break;
					}

					if( $template_file ){
						include "templates/".$template_file;
					}

				}
				else{
					// LEGACY CODE ONLY APPICABLE FOR TEXT FIELDS
					$label = $field;
					include "templates/metafield_text.php";
				}
			}

		}

		/* SAVE META BOXES */
		function save_meta_fields( $post_id, $post ){

			$metaboxes = $this->get_meta_boxes();

			foreach( $metaboxes as $metabox ){

				$flag = false;

				// CHECK IF THIS METABOX IS VALID FOR THE CURRENT SCREEN
				if( isset( $metabox['post_type'] ) ){
					if( ( is_array( $metabox['post_type'] ) && in_array( $post->post_type, $metabox['post_type'] ) ) ||
						( $metabox['post_type'] == $post->post_type ) ){
						$flag = true;

						//print_r( $metabox );

						//wp_die();
					}
				}

				if( $flag ){

					$fields = $metabox['fields'];

					foreach( $fields as $slug => $field ){									/* ITERATE THROUGH THE FIELDS */

						if ( isset( $_POST[ $slug ] ) ) {
							update_post_meta( $post_id, $slug, $_POST[ $slug ] );			/* Store data in post meta table if present in post data */
						}

						if( !isset( $_POST[ $slug ] ) && is_array( $field ) && isset( $field['type'] ) && $field['type'] == 'boolean' ){
							delete_post_meta( $post_id, $slug );
						}
					}
				}

			}

		}


	}
