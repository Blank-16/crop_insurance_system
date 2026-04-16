<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Proposer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-alert />

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <x-card title="Total Plans Created" :value="$plansCount" color="purple" />
                <x-card title="Active Policies" :value="$policiesCount" color="blue" />
                <x-card title="Claims Received" :value="$claimsCount" color="red" />
            </div>
        </div>
    </div>
</x-app-layout>
