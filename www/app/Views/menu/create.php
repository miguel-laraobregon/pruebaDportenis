<?php
$title = 'Formulario de creación';
ob_start();
?>
<div>
<h4>Alta de menu</h4>
</div>

<div class="responsive">
    <form method="POST" action="/menus">
        <div class="form-group">
            <label for="parent_id">Menú padre</label>
            <select class="form-control" id="parent_id"  name="parent_id">
                <option value="">Seleccione una opción</option>
                <?php foreach ($menus as $menu) : ?>
                    <option value="<?= $menu['id'] ?>" <?= (isset($data['parent_id']) && $data['parent_id'] == $menu['id']) ? 'selected' : '' ?> >
                        <?= $menu['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" class="form-control" id="name"  name="name" value="<?= isset($data['name']) ? htmlspecialchars($data['name']) : '' ?>">
        </div>

        <div class="form-group">
            <label for="description">Descripción</label>
            <textarea class="form-control" id="description"  name="description" rows="3" ><?= isset($data['description']) ? htmlspecialchars($data['description']) : '' ?></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>


</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';


