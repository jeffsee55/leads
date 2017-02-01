<?php

namespace Heidi\Plugin\Models;

class ListingSearchCleaner
{
    public function clean()
    {
        global $wpdb;
        // delete listings with no alert day
        $query = $wpdb->get_results( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'alert_day' AND meta_value = '0'", ARRAY_N);
        $ids = array_pluck($query, 0);
        $ids = implode(',', $ids);
        $wpdb->query("DELETE FROM $wpdb->posts WHERE ID IN ($ids)");

        // delete listings with max price = 25000
        $query = $wpdb->get_results( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'max_price' AND meta_value = '10593'", ARRAY_N);
        $ids = array_pluck($query, 0);
        $ids = implode(',', $ids);
        $wpdb->query("DELETE FROM $wpdb->posts WHERE ID IN ($ids)");

        // delete sub-type taxonomy
        $query = $wpdb->get_results("SELECT term_id FROM wp_term_taxonomy WHERE taxonomy = 'sub-type'", ARRAY_N);
        $ids = array_pluck($query, 0);
        $ids = implode(',', $ids);
        $wpdb->query("DELETE FROM $wpdb->wp_term_taxonomy WHERE ID IN ($ids)");

        // delete type taxonomy
        $query = $wpdb->get_results("SELECT term_id FROM wp_term_taxonomy WHERE taxonomy = 'type'", ARRAY_N);
        $ids = array_pluck($query, 0);
        $ids = implode(',', $ids);
        $wpdb->query("DELETE FROM $wpdb->wp_term_taxonomy WHERE ID IN ($ids)");

    }
}
