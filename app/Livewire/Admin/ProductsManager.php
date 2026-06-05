<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Validation\Rule;

class ProductsManager extends Component
{
    use WithPagination;

    public string $q = '';
    public string $status = '';
    public int $perPage = 10;

    // formulario
    public ?int $product_id = null;
    public $name = '';
    public $price = '';
    public $stock = '';
    public $category_id = '';
    public $brand_id = '';
    public $is_active = true;
    public $image = ''; // si solo guardas path (sin upload aquí)

    protected $queryString = ['q', 'status'];

    protected function rules()
    {
        return [
            'name'        => ['required','string','max:120'],
            'price'       => ['required','numeric','min:0'],
            'stock'       => ['required','integer','min:0'],
            'category_id' => ['required','exists:categories,categories_id'], // ajusta PK
            'brand_id'    => ['nullable','exists:brands,brands_id'],         // ajusta PK
            'is_active'   => ['boolean'],
            'image'       => ['nullable','string','max:255'],
        ];
    }

    public function updatingQ(){ $this->resetPage(); }
    public function updatingStatus(){ $this->resetPage(); }

    public function create()
    {
        $this->resetForm();
        $this->dispatch('open-modal','#productModal');
    }

    public function edit(int $id)
    {
        $p = Product::findOrFail($id);
        $this->product_id = $p->id;
        $this->name       = $p->name;
        $this->price      = $p->price;
        $this->stock      = $p->stock;
        $this->category_id= $p->category_id ?? $p->categories_id ?? null;
        $this->brand_id   = $p->brand_id ?? $p->brands_id ?? null;
        $this->is_active  = (bool)($p->is_active ?? $p->status ?? 1);
        $this->image      = $p->image ?? '';

        $this->dispatch('open-modal','#productModal');
    }

    public function store()
    {
        $data = $this->validate();
        $data['status'] = $this->is_active ? 1 : 0;

        if ($this->product_id) {
            Product::findOrFail($this->product_id)->update($data);
            $msg = 'Producto actualizado';
        } else {
            Product::create($data);
            $msg = 'Producto creado';
        }

        $this->dispatch('close-modal','#productModal');
        $this->resetForm();
        session()->flash('success',$msg);
    }

    public function confirmDelete(int $id)
    {
        $this->product_id = $id;
        $this->dispatch('open-modal','#confirmDeleteModal');
    }

    public function destroy()
    {
        if ($this->product_id) {
            Product::find($this->product_id)?->delete();
            $this->reset('product_id');
            session()->flash('success','Producto eliminado');
        }
        $this->dispatch('close-modal','#confirmDeleteModal');
    }

    public function resetForm()
    {
        $this->reset(['product_id','name','price','stock','category_id','brand_id','is_active','image']);
        $this->is_active = true;
    }

    public function render()
    {
        $categories = Category::select('categories_id as id','name')->orderBy('name')->get();
        $brands     = Brand::select('brands_id as id','name')->orderBy('name')->get();

        $query = Product::query()
            ->with(['category','brand'])
            ->when($this->q, fn($q)=>$q->where('name','like',"%{$this->q}%"))
            ->when($this->status !== '',
                fn($q)=>$q->where(function($qq){
                    $val = $this->status === '1' ? 1 : 0;
                    $qq->where('status',$val)->orWhere('is_active',$val);
                })
            )
            ->orderByDesc('id');

        $products = $query->paginate($this->perPage);

        return view('livewire.admin.products-manager', compact('products','categories','brands'));
    }
}
