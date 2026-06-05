
<?php $__env->startSection('title','Órdenes | PROCAFES'); ?>

<?php $__env->startPush('styles'); ?>
<style>
  /* Contenedor */
  .page-wrap{max-width:1200px;margin-inline:auto}
  /* Toolbar filtros */
  .toolbar .form-control,.toolbar .form-select{height:42px}
  /* Tarjeta tabla */
  .card-table{border:0;box-shadow:0 6px 20px rgba(15,23,42,.06)}
  /* Tabla */
  .table thead th{font-weight:600;color:#6b7280;background:#f8fafc;border-bottom:1px solid #e5e7eb}
  .table tbody td{vertical-align:middle;border-color:#f1f5f9}
  .table-hover tbody tr:hover{background:#f9fafb}
  .col-money{width:140px}
  .col-id{width:72px}
  .col-date{width:170px}
  .col-actions{width:88px}
  .col-status{width:220px}
  /* Estado */
  .badge-status{font-weight:600;letter-spacing:.2px}
  .badge-paid{background:#dcfce7;color:#065f46}
  .badge-proc{background:#fff7ed;color:#9a3412}
  .badge-cancel{background:#fee2e2;color:#7f1d1d}
  /* Cabecera pegajosa */
  .sticky-head thead th{position:sticky;top:0;z-index:1}
  /* Empty state */
  .empty{padding:48px 16px;color:#64748b}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('admin-content'); ?>
<?php
  // Mapeos de estado → etiqueta/clase
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

  // Normalizador para el total (total_price | total)
  $fmt = fn($n) => number_format((float)$n, 2);
?>

<div class="container-fluid">
  <div class="page-wrap">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h3 class="mb-0">Órdenes</h3>
      <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-speedometer2 me-1"></i> Volver al panel
      </a>
    </div>

    <?php if(session('status')): ?>  <div class="alert alert-success py-2 px-3"><?php echo e(session('status')); ?></div> <?php endif; ?>
    <?php if(session('warning')): ?> <div class="alert alert-warning py-2 px-3"><?php echo e(session('warning')); ?></div> <?php endif; ?>

    
    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body">
        <form class="row g-2 toolbar" method="GET">
          <div class="col-md-5">
            <input type="text" name="q" class="form-control"
                   placeholder="Buscar cliente, email o #ID…"
                   value="<?php echo e($q); ?>">
          </div>
          <div class="col-md-4">
            <select name="status" class="form-select">
              <option value="">Todos los estados</option>
              <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $st): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $k = strtolower($st); ?>
                <option value="<?php echo e($st); ?>" <?php if($status===$st): echo 'selected'; endif; ?>>
                  <?php echo e($statusLabel[$k] ?? strtoupper($st)); ?>

                </option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
          <div class="col-md-3 d-grid d-sm-flex gap-2">
            <button class="btn btn-dark flex-fill">
              <i class="bi bi-search me-1"></i> Filtrar
            </button>
            <?php if(request()->hasAny(['q','status']) && (request('q') || request('status'))): ?>
              <a class="btn btn-outline-secondary flex-fill" href="<?php echo e(route('admin.orders.index')); ?>">
                <i class="bi bi-x-circle me-1"></i> Limpiar
              </a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>

    
    <div class="card card-table">
      <div class="table-responsive">
        <table class="table table-hover align-middle sticky-head mb-0">
          <thead>
            <tr>
              <th class="col-id">#</th>
              <th>Cliente</th>
              <th class="col-status">Estado</th>
              <th class="text-end col-money">Total</th>
              <th class="col-date">Fecha</th>
              <th class="col-actions"></th>
            </tr>
          </thead>
          <tbody>
          <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
              $key  = strtolower($o->status ?? '');
              $tag  = $statusLabel[$key] ?? ucfirst($key ?: '—');
              $cls  = $statusClass[$key] ?? 'bg-secondary text-white';
              $tot  = $o->total_price ?? $o->total ?? 0;
            ?>
            <tr>
              <td class="text-muted">#<?php echo e($o->id); ?></td>
              <td>
                <div class="fw-semibold"><?php echo e($o->customer_name ?? '—'); ?></div>
                <small class="text-muted"><?php echo e($o->customer_email ?? '—'); ?></small>
              </td>

              
              <td>
                <div class="d-flex align-items-center gap-2">
                  <span class="badge badge-status <?php echo e($cls); ?>"><?php echo e($tag); ?></span>
                  <form action="<?php echo e(route('admin.orders.status', $o->id)); ?>" method="POST" class="d-inline status-form">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <select name="status" class="form-select form-select-sm status-select">
                      <option value="pending"   <?php if($o->status === 'pending'): echo 'selected'; endif; ?>>Pendiente</option>
                      <option value="paid"      <?php if($o->status === 'paid'): echo 'selected'; endif; ?>>Pagado</option>
                      <option value="cancelled" <?php if($o->status === 'cancelled' || $o->status === 'canceled'): echo 'selected'; endif; ?>>Cancelado</option>
                    </select>
                    <noscript><button class="btn btn-sm btn-outline-secondary ms-2">Guardar</button></noscript>
                  </form>
                </div>
              </td>

              <td class="text-end">S/ <?php echo e($fmt($tot)); ?></td>
              <td><?php echo e(\Illuminate\Support\Carbon::parse($o->created_at)->format('d/m/Y H:i')); ?></td>
              <td class="text-end">
                <a href="<?php echo e(route('admin.orders.show', $o->id)); ?>" class="btn btn-sm btn-outline-primary" title="Ver">
                  <i class="bi bi-eye"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
              <td colspan="6" class="text-center empty">
                <i class="bi bi-inbox me-2"></i> No hay órdenes que coincidan con el filtro.
              </td>
            </tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>

      <?php if($orders->hasPages()): ?>
        <div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3">
          <div class="small text-muted">
            Mostrando <?php echo e($orders->firstItem()); ?>–<?php echo e($orders->lastItem()); ?> de <?php echo e($orders->total()); ?> resultados
          </div>
          <?php echo e($orders->onEachSide(1)->links()); ?>

        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  // Auto-submit del form al cambiar el estado
  document.querySelectorAll('.status-select').forEach(function(sel){
    sel.addEventListener('change', function(){
      this.closest('form').submit();
    });
  });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u591048471/domains/pro-cafes.com/procafes/resources/views/admin/orders/index.blade.php ENDPATH**/ ?>