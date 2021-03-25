<?php

namespace App\Http\Livewire\ServiceRequest;

use Livewire\Component;

class PaymentReadyToggle extends Component
{
    public $payment_ready;
    public $fulfillment;

    public function save()
    {
        $this->fulfillment->update(['payment_ready' => $this->payment_ready]);
    }

    public function render()
    {
        $this->payment_ready = $this->fulfillment->payment_ready;
        return view('livewire.service-request.payment-ready-toggle');
    }
}