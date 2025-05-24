<?php
// selector_imagen.php
$stmt = $conexion->query("SELECT id, nombre, descripcion FROM img");
$imagenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div id="galeria">
    
    <div style="background: #fff; padding: 20px; border-radius: 10px; 
        max-width: 90%; max-height: 80%; overflow-y: auto;">
        
        <h3>Selecciona una imagen</h3>
        <div style="display: flex; flex-wrap: wrap; gap: 15px;">
            <?php foreach ($imagenes as $img): ?>
                <img src="../img/<?= htmlspecialchars($img['nombre']) ?>"
                     alt="<?= htmlspecialchars($img['descripcion'] ?? '') ?>"
                     data-id="<?= $img['id'] ?>"
                     data-nombre="<?= htmlspecialchars($img['nombre']) ?>"
                     style="width: 120px; height: auto; cursor: pointer; border: 2px solid transparent;"
                     onclick="seleccionarImagen(this)">
            <?php endforeach; ?>
        </div>
        <br>
        <button onclick="cerrarGaleria()">Cancelar</button>
    </div>
</div>
<script src='galeria.js' defer></script>
