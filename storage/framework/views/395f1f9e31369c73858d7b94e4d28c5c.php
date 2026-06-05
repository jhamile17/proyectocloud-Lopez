<?php
use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

new #[Layout('components.layouts.app')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $address = '';
    public ?string $document_type = '';
    public ?string $document_number = null;
    public string $password = '';
    public string $password_confirmation = '';

    protected function rules(): array
    {
        return [
            'name'            => ['required','string','min:3','max:255'],
            'email'           => ['required','email','max:255','unique:users,email'],
            'phone'           => ['required','regex:/^[0-9]{6,15}$/'],
            'address'         => ['required','string','min:5','max:255'],
            'document_type'   => ['nullable','in:dni,ruc'],
            'document_number' => ['nullable','string','max:20'],
            'password'        => ['required','min:6','same:password_confirmation'],
        ];
    }

    public function register()
    {
        $validated = $this->validate();

        // Ajusta longitud válida de documento según tipo
        if ($validated['document_type'] === 'dni' && strlen($validated['document_number']) !== 8) {
            $this->addError('document_number', 'El DNI debe tener 8 dígitos.');
            return;
        }
        if ($validated['document_type'] === 'ruc' && strlen($validated['document_number']) !== 12) {
            $this->addError('document_number', 'El RUC debe tener 12 dígitos.');
            return;
        }

        $user = User::create([
            'name'            => $validated['name'],
            'email'           => $validated['email'],
            'phone'           => $validated['phone'],
            'address'         => $validated['address'],
            'document_type'   => $validated['document_type'],
            'document_number' => $validated['document_number'],
            'password'        => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        return redirect()->route('home')->with('status','Registro completado correctamente.');
    }
};
?>

<?php $__env->startPush('styles'); ?>
<style>
  main.container { max-width: 100% !important; padding: 0 !important; }

  .register-container{
    display:flex;
    min-height:calc(100vh - 72px);
    background:#fff;
  }

  .register-image{
    flex:1;
    background:url("<?php echo e(asset('images/cafe_register.jpg')); ?>") center center/cover no-repeat;
  }

  .register-form{
    flex:1;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:24px;
    background:#fff;
  }

  .register-card{
    width:100%;
    max-width:520px;
  }

  .register-card .form-control,
  .register-card .form-select { padding:.55rem .75rem; }

  @media (max-width: 991.98px){
    .register-container{ flex-direction:column; }
    .register-image{ height:32vh; min-height:220px; flex:none; }
    .register-form{ padding:16px; }
  }
</style>
<?php $__env->stopPush(); ?>

<div class="register-container" x-data="registerForm()">
  
  <div class="register-image" aria-hidden="true"></div>

  
  <div class="register-form">
    <div class="card border-0 shadow-sm p-4 register-card">
      <h4 class="mb-1 text-center">Crea tu cuenta</h4>
      <p class="text-muted text-center mb-4">Únete a <strong>PROCAFES</strong></p>

      <form wire:submit.prevent="register" novalidate>
        
        <div class="mb-3">
          <label class="form-label">Nombre completo</label>
          <input
            type="text"
            wire:model.lazy="name"
            class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            required
            minlength="3"
            oninvalid="this.setCustomValidity(this.validity.valueMissing ? 'Completa este campo' : 'Mínimo 3 caracteres')"
            oninput="this.setCustomValidity('')"
          >
          <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        
        <div class="mb-3">
          <label class="form-label">Correo electrónico</label>
          <input
            type="email"
            wire:model.lazy="email"
            class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            required
            oninvalid="this.setCustomValidity(this.validity.valueMissing ? 'Completa este campo' : 'Ingresa un correo válido')"
            oninput="this.setCustomValidity('')"
          >
          <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        
        <div class="mb-3">
          <label class="form-label">Teléfono</label>
          <input
            type="tel"
            wire:model.lazy="phone"
            class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            inputmode="numeric"
            pattern="\d{6,15}"
            maxlength="15"
            required
            oninput="this.value=this.value.replace(/[^0-9]/g,''); this.setCustomValidity('')"
            oninvalid="this.setCustomValidity(this.validity.valueMissing ? 'Completa este campo' : 'Solo números (6 a 15 dígitos)')"
          >
          <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        
        <div class="mb-3">
          <label class="form-label">Dirección</label>
          <textarea
            wire:model.lazy="address"
            class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            rows="2"
            required
            minlength="5"
            oninvalid="this.setCustomValidity(this.validity.valueMissing ? 'Completa este campo' : 'Mínimo 5 caracteres')"
            oninput="this.setCustomValidity('')"
          ></textarea>
          <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        
        <div class="mb-3">
          <label class="form-label">Tipo de documento</label>
          <select
            id="docType"
            wire:model="document_type"
            class="form-select <?php $__errorArgs = ['document_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            required
            oninvalid="this.setCustomValidity('Selecciona un tipo de documento')"
            oninput="this.setCustomValidity('')"
          >
            <option value="" disabled selected>Selecciona...</option>
            <option value="dni">DNI</option>
            <option value="ruc">RUC</option>
          </select>
          <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['document_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        
        <div class="mb-3">
          <label class="form-label">Número de documento</label>
          <input
            type="text"
            wire:model.lazy="document_number"
            class="form-control <?php $__errorArgs = ['document_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            :minlength="docMin"
            :maxlength="docMax"
            required
            oninput="this.setCustomValidity('')"
            :oninvalid="'this.setCustomValidity(this.validity.valueMissing ? \'Completa este campo\' : docHint)'" 
          >
          <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['document_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        
        <div class="mb-3">
          <label class="form-label">Contraseña</label>
          <input
            type="password"
            wire:model.lazy="password"
            class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            required
            minlength="6"
            oninvalid="this.setCustomValidity(this.validity.valueMissing ? 'Completa este campo' : 'Mínimo 6 caracteres')"
            oninput="this.setCustomValidity('')"
          >
          <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <div class="mb-3">
          <label class="form-label">Confirmar contraseña</label>
          <input
            type="password"
            wire:model.lazy="password_confirmation"
            class="form-control"
            required
            oninvalid="this.setCustomValidity('Completa este campo')"
            oninput="this.setCustomValidity('')"
          >
        </div>

        
        <button type="submit" class="btn btn-dark w-100 py-2">Crear cuenta</button>

        <div class="text-center text-muted small my-3">o</div>
        <a href="<?php echo e(route('auth.google.redirect')); ?>" class="btn btn-light w-100 border">
          <i class="bi bi-google me-2"></i> Registrarme con Google
        </a>

        <div class="text-center mt-3">
          <small>¿Ya tienes cuenta? <a href="<?php echo e(route('login')); ?>">Inicia sesión</a></small>
        </div>
      </form>
    </div>
  </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
  // Alpine helpers para longitud dinámica de DNI/RUC
  function registerForm() {
    return {
      docMin: 8,
      docMax: 8,
      get docHint() { return this.docMax === 12 ? 'Debe tener 12 dígitos.' : 'Debe tener 8 dígitos.'; },
      syncDocLength() {
        const type = document.getElementById('docType').value;
        if (type === 'ruc') { this.docMin = 12; this.docMax = 12; }
        else { this.docMin = 8; this.docMax = 8; }
      },
      init() { this.syncDocLength(); }
    }
  }
</script>
<?php $__env->stopPush(); ?><?php /**PATH /home/u591048471/domains/pro-cafes.com/procafes/resources/views/livewire/pages/auth/register.blade.php ENDPATH**/ ?>