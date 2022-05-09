<?php

namespace App\Http\Livewire\Warehouse\Control;

use App\Models\Warehouse\Category;
use App\Models\Warehouse\ControlItem;
use App\Models\Warehouse\Product;
use Livewire\Component;

class ControlReceivingAddProduct extends Component
{
    public $store;
    public $control;
    public $type;
    public $unspsc_product_id;
    public $search_product;
    public $description;
    public $wre_product_id;
    public $category_id;
    public $quantity;
    public $barcode;
    public $categories;

    protected $listeners = [
        'myProductId'
    ];

    public function rules()
    {
        return [
            'unspsc_product_id' => 'nullable|required_if:type,1|exists:unspsc_products,id',
            'description'       => 'nullable|required_if:type,1|string|min:1|max:255',
            'barcode'           => 'nullable|required_if:type,1|string|min:1|max:255',
            'wre_product_id'    => 'nullable|required_if:type,0|exists:wre_products,id',
            'category_id'       => 'nullable|exists:wre_categories,id',
            'quantity'          => 'required|integer|min:1',
        ];
    }

    public function mount()
    {
        $this->type = 1;
        $this->products = collect([]);
        $this->categories = $this->store->categories->sortBy('name');
    }

    public function render()
    {
        return view('livewire.warehouse.control.control-receiving-add-product');
    }

    public function addProduct()
    {
        $dataValidated = $this->validate();

        if($this->type)
        {
            $product = Product::create([
                'name' => $dataValidated['description'],
                'barcode' => $dataValidated['barcode'],
                'store_id' => $this->store->id,
                'unspsc_product_id' => $dataValidated['unspsc_product_id'],
                'category_id' => $dataValidated['category_id'] ? $dataValidated['category_id'] : null,
            ]);
        }
        else
        {
            $product = Product::find($dataValidated['wre_product_id']);
        }

        $lastBalance = Product::lastBalance($product, $this->control->program);

        $balance = $dataValidated['quantity'] + $lastBalance;

        $dataValidated['balance'] = $balance;
        $dataValidated['control_id'] = $this->control->id;
        $dataValidated['program_id'] = $this->control->program_id;
        $dataValidated['product_id'] = $product->id;

        $controlItem = ControlItem::query()
            ->whereControlId($this->control->id)
            ->whereProgramId($this->control->program_id)
            ->whereProductId($product->id);

        if($controlItem->exists())
        {
            $controlItem = clone $controlItem->first();
            $controlItem->update([
                'quantity'  => $controlItem->quantity + $dataValidated['quantity'],
                'balance'   => $controlItem->quantity + $dataValidated['quantity'],
            ]);
        }
        else
        {
            ControlItem::create($dataValidated);
        }

        $this->emit('refreshControlProductList');
        $this->resetInput();
    }

    public function resetInput()
    {
        $this->type = 1;
        $this->search_product = null;
        $this->unspsc_product_id = null;
        $this->wre_product_id = null;
        $this->quantity = null;
        $this->barcode = null;
        $this->description = null;
        $this->emit('onClearSearch');
    }

    public function updatedSearchProduct()
    {
        $this->emit('searchProduct', $this->search_product);
    }

    public function myProductId($value)
    {
        $this->unspsc_product_id = $value;
    }

    public function updatedWreProductId()
    {
        $this->barcode = '';
        if($this->type == 0 && $this->wre_product_id)
        {
            $product = Product::find($this->wre_product_id);
            $this->barcode = $product->barcode;
        }
    }

    public function updatedType()
    {
        if($this->type)
        {
            $this->barcode = '';
        }
    }
}
