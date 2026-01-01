<section class="w-full">
    <flux:menu.separator />
    <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
        <flux:radio value="light" icon="sun">{{ __('Claro') }}</flux:radio>
        <flux:radio value="dark" icon="moon">{{ __('Oscuro') }}</flux:radio>
        <flux:radio value="system" icon="computer-desktop">{{ __('Sistema') }}</flux:radio>
    </flux:radio.group>
</section>
