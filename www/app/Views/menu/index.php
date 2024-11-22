<?php
$title = 'Menu';
ob_start();
?>
<div>
    <nav class="navbar navbar-light bg-light">
        <h4>Menu</h4>
        <a class=" btn btn-outline-success" href="/menus/create">Nuevo</a>
    </nav>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>id</th>
                <th>Nombre</th>
                <th>Menú padre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($menus as $menu): ?>
                <tr>
                    <td><?= $menu['id'] ?></td>
                    <td><?= htmlspecialchars($menu['name']) ?></td>
                    <td><?= htmlspecialchars($menu['parent_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($menu['description']) ?></td>
                    <td>
                        <a class="btn btn-primary" href="/menus/<?= $menu['id'] ?>/edit">Editar</a>
                        <form method="POST" action="/menus/<?= $menu['id'] ?>" style="display:inline;">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';
