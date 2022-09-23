<?php require __DIR__ . '/parts/__connect_db.php';?>
<?php include __DIR__ . '/parts/index_header.php'; ?>
<?php include __DIR__ . '/parts/index_navber.php'; ?>
<?php
$pageName = 'list';
//算總筆數
$t_sql = "SELECT COUNT(1) FROM `camping_order2`";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];
//一頁有幾筆
$perPage = 15;
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
    $sql = sprintf("SELECT * FROM `camping_order2` ORDER BY sid DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);

    $rows = $pdo->query($sql)->fetchAll();
}





$outPut = ([
    'totalRows' => $totalRows,
    'totalPages' => $totalPage,
    'page' => $page,
    'rows' => $rows,
    'perPage' => $perPage,
]);
// echo json_encode($outPut); exit;
?>



<div class="container">
    <div class="row">
        <div class="col">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item <?= $page == 1 ? "disabled" : "" ?>">
                        <a class="page-link" href="?page=<?= $page - 1 ?>">
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                    </li>

                    <?php for ($i = $page - 5; $i <= $page + 5; $i++) :
                        if ($i >= 1 and $i <= $totalPage) :
                    ?>
                            <li class="page-item <?= $i == $page ? "active" : "" ?>"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li>
                    <?php
                        endif;
                    endfor; ?>

                    <li class="page-item <?= $page == $totalPage ? "disabled" : "" ?>">
                        <a class="page-link" href="?page=<?= $page + 1 ?>">
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">
                            <i class="fa-solid fa-trash-can"></i>
                        </th>
                        <th scope="col">#</th>
                        <th scope="col">姓名</th>
                        <th scope="col">email</th>
                        <th scope="col">手機</th>
                        <th scope="col">生日</th>
                        <th scope="col">地址</th>
                        <th scope="col">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $r) : ?>
                        <tr>
                            <td>
                                <a href="javascript: delete_it(<?= $r['sid'] ?>)">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </td>
                            <td><?= $r['sid'] ?></td>
                            <td><?= $r['name'] ?></td>
                            <td><?= $r['email'] ?></td>
                            <td><?= $r['mobile'] ?></td>
                            <td><?= $r['birthday'] ?></td>
                            <td><?= htmlentities($r['address']) ?></td>
                            <td>
                                <a href="edit-form.php?sid=<?= $r['sid'] ?>">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>

<?php include __DIR__ . '/parts/html.script.php'; ?>
<script>
    const table = document.querySelector('table');

    function delete_it(sid){
        if(confirm(`確定要刪除編號為${sid}的資料嗎?`)){
            location.href = `delete-api.php?sid=${sid}`;
        }
    }
    //刪除 找到某個欄位
    // table.addEventListener("click" , event=>{
    //     const t = event.target;
    //     if(t.classList.contains("fa-trash-can")){
    //         t.closest("tr").remove();
    //     }
    //     if(t.classList.contains("fa-pen-to-square")){
    //         console.log(t.closest("tr").querySelectorAll('td')[2]);
    //     }
    // })
    
</script>


<?php include __DIR__ . '/parts/html.foot.php'; ?>