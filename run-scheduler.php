<?php
// run-scheduler.php
$projectPath = '/home/u591048471/procafes';
exec("cd $projectPath && php artisan schedule:run >> /dev/null 2>&1");
