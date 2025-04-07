
<?php
// This script copies images from public to assets/img
// You will need to run this once to set up the site

$sourceDir = 'public/';
$destDir = 'assets/img/';

// Create destination directory if it doesn't exist
if (!file_exists($destDir)) {
    mkdir($destDir, 0755, true);
}

// List of images to copy
$images = [
    'dj-background.jpg',
    'jeff-bone-profile.jpg',
    'gallery-1.jpg',
    'gallery-2.jpg',
    'gallery-3.jpg',
    'gallery-4.jpg',
    'gallery-5.jpg',
    'gallery-6.jpg'
];

foreach ($images as $image) {
    if (file_exists($sourceDir . $image)) {
        copy($sourceDir . $image, $destDir . $image);
        echo "Copied $image to $destDir<br>";
    } else {
        echo "Source file $sourceDir$image not found<br>";
    }
}

echo "<p>Image copying complete. <a href='index.php'>Go to site</a></p>";
?>
