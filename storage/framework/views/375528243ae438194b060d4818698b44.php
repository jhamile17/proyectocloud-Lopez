

<?php $__env->startSection('title', 'Usuarios | PROCAFES'); ?>

<?php $__env->startSection('admin-content'); ?>
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 mb-0">Usuarios</h1>
  <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary">
    + Nuevo usuario
  </a>
</div>

<div class="card shadow-sm border-0">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          
          <th>Nombre</th>
          <th>Email</th>
          <th>Teléfono</th>
          <th>Tipo de documento</th>
          <th>Número de documento</th>
          <th>Dirección</th>
          <th>Rol</th>
          <th>Verificado</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            
            <td><?php echo e($u->name); ?></td>
            <td><?php echo e($u->email); ?></td>
            <td><?php echo e($u->phone); ?></td>
            <td><?php echo e($u->document_type); ?></td>
            <td><?php echo e($u->document_number); ?></td>
            <td><?php echo e($u->address); ?></td>
            <td>
              <span class="badge <?php echo e($u->role == 'admin' ? 'text-bg-danger' : 'text-bg-secondary'); ?>">
                <?php echo e(ucfirst($u->role)); ?>

              </span>
            </td>
            <td>
              <?php if($u->email_verified_at): ?>
                <span class="badge text-bg-success">Sí</span>
              <?php else: ?>
                <span class="badge text-bg-warning text-dark">No</span>
              <?php endif; ?>
            </td>
            <td class="text-end">
              <a href="<?php echo e(route('admin.users.edit', $u)); ?>" class="btn btn-sm btn-warning">
                <i class="bi bi-pencil"></i>
              </a>
              <form action="<?php echo e(route('admin.users.destroy', $u)); ?>" method="POST" class="d-inline"
                    onsubmit="return confirm('¿Eliminar usuario?');">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button class="btn btn-sm btn-danger">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr>
            <td colspan="6" class="text-center text-muted py-4">Sin usuarios</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <?php if(method_exists($users, 'links')): ?>
    <div class="card-body">
      <?php echo e($users->links()); ?>

    </div>
  <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u591048471/domains/pro-cafes.com/procafes/resources/views/admin/users/users-index.blade.php ENDPATH**/ ?>