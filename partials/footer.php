<?php
$use_footer = $use_footer ?? true;
?>
    <?php if ($use_footer) : ?>
        <footer class="text-center py-2 mt-4 border-top text-muted small bg-white">
            <div class="container">
                <p>&copy; 2026 ZePocket. All rights reserved.</p>
            </div>
        </footer>
    <?php endif ?>

    <?= isset($js) ? $js : '<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>' ?>
    </body>

</html>