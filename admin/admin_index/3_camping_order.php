<?php require __DIR__ . '/parts/__connect_db.php';

$pageName = 'list'; // 頁面名稱
$title = '資料列表';

$perPage = 10;  // 每頁最多有幾筆
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;



// 取得資料的總筆數
$t_sql = "SELECT COUNT(1) FROM camping_order2";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];

// 計算總頁數
$totalPages = ceil($totalRows / $perPage);

$rows = [];  // 預設值
// 有資料才執行
if ($totalRows) {
    if ($page < 1) {
        header('Location: ?page=1');
        exit;
    }
    if ($page > $totalPages) {
        header('Location: ?page=' . $totalPages);
        exit;
    }
    $sql = sprintf(
        "SELECT `camping_order2`.* ,`background_management`.`price`,`background_management`.`name` FROM `camping_order2`
        JOIN `background_management`
        ON `camping_order2`.`roomtype_sid` = `background_management`.`sid`
        ORDER BY sid DESC LIMIT %s,%s",
        ($page - 1) * $perPage,
        $perPage
    );
    $rows = $pdo->query($sql)->fetchAll();
};




// echo json_encode([
//     'totalRows' => $totalRows,
//     'totalPages' => $totalPages,
//     'perPage' => $perPage,
//     'page' => $page,
//     'rows' => $rows,
// ]);
// exit;



?>
<?php include __DIR__ . '/parts/index_header.php'; ?>
<?php include __DIR__ . '/parts/index_navber.php'; ?>
<div class="container">

    <div class="row">
        <div class="col">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col"><i class="fa-solid fa-trash-can"></i></th>
                        <th scope="col">訂單編號</th>
                        <th scope="col">會員編號</th>
                        <th scope="col">入住日期</th>

                        <th scope="col">退房日期</th>
                        <th scope="col">入住人數</th>
                        <th scope="col">帳型</th>
                        <th scope="col">價目表</th>
                        <th scope="col">住宿天數</th>
                        <th scope="col">總價</th>
                        <th scope="col">建檔日期</th>

                        <th scope="col"><i class="fa-solid fa-pen-to-square"></i></th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $r) : ?>
                        <tr>
                            <td>
                                <a href="javascript: remove_it(<?= $r['sid'] ?>)" data-onclick="event.currentTarget.closest('tr').remove()">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </td>
                            <td><?= $r['sid'] ?></td>
                            <td><?= $r['membership-sid'] ?></td>
                            <td><?= $r['check_in'] ?></td>
                            <td><?= $r['check_out'] ?></td>
                            <td><?= $r['people'] ?></td>
                            <td><?= $r['name'] ?></td>
                            <td><?= $r['price'] ?></td>
                            <!-- <td><? ##= $r['housing_days'] 
                                        ?></td> -->
                            <td><?php $dt = strtotime($r['check_out']) - strtotime($r['check_in']);
                                $days = $dt / (24 * 60 * 60);
                                echo $days;

                                ?></td>
                            <!-- <td><?= $r['total_price'] ?></td> -->
                            <td><?php $price = intval($r['housing_days']) * intval($r['price']);
                                // $price = $dt / ();
                                echo $price;

                                ?></td>

                            <td><?= $r['create_at'] ?></td>
                            <td>
                                <a href="3_order-edit.php?sid=<?= $r['sid'] ?>">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>

                </tbody>

            </table>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page - 1 ?>">
                            <i class="fa-solid fa-circle-arrow-left"></i>
                        </a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor ?>
                    <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page + 1 ?>">
                            <i class="fa-solid fa-circle-arrow-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>


</div>
<?php include __DIR__ . '/parts/index_script.php'; ?>
<script>
    function remove_it(sid) {
        if (confirm(`是否要刪除編號為 ${sid} 的資料?`)) {
            location.href = `3_camping-order_delete.php?sid=${sid}`;
        }
    }
</script>
<?php include __DIR__ . '/parts/index_footer.php'; ?>