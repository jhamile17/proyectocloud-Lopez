
<?php $__env->startSection('title','Orden #'.$order->id.' | PROCAFES'); ?>

<?php $__env->startPush('styles'); ?>
<style>
  .card{border:0;box-shadow:0 6px 20px rgba(15,23,42,.06)}
  .meta dt{color:#6b7280;font-weight:600}
  .badge-status{font-weight:600;letter-spacing:.2px}
  .badge-paid{background:#dcfce7;color:#065f46}
  .badge-proc{background:#fff7ed;color:#9a3412}
  .badge-cancel{background:#fee2e2;color:#7f1d1d}
  .table tfoot th{background:#fafafa}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('admin-content'); ?>
<?php
  // Traducción + clase visual del estado
  $statusLabel = [
    'paid'       => 'Pagado',
    'shipped'    => 'Enviado',
    'completed'  => 'Completado',
    'success'    => 'Completado',
    'processing' => 'Procesando',
    'pending'    => 'Pendiente',
    'cancelled'  => 'Cancelado',
    'canceled'   => 'Cancelado',
    'failed'     => 'Fallido',
  ];
  $statusClass = [
    'paid'       => 'badge-paid',
    'shipped'    => 'badge-paid',
    'completed'  => 'badge-paid',
    'success'    => 'badge-paid',
    'processing' => 'badge-proc',
    'pending'    => 'badge-proc',
    'cancelled'  => 'badge-cancel',
    'canceled'   => 'badge-cancel',
    'failed'     => 'badge-cancel',
  ];

  $kStatus = strtolower($order->status ?? '');
  $label   = $statusLabel[$kStatus] ?? ucfirst($kStatus ?: '—');
  $cls     = $statusClass[$kStatus] ?? 'bg-secondary text-white';

  // Total tolerante (según tu BD puede ser total_price o total)
  $total = $order->total_price ?? $order->total ?? 0;

  $fmt = fn($n) => number_format((float)$n, 2);
?>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-12 col-xl-10">

      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Orden #<?php echo e($order->id); ?></h3>
        <div class="d-flex gap-2">
          <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver
          </a>
          <button class="btn btn-outline-primary btn-sm" onclick="window.print()">
            <i class="bi bi-printer"></i> Imprimir
          </button>
        </div>
      </div>

      <div class="row g-3">
        
        <div class="col-12 col-lg-6">
          <div class="card h-100">
            <div class="card-body">
              <h6 class="text-muted mb-3">Resumen</h6>
              <dl class="row meta mb-0">
                <dt class="col-4">Estado</dt>
                <dd class="col-8">
                  <span class="badge badge-status <?php echo e($cls); ?>"><?php echo e($label); ?></span>
                </dd>

                <dt class="col-4">Total</dt>
                <dd class="col-8">S/ <?php echo e($fmt($total)); ?></dd>

                <dt class="col-4">Fecha</dt>
                <dd class="col-8"><?php echo e(\Illuminate\Support\Carbon::parse($order->created_at)->format('d/m/Y H:i')); ?></dd>
              </dl>
            </div>
          </div>
        </div>

        
        <div class="col-12 col-lg-6">
          <div class="card h-100">
            <div class="card-body">
              <h6 class="text-muted mb-3">Cliente</h6>
              <dl class="row meta mb-0">
                <dt class="col-4">Nombre</dt>
                <dd class="col-8"><?php echo e($order->customer_name ?? '—'); ?></dd>

                <dt class="col-4">Email</dt>
                <dd class="col-8"><?php echo e($order->customer_email ?? '—'); ?></dd>
              </dl>
            </div>
          </div>
        </div>

        
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <h6 class="text-muted mb-3">Ítems</h6>
              <div class="table-responsive">
                <table class="table align-middle">
                  <thead class="table-light">
                    <tr>
                      <th>#</th>
                      <th>Producto</th>
                      <th class="text-end" style="width:120px">Cant.</th>
                      <th class="text-end" style="width:160px">P. Unit.</th>
                      <th class="text-end" style="width:160px">Subtotal</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                      <?php
                        // Campos esperados desde el controlador:
                        // product_name, quantity, unit_price, subtotal
                        $qty  = (float)($it->quantity ?? 0);
                        $unit = (float)($it->unit_price ?? $it->price ?? 0);
                        $sub  = (float)($it->subtotal ?? ($qty * $unit));
                      ?>
                      <tr>
                        <td><?php echo e($i+1); ?></td>
                        <td><?php echo e($it->product_name ?? ('Prod. #'.$it->product_id)); ?></td>
                        <td class="text-end"><?php echo e($fmt($qty)); ?></td>
                        <td class="text-end">S/ <?php echo e($fmt($unit)); ?></td>
                        <td class="text-end">S/ <?php echo e($fmt($sub)); ?></td>
                      </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                      <tr>
                        <td colspan="5" class="text-center text-muted py-4">Sin ítems registrados.</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>

                  <?php if(count($items)): ?>
                    <tfoot>
                      <tr>
                        <th colspan="4" class="text-end">Total</th>
                        <th class="text-end">S/ <?php echo e($fmt($total)); ?></th>
                      </tr>
                    </tfoot>
                  <?php endif; ?>
                </table>
              </div>
            </div>
          </div>
        </div>

      </div> 
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u591048471/domains/pro-cafes.com/procafes/resources/views/admin/orders/show.blade.php ENDPATH**/ ?>