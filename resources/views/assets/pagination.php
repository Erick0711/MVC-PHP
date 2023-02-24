<!-- /* $$paginate hara que una variable encima de otra variables es decir que pasar la variable $paginate = 'contacts' */ -->
<!--  $contacts; -->
<nav aria-label="Page navigation example">
        <ul class="">
            <li>Mostrando <?= $$paginate['from'] ?></li>
            <li>Al <?= $$paginate['to'] ?></li>
            <li>de Resultados <?= $$paginate['total'] ?></li>
        </ul>
        <ul class="pagination justify-content-center">
            <li class="page-item">
                <a class="page-link" href="<?= $$paginate['prev_page'] ?>">Previous</a>
            </li>
            <?php for($i = 1; $i <= $$paginate['last_page']; $i++):?>

            <li class="page-item">
                <a class="page-link <?=$$paginate['current_page'] == $i ? 'border border-dark' : 'color-dark'?>" 
                href="/contacts?page=<?=$i?><?=isset($_GET['search']) ? "&search={$_GET['search']}" : '' ?>">
                <?= $i ?>
                </a>
            </li>

            <?php endfor ?>
            <li class="page-item">
                <a class="page-link" href="<?= $$paginate['next_page'] ?>">Next</a>
            </li>
        </ul>
</nav>