<?php

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "openCartdatabase";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Function to fetch meta title from the database
function getMetaTitle($conn, $product_id) {
    // Sanitize input to prevent SQL injection
    $product_id = $conn->real_escape_string($product_id);

    // Prepare SQL query
    $sql = "SELECT meta_title FROM oc_product_description WHERE product_id = '{$product_id}'";

    // Execute query
    $result = $conn->query($sql);

    // Check if query executed successfully
    if ($result && $result->num_rows > 0) {
        // Fetch the first row
        $row = $result->fetch_assoc();
        // Return the meta title value
        return $row['meta_title'];
    } else {
        // Handle errors or return a default value
        return "";
    }
}



// Function to fetch Product Description from the database
function getProductDescription($conn, $product_id) {
    // Sanitize input to prevent SQL injection
    $product_id = $conn->real_escape_string($product_id);

    // Prepare SQL query
    $sql = "SELECT description FROM oc_product_description WHERE product_id = '{$product_id}'";

    // Execute query
    $result = $conn->query($sql);

    // Check if query executed successfully
    if ($result && $result->num_rows > 0) {
        // Fetch the first row
        $row = $result->fetch_assoc();
        // Return the meta description value
        return $row['description'];
    } else {
        // Handle errors or return a default value
        return "";
    }
}


// Function to fetch meta description from the database
function getMetaDescription($conn, $product_id) {
    // Sanitize input to prevent SQL injection
    $product_id = $conn->real_escape_string($product_id);

    // Prepare SQL query
    $sql = "SELECT meta_description FROM oc_product_description WHERE product_id = '{$product_id}'";

    // Execute query
    $result = $conn->query($sql);

    // Check if query executed successfully
    if ($result && $result->num_rows > 0) {
        // Fetch the first row
        $row = $result->fetch_assoc();
        // Return the meta description value
        return $row['meta_description'];
    } else {
        // Handle errors or return a default value
        return "";
    }
}


// Function to fetch tags from the database
function getTags($conn, $product_id) {
    // Sanitize input to prevent SQL injection
    $product_id = $conn->real_escape_string($product_id);

    // Prepare SQL query
    $sql = "SELECT tags FROM oc_product_description WHERE product_id = '{$product_id}'";

    // Execute query
    $result = $conn->query($sql);

    // Check if query executed successfully
    if ($result && $result->num_rows > 0) {
        // Fetch the first row
        $row = $result->fetch_assoc();
        // Fetch the tags value
        $tags = $row['tag'];
        // Replace '|' with commas
        $tags = str_replace('|', ',', $tags);
        // Return the tags value
        return $tags;
    } else {
        // Handle errors or return a default value
        return "";
    }
}


// Function to fetch special price from the database
function getSpecialPrice($conn, $product_id) {
    // Sanitize input to prevent SQL injection
    $product_id = $conn->real_escape_string($product_id);

    // Prepare SQL query
    $sql = "SELECT price FROM oc_product_special WHERE product_id = '{$product_id}'";

    // Execute query
    $result = $conn->query($sql);

    // Check if query executed successfully
    if ($result && $result->num_rows > 0) {
        // Fetch the first row
        $row = $result->fetch_assoc();
        // Return the special price value
        return $row['price'];
    } else {
        // Handle errors or return a default value
        return "";
    }
}


// Function to fetch text from the database
function getTextFromDatabase($conn, $product_id, $attribute_id) {
    // Sanitize inputs to prevent SQL injection
    $product_id = $conn->real_escape_string($product_id);
    $attribute_id = $conn->real_escape_string($attribute_id);

    // Prepare SQL query
    $sql = "SELECT text FROM oc_product_attribute WHERE product_id = '{$product_id}' AND attribute_id = '{$attribute_id}'";

    // Execute query
    $result = $conn->query($sql);

    // Check if query executed successfully
    if ($result && $result->num_rows > 0) {
        // Fetch the first row
        $row = $result->fetch_assoc();
        // Return the text value
        return $row['text'];
    } else {
        // Handle errors or return a default value
        return "";
    }
}


