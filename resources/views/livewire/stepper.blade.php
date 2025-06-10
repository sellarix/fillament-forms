<div class="flex items-center">
    <div class="flex space-x-2">
        @for($i =1; $i <= $totalSteps; $i++)
            <div
                class="w-8 h-8 rounded-full {{$currentStep >= $i ? 'bg-primary-100 text-primary-700 current-step' : 'bg-gray-100 text-gray-900'}} flex items-center justify-center text-xs font-medium ">{{ $i }}</div>
        @endfor
    </div>
</div>
