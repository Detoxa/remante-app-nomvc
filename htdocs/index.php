<?php

include_once 'db.php';

$record_per_page = 5;
$page = '';
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$start_from = ($page - 1) * $record_per_page;

$query = "SELECT * FROM products ORDER BY product_id DESC LIMIT $start_from, $record_per_page";

$result = mysqli_query($con, $query);
?>
<table class="table table-bordered">
	<tr>
		<th>Name</th>
		<th>Phone</th>
	</tr>
	<?php
	while ($row = mysqli_fetch_array($result)) { ?>
	<tr>
		<td><?php echo $row['product_id']; ?></td>
		<td><?php echo $row['product_name']; ?></td>
	</tr>
	<?php }
	?>
</table>
<div align="center" class="pagination">
	<?php
	$page_query = "SELECT * FROM products ORDER BY product_id";
	$page_result = mysqli_query($con, $page_query);
	$total_records = mysqli_num_rows($page_result);
	$total_pages = ceil($total_records/$record_per_page); ?>
	<nav aria-label="Page navigation example">
		<ul class="pagination">
	<?php for ($i = 1; $i <= $total_pages ; $i++) { ?>
		<li class="page-item <?php if($_GET['page'] == $i){echo 'active';} ?>"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>		
	<?php }	?>
		</ul>
	</nav>
</div>