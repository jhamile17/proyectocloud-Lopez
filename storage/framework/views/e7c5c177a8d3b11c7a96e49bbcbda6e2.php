<header class="border-bottom" style="background:#F2DD6C;">
  <nav class="container d-flex align-items-center justify-content-between py-2" style="color:#3E350E;">
    
    <a href="<?php echo e(url('/')); ?>" class="d-flex align-items-center text-decoration-none">
      <img src="<?php echo e(asset('images/logo.png')); ?>" alt="PROCAFES" style="height:36px" onerror="this.style.display='none'">
      <span class="fw-bold ms-2" style="color:#3E350E;">PROCAFES</span>
    </a>

    
    <div class="d-none d-md-flex align-items-center flex-grow-1 mx-3" style="max-width:780px;">
      <ul class="nav me-3">
        <li class="nav-item">
          <a href="<?php echo e(url('/nosotros')); ?>" class="nav-link px-2" style="color:#3E350E;">Nosotros</a>
        </li>
        <li class="nav-item">
          <a href="<?php echo e(url('/ubicacion')); ?>" class="nav-link px-2" style="color:#3E350E;">Ubícanos</a>
        </li>
      </ul>

      
      <form action="<?php echo e(url('/')); ?>" method="GET" class="d-flex flex-grow-1">
        <input type="text"
               name="q"
               value="<?php echo e(request('q')); ?>"
               class="form-control"
               placeholder="Buscar productos..."
               style="background:#FFFBEA;border:1px solid #E0CF61;color:#3E350E;">
        <button class="btn ms-2" type="submit"
                style="background:#E0CF61;color:#3E350E;border:1px solid #D4BF4E;">
          <i class="bi bi-search"></i>
        </button>
      </form>
    </div>

    
    <div class="d-flex align-items-center">
      
      <a href="<?php echo e(url('/wishlist')); ?>" class="btn btn-sm me-2"
         style="background:#E0CF61;border:none;color:#3E350E;">
        <i class="bi bi-heart"></i>
      </a>

      
      <?php $cartCount = session('cart_count', 0); ?>
      <a href="<?php echo e(url('/cart')); ?>" class="btn btn-sm position-relative me-3"
         style="background:#E0CF61;border:none;color:#3E350E;">
        <i class="bi bi-cart"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
          <?php echo e($cartCount); ?>

        </span>
      </a>

      <?php if(auth()->guard()->guest()): ?>
        <a href="<?php echo e(route('login')); ?>" class="btn btn-sm me-2" style="background:#3E350E;color:#FFFFFF;border:none;">
          Iniciar sesión
        </a>
        <a href="<?php echo e(route('register')); ?>" class="btn btn-sm"
           style="background:#DAAD29;color:#3E350E;border:none;">
          Registrarse
        </a>
      <?php endif; ?>

      <?php if(auth()->guard()->check()): ?>
        <div class="dropdown">
          <button class="btn btn-sm dropdown-toggle me-2"
                  data-bs-toggle="dropdown"
                  style="background:#E0CF61;color:#3E350E;border:none;">
            <?php echo e(Str::limit(auth()->user()->name, 16)); ?>

          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form action="<?php echo e(route('logout')); ?>" method="POST"><?php echo csrf_field(); ?>
                <button class="dropdown-item">Salir</button>
              </form>
            </li>
          </ul>
        </div>
      <?php endif; ?>
    </div>
  </nav>
</header>
<?php /**PATH /home/u591048471/domains/pro-cafes.com/procafes/resources/views/partials/header-auth.blade.php ENDPATH**/ ?>