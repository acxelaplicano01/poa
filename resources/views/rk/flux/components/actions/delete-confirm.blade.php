<flux:modal name="confirm-modal" class="min-w-[22rem]">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">
                Eliminar?
            </flux:heading>
            <flux:text class="mt-2">
                <p>Estás a punto de eliminar.</p>
                <p>Esta acción no se puede deshacer.</p>
            </flux:text>
        </div>
        <div class="flex gap-2">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="ghost">
                    Cancel
                </flux:button>
            </flux:modal.close>
            <flux:button type="submit" variant="danger">
                Delete User
            </flux:button>
        </div>
    </div>
</flux:modal>
