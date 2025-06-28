<x-error-modal 
    wire:model="showErrorModal"
    title="Error"
    :message="$errorMessage"
    close-method="hideError"
/>
