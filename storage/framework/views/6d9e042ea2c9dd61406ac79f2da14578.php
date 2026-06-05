
<?php $__env->startSection('title', 'Mi cuenta'); ?>

<?php $__env->startPush('styles'); ?>
<style>
  .account-cover {
    background: linear-gradient(135deg, #e9f2ff, #f7f9ff);
    height: 120px;
    border-top-left-radius: .75rem;
    border-top-right-radius: .75rem;
  }
  .avatar-wrap {
    margin-top: -42px;
  }
  .avatar {
    width: 84px; height: 84px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,.08);
  }
  .menu-link.active {
    background: #fff6d6; /* tono procafes */
    font-weight: 600;
  }
  .stat-card {
    border: 1px solid #f0f0f0;
  }
  .stat-ico {
    width: 40px; height: 40px;
    display: grid; place-items: center;
    border-radius: .75rem;
    background: #f8fafc;
  }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
  <div class="row g-3">
    
    <aside class="col-12 col-lg-3">
      <div class="card border-0 shadow-sm">
        <div class="account-cover"></div>

        <div class="card-body">
          <div class="d-flex align-items-center gap-3 avatar-wrap">
            <img
              class="avatar"
              src="<?php echo e($user->avatar_url ?? 'https://i.pravatar.cc/160?img=5'); ?>"
              alt="<?php echo e($user->name); ?>"
            >
            <div>
              <div class="fw-semibold"><?php echo e($user->name); ?></div>
              <div class="text-muted small"><?php echo e($user->email); ?></div>
            </div>
          </div>

          <hr>

          <div class="list-group list-group-flush">
            <a class="list-group-item list-group-item-action menu-link active" href="<?php echo e(route('customer.dashboard')); ?>">
              <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>

            <?php if(Route::has('orders.index')): ?>
            <a class="list-group-item list-group-item-action menu-link" href="<?php echo e(route('orders.index')); ?>">
              <i class="bi bi-bag-check me-2"></i> Mis pedidos
            </a>
            <?php endif; ?>

            <?php if(Route::has('wishlist.index')): ?>
            <a class="list-group-item list-group-item-action menu-link" href="<?php echo e(route('wishlist.index')); ?>">
              <i class="bi bi-heart me-2"></i> Wishlist
            </a>
            <?php endif; ?>

            <?php if(Route::has('addresses.index')): ?>
            <a class="list-group-item list-group-item-action menu-link" href="<?php echo e(route('addresses.index')); ?>">
              <i class="bi bi-geo-alt me-2"></i> Direcciones
            </a>
            <?php endif; ?>

            <?php if(Route::has('profile')): ?>
            <a class="list-group-item list-group-item-action menu-link" href="<?php echo e(route('profile')); ?>">
              <i class="bi bi-person-gear me-2"></i> Perfil
            </a>
            <?php endif; ?>

            <a class="list-group-item list-group-item-action menu-link" href="<?php echo e(route('home')); ?>">
              <i class="bi bi-shop me-2"></i> Ver productos
            </a>

            <form action="<?php echo e(route('logout')); ?>" method="POST" class="list-group-item p-0 border-0">
              <?php echo csrf_field(); ?>
              <button class="btn w-100 text-start px-3 py-2">
                <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
              </button>
            </form>
          </div>
        </div>
      </div>
    </aside>

    
    <section class="col-12 col-lg-9">
      
      <div class="card border-0 shadow-sm mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
          <div>
            <h5 class="mb-1">Hola, <?php echo e($user->name); ?> 👋</h5>
            <div class="text-muted">Este es un resumen de tu cuenta.</div>
          </div>
          <div class="d-none d-md-block">
            <a href="<?php echo e(route('home')); ?>" class="btn btn-outline-secondary me-2">
              <i class="bi bi-shop me-1"></i> Ver productos
            </a>
            <?php if(Route::has('checkout')): ?>
              <a href="<?php echo e(route('checkout')); ?>" class="btn btn-primary">
                <i class="bi bi-credit-card me-1"></i> Ir a pagar
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>

      
      <div class="row g-3 mb-3">
        <div class="col-12 col-md-4">
          <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
              <div class="stat-ico"><i class="bi bi-bag fs-5"></i></div>
              <div>
                <div class="text-muted small">Total de pedidos</div>
                <div class="fs-5 fw-semibold"><?php echo e(number_format($stats['totalOrders'] ?? 0)); ?></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
              <div class="stat-ico"><i class="bi bi-hourglass-split fs-5"></i></div>
              <div>
                <div class="text-muted small">Pendientes</div>
                <div class="fs-5 fw-semibold"><?php echo e(number_format($stats['pendingOrders'] ?? 0)); ?></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
              <div class="stat-ico"><i class="bi bi-heart fs-5"></i></div>
              <div>
                <div class="text-muted small">Wishlist</div>
                <div class="fs-5 fw-semibold"><?php echo e(number_format($stats['wishlistCount'] ?? 0)); ?></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      
      <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
          <h6 class="mb-3">Información de la cuenta</h6>
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <div class="border rounded p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="fw-semibold">Contacto</span>
                  <?php if(Route::has('profile')): ?> <a href="<?php echo e(route('profile')); ?>" class="small">Editar</a> <?php endif; ?>
                </div>
                <div class="small text-muted">Nombre</div>
                <div class="mb-2"><?php echo e($user->name); ?></div>
                <div class="small text-muted">Email</div>
                <div><?php echo e($user->email); ?></div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="border rounded p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="fw-semibold">Direcciones</span>
                  <?php if(Route::has('addresses.index')): ?> <a href="<?php echo e(route('addresses.index')); ?>" class="small">Editar</a> <?php endif; ?>
                </div>
                <div class="text-muted">No has configurado una dirección predeterminada.</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      
