

<?php $__env->startSection('admin-content'); ?>
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 mb-0">Categorías</h1>
  <a href="<?php echo e(route('admin.categories.create')); ?>" class="btn btn-primary">
    + Nueva categoría
  </a>
</div>

<div class="card shadow-sm border-0">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          
          <th>Nombre</th>
          <th>Descripción</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            
            <td><?php echo e($cat->name); ?></td>
            <td><?php echo e(Str::limit($cat->description, 60)); ?></td>
            <td class="text-end">
              <a href="<?php echo e(route('admin.categories.edit', $cat)); ?>" class="btn btn-sm btn-warning">
                <i class="bi bi-pencil"></i>
              </a>
              <form action="<?php echo e(route('admin.categories.destroy', $cat)); ?>" method="POST" class="d-inline"
                    onsubmit="return confirm('¿Eliminar categoría?');">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button class="btn btn-sm btn-danger">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr>
            <td colspan="5" class="text-center text-muted py-4">Sin categorías</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
    <?php if(method_exists($categories, 'links')): ?>
  <div class="card-body">
    <?php echo e($categories->onEachSide(1)->links('vendor.pagination.procafes')); ?>

  </div>
<?php endif; ?>

  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u591048471/domains/pro-cafes.com/procafes/resources/views/admin/categories/categories-index.blade.php ENDPATH**/ ?>