// Fetch ALL SAREES from the database
$sql = "SELECT 
    DISTINCT p.*, 
    pd.name AS product_name,
    GROUP_CONCAT(pi.image) AS additional_images
FROM 
    oc_product p
INNER JOIN 
    oc_product_description pd ON p.product_id = pd.product_id
INNER JOIN 
    oc_product_to_category p2c ON p.product_id = p2c.product_id
LEFT JOIN 
    oc_product_image pi ON p.product_id = pi.product_id
WHERE 
    p2c.category_id IN (59, 67, 162)
    AND p.quantity > 0
    AND p.status = 1
    AND p.image NOT LIKE 'catalog%'
GROUP BY 
    p.product_id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Open a file handle for writing to CSV
    $file = fopen('simple_products.csv', 'w');

    // Write column headers to CSV
    $headers = array(
        'ID',
        'Type',
        'SKU',
        'Name',
        'Published',
        'Is featured?',
        'Visibility in catalog',
        'Short description',
        'Description',
        'Date sale price starts',
        'Date sale price ends',
        'Tax status',
        'Tax class',
        'In stock?',
        'Stock',
        'Low stock amount',
        'Backorders allowed?',
        'Sold individually?',
        'Weight (kg)',
        'Length (cm)',
        'Width (cm)',
        'Height (cm)',
        'Allow customer reviews?',
        'Purchase note',
        'Sale price',
        'Regular price',
        'Categories',
        'Tags',
        'Shipping class',
        'Images',
        'Download limit',
        'Download expiry days',
        'Parent',
        'Grouped products',
        'Upsells',
        'Cross-sells',
        'External URL',
        'Button text',
        'Position',
        'Brands',
        'Bundled Items (JSON-encoded)',
        'Min Bundle Size',
        'Max Bundle Size',
        'Bundle Contents Virtual',
        'Bundle Aggregate Weight',
        'Bundle Layout',
        'Bundle Group Mode',
        'Bundle Cart Editing',
        'Bundle Sold Individually',
        'Bundle Form Location',
        'Bundle Sells',
        'Bundle Sells Title',
        'Bundle Sells Discount',
        'Attribute 1 name',
        'Attribute 1 value(s)',
        'Attribute 1 visible',
        'Attribute 1 global',
        'Attribute 2 name',
        'Attribute 2 value(s)',
        'Attribute 2 visible',
        'Attribute 2 global',
        'Meta: _wpcom_is_markdown',
        'Meta: _last_editor_used_jetpack',
        'Meta: _wp_page_template',
        'Meta: _product_addons_exclude_global',
        'Meta: group_of_quantity',
        'Meta: minimum_allowed_quantity',
        'Meta: maximum_allowed_quantity',
        'Meta: minmax_do_not_count',
        'Meta: minmax_cart_exclude',
        'Meta: minmax_category_group_of_exclude',
        'Meta: model',
        'Meta: _model',
        'Meta: shoot_day',
        'Meta: _shoot_day',
        'Meta: isbn',
        'Meta: _isbn',
        'Meta: location',
        'Meta: _location',
        'Meta: newts_product_brand',
        'Meta: newts_product_brand_parent',
        'Meta: _ajax_nonce-add-ts_product_brand',
        'Meta: ts_prod_layout',
        'Meta: ts_prod_left_sidebar',
        'Meta: ts_prod_right_sidebar',
        'Meta: ts_prod_custom_tab',
        'Meta: ts_prod_custom_tab_title',
        'Meta: ts_prod_custom_tab_content',
        'Meta: ts_bg_breadcrumbs',
        'Meta: ts_prod_video_url',
        'Meta: ts_prod_360_gallery',
        'Meta: ts_prod_size_chart',
        'Meta: _wpas_done_all',
        'Meta: brand',
        'Meta: _brand',
        'Meta: color',
        'Meta: _color',
        'Meta: fabric',
        'Meta: _fabric',
        'Meta: blouse',
        'Meta: _blouse',
        'Meta: time_to_ship',
        'Meta: _time_to_ship',
        'Meta: allow_combination',
        'Meta: rank_math_primary_fb_product_set',
        'Meta: rank_math_title',
        'Meta: rank_math_description',
        'Meta: manufacturing_country',
        'Meta: _manufacturing_country'
    );
    fputcsv($file, $headers);


     // Fetching data and storing in array
    while($row = $result->fetch_assoc()) {
          $brand='Demo Inc';
          $location='Canada';
          $category='Sarees , Sarees > All Sarees';
        $path = "https://example.com/wp-content/uploads/catalog/images/";

         $img = $row['image'];
        
        $dt = explode('/', $img);
        // Check if $dt[0] is not empty or null
       if ($dt[0]!="catalog") {
        $date = DateTime::createFromFormat('dmY', $dt[0]);
        // Format the DateTime object in yyyymmdd format
        $shoot_day = $date->format('Ymd');
        } else {
        $shoot_day = ""; 
        }
        
        // Initialize the image name
        $imageName = '';

        // Check and append image URLs if they are not empty
        if (!empty($row['image'])) {
            $imageName .= $path . $row['image'];
        }
        
        if (!empty($row['additional_images'])) {
        $additionalImages = explode(',', $row['additional_images']); // Split the string by comma
        foreach ($additionalImages as $image) {
        $imageName .= ', ' . $path . $image; // Concatenate path to each image
        }
        }


        $tags = getTags($conn, $row['product_id']);

        $special_price=getSpecialPrice($conn, $row['product_id']);
        $color1 = getTextFromDatabase($conn, $row['product_id'], 12);
        $color2 = getTextFromDatabase($conn, $row['product_id'], 13);
        $fabric1 = getTextFromDatabase($conn, $row['product_id'], 14);
        $fabric2 = getTextFromDatabase($conn, $row['product_id'], 15);
        $timetoship = getTextFromDatabase($conn, $row['product_id'], 16);
        $blouse_piece = getTextFromDatabase($conn, $row['product_id'], 17);

        $meta_title=getMetaTitle($conn, $row['product_id']);
        $meta_description=getMetaDescription($conn, $row['product_id']);
        $product_description=getProductDescription($conn, $row['product_id']);
        // Combine static values with database values
        $data = array(
            '', // ID, leave blank
            'simple', // Type, static value
            $row['sku'], // SKU from database
            $row['product_name'], // Name from database
            '1', // Published, static value
            '0', // Is featured?, static value
            'visible', // Visibility in catalog, static value
            $product_description, // Short description from database
            '', // Description from database
            '', // Date sale price starts, static value (not provided)
            '', // Date sale price ends, static value (not provided)
            'taxable', // Tax status, static value
            '', // Tax class, static value (not provided)
            '1', // In stock?, static value
            '1', // Stock, static value (not provided)
            '', // Low stock amount, static value (not provided)
            '0', // Backorders allowed?, static value
            '0', // Sold individually?, static value
            $row['weight'], // Weight (kg), static value (not provided)
            '', // Length (cm), static value (not provided)
            '', // Width (cm), static value (not provided)
            '', // Height (cm), static value (not provided)
            '1', // Allow customer reviews?, static value
            '', // Purchase note, static value (not provided)
            $special_price, // Sale price, static value (not provided)
            $row['price'], // Regular price, static value (not provided)
            $category, // Categories, static value
            $tags, // Tags, static value (not provided)
            '', // Shipping class, static value (not provided)
            $imageName, // Images from database
            '', // Download limit, static value (not provided)
            '', // Download expiry days, static value (not provided)
            '', // Parent, static value (not provided)
            '', // Grouped products, static value (not provided)
            '', // Upsells, static value (not provided)
            '', // Cross-sells, static value (not provided)
            '', // External URL, static value (not provided)
            '', // Button text, static value (not provided)
            '0', // Position, static value (not provided)
            '', // Brands, static value (not provided)
            '', // Bundled Items (JSON-encoded), static value (not provided)
            '', // Min Bundle Size, static value (not provided)
            '', // Max Bundle Size, static value (not provided)
            '', // Bundle Contents Virtual, static value (not provided)
            '', // Bundle Aggregate Weight, static value (not provided)
            '', // Bundle Layout, static value (not provided)
            '', // Bundle Group Mode, static value (not provided)
            '', // Bundle Cart Editing, static value (not provided)
            '', // Bundle Sold Individually, static value (not provided)
            '', // Bundle Form Location, static value (not provided)
            '', // Bundle Sells, static value (not provided)
            '', // Bundle Sells Title, static value (not provided)
            '', // Bundle Sells Discount, static value (not provided)
            'Color', // Attribute 1 name, static value (not provided)
            $color1 , // Attribute 1 value(s),
            '0', // Attribute 1 visible, static value (not provided)
            '1', // Attribute 1 global, static value (not provided)
            'Fabric', // Attribute 2 name, static value (not provided)
            $fabric1, // Attribute 2 value(s), (not provided)
            '0', // Attribute 2 visible, static value (not provided)
            '1', // Attribute 2 global, static value (not provided)
            '1', // Meta: _wpcom_is_markdown, static value (not provided)
            'classic-editor', // Meta: _last_editor_used_jetpack, static value (not provided)
            'default', // Meta: _wp_page_template, static value (not provided)
            '0', // Meta: _product_addons_exclude_global, static value (not provided)
            '', // Meta: group_of_quantity, static value (not provided)
            '', // Meta: minimum_allowed_quantity, static value (not provided)
            '', // Meta: maximum_allowed_quantity, static value (not provided)
            'no', // Meta: minmax_do_not_count, static value (not provided)
            'no', // Meta: minmax_cart_exclude, static value (not provided)
            'no', // Meta: minmax_category_group_of_exclude, static value (not provided)
            $row['model'], // Meta: model, static value (not provided)
            'field_65bd452ce8150', // Meta: _model, static value (not provided)
            $shoot_day, // Meta: shoot_day, static value (not provided)
            'field_65bd49d3c22d5', // Meta: _shoot_day, static value (not provided)
            $row['isbn'], // Meta: isbn, static value (not provided)
            'field_65bd4a8eede7f', // Meta: _isbn, static value (not provided)
            $location, // Meta: location, static value (not provided)
            'field_65bd4ab58bc4c', // Meta: _location, static value (not provided)
            '', // Meta: newts_product_brand, static value (not provided)
            '', // Meta: newts_product_brand_parent, static value (not provided)
            '', // Meta: _ajax_nonce-add-ts_product_brand, static value (not provided)
            '0', // Meta: ts_prod_layout, static value (not provided)
            '0', // Meta: ts_prod_left_sidebar, static value (not provided)
            '0', // Meta: ts_prod_right_sidebar, static value (not provided)
            '0', // Meta: ts_prod_custom_tab, static value (not provided)
            '', // Meta: ts_prod_custom_tab_title, static value (not provided)
            '', // Meta: ts_prod_custom_tab_content, static value (not provided)
            '', // Meta: ts_bg_breadcrumbs, static value (not provided)
            '', // Meta: ts_prod_video_url, static value (not provided)
            '', // Meta: ts_prod_360_gallery, static value (not provided)
            '', // Meta: ts_prod_size_chart, static value (not provided)
            '1', // Meta: _wpas_done_all, static value (not provided)
            $brand, // Meta: brand, static value (not provided)
            'field_65be6753ac3d6', // Meta: _brand, static value (not provided)
            $color2, // Meta: color, static value (not provided)
            'field_65be6759ac3d7', // Meta: _color, static value (not provided)
            $fabric2, // Meta: fabric, static value (not provided)
            'field_65be6778ac3d8', // Meta: _fabric, static value (not provided)
            $blouse_piece, // Meta: blouse
            'field_65be677eac3d9', // Meta: _blouse, static value (not provided)
            $timetoship, // Meta: time_to_ship,(not provided)
            'field_65be678fac3da', // Meta: _time_to_ship, (not provided)
            'no', // Meta: allow_combination, static value (not provided)
            '0', // Meta: rank_math_primary_fb_product_set, static value (not provided)
            $meta_title, // Meta: rank_math_title, static value (not provided)
            $meta_description,
            'India',
            'field_662409950eaff'
        );

        // Write data to CSV
        fputcsv($file, $data);
    }

    // Close the file handle
    fclose($file);
    echo "Write data to CSV";
} else {
    echo "0 results";
}

// Close the database connection
$conn->close();

?>
