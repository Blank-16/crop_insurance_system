<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Plan Comparison') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-alert />
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-800">Comparing {{ $plans->count() }} Plans</h3>
                        <a href="{{ route('farmer.plans') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-semibold transition">
                            &larr; Back to Plans
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left table-fixed border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-3 border-b border-r w-[150px] text-gray-700">Feature</th>
                                    @foreach($plans as $plan)
                                        <th class="px-4 py-3 border-b w-[250px] text-center font-bold text-gray-800">
                                            Plan #{{ $plan->id }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="hover:bg-gray-50 border-b">
                                    <td class="px-4 py-3 font-semibold text-gray-600 border-r bg-gray-50">Crop Type</td>
                                    @foreach($plans as $plan)
                                        <td class="px-4 py-3 text-center text-indigo-600 font-medium">{{ $plan->crop_type }}</td>
                                    @endforeach
                                </tr>
                                <tr class="hover:bg-gray-50 border-b">
                                    <td class="px-4 py-3 font-semibold text-gray-600 border-r bg-gray-50">Region</td>
                                    @foreach($plans as $plan)
                                        <td class="px-4 py-3 text-center">
                                            <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2 py-1 rounded">{{ $plan->region }}</span>
                                        </td>
                                    @endforeach
                                </tr>
                                <tr class="hover:bg-gray-50 border-b">
                                    <td class="px-4 py-3 font-semibold text-gray-600 border-r bg-gray-50">Provided By</td>
                                    @foreach($plans as $plan)
                                        <td class="px-4 py-3 text-center text-sm">{{ $plan->proposer->name ?? 'N/A' }}</td>
                                    @endforeach
                                </tr>
                                <tr class="hover:bg-gray-50 border-b">
                                    <td class="px-4 py-3 font-semibold text-gray-600 border-r bg-gray-50">Premium Amount</td>
                                    @foreach($plans as $plan)
                                        <td class="px-4 py-3 text-center font-bold text-gray-800">₹{{ number_format($plan->premium, 2) }}</td>
                                    @endforeach
                                </tr>
                                <tr class="hover:bg-gray-50 border-b">
                                    <td class="px-4 py-3 font-semibold text-gray-600 border-r bg-gray-50">Max Coverage</td>
                                    @foreach($plans as $plan)
                                        <td class="px-4 py-3 text-center font-bold text-green-600">₹{{ number_format($plan->coverage, 2) }}</td>
                                    @endforeach
                                </tr>
                                <tr class="hover:bg-gray-50 border-b">
                                    <td class="px-4 py-3 font-semibold text-gray-600 border-r bg-gray-50">Duration</td>
                                    @foreach($plans as $plan)
                                        <td class="px-4 py-3 text-center">{{ $plan->duration }} Months</td>
                                    @endforeach
                                </tr>
                                <tr class="bg-gray-50">
                                    <td class="px-4 py-4 border-r"></td>
                                    @foreach($plans as $plan)
                                        <td class="px-4 py-4 text-center">
                                            <form action="{{ route('farmer.plans.apply', $plan->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-green-600 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-green-700 transition">
                                                    Apply Now
                                                </button>
                                            </form>
                                        </td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
