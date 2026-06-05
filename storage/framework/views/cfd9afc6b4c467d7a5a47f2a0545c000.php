<?php
    use Livewire\Volt\Component;
    use Livewire\Attributes\Layout;
    use Illuminate\Support\Facades\Password;

    new #[Layout('components.layouts.app')] class extends Component
    {
        public array $state = ['email' => ''];

        public function sendLink()
        {
            $data = validator($this->state, [
                'email' => ['required','email','exists:users,email'],
            ])->validate();

            $status = Password::sendResetLink(['email' => $data['email']]);
            session()->flash('status', __($status));
        }
    };
?>

<div class="row g-4 align-items-stretch">
  
  <div class="col-lg-7 d-none d-lg-block">
    <div class="h-100 rounded-3 overflow-hidden">
      <img src="<?php echo e(asset('images/auth-hero.jpg')); ?>"
           alt="Recuperar contraseña - PROCAFES"
           class="w-100 h-100"
           style="object-fit: cover; min-height: 560px;">
    </div>
  </div>

  
  <div class="col-12 col-lg-5">
    <div class="card shadow-sm h-100 rounded-4 border-0">
      <div class="card-body p-4 p-lg-5">
        <h2 class="h4 mb-1">¿Olvidaste tu contraseña?</h2>
        <p class="text-muted mb-4">Te enviaremos un enlace para restablecerla.</p>

        <!--[if BLOCK]><![endif]--><?php if(session('status')): ?>
          <div class="alert alert-success" role="alert"><?php echo e(session('status')); ?></div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <form wire:submit="sendLink" novalidate>
          <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input id="email" type="email"
                   wire:model.defer="state.email"
                   required autocomplete="email" autofocus
                   class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   placeholder="tucorreo@dominio.com">
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-procafes-dark btn-lg" wire:loading.attr="disabled">
              Enviar enlace
            </button>
          </div>
        </form>

        <p class="text-center mt-3 mb-0">
          <a href="<?php echo e(route('login')); ?>" class="link-procafes text-decoration-none">Volver a iniciar sesión</a>
        </p>
      </div>
    </div>
  </div>
</div><?php /**PATH /home/u591048471/domains/pro-cafes.com/procafes/resources/views/livewire/pages/auth/forgot-password.blade.php ENDPATH**/ ?>