<?php

namespace Heidi\Plugin\Controllers\Frontend;

use Heidi\Core\Controller;

class ProfilePageController extends Controller
{
    public function save($post_id) {
        // wp_redirect('https://drewsineath.dev/profile/');
    }

    public function addHeader()
    {
        acf_form_head();
    }

    public function enqueueListingSearchScripts()
    {
        wp_enqueue_script('lising_search_ajax', HEIDI_RESOURCE_DIR . 'assets/js/listing_search_ajax.js', [], HEIDI_VERSION, true);
    }


    public function renderProfilePage()
    {
        ?>

	<div id="primary">
		<div id="content" role="main">

			<?php
            while ( have_posts() ) : the_post();

				echo '<h1>' . the_title() . '</h1>';

				the_content();

			endwhile;

            if( function_exists( 'get_user_favorites' ) ) :
                $listings = get_user_favorites();
            endif;


            if( !empty($listings) ) :
                $args = array(
                    'post__in' => $listings,
                    'post_type' => 'listing'
                );
                $listings = new \WP_Query($args);
                $listings_count = count( $listings->posts );

                if( $listings_count == 0 ) :
                    echo '<h4>You have no Favorite Properties</h4>';
                    echo '<a class="button" href="/listings">Search Here</a>';
                    elseif( $listings_count == 1 ) :
                        echo '<h4>You have 1 Favorite Property</h4>';
                        else :
                            printf( '<h4>You have %s Favorite Properties</h4>', $listings_count );
                        endif;


                        echo '<div class="row">';

                        while ( $listings->have_posts() ) : $listings->the_post();
                        get_template_part( 'listing/listing-card' );
                    endwhile;

                    echo '</div>';
                    else :
                        echo '<h4>You have no favorite listings</h4>';
                    endif;


            $user_id = get_current_user_id();
            $listingSearchQuery = new \WP_Query(['post_type' => 'listing_search', 'author' => $user_id]);
            $listingSearches = $listingSearchQuery->get_posts();
            ?>
            <h2>Saved Searches</h2>
            <style>
                .acf-form-submit {
                    padding: 1rem;
                    border-top: 1px solid #dadada;
                    background: #f9f9f9;
                }
            </style>
            <div class="row">
                <div class="small-2 large-4 columns">
                    Name
                </div>
                <div class="small-4 large-4 columns">
                    Description
                </div>
                <div class="small-4 large-4 columns">
                    Description
                </div>
            </div>
            <?php
            foreach($listingSearches as $listingSearch) :
                $new_post = array(
                    'post_id'            => $listingSearch->ID, // Create a new post
                    // PUT IN YOUR OWN FIELD GROUP ID(s)
                    'field_groups'       => array(103773), // Create post field group ID(s)
                    'form'               => true,
                    'return'             => '', // Redirect to new post url
                    'html_before_fields' => '',
                    'label_placement' => 'left',
                    'html_after_fields'  => '',
                    'submit_value'       => 'Save Search',
                    'updated_message'    => 'Search Saved',
                );
                ?>

                <div class="row">
                    <div class="small-2 large-4 columns">
                        <?= $listingSearch->post_title; ?>
                    </div>
                    <div class="small-4 large-4 columns">
                        <?= get_post_meta($listingSearch->ID, 'location', true); ?>
                    </div>
                    <div class="small-6 large-4 columns">
                        <ul class="button-group spaced">
                            <li><a class="button" href="/wp-admin/admin-post.php?action=run_listing_search&withSlugs=true&listing_search_id=<?= $listingSearch->ID ?>">Run Search</a></li>
                            <li><a class="button secondary" href="#" data-reveal-id="search-<?= $listingSearch->ID ?>">Edit</a>
                        </ul>


                    </div>

                    <div id="search-<?= $listingSearch->ID ?>" class="reveal-modal" style="padding: 0" data-reveal aria-labelledby="<?= $listingSearch->post_title ?>" aria-hidden="true" role="dialog">
                        <h3 style="padding: 1rem 0.5rem; margin-bottom: 0; border-bottom: 1px solid #dadada"><?= $listingSearch->post_title ?></h3>
                        <?php acf_form( $new_post ); ?>
                        <a class="close-reveal-modal" aria-label="Close">&#215;</a>
                    </div>
                </div>
            <?php endforeach ?>
		</div><!-- #content -->
	</div><!-- #primary -->
    <?php

    }
}
