@php
    $lowerStatus = strtolower(str_replace('_', ' ', $status));
    $labels = [
        'submitted'         => 'Awaiting Review',
        'pending'           => 'Under Consideration',
        'under review'      => 'Under Review',
        'field verification'=> 'Field Verification',
        'approved'          => 'Approved',
        'active'            => 'Active',
        'rejected'          => 'Rejected',
        'expired'           => 'Expired',
    ];
    $label = $labels[$lowerStatus] ?? ucwords($lowerStatus);
@endphp

<span class="px-3 py-1 inline-flex items-center gap-1 rounded-full text-xs font-semibold border
    @if(in_array($lowerStatus, ['submitted', 'pending']))
        bg-yellow-50 text-yellow-800 border-yellow-300
    @elseif($lowerStatus === 'under review')
        bg-blue-50 text-blue-800 border-blue-300
    @elseif($lowerStatus === 'field verification')
        bg-purple-50 text-purple-800 border-purple-300
    @elseif(in_array($lowerStatus, ['approved', 'active']))
        bg-green-50 text-green-800 border-green-300
    @elseif($lowerStatus === 'rejected')
        bg-red-50 text-red-800 border-red-300
    @elseif($lowerStatus === 'expired')
        bg-gray-50 text-gray-600 border-gray-300
    @else
        bg-gray-100 text-gray-700 border-gray-300
    @endif
">
    <span class="w-1.5 h-1.5 rounded-full 
        @if(in_array($lowerStatus, ['submitted', 'pending'])) bg-yellow-500
        @elseif($lowerStatus === 'under review') bg-blue-500
        @elseif($lowerStatus === 'field verification') bg-purple-500
        @elseif(in_array($lowerStatus, ['approved', 'active'])) bg-green-500
        @elseif($lowerStatus === 'rejected') bg-red-500
        @elseif($lowerStatus === 'expired') bg-gray-400
        @else bg-gray-400
        @endif
    "></span>
    {{ $label }}
</span>
