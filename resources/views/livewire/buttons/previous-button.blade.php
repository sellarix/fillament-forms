<div>
@if(!$complete)
<button
    wire:click="click"
    class="btn btn-outline btn-lg btn-secondary"
    @if ($currentStep === 1) disabled class="btn-disabled opacity-50 cursor-not-allowed" @endif
>
    Back
</button>
@endif
</div>
