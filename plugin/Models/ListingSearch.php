<?php

namespace Heidi\Plugin\Models;

class ListingSearch
{
    function buildQuery($listing_search_id, $slugs = false)
    {
        global $wpdb;

        $metaKeys = [
            // 'location',
            'prop_type',
            'min_price',
            'max_price',
            'min_beds',
            'max_beds',
            'min_baths',
            'max_baths',
            'min_sqft',
            'max_sqft',
            'min_year',
            'max_year',
            'min_acrea',
            'max_acres',
            'amenities',
            'stories_levels',
            'master_bedroom',
            'rooms',
            'garage_parking',
            'lot_description',
        ];

        $location = get_post_meta($listing_search_id, 'location', true);
        if($location)
            $query['location'] = $location;

        foreach($metaKeys as $metaKey)
        {
            $metaValue = get_post_meta($listing_search_id, $metaKey, true);
            $term_ids = implode(',', $metaValue);
            $metaSlugs = $wpdb->get_results("SELECT $wpdb->terms.slug FROM $wpdb->terms WHERE $wpdb->terms.term_id IN ($term_ids)", ARRAY_N);
            if($metaValue)
                $query[$metaKey] = array_column($metaSlugs, 0);
        }
         $queryString = http_build_query($query);

         return get_site_url() . '?s=&withSlugs=true&post_type=listing&' . $queryString;
    }

    function recordEmail()
    {
        $emails = get_post_meta($post->ID, 'email', true);

        if(count($emails) > 50)
            array_shift($emails);

        $emails[] = [
            'timestamp' => '',
            'listings' => [
                ''
            ]
        ];

        update_post_meta($post->ID, 'email', $emails);
    }

    public function tax_query( $query )
    {
        if(isset($_GET['withSlugs']))
            $this->slugs = true;

        if ( $query->is_search && !is_admin() ) {
            $this->query_vars = $query->query_vars;

            $tax_query_args = array(
                'relation' => 'AND'
            );

            $area_items = array(
                'city',
                'county',
                'subdivision',
                'street_name',
                'schools',
                'area',
                'zip_code',
                'address',
            );

            $multiQueryItems = [
                'prop_type',
                'amenities',
                'stories_levels',
                'master_bedroom',
                'rooms',
                'garage_parking',
                'lot_description'
            ];

            foreach($multiQueryItems as $queryItem)
            {
                if(isset($this->query_vars[$queryItem]))
                {
                    $tax_query_args[] = [
                        'taxonomy' => $queryItem,
                        'field' => 'slug',
                        'terms' => $this->query_vars[$queryItem]
                    ];
                }
            }

            $query_items = array(
                'price_range' => array('min_price', 'max_price'),
                'apx._sqft'   => array('min_sqft', 'max_sqft'),
                'bedrooms'    => array('min_beds', 'max_beds'),
                'bathrooms'   => array('min_baths', 'max_baths'),
                'year_built'  => array('min_year', 'max_year'),
                'acreage'     => array('min_acres', 'max_acres'),
            );

            foreach($query_items as $taxonomy => $meta_keys)
            {
                if(isset($this->query_vars[$meta_keys[0]]) || isset($this->query_vars[$meta_keys[1]]))
                {
                    $terms = get_terms($taxonomy, [
                        'hide_empty' => false,
                        'fields' => 'slugs',
                        'orderby' => 'meta_value_num',
                        'meta_key' => 'range_order'
                    ]);

                    $tax_query_args[] = [
                        'taxonomy' => $taxonomy,
                        'field' => 'slug',
                        'terms' => $this->getRange($terms, $meta_keys)
                    ];
                }
            }

            $query->set( 'tax_query', $tax_query_args );
            $query->set( 'post_type', 'listing' );
        }
    }

    public function getRange($terms, $meta_keys)
    {
        $min = isset($this->query_vars[$meta_keys[0]]) ? array_search($this->query_vars[$meta_keys[0]], $terms) : 0;
        $max = isset($this->query_vars[$meta_keys[1]]) ? array_search($this->query_vars[$meta_keys[1]], $terms) : (count($terms) - 1);
        $range = range($min, $max);
        $ids = array_only($terms, $range);
        return $ids;
    }
}
