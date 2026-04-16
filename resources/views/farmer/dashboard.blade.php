<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Farmer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-alert />

            @if(!auth()->user()->farmerProfile)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Incomplete Profile!</strong> Please complete your profile to apply for insurance policies. 
                                <a href="{{ route('farmer.profile') }}" class="font-medium underline text-yellow-700 hover:text-yellow-600">Complete now &rarr;</a>
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <x-card title="Total Policies" :value="$policiesCount" color="indigo" />
                <x-card title="Active Policies" :value="$activePoliciesCount" color="green" />
                <x-card title="Total Claims" :value="$claimsCount ?? 0" color="orange" />
                <x-card title="Approved Claims" :value="$approvedClaimsCount ?? 0" color="blue" />
            </div>
        </div>
    </div>
</x-app-layout>
