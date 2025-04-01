<?php
header('Content-Type: application/json');

require_once __DIR__ . '/database.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['error' => 'Invalid JSON data']);
    exit;
}

// التحقق من الحقول المطلوبة
$required = ['categories', 'skillLevel', 'locationType', 'locations', 'budget'];
$missing = array_diff($required, array_keys($data));

if (!empty($missing)) {
    echo json_encode(['error' => 'Missing fields: ' . implode(', ', $missing)]);
    exit;
}

// بناء الاستعلام
$query = "SELECT * FROM workshop WHERE 1=1";

// فلترة حسب التصنيف
if (!empty($data['categories'])) {
    $categories = array_map(function($cat) use ($connection) {
        return mysqli_real_escape_string($connection, $cat);
    }, $data['categories']);
    $query .= " AND Category IN ('" . implode("','", $categories) . "')";
}

// فلترة حسب نوع المكان
if (!empty($data['locationType'])) {
    $locationType = mysqli_real_escape_string($connection, $data['locationType']);
    $query .= " AND Type = '$locationType'";
}

// فلترة حسب الموقع الجغرافي
if (!empty($data['locations'])) {
    $locations = array_map(function($loc) use ($connection) {
        return mysqli_real_escape_string($connection, $loc);
    }, $data['locations']);
    $query .= " AND Location IN ('" . implode("','", $locations) . "')";
}

// فلترة حسب الميزانية
if (!empty($data['budget'])) {
    switch ($data['budget']) {
        case '0-150':
            $query .= " AND Price <= 150";
            break;
        case '150-250':
            $query .= " AND Price > 150 AND Price <= 250";
            break;
        case '250+':
            $query .= " AND Price > 250";
            break;
    }
}

// فلترة حسب مستوى الخبرة (العمر)
switch ($data['skillLevel']) {
    case 'Beginner':
        $query .= " AND (Age <= 15 OR Age = 0)";
        break;
    case 'Intermediate':
        $query .= " AND (Age > 15 AND Age < 18 OR Age = 0)";
        break;
    case 'Advanced':
        $query .= " AND (Age >= 18 OR Age = 0)";
        break;
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
        'Duration' => $row['Duration'],
        'Price' => $row['Price'],
        'ImageURL' => $row['ImageURL']
    ];
}



// إذا لم توجد ورش، نرسل رسالة مناسبة
if (empty($workshops)) {
    echo json_encode(['message' => 'No workshops found matching your criteria. Please try different selections.']);
} else {
    echo json_encode($workshops);
}

mysqli_close($connection);
?>