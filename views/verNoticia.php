<?php
include '../includes/header.php';
include '../includes/conexion.php';

// Comprobar se o ID da noticia é válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>ID de noticia non válido.</p>";
    include '../includes/footer.php';
    exit;
}

// Gardamos o ID para usalo na consulta
$id = $_GET['id'];
try {
    $stmt = $conexion->prepare("SELECT * 
                                FROM noticias
                                    inner join img on img.id=imagen_id
                                WHERE noticias.id = ? AND categoria = 'publica'");
    $stmt->execute([$id]);
    $noticia = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$noticia) {
        echo "<p>Noticia non atopada ou non é pública.</p>";
        include '../includes/footer.php';
        exit;
    }

} catch (PDOException $e) {
    echo "Erro ao obter a noticia: " . $e->getMessage();
    include '../includes/footer.php';
    exit;
}
?>
<!-- Contido da noticia -->
<div class="container">
    <h2><?= htmlspecialchars($noticia['titulo']) ?></h2>
    <p><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($noticia['fecha'])) ?></p>
    
    <div class="contenido">
        <?= nl2br(htmlspecialchars($noticia['contenido'])) ?>
    </div>
    
    <?php
    // Se a noticia ten unha foto asociada, amosámola
        if (!empty($noticia['foto'])): ?>
            <img src="../img/<?= htmlspecialchars($noticia['nombre']) ?>" alt="Imaxe da noticia" style="max-width: 100%; height: auto; margin: 20px 0;">
    <?php endif; ?>

</div>

<?php include '../includes/footer.php'; ?>
