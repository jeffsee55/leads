<?php

namespace Heidi\Plugin\Models;

class ListingSearchConverter
{

    public function convert()
    {
        ini_set('memory_limit','512M');

        global $wpdb;
        $taxonomies = array_keys(get_object_taxonomies('listing', 'objects'));
        $terms = [
            'direct' => [
                'location',
                'alert_day'
            ],
            'multi' => [
                'prop_type',
                'amenities',
                'stories_levels',
                'rooms',
                'master_bedroom',
                'garage_parking',
                'lot_description'
            ],
            'min' => [
                'min_price' => 'price_range',
                'min_beds' => 'bedrooms',
                'min_baths' => 'bathrooms',
                'min_sqft' => 'apx._sqft',
                'min_year' => 'year_built',
                'min_acres' => 'acreage'
            ],
            'max' => [
                'max_price' => 'price_range',
                'max_beds' => 'bedrooms',
                'max_baths' => 'bathrooms',
                'max_sqft' => 'apx._sqft',
                'max_year' => 'year_built',
                'max_acres' => 'acreage'
            ]
        ];
        $this->convertLeads($wpdb);
        $this->convertDirect($wpdb, $terms['direct']);
        $this->convertMultis($wpdb, $terms['multi']);
        $this->convertMinMax($wpdb, $terms['min']);
        $this->convertMinMax($wpdb, $terms['max'], false);
        $this->setTermOrder($terms['min']);

        wp_redirect(get_site_url() . '/wp-admin/admin.php?page=acf-options-leads');
        die();
    }

    public function convertLeads($wpdb) {
        $query = $wpdb->get_results( "SELECT ID, post_author FROM $wpdb->posts WHERE post_type = 'listing_search'", ARRAY_A );

        foreach($query as $meta)
        {

            $wpdb->insert($wpdb->postmeta,
                [
                    'post_id' => $meta['ID'],
                    'meta_value' => $meta['post_author'],
                    'meta_key' => 'lead'
                ]
            );

        }
    }

    public function convertDirect($wpdb, $directValues) {
        // no action needed
    }

    public function convertMinMax($wpdb, $taxonomies, $min = true) {
        foreach($taxonomies as $meta_key => $taxonomy)
        {
            $term_ids = $this->getTerms($taxonomy);
            $minSlugs = [];
            foreach($term_ids as $slug => $id)
            {
                if($min)
                    $slug = array_shift(explode('-to-', $slug));
                if(! $min)
                    $slug = array_pop(explode('-to-', $slug));

                $slugs[$slug] = $id;
            }

            $query = $wpdb->get_results( "SELECT meta_id, post_id, meta_key, meta_value FROM $wpdb->postmeta WHERE meta_key = '$meta_key'" );

            foreach($query as $meta)
            {
                $meta->meta_value = str_replace('.', '-', $meta->meta_value);

                $wpdb->update($wpdb->postmeta,
                    ['meta_value' => $slugs[$meta->meta_value]],
                    ['meta_id' => $meta->meta_id]
                );
            }
        }
    }

    public function convertMultis($wpdb, $multiTaxonomies) {
        foreach($multiTaxonomies as $taxonomy)
        {
            $term_ids = $this->getTerms($taxonomy);

            $query = $wpdb->get_results( "SELECT meta_id, post_id, meta_key, meta_value FROM $wpdb->postmeta WHERE meta_key = '$taxonomy'" );

            foreach($query as $meta)
            {
                $value = unserialize($meta->meta_value);
                if($value == false)
                {
                    $stringValue = $meta->meta_value;
                    $value = explode(',', $stringValue);
                }

                $values = array_unique($value);

                $metaTermIds = [];
                foreach($values as $value)
                {
                    $metaTermIds[] = $term_ids[$value];
                }

                $wpdb->update($wpdb->postmeta,
                    ['meta_value' => serialize($metaTermIds)],
                    ['meta_id' => $meta->meta_id]
                );

            }
        }
    }

    private function getTerms($taxonomy)
    {
        $terms = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]);
        foreach($terms as $term)
        {
            $term_ids[$term->slug] = $term->term_id;
        }

        return $term_ids;
    }

    private function getTermNames($taxonomy)
    {
        $terms = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]);
        foreach($terms as $term)
        {
            $term_ids[title_case($term->name)] = $term->term_id;
        }

        return $term_ids;
    }

    private function getTermsLowest($taxonomy)
    {
        $terms = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false]);

        foreach($terms as $term)
        {
            $lowSlug = array_shift(explode('-to-', $term->slug));

            $lowSlug = str_replace('-', '.', $lowSlug);

            $term_ids[$lowSlug] = $term->term_id;
        }

        return $term_ids;
    }

    private function setTermOrder($taxonomies)
    {
        foreach($taxonomies as $taxonomy) :

            $term_ids = $this->getTermsLowest($taxonomy);

            ksort($term_ids);

            foreach($term_ids as $order => $term_id)
            {
                update_term_meta($term_id, 'range_order', $order);
            }
        endforeach;
    }

}
