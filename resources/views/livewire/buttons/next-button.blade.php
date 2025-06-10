<div class="flex">
    @if($currentStep && $totalSteps && !$complete)
        @if ($currentStep < $totalSteps)
            <button
                wire:click="click"
                class="btn btn-primary btn-lg items-center flex justify-center group"
            >
                <span>Next</span>
                <i class="fas fa-arrow-right ml-2 text-sm transform transition-transform duration-200 group-hover:translate-x-1"></i>
            </button>
        @else
            <button
                wire:click="click"
                class="btn btn-primary btn-lg"
            >
                Submit
            </button>
        @endif
    @endif
</div>
