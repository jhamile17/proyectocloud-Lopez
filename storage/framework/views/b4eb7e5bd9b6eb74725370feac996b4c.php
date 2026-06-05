

<?php $__env->startSection('admin-content'); ?>
<h2 class="h5 mb-3">Editar categoría</h2>

<form action="<?php echo e(route('admin.categories.update', $category)); ?>" method="POST">
  <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

  <div class="mb-3">
    <label class="form-label">Nombre</label>
    <input type="text" name="name" value="<?php echo e(old('name', $category->name)); ?>" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
  </div>

  <div class="mb-3">
    <label class="form-label">Descripción</label>
    <textarea name="description" class="form-control" rows="3"><?php echo e(old('description', $category->description)); ?></textarea>
  </div>

  <div class="d-flex justify-content-between">
    <a href="<?php echo e(route('admin.categories.index')); ?>" class="btn btn-secondary">Volver</a>
    <button class="btn btn-primary">Actualizar</button>
  </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u591048471/domains/pro-cafes.com/procafes/resources/views/admin/categories/categories-edit.blade.php ENDPATH**/ ?>