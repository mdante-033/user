INSERT INTO users (name, email, phone, password_hash, role)
VALUES ('Cheryne Admin', 'admin@cherynes.com', '0795879797', '$2y$10$lifP8jB6OrpAbU40HqyhmelZ.DjV8yPtYeyRhM9upDZQYgRF5UUZu', 'admin')
ON CONFLICT (email) DO NOTHING;

INSERT INTO categories (name, slug) VALUES
('Starters', 'starters'),
('Mains', 'mains'),
('Drinks', 'drinks')
ON CONFLICT (slug) DO NOTHING;

INSERT INTO menu_items (category_id, name, slug, description, price, image_url, is_available) VALUES
((SELECT id FROM categories WHERE slug = 'starters'), 'Kachumbari Bowl', 'kachumbari-bowl', 'Fresh tomato, onion, coriander, and lemon salad served chilled.', 250.00, 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=900&q=80', TRUE),
((SELECT id FROM categories WHERE slug = 'starters'), 'Crisp Bhajia', 'crisp-bhajia', 'Spiced potato bhajia with house chilli and tamarind dip.', 350.00, 'https://images.unsplash.com/photo-1626200419199-391ae4be7a41?auto=format&fit=crop&w=900&q=80', TRUE),
((SELECT id FROM categories WHERE slug = 'mains'), 'Nyama Choma Plate', 'nyama-choma-plate', 'Char-grilled beef served with ugali, kachumbari, and greens.', 950.00, 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=900&q=80', TRUE),
((SELECT id FROM categories WHERE slug = 'mains'), 'Swahili Coconut Fish', 'swahili-coconut-fish', 'Tender fish simmered in coconut curry with fragrant rice.', 1100.00, 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?auto=format&fit=crop&w=900&q=80', TRUE),
((SELECT id FROM categories WHERE slug = 'drinks'), 'Fresh Passion Juice', 'fresh-passion-juice', 'Freshly pressed passion fruit juice served cold.', 220.00, 'https://images.unsplash.com/photo-1622597467836-f3285f2131b8?auto=format&fit=crop&w=900&q=80', TRUE),
((SELECT id FROM categories WHERE slug = 'drinks'), 'Tangawizi Iced Tea', 'tangawizi-iced-tea', 'Ginger iced tea with citrus and mint.', 260.00, 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=900&q=80', TRUE)
ON CONFLICT (slug) DO NOTHING;
