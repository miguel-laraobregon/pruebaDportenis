<?php
$title = 'Formulario de edición';
ob_start();
?>
<div>
<h4>Edición de menu</h4>
</div>

<div class="responsive">
    <form method="POST" action="/menus/<?= $menu['id'] ?>">
        <input type="hidden" name="_method" value="PUT">

        <div class="form-group">
            <label for="parent_id">Menú padre</label>
            <select class="form-control" id="parent_id"  name="parent_id">
                <option value="">Seleccione una opción</option>
                <?php foreach ($menus as $m): ?>
                    <option value="<?= $m['id'] ?>" <?= $menu['parent_id'] === $m['id'] ? 'selected' : '' ?>>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" class="form-control" id="name"  name="name" value="<?= htmlspecialchars($menu['name']) ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Descripción</label>
            <textarea class="form-control" id="description"  name="description" rows="3" required><?= htmlspecialchars($menu['description']) ?></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>


</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';
