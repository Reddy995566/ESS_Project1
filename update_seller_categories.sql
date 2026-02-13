-- Update all seller categories to show on homepage automatically
UPDATE categories 
SET show_in_homepage = 1 
WHERE seller_id IS NOT NULL;

-- Check the results
SELECT id, name, seller_id, show_in_homepage 
FROM categories 
WHERE seller_id IS NOT NULL;
