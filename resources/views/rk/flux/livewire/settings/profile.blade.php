<section class="w-full">
    <x-rk.flux::components.settings.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">
        <form  class="my-6 w-full space-y-6">
            <flux:input wire:model="name" disabled :label="__('Name')" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" disabled :label="__('Email')" type="email" required autocomplete="email" />

               
            </div>

            
        </form>

        <livewire:settings.delete-user-form />
    </x-rk.flux::components.settings.layout>
</section>
