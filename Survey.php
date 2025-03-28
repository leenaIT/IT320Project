<?php
header('Content-Type: application/json');

// تسجيل البيانات الواردة للتحقق منها
file_put_contents('debug_log.txt', print_r(file_get_contents('php://input'), true), FILE_APPEND);

require_once __DIR__ . '/database.php';

$data = json_decode(file_get_contents('php://input'), true);

// تسجيل البيانات بعد التحويل
file_put_contents('debug_log.txt', print_r($data, true), FILE_APPEND);

if (!$data || empty($data['interests']) || empty($data['skillLevel']) || empty($data['format'])) {
    $missing = [];
    if (empty($data['interests'])) $missing[] = 'interests';
    if (empty($data['skillLevel'])) $missing[] = 'skillLevel';
    if (empty($data['format'])) $missing[] = 'format';
    
    error_log("Missing fields: " . implode(', ', $missing));
    echo json_encode(['error' => 'Missing required fields: ' . implode(', ', $missing)]);
    exit;
}


// تنقية المدخلات
$interests = array_map(function($item) use ($connection) {
    return mysqli_real_escape_string($connection, $item);
}, $interests);

$skillLevel = mysqli_real_escape_string($connection, $skillLevel);
$format = mysqli_real_escape_string($connection, $format);

// تحويل التنسيق إلى ما هو موجود في قاعدة البيانات
$formatMap = [
    'Online' => 'Online',
    'In-person' => 'in-person',
    'Both' => 'Both'
];
$dbFormat = $formatMap[$format] ?? $format;

// تحويل الاهتمامات إلى ما هو موجود في قاعدة البيانات
$categoryMap = [
    'Arts and Design' => 'Art',
    'Programming and Technology' => 'Technology',
    'Sports and Fitness' => 'Adventure',
    'Health and Wellness' => 'Wellness'
];

$dbInterests = [];
foreach ($interests as $interest) {
    if (isset($categoryMap[$interest])) {
        $dbInterests[] = $categoryMap[$interest];
    } else {
        $dbInterests[] = $interest;
    }
}

$interestsList = "'" . implode("','", $dbInterests) . "'";

// بناء الاستعلام مع الأعمدة الصحيحة
$query = "SELECT 
            WorkshopID,
            Title,
            ShortDes AS description,
            Category,
            Location,
            Duration,
            Price,
            ImageURL,
            Type AS format
          FROM workshop 
          WHERE Category IN ($interestsList)
          AND Type = '$dbFormat'";

// إضافة فلترة مستوى المهارة إذا كانت موجودة في قاعدة البيانات
if (!empty($skillLevel)) {
    $query .= " AND (Age <= 0 OR Age >= 15)"; // مثال على فلترة العمر حسب مستوى المهارة
}

$query .= " ORDER BY Price ASC LIMIT 9";

$result = mysqli_query($connection, $query);

if (!$result) {
    echo json_encode(['error' => 'خطأ في استعلام قاعدة البيانات: ' . mysqli_error($connection)]);
    exit;
}

$workshops = [];
while ($row = mysqli_fetch_assoc($result)) {
    $workshops[] = [
        'WorkshopID' => $row['WorkshopID'],
        'Title' => $row['Title'],
        'ShortDes' => $row['description'],
        'Category' => $row['Category'],
        'Location' => $row['Location'],
        'Duration' => $row['Duration'],
        'Price' => $row['Price'],
        'ImageURL' => $row['ImageURL'],
        'Type' => $row['format']
    ];
}

echo json_encode($workshops);

mysqli_close($connection);
?>