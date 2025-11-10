<?php
include 'includes/config.php';

// Create tables for editable content
$sql = "
CREATE TABLE IF NOT EXISTS about_content (
    id INT PRIMARY KEY AUTO_INCREMENT,
    heading VARCHAR(255) NOT NULL,
    content_text TEXT NOT NULL,
    image_path VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS portfolio_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image_path VARCHAR(255) NOT NULL,
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default about content if empty
INSERT IGNORE INTO about_content (id, heading, content_text, image_path) 
VALUES (1, 'Hi, I\'m Genevieve', 'I\'m a passionate fashion designer focused on creating garments that tell stories — combining handcrafted techniques with contemporary lines to produce wearable art.', 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=1200&q=80');

-- Insert sample portfolio items if empty
INSERT IGNORE INTO portfolio_items (title, description, image_path, display_order) VALUES 
('Evening Gown', 'Hand-stitched detailing and silk lining.', 'assets/images/project1.jpg', 1),
('Tradition Reimagined', 'Modern silhouettes with artisanal prints.', 'assets/images/project2.jpg', 2),
('Daywear Collection', 'Lightweight, sustainable fabrics.', 'assets/images/project3.jpg', 3);
";

// Execute the multi-query SQL
if ($conn->multi_query($sql)) {
    do {
        // Store first result set
        if ($result = $conn->store_result()) {
            $result->free();
        }
        // Prepare next result set
    } while ($conn->more_results() && $conn->next_result());
    
    echo "Content tables created successfully.";
} else {
    echo "Error creating tables: " . $conn->error;
}

$conn->close();
?>