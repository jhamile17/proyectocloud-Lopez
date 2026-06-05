

<?php $__env->startSection('title', 'Marcas | PROCAFES'); ?>

<?php $__env->startSection('admin-content'); ?>
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 mb-0">Marcas</h1>
  <a href="<?php echo e(route('admin.brands.create')); ?>" class="btn btn-primary">
    + Nueva marca
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
        <?php $__empty_1 = true; $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            
            
            <td><?php echo e($brand->name); ?></td>
            <td><?php echo e(\Illuminate\Support\Str::limit($brand->description, 60)); ?></td>
            <td class="text-end">
              <a href="<?php echo e(route('admin.brands.edit', ['brand' => $brand->getKey()])); ?>" class="btn btn-sm btn-warning">
                <i class="bi bi-pencil"></i>
              </a>

              <form action="<?php echo e(route('admin.brands.destroy', ['brand' => $brand->getKey()])); ?>"
                    method="POST" class="d-inline"
                    onsubmit="return confirm('¿Eliminar marca?');">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button class="btn btn-sm btn-danger">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr>
            <td colspan="5" class="text-center text-muted py-4">Sin marcas registradas</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <?php if(method_exists($brands, 'links')): ?>
    <div class="card-body">
      <?php echo e($brands->links()); ?>

    </div>
  <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u591048471/domains/pro-cafes.com/procafes/resources/views/admin/brands/index.blade.php ENDPATH**/ ?>