INSERT INTO blog_posts (author_id, title, slug, excerpt, content, published_at, created_at, updated_at)
VALUES
(NULL, 'Welcome to Cause we Can', 'welcome-to-cause-we-can', 'Our new website is live.', 'Our new guild website with Discord login, calendar, and roster is now online.', NOW(), NOW(), NOW());

INSERT INTO guild_events (title, description, event_type, start_at, end_at, created_by, created_at, updated_at)
VALUES
('Weekly Raid', 'Molten Core + Onyxia', 'raid', DATE_ADD(NOW(), INTERVAL 2 DAY), DATE_ADD(DATE_ADD(NOW(), INTERVAL 2 DAY), INTERVAL 3 HOUR), 1, NOW(), NOW());
