<?php

namespace Heidi\Plugin\Models;

class ListingSearchDisplayFilters
{

    public function addFilters()
    {
        add_filter('acf/fields/taxonomy/query', [$this, 'addMinMaxTermFilterArgs'], 10, 3);
        add_filter('acf/load_value', [$this, 'filterMinMaxValue'], 10, 3);
        add_filter('get_terms', [$this, 'filterMinMaxTerms'], 10, 3);

        add_filter('acf/load_value/name=lead', [$this, 'addLeadToFields'], 10, 3);
        add_filter('acf/load_field/type=message', [$this, 'displayEmailedListings'], 10, 3);
        add_filter( 'manage_edit-listing_search_columns', [$this, 'manageColumns'] );
        add_filter( 'manage_listing_search_posts_custom_column', [$this, 'manageCustomColumns'], 10, 3 );
    }

    public function displayEmailedListings($field)
    {
        global $post;
        if($field['label'] == 'Recent Emails')
        {
            $field['instructions'] = '<ul>';
            $field['instructions'] .= '<li><a class="viewEmailedListings" data-listings="91501,81682,102894,101955,98938,86976" href="javascript:void(0)">Mon, Jan 30th</a></li>';
            $field['instructions'] .= '<li><a class="viewEmailedListings" data-listings="98938,102047,86976,97150" href="javascript:void(0)">Mon, Jan 23rd</a></li>';
            $field['instructions'] .= '<li><a class="viewEmailedListings" data-listings="89381,102894,98938,89378" href="javascript:void(0)">Mon, Jan 17th</a></li>';
            if($emails = get_post_meta($post->ID, '_emails', true))
            {
                foreach($emails as $email)
                {
                    $field['instructions'] .= '<li><a id="viewEmailedListings" data-listings="102894,98938,86976" href="javascript:void(0)">Fri, Jan 23rd</a></li>';
                }
            }
            $field['instructions'] .= '</ul>';
        }

        return $field;
    }

    public function renderRecentEmails($field)
    {
        global $post;
        switch($field['label']) {
            case ('Email Summary') :
                $date = date_create(get_post_meta($post->ID, '_last_viewed', true));

                echo date_format($date, 'H:i:s m-d-Y');

                break;
            case ('Recent Emails') :
                echo '<div id="emailedListings"><strong><h1 style="color: #b9b9b9">Choose a date to view which listings were sent</h1></strong></div>';
                break;
            case ('Test Search') :
                echo '<p>Use this area to see how many listings the search will return</p>';
                echo '<strong id="test_search_result">Ready...</strong>';
                echo '<div style="margin-top: 1rem;">';
                echo '<a id="runTestSearch" class="button button-primary">Run Search</a>';
                echo '</div>';
                break;
            default;
            break;
        }
    }

    function filterMinMaxValue($value, $post_id, $field)
    {
        $subString = substr($field['name'], 0, 3);

        if($subString == 'min') :
            remove_filter('get_term', [$this, 'filterMaxTerm']);
            add_filter('get_term', [$this, 'filterMinTerm']);
        elseif ($subString == 'max') :
            remove_filter('get_term', [$this, 'filterMinTerm']);
            add_filter('get_term', [$this, 'filterMaxTerm']);
        endif;

        return $value;
    }

    function filterMinTerm($term)
    {
        $name = $term->name;
        $names = explode(' to ', $name);
        $term->name = array_shift($names);

        return $term;
    }

    function filterMaxTerm($term)
    {
        $name = $term->name;
        $names = explode(' to ', $name);
        $term->name = array_pop($names);

        return $term;
    }

    function addMinMaxTermFilterArgs( $args, $field, $post_id ) {
        $subString = substr($field['name'], 0, 3);
        if($subString == 'min')
        {
            $args['side'] = 'left';
            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = 'range_order';
        } elseif($subString == 'max')
        {
            $args['side'] = 'right';
            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = 'range_order';

        }

        // if($field['name'] == 'min_year' || $field['name'] == 'max_year')
        //     $args['order'] = 'desc';

        return $args;

    }

    function filterMinMaxTerms($terms, $taxonomy, $args)
    {
        if(! isset($args['side']))
            return $terms;

        if($args['side'] == 'left' || $args['side'] == 'right')
        {
            foreach($terms as $term)
            {
                if($args['side'] == 'left')
                    $term->name = array_shift(explode(' to ', $term->name));

                if($args['side'] == 'right')
                    $term->name = array_pop(explode(' to ', $term->name));
            }
        }

        return $terms;
    }

    function addLeadToFields( $value, $post_id, $field )
    {
        if(isset($_GET['user_id']))
            $value = $_GET['user_id'];

        return $value;
    }

    public function saveLeadAsAuthor($post_id)
    {
        $lead = get_field('lead', $post_id);

        remove_action('save_post', [$this, 'saveLeadAsAuthor']);

        if ( isset($lead['ID']) ) {
            $args = [
                'ID'=> $post_id,
                'post_author'=> $lead['ID']
            ];

            wp_update_post( $args );
        }

        add_action('save_post', [$this, 'saveLeadAsAuthor']);
    }

    function manageCustomColumns($column, $post_id)
    {
        global $post;
        switch($column)
        {
            case 'alert_day' :
                echo get_post_meta($post_id, 'alert_day', true);
                break;
            case 'lead' :
                $lead = get_userdata($post->post_author);
                echo $lead->user_login;
                break;
            default :
                break;
        }
    }

    function manageColumns($columns) {
        $columns['alert_day'] = 'Alert Day';
        $columns['lead'] = 'Lead';
        unset($columns['date']);

        return $columns;
    }

}
