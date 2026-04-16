<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($plans as $plan)
        <div class="border rounded-lg shadow-sm hover:shadow-md transition duration-200 overflow-hidden">
            <div class="bg-gray-50 border-b px-4 py-3 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <input type="checkbox" form="compare-form" name="plan_ids[]" value="{{ $plan->id }}" class="rounded text-indigo-600 focus:ring-indigo-500 cursor-pointer w-4 h-4" title="Select for comparison">
                    <h4 class="font-bold text-gray-800">{{ $plan->crop_type }}</h4>
                </div>
                <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $plan->region }}</span>
            </div>
            <div class="p-4 space-y-2">
                <p class="text-sm text-gray-600"><span class="font-semibold text-gray-800">Provided By:</span> {{ $plan->proposer->name ?? 'Unknown Provider' }}</p>
                <p class="text-sm text-gray-600"><span class="font-semibold text-gray-800">Premium:</span> ${{ number_format($plan->premium, 2) }}</p>
                <p class="text-sm text-gray-600"><span class="font-semibold text-gray-800">Coverage:</span> ${{ number_format($plan->coverage, 2) }}</p>
                <p class="text-sm text-gray-600"><span class="font-semibold text-gray-800">Duration:</span> {{ $plan->duration }} Months</p>
            </div>
            @php
                $profile = auth()->user()->farmerProfile;
                $isEligible = false;
                if ($profile && strtolower(trim($profile->region)) === strtolower(trim($plan->region)) && strtolower(trim($profile->crop_type)) === strtolower(trim($plan->crop_type))) {
                    $isEligible = true;
                }
            @endphp
            <div class="bg-gray-50 border-t px-4 py-3 flex items-center justify-between">
                @if($isEligible)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Eligible
                    </span>
                    <form action="{{ route('farmer.plans.apply', $plan->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-green-600 py-1.5 px-4 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition">
                            Apply
                        </button>
                    </form>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        Not Eligible
                    </span>
                    <button disabled class="inline-flex justify-center rounded-md border border-gray-300 bg-gray-100 py-1.5 px-4 text-sm font-medium text-gray-400 cursor-not-allowed">
                        Apply
                    </button>
                @endif
            </div>
        </div>
    @empty
        <div class="col-span-full py-8 text-center text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-lg">No insurance plans found matching your criteria.</p>
        </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $plans->links() }}
</div>
