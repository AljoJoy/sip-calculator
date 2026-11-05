<?php

use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public $monthlyInvestment = 5000;
    public $annualReturn = 12;
    public $years = 10;

    #[Computed]
    public function result()
    {
        $p = (float) $this->monthlyInvestment;
        $rate = (float) $this->annualReturn;
        $years = (int) $this->years;

        // Return null on invalid input so the view can show nothing
        if ($p <= 0 || $rate <= 0 || $years <= 0 || $rate > 50 || $years > 50) {
            return null;
        }

        $i = $rate / 12 / 100;
        $n = $years * 12;

        $maturity = $p * ((pow(1 + $i, $n) - 1) / $i) * (1 + $i);
        $invested = $p * $n;

        return [
            'invested' => $invested,
            'returns'  => $maturity - $invested,
            'maturity' => $maturity,
            'investedPct' => round(($invested / $maturity) * 100, 1),
        ];
    }
};
?>

<div class="max-w-3xl mx-auto p-6">
    <h1 class="text-3xl font-bold text-center text-blue-900 mt-5 mb-8">SIP Calculator</h1>

    <div class="flex justify-between">
        {{-- Inputs --}}
        <div class="p-6 space-y-6">
            <div>
                <label class="flex justify-between text-sm font-medium text-gray-700">
                    <span>Monthly investment</span>
                    <span class="text-indigo-600">₹{{ number_format((float) $monthlyInvestment) }}</span>
                </label>
                <input type="range" min="500" max="100000" step="500"
                       wire:model.live="monthlyInvestment" class="w-full h-2 rounded-lg appearance-none cursor-pointer bg-gray-400 accent-indigo-600">
                <input type="number" wire:model.live.debounce.400ms="monthlyInvestment"
                       class="mt-2 w-full border-gray-400 rounded-lg px-3 py-2">
            </div>

            <div>
                <label class="flex justify-between text-sm font-medium text-gray-700">
                    <span>Expected annual return</span>
                    <span class="text-indigo-600">{{ $annualReturn }}%</span>
                </label>
                <input type="range" min="1" max="30" step="0.5"
                       wire:model.live="annualReturn" class="w-full h-2 rounded-lg appearance-none cursor-pointer bg-gray-400 accent-indigo-600">
                <input type="number" step="0.1" wire:model.live.debounce.400ms="annualReturn"
                       class="mt-2 w-full border-gray-400 rounded-lg px-3 py-2">
            </div>

            <div>
                <label class="flex justify-between text-sm font-medium text-gray-700">
                    <span>Time period</span>
                    <span class="text-indigo-600">{{ $years }} years</span>
                </label>
                <input type="range" min="1" max="40" step="1"
                       wire:model.live="years" class="w-full h-2 rounded-lg appearance-none cursor-pointer bg-gray-400 accent-indigo-600">
                <input type="number" wire:model.live.debounce.400ms="years"
                       class="mt-2 w-full border-gray-400 rounded-lg px-3 py-2">
            </div>
        </div>

        {{-- Results --}}
        <div class="p-6">
            @if ($this->result)
                <dl class="space-y-4">
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Invested amount</dt>
                        <dd class="font-semibold">₹{{ number_format($this->result['invested']) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-600">Estimated returns</dt>
                        <dd class="font-semibold text-green-600">₹{{ number_format($this->result['returns']) }}</dd>
                    </div>
                    <div class="flex justify-between border-t pt-4">
                        <dt class="font-medium text-gray-800">Total value</dt>
                        <dd class="text-xl font-bold text-indigo-600">₹{{ number_format($this->result['maturity']) }}</dd>
                    </div>
                </dl>

                {{-- Invested vs returns bar --}}
                <div class="mt-6">
                    <div class="flex h-4 rounded-full overflow-hidden bg-green-400">
                        <div class="bg-indigo-500" style="width: {{ $this->result['investedPct'] }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mt-2">
                        <span>Invested {{ $this->result['investedPct'] }}%</span>
                        <span>Returns {{ round(100 - $this->result['investedPct'], 1) }}%</span>
                    </div>
                </div>
            @else
                <p class="text-gray-500 text-center">Enter valid values to see results.</p>
            @endif
        </div>
    </div>

    <p class="text-xs text-gray-500 text-center mt-6">
        Returns are estimates and not guaranteed. Actual mutual fund returns vary with the market.
    </p>
</div>