<div class="card border-0 shadow-sm">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h6 class="mb-0">🧾 Últimos pedidos</h6>
      <?php if(Route::has('orders.index')): ?>
        <a href="<?php echo e(route('orders.index')); ?>" class="small text-decoration-none fw-semibold">
          Ver todos →
        </a>
      <?php endif; ?>
    </div>

    <?php if($recentOrders->isEmpty()): ?>
      <div class="alert alert-secondary mb-0">
        Aún no tienes pedidos.
        <a href="<?php echo e(route('home')); ?>" class="alert-link fw-semibold">
          ¡Empieza a comprar!
        </a>
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table align-middle">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Fecha</th>
              <th>Estado</th>
              <th>Total</th>
              <th class="text-end">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td class="fw-semibold">#<?php echo e($o->id); ?></td>

                <td>
                  <div><?php echo e(optional($o->created_at)->format('d/m/Y')); ?></div>
                  <small class="text-muted">
                    <?php echo e(optional($o->created_at)->format('H:i')); ?>

                  </small>
                </td>

                <td>
                  <?php
                    $badgeClass = match($o->status) {
                      'pending' => 'warning',
                      'paid' => 'primary',
                      'processing' => 'info',
                      'completed' => 'success',
                      'cancelled' => 'danger',
                      default => 'secondary'
                    };
                  ?>

                  <span class="badge text-bg-<?php echo e($badgeClass); ?>">
                    <?php echo e(ucfirst($o->status)); ?>

                  </span>
                </td>

                <td class="fw-semibold text-dark">
                  <?php
                    $total = $o->total ?? $o->total_price ?? 0;
                
                    // 🔥 si está en 0, calcular desde DB
                    if ($total == 0) {
                        $items = \Illuminate\Support\Facades\DB::table('order_items')
                            ->join('products', 'products.id', '=', 'order_items.product_id')
                            ->where('order_items.order_id', $o->id)
                            ->select(
                                'order_items.quantity',
                                'order_items.price',
                                'order_items.unit_price',
                                'products.price as product_price'
                            )
                            ->get();
                
                        $total = 0;
                        foreach ($items as $it) {
                            $qty   = $it->quantity ?? 1;
                            $price = $it->price ?? $it->unit_price ?? $it->product_price ?? 0;
                            $total += ($price * $qty);
                        }
                    }
                  ?>
                
                  S/ <?php echo e(number_format($total, 2)); ?>

                </td>

                <td class="text-end">
                  <div class="d-flex justify-content-end gap-2">

                    
                    <?php if(Route::has('orders.show')): ?>
                      <a href="<?php echo e(route('orders.show', $o)); ?>"
                         class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-eye"></i>
                      </a>
                    <?php endif; ?>

                    
                    <?php if(in_array($o->status, ['paid','shipped','completed','success'])): ?>
                      <a href="<?php echo e(route('customer.boleta.download', $o->id)); ?>"
                         class="btn btn-sm btn-primary">
                         <i class="bi bi-file-earmark-pdf"></i> Boleta
                      </a>
                    <?php else: ?>
                      <span class="text-muted small">Pago pendiente</span>
                    <?php endif; ?>

                  </div>
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

    </section>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u591048471/domains/pro-cafes.com/procafes/resources/views/customer/dashboard.blade.php ENDPATH**/ ?>