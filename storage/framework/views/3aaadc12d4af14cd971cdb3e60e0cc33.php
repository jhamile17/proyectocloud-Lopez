
<?php $__env->startSection('title', 'Editar producto'); ?>

<?php $__env->startSection('admin-content'); ?>
<div class="container-fluid px-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Editar producto</h4>
    <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-secondary btn-sm">
      <i class="bi bi-arrow-left"></i> Volver
    </a>
  </div>

  <div class="card shadow-sm border-0">
    <div class="card-body">
      <form action="<?php echo e(route('admin.products.update', $product->id)); ?>" method="POST" enctype="multipart/form-data" class="row g-3">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        
        <div class="col-md-6">
          <label class="form-label fw-semibold">Nombre</label>
          <input type="text" name="name" value="<?php echo e(old('name', $product->name)); ?>" required
                 class="form-control <?php $__errorArgs = ['name'];
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

        
        <div class="col-md-4">
          <label class="form-label fw-semibold">Precio (S/)</label>
          <input type="number" name="price" min="0" step="0.01"
                 value="<?php echo e(old('price', $product->price)); ?>" class="form-control <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
          <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Stock</label>
          <input type="number" name="stock" min="0"
                 value="<?php echo e(old('stock', $product->stock)); ?>" class="form-control <?php $__errorArgs = ['stock'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
          <?php $__errorArgs = ['stock'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Stock mínimo</label>
          <input type="number" name="stock_minimo" min="0"
                 value="<?php echo e(old('stock_minimo', $product->stock_minimo)); ?>" class="form-control <?php $__errorArgs = ['stock_minimo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
          <?php $__errorArgs = ['stock_minimo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="col-md-4">
          <label class="form-label fw-semibold">Estado</label>
          <select name="status" class="form-select">
            <option value="active" <?php echo e(old('status', $product->status) == 'active' ? 'selected' : ''); ?>>Activo</option>
            <option value="inactive" <?php echo e(old('status', $product->status) == 'inactive' ? 'selected' : ''); ?>>Inactivo</option>
          </select>
        </div>
        
        <div class="mb-3">
          <label for="categories_id" class="form-label fw-semibold">Categoría</label>
          <select name="categories_id" id="categories_id" class="form-select" required>
            <option value="">Seleccionar categoría...</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($cat->categories_id); ?>"
                <?php echo e(old('categories_id', $product->categories_id) == $cat->categories_id ? 'selected' : ''); ?>>
                <?php echo e($cat->name); ?>

              </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
          <?php $__errorArgs = ['categories_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        
        
        <div class="mb-3">
          <label for="brand_id" class="form-label fw-semibold">Marca</label>
          <select name="brand_id" id="brand_id" class="form-select">
            <option value="">Sin marca</option>
            <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($b->brand_id); ?>"
                <?php echo e(old('brand_id', $product->brand_id) == $b->brand_id ? 'selected' : ''); ?>>
                <?php echo e($b->name); ?>

              </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
          <?php $__errorArgs = ['brand_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        
        <div class="col-md-6">
          <label class="form-label fw-semibold">Imagen</label>
          <input type="file" name="image" class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept="image/*" onchange="previewImage(event)">
          <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

          
            <div class="mt-2">
                <img id="imagePreview"
                    src="<?php echo e(($product->image && Storage::disk('public')->exists($product->image)) ? Storage::url($product->image) : 'https://via.placeholder.com/150x150?text=Sin+imagen'); ?>"
                    class="img-thumbnail" style="max-width:150px;">
             </div>

        
        <div class="col-12">
          <label class="form-label fw-semibold">Descripción</label>
          <textarea name="description" rows="3" class="form-control"><?php echo e(old('description', $product->description)); ?></textarea>
        </div>

        
        <div class="col-12 text-end mt-3">
          <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-secondary">Cancelar</a>
          <button type="submit" class="btn btn-primary px-4">Actualizar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('scripts'); ?>
<script>
  // Generar slug automáticamente
  document.addEventListener('DOMContentLoaded', function () {
    const nameInput = document.querySelector('input[name="name"]');
    const slugInput = document.querySelector('input[name="slug"]');
    if (nameInput && slugInput) {
      nameInput.addEventListener('input', function () {
        const slug = this.value
          .toLowerCase()
          .trim()
          .replace(/[^\w\s-]/g, '')
          .replace(/\s+/g, '-')
          .replace(/--+/g, '-');
        slugInput.value = slug;
      });
    }
  });

  // Vista previa de imagen
  function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
      const img = document.getElementById('imagePreview');
      img.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
  }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u591048471/domains/pro-cafes.com/procafes/resources/views/admin/products/products-edit.blade.php ENDPATH**/ ?>