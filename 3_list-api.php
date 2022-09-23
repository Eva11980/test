<?php require __DIR__ . '/parts/__connect_db.php';
//算總筆數
$t_sql = "SELECT COUNT(1) FROM `address_book`";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];
//一頁有幾筆
$perPage = 5;
//有幾頁
$totalPage = ceil($totalRows / $perPage);
//決定在哪一頁  
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$rows = [];
//如果有資料才執行
if ($totalRows) {
    //如果page小於第一頁 停留在第一頁
    if ($page < 1) {
        header('Location: ?page=1');
        exit;
    }
    //如果page大於最大頁數 停留在最大頁數
    if ($page > $totalPage) {
        header('Location: ?page=' . $totalPage);
        exit;
    }
    //降冪排序
    $sql = sprintf("SELECT * FROM `/parts/__connect_db.php` ORDER BY sid DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);

    $rows = $pdo->query($sql)->fetchAll();
}


$output = ([
    'totalRows' => $totalRows,
    'totalPages' => $totalPage,
    'page' => $page,
    'rows' => $rows,
    'perPage' => $perPage,
]);
header('Content-Type: application/json');
echo json_encode($output); exit;
?>