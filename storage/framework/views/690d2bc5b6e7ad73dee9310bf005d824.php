<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <title><?php echo $__env->yieldContent('title','PROCAFES'); ?></title>

  <style>
    .bg-procafes { background-color:#f2da66; }
    .btn-procafes-dark { background-color:#2c2c2c; color:#fff; }
    .btn-procafes-dark:hover { filter:brightness(1.1); }
    .btn-procafes-accent { background-color:#dcae3e; color:#2c2c2c; }
    .btn-procafes-accent:hover { filter:brightness(0.95); }
    a.link-procafes { color:#2c2c2c; }
    a.link-procafes:hover { color:#2c2c2c; }
  </style>
   <script>
  window.Laravel = {
    csrfToken: '<?php echo e(csrf_token()); ?>',
    routes: {
      index:  '<?php echo e(route('cart.index')); ?>',
      add:    '<?php echo e(route('cart.add')); ?>',
      update: '<?php echo e(route('cart.update', ['rowId' => '__ID__'])); ?>',
      remove: '<?php echo e(route('cart.remove', ['rowId' => '__ID__'])); ?>',
      clear:  '<?php echo e(route('cart.clear')); ?>',
    }
  };
  window.App = {
    isAuth: <?php echo e(auth()->check() ? 'true' : 'false'); ?>,
    routes: {
      login:    '<?php echo e(route('login')); ?>',
      <?php if(Route::has('checkout')): ?> checkout: '<?php echo e(route('checkout')); ?>', <?php endif; ?>
    }
  };
</script>

  <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-light">

  
  <?php echo $__env->make('partials.header-auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  
  <main class="container py-5">
    <?php echo e($slot); ?>

  </main>

  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></>
  
  <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /home/u591048471/domains/pro-cafes.com/procafes/resources/views/components/layouts/app.blade.php ENDPATH**/ ?>