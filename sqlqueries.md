<!-- delete all post meta thats null or empty -->
DELETE pm
FROM wp_postmeta pm
WHERE pm.meta_value IS NULL
OR pm.meta_value = ''

<!-- Unused terms -->
DELETE t
FROM wp_terms AS t
INNER JOIN wp_term_taxonomy AS tt ON t.term_id = tt.term_id
WHERE tt.count = 0

<!-- orphaned terms -->
DELETE wp_term_relationships FROM wp_term_relationships
    LEFT JOIN wp_posts ON wp_term_relationships.object_id = wp_posts.ID
    WHERE wp_posts.ID is NULL;

<!-- orphaned post meta -->
DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT ID FROM wp_posts)

<!-- Orphaned term meta -->
DELETE tm
FROM wp_termmeta as tm
WHERE tm.term_id NOT IN (SELECT wp_terms.term_id FROM wp_terms)
