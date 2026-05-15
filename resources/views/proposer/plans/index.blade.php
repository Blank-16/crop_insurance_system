<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage My Insurance Plans') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="mb-4 flex justify-end">
                <a href="{{ route('proposer.plans.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                    + Create New Plan
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-b border-gray-200">
                    <h3 class="text-lg font-bold mb-4">My Offered Plans</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left table-auto border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 border-b">Plan ID</th>
                                    <th class="px-4 py-2 border-b">Crop Type</th>
                                    <th class="px-4 py-2 border-b">Region</th>
                                    <th class="px-4 py-2 border-b">Premium</th>
                                    <th class="px-4 py-2 border-b">Coverage</th>
                                    <th class="px-4 py-2 border-b">Duration</th>
                                    <th class="px-4 py-2 border-b text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($plans as $plan)
                                    <tr class="hover:bg-gray-50 border-b">
                                        <td class="px-4 py-2">#{{ $plan->id }}</td>
                                        <td class="px-4 py-2 font-medium text-gray-800">{{ $plan->crop_type }}</td>
                                        <td class="px-4 py-2">{{ $plan->region }}</td>
                                        <td class="px-4 py-2">₹{{ number_format($plan->premium, 2) }}</td>
                                        <td class="px-4 py-2">₹{{ number_format($plan->coverage, 2) }}</td>
                                        <td class="px-4 py-2">{{ $plan->duration }} Mos</td>
                                        <td class="px-4 py-2 text-center">
                                            <a href="{{ route('proposer.plans.edit', $plan) }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-sm mr-2">Edit</a>
                                            <form action="{{ route('proposer.plans.destroy', $plan) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this plan?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium text-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                            <p>You haven't created any insurance plans yet.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $plans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
