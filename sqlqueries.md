DELETE wp_posts
FROM wp_postmeta
INNER JOIN wp_posts ON wp_postmeta.post_id = wp_posts.id
WHERE wp_postmeta.meta_key = 'alert_day'
AND wp_postmeta.meta_value = '0'
AND wp_posts.post_type = 'listing_search'

<!-- delete all post meta thats null or empty -->
DELETE pm
FROM wp_postmeta pm
WHERE pm.meta_value IS NULL
OR pm.meta_value = ''

<!-- sub-type and type -->
DELETE tx
FROM wp_term_taxonomy as tx
WHERE tx.taxonomy = 'sub-type'

DELETE tx
FROM wp_term_taxonomy as tx
WHERE tx.taxonomy = 'type'

<!-- Unused terms -->
DELETE t
FROM wp_terms AS t
INNER JOIN wp_term_taxonomy AS tt ON t.term_id = tt.term_id
WHERE tt.count = 0

<!-- orphaned terms -->
DELETE wp_term_relationships FROM wp_term_relationships
    LEFT JOIN wp_posts ON wp_term_relationships.object_id = wp_posts.ID
    WHERE wp_posts.ID is NULL;

<!-- Orphaned term meta -->
DELETE tm
FROM wp_termmeta as tm
WHERE tm.term_id NOT IN (SELECT wp_terms.term_id FROM wp_terms)


<!-- get post meta and term meta by common slug -->
SELECT pm.*, tm.*
FROM wp_postmeta AS pm
LEFT OUTER JOIN wp_terms AS tm
	ON pm.meta_value LIKE CONCAT("%\"", tm.slug, "\"%")
	OR pm.meta_value LIKE tm.slug
WHERE pm.meta_key IN ('prop_type')
GROUP BY pm.meta_id

UPDATE wp_postmeta AS pm
LEFT OUTER JOIN wp_terms AS tm
	ON pm.meta_value LIKE CONCAT("%\"", tm.slug, "\"%")
	OR pm.meta_value LIKE tm.slug
SET pm.meta_value = tm.term_id
WHERE pm.meta_key IN ('prop_type')

UPDATE wp_postmeta AS pm
LEFT OUTER JOIN wp_terms AS tm
	ON pm.meta_value = tm.slug
SET pm.meta_value = '10671'
WHERE pm.meta_key IN ('prop_type')
