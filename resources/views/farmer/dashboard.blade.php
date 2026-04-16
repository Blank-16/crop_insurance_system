<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-alert />

            {{-- Incomplete Profile Warning --}}
            @if(!auth()->user()->farmerProfile)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Action Required:</strong> Your profile is incomplete. You need to complete your farm details before applying for any insurance plans.
                                <a href="{{ route('farmer.profile') }}" class="font-bold underline text-yellow-700 hover:text-yellow-600 ml-1">Complete Profile &rarr;</a>
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Welcome Banner --}}
            <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-xl p-6 mb-8 text-white shadow-lg">
                <h3 class="text-xl font-bold">Hello, {{ auth()->user()->name }}</h3>
                <p class="text-green-100 mt-1 text-sm">
                    @if($activePoliciesCount > 0)
                        You have {{ $activePoliciesCount }} active {{ Str::plural('policy', $activePoliciesCount) }} protecting your crops.
                    @else
                        You currently have no active coverage. Browse plans to protect your crops.
                    @endif
                </p>
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- Total Policies --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                    <div class="bg-indigo-100 text-indigo-600 rounded-full p-3">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Total Policies</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $policiesCount }}</p>
                    </div>
                </div>
                {{-- Active Policies --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                    <div class="bg-green-100 text-green-600 rounded-full p-3">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Active Coverage</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $activePoliciesCount }}</p>
                    </div>
                </div>
                {{-- Total Claims --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                    <div class="bg-orange-100 text-orange-600 rounded-full p-3">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Total Claims Filed</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $claimsCount ?? 0 }}</p>
                    </div>
                </div>
                {{-- Approved Claims --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-4">
                    <div class="bg-blue-100 text-blue-600 rounded-full p-3">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Claims Approved</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $approvedClaimsCount ?? 0 }}</p>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('farmer.plans') }}"
                   class="flex items-center justify-between bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:bg-green-50 hover:border-green-200 transition group">
                    <div>
                        <p class="font-semibold text-gray-800 group-hover:text-green-700">Browse Insurance Plans</p>
                        <p class="text-sm text-gray-500">Find coverage for your crops</p>
                    </div>
                    <svg class="h-5 w-5 text-gray-400 group-hover:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('farmer.claims.index') }}"
                   class="flex items-center justify-between bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:bg-orange-50 hover:border-orange-200 transition group">
                    <div>
                        <p class="font-semibold text-gray-800 group-hover:text-orange-700">My Claims</p>
                        <p class="text-sm text-gray-500">Track the status of your filed claims</p>
                    </div>
                    <svg class="h-5 w-5 text-gray-400 group-hover:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
