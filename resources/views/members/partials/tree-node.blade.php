@php
    $relation = $node['relation'] ?? 'Member';
    $badgeClass = match ($relation) {
        'Spouse' => 'badge-spouse',
        'Sibling' => 'badge-sibling',
        'Parent' => 'badge-parent',
        'Child' => 'badge-child',
        default => 'badge-member',
    };
    $relationUrdu = match ($relation) {
        'Spouse' => 'شریک حیات',
        'Sibling' => 'بہن بھائی',
        'Parent' => 'والدین',
        'Child' => 'بچہ',
        default => 'رکن',
    };
    $identifier = !empty($node['cnic'])
        ? 'CNIC: ' . $node['cnic']
        : (!empty($node['b_form_number']) ? 'B-Form: ' . $node['b_form_number'] : 'No CNIC/B-Form');
@endphp
<ul class="tree {{ !empty($root) ? 'root' : '' }}">
    @if ($node)
        <li class="node">
            <span class="badge {{ $badgeClass }}">{{ $relation }}</span>
            <span class="name">{{ $node['name'] ?? $node['text'] }}</span>
            <span class="urdu" style="color:#8a6d1f; font-size:11px;">{{ \App\Support\Urdu::shape($relationUrdu) }}</span>
            <span class="identifier">&mdash; {{ $identifier }}</span>
            @if (!empty($node['children']))
                @foreach ($node['children'] as $child)
                    @include('members.partials.tree-node', ['node' => $child])
                @endforeach
            @endif
        </li>
    @endif
</ul>
