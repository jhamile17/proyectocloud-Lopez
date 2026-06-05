

<?php $__env->startSection('admin-content'); ?>
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 mb-0">Productos</h1>
  <a href="<?php echo e(route('admin.products.create')); ?>" class="btn btn-primary">
    + Nuevo producto
  </a>
</div>

<div class="card shadow-sm border-0">
  <div class="table-responsive">
    <table class="table align-middle mb-0">
      <thead>
        <tr>
          
          <th>Nombre</th>
          <th>Categoría</th>
          <th>Marca</th>
          <th>Precio</th>
          <th>Stock</th>
          <th>stock_minimo</th>
          <th class="text-end">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            
            <td><?php echo e($p->name); ?></td>
            <td><?php echo e($p->category->name ?? '-'); ?></td>
            <td><?php echo e($p->brand->name ?? '-'); ?></td>
            <td>S/ <?php echo e(number_format($p->price, 2)); ?></td>
            <td><?php echo e($p->stock); ?></td>
            <td><?php echo e($p->stock_minimo); ?></td>
            <td class="text-end">
              <a href="<?php echo e(route('admin.products.edit', $p)); ?>" class="btn btn-sm btn-warning">
                <i class="bi bi-pencil"></i>
              </a>
              <form action="<?php echo e(route('admin.products.destroy', $p)); ?>" method="POST" class="d-inline"
                    onsubmit="return confirm('¿Eliminar producto?');">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button class="btn btn-sm btn-danger">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <tr>
            <td colspan="7" class="text-center text-muted py-4">Sin productos</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
    <?php if(method_exists($products, 'links')): ?>
  <div class="card-body">
    <?php echo e($products->onEachSide(1)->links('vendor.pagination.procafes')); ?>

  </div>
<?php endif; ?>

  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u591048471/domains/pro-cafes.com/procafes/resources/views/admin/products/products-index.blade.php ENDPATH**/ ?>