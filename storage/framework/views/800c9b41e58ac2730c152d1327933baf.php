
<?php $__env->startSection('title', 'Mi lista de deseos'); ?>

<?php $__env->startSection('content'); ?>
<?php use Illuminate\Support\Facades\Storage; ?>

<div class="container py-4">
  <h3 class="mb-4">💖 Mi lista de deseos</h3>

  <?php if($items->isEmpty()): ?>
    <div class="alert alert-secondary text-center shadow-sm">
      <i class="bi bi-heart text-danger me-2"></i> No tienes productos en tu lista de deseos aún.
    </div>
  <?php else: ?>
    <div class="row g-3">
      <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $product = $item->product; ?>
        <?php if($product): ?>
          <div class="col-6 col-md-4 col-lg-3 js-wishlist-card" data-product="<?php echo e($product->id); ?>">
            <div class="card h-100 shadow-sm border-0">
              <div class="ratio ratio-1x1 bg-light">
                <?php
                  $img = ($product->image && Storage::disk('public')->exists($product->image))
                          ? Storage::url($product->image)
                          : 'https://via.placeholder.com/400x400?text=Producto';
                ?>
                <img src="<?php echo e($img); ?>" alt="<?php echo e($product->name); ?>" class="w-100 h-100 object-fit-cover rounded-top">
              </div>

              <div class="card-body text-center">
                <h6 class="card-title mb-1"><?php echo e($product->name); ?></h6>
                <p class="text-muted small mb-2"><?php echo e($product->category->name ?? 'Sin categoría'); ?></p>
                <strong class="d-block mb-3">S/ <?php echo e(number_format($product->price, 2)); ?></strong>
              </div>

              <div class="card-footer bg-white border-0 d-flex justify-content-center gap-2 pb-3">
                
                <form class="js-wishlist-toggle" data-product="<?php echo e($product->id); ?>">
                  <?php echo csrf_field(); ?>
                  <button class="btn btn-outline-danger btn-sm" title="Quitar de la lista">
                    <i class="bi bi-heart-fill"></i>
                  </button>
                </form>

                
                <button type="button" class="btn btn-warning btn-sm btn-add-to-cart"
                        data-id="<?php echo e($product->id); ?>"
                        data-name="<?php echo e($product->name); ?>"
                        data-price="<?php echo e($product->price); ?>"
                        data-image="<?php echo e($img); ?>"
                        data-url="<?php echo e(url('/product/'.$product->id)); ?>">
                  <i class="bi bi-cart-plus me-1"></i> Añadir al carrito
                </button>
              </div>
            </div>
          </div>
        <?php endif; ?>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u591048471/domains/pro-cafes.com/procafes/resources/views/wishlist/index.blade.php ENDPATH**/ ?>