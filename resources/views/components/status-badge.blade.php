<div class="px-3 py-1 rounded-full text-xs font-semibold inline-block
    @php $lowerStatus = strtolower($status); @endphp
    @if($lowerStatus === 'submitted' || $lowerStatus === 'pending') bg-yellow-100 text-yellow-800
    @elseif($lowerStatus === 'under_review' || $lowerStatus === 'under review') bg-blue-100 text-blue-800
    @elseif($lowerStatus === 'field_verification') bg-purple-100 text-purple-800
    @elseif($lowerStatus === 'approved' || $lowerStatus === 'active') bg-green-100 text-green-800
    @elseif($lowerStatus === 'rejected') bg-red-100 text-red-800
    @else bg-gray-100 text-gray-800
    @endif">
    {{ ucwords(str_replace('_', ' ', $status)) }}
</div>
