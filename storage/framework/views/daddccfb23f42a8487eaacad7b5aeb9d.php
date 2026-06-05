

<?php $__env->startSection('admin-content'); ?>
<h2 class="h5 mb-3">Editar cliente</h2>

<?php if($errors->any()): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li><?php echo e($error); ?></li>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
  </div>
<?php endif; ?>

<form action="<?php echo e(route('admin.users.update', $user)); ?>" method="POST">
  <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

  <div class="row">
    <div class="col-md-6 mb-3">
      <label class="form-label">Nombre</label>
      <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" class="form-control <?php $__errorArgs = ['name'];
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

    <div class="col-md-6 mb-3">
      <label class="form-label">Correo electrónico</label>
      <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
      <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-6 mb-3">
      <label class="form-label">Teléfono</label>
      <input type="text" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>" class="form-control">
    </div>

     <div class="col-md-6 mb-3">
      <label class="form-label">Tipo de documento</label>
      <select name="document_type" class="form-control">
        <option value="dni" <?php echo e(old('document_type', $user->document_type) == 'dni' ? 'selected' : ''); ?>>DNI</option>
        <option value="ce" <?php echo e(old('document_type', $user->document_type) == 'ce' ? 'selected' : ''); ?>>Carnet de extranjería</option>
      </select>
    </div>

    <div class="col-md-6 mb-3">
      <label class="form-label">Número de documento</label>
      <input type="text" name="document_number" 
       value="<?php echo e(old('document_number', $user->document_number)); ?>" 
       class="form-control <?php $__errorArgs = ['document_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
       id="docNumber" required>

      <?php $__errorArgs = ['document_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="invalid-feedback"><?php echo e($message); ?></div>
      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

    <div class="col-md-6 mb-3">
      <label class="form-label">Dirección</label>
      <input type="text" name="address" value="<?php echo e(old('address', $user->address)); ?>" class="form-control">
    </div>
  </div>

  <div class="col-md-6 mb-3">
      <label class="form-label">Rol del usuario</label>
      <select name="role" class="form-control" required>
        <option value="customer" <?php echo e(old('role') == 'customer' ? 'selected' : ''); ?>>Cliente</option>
        <option value="admin" <?php echo e(old('role') == 'admin' ? 'selected' : ''); ?>>Administrador</option>
      </select>
      <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    </div>

  <div class="d-flex justify-content-between">
    <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-secondary ">Volver</a>
    <button class="btn btn-primary">Actualizar</button>
  </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u591048471/domains/pro-cafes.com/procafes/resources/views/admin/users/users-edit.blade.php ENDPATH**/ ?>