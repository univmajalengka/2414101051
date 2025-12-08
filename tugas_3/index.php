<!DOCTYPE html>
<html>
<head>
    <title>Status Pendaftaran</title>
</head>
<body>

    <?php if (isset($_GET['status'])): ?>
        <?php if ($_GET['status'] == 'sukses'): ?>
            <h2>Pendaftaran berhasil!</h2>
        <?php else: ?>
            <h2>Pendaftaran gagal!</h2>
        <?php endif; ?>
    <?php endif; ?>

</body>
</html>
