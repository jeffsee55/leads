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

        // <!-- postmeta with no value -->
        // DELETE pm
        // FROM wp_postmeta pm
        // WHERE pm.meta_value IS NULL
        // OR pm.meta_value = ''

        // <!-- Unused terms -->
        // DELETE t
        // FROM wp_terms AS t
        // INNER JOIN wp_term_taxonomy AS tt ON t.term_id = tt.term_id
        // WHERE tt.count = 0

        // <!-- orphaned terms -->
        // DELETE wp_term_relationships FROM wp_term_relationships
        //     LEFT JOIN wp_posts ON wp_term_relationships.object_id = wp_posts.ID
        //     WHERE wp_posts.ID is NULL;

        // <!-- post meta -->
        // DELETE
        // FROM wp_postmeta
        // WHERE post_id NOT IN (SELECT ID FROM wp_posts)

        // <!-- Orphaned term meta -->
        // DELETE tm
        // FROM wp_termmeta as tm
        // WHERE tm.term_id NOT IN (SELECT wp_terms.term_id FROM wp_terms)
    }
}
