<div wire:key="{{ 'form_' . $form->slug }}">
    <livewire:dynamic-component :component="$component" :form="$form" :thank-you-content="$slot->toHtml()"/>
</div>
<script>
    window.addEventListener('form_submitted_success', function (event) {
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push(event.detail[0].dataLayer);
    });
</script>
