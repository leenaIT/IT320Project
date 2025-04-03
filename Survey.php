<?php
header('Content-Type: application/json');
require_once __DIR__ . '/database.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['error' => 'Invalid data format']);
    exit;
}

// التحقق من البيانات الأساسية
if (empty($data['categories'])) {
    echo json_encode(['error' => 'Please select at least one category']);
    exit;
}

if (empty($data['locations'])) {
    echo json_encode(['error' => 'Please select at least one location']);
    exit;
}

// تنظيف المدخلات
$categories = array_map(function($cat) use ($connection) {
    return mysqli_real_escape_string($connection, $cat);
}, $data['categories']);

$locations = array_map(function($loc) use ($connection) {
    return mysqli_real_escape_string($connection, $loc);
}, $data['locations']);

// بناء الاستعلام الأساسي
$query = "SELECT * FROM workshop WHERE Category IN ('" . implode("','", $categories) . "')";

// فلترة حسب نوع الورشة
if (!empty($data['workshopType'])) {
    $workshopType = mysqli_real_escape_string($connection, $data['workshopType']);
    if ($workshopType !== 'Both') {
        $query .= " AND Type = '$workshopType'";
    }
}

// فلترة حسب الموقع
$query .= " AND Location IN ('" . implode("','", $locations) . "')";

// فلترة حسب السعر
if (!empty($data['priceRange'])) {
    switch ($data['priceRange']) {
        case '0-200':
            $query .= " AND Price <= 200";
            break;
        case '200-300':
            $query .= " AND Price > 200 AND Price <= 300";
            break;
        case '300+':
            $query .= " AND Price > 300";
            break;
    }
}

$query .= " ORDER BY Price ASC LIMIT 12";

$result = mysqli_query($connection, $query);

if (!$result) {
    echo json_encode(['error' => 'Database error: ' . mysqli_error($connection)]);
    exit;
}

$workshops = [];
while ($row = mysqli_fetch_assoc($result)) {
    $workshops[] = [
        'WorkshopID' => $row['WorkshopID'],
        'Title' => $row['Title'],
        'ShortDes' => $row['ShortDes'],
        'Category' => $row['Category'],
        'Location' => $row['Location'],
        'Type' => $row['Type'],
        'Price' => $row['Price'],
        'ImageURL' => $row['ImageURL']
    ];
}

echo json_encode($workshops);
?>