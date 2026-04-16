<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Proposer Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-alert />

            {{-- Welcome Banner --}}
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl p-6 mb-8 text-white shadow-lg">
                <h3 class="text-xl font-bold">Welcome back, {{ auth()->user()->proposerProfile->company_name ?? auth()->user()->name }}</h3>
                <p class="text-indigo-100 mt-1 text-sm">Here's a live overview of your insurance portfolio.</p>
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                {{-- Plans --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-5">
                    <div class="bg-purple-100 text-purple-600 rounded-full p-3">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Active Plans</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $plansCount }}</p>
                    </div>
                </div>
                {{-- Active Policies --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-5">
                    <div class="bg-green-100 text-green-600 rounded-full p-3">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Active Policies</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $policiesCount }}</p>
                        @if($pendingPoliciesCount > 0)
                            <p class="text-xs text-yellow-600 font-medium mt-1">
                                {{ $pendingPoliciesCount }} awaiting your approval
                            </p>
                        @endif
                    </div>
                </div>
                {{-- Claims --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center gap-5">
                    <div class="bg-red-100 text-red-600 rounded-full p-3">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Claims</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $claimsCount }}</p>
                        @if($pendingClaimsCount > 0)
                            <p class="text-xs text-orange-600 font-medium mt-1">
                                {{ $pendingClaimsCount }} need your attention
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Claim Health Bar --}}
            @if($claimsCount > 0)
                @php $approvalRate = round(($approvedClaimsCount / $claimsCount) * 100); @endphp
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-sm font-semibold text-gray-700">Claim Approval Rate</p>
                        <span class="text-sm font-bold text-indigo-600">{{ $approvalRate }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-indigo-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $approvalRate }}%"></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">{{ $approvedClaimsCount }} approved out of {{ $claimsCount }} total claims.</p>
                </div>
            @endif

            {{-- Quick Links --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('proposer.policies.index') }}"
                   class="flex items-center justify-between bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:bg-indigo-50 hover:border-indigo-200 transition group">
                    <div>
                        <p class="font-semibold text-gray-800 group-hover:text-indigo-700">Review Policy Applications</p>
                        <p class="text-sm text-gray-500">{{ $pendingPoliciesCount }} pending</p>
                    </div>
                    <svg class="h-5 w-5 text-gray-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('proposer.claims.index') }}"
                   class="flex items-center justify-between bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:bg-orange-50 hover:border-orange-200 transition group">
                    <div>
                        <p class="font-semibold text-gray-800 group-hover:text-orange-700">Process Claims</p>
                        <p class="text-sm text-gray-500">{{ $pendingClaimsCount }} awaiting action</p>
                    </div>
                    <svg class="h-5 w-5 text-gray-400 group-hover:text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
