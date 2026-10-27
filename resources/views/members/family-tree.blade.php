@extends('layouts.app')

@section('title', 'Family Tree - ' . $member->name)

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/themes/default/style.min.css" />
    <style>
        #family-tree {
            margin-top: 20px;
            min-height: 300px;
        }

        /*
         * jsTree's `types` plugin puts the type class (householder, parent, spouse, …)
         * directly on the `.jstree-icon` element itself, not on an ancestor `<li>` — so
         * the old `.jstree-node.jstree-leaf.X > .jstree-icon` selectors never matched
         * anything and every node fell back to jsTree's default folder/file icons.
         */
        .jstree-icon.householder,
        .jstree-icon.non_householder {
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="blue" class="bi bi-person" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/></svg>') no-repeat center !important;
            background-size: 16px 16px !important;
        }

        .jstree-icon.parent {
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="blue" class="bi bi-person" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/></svg>') no-repeat center !important;
            background-size: 16px 16px !important;
        }

        .jstree-icon.spouse {
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="green" class="bi bi-person" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/></svg>') no-repeat center !important;
            background-size: 16px 16px !important;
        }

        .jstree-icon.child {
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="orange" class="bi bi-person" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/></svg>') no-repeat center !important;
            background-size: 16px 16px !important;
        }

        .jstree-icon.sibling {
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="purple" class="bi bi-people" viewBox="0 0 16 16"><path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.262 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 1-1.23-.247A7.35 7.35 0 0 1 5 8.5a7.5 7.5 0 0 1-.583-.5A5.75 5.75 0 0 1 4 7.5c0-1.247.573-2.354 1.45-3.106A3.5 3.5 0 0 1 8.5 3c.884 0 1.721.247 2.44.697a6.01 6.01 0 0 1 1.09 1.053c-.01.023-.015.048-.024.072A2.98 2.98 0 0 0 11 5a3 3 0 0 0-3 3 3 3 0 0 0 .17.868c.042.135.094.266.157.394.09.183.207.347.344.487.137.14.294.263.468.368a6.68 6.68 0 0 1-.733.238A5.93 5.93 0 0 1 6.936 9.28zm1.564-6.28A2 2 0 0 0 6.5 5a2 2 0 0 0 2 2 2 2 0 0 0 2-2 2 2 0 0 0-2-2z"/></svg>') no-repeat center !important;
            background-size: 16px 16px !important;
        }

        .jstree-anchor {
            cursor: pointer;
        }

        .legend-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 4px;
        }
    </style>
@endsection

@section('content')
    <div class="card shadow-sm mb-3">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-0">Family Tree: {{ $member->name }} ({{ $member->community->name }})</h5>
                <small dir="rtl" class="text-white-50">شجرہ نسب / خاندانی تعلقات</small>
            </div>
            <a href="{{ route('members.family-tree-pdf', $member) }}" class="btn btn-light btn-sm">Download Family
                Tree PDF / پی ڈی ایف ڈاؤنلوڈ</a>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-2 text-center mb-3 mb-md-0">
                    @if ($member->profile_picture)
                        <img src="{{ asset('profiles/' . $member->profile_picture) }}" alt="{{ $member->name }}"
                            class="img-fluid rounded-circle border" style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                        <img src="{{ asset('image/default-profile.jpg') }}" alt="Default Profile"
                            class="img-fluid rounded-circle border" style="width: 100px; height: 100px; object-fit: cover;">
                    @endif
                </div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-sm-4 mb-2"><strong>Name / نام:</strong> {{ $member->name }}</div>
                        <div class="col-sm-4 mb-2">
                            <strong>{{ $member->cnic ? 'CNIC / شناختی کارڈ' : 'B-Form / ب فارم' }}:</strong>
                            {{ $member->cnic ?? $member->b_form_number ?? 'N/A' }}
                        </div>
                        <div class="col-sm-4 mb-2"><strong>Community / برادری:</strong> {{ $member->community->name }}</div>
                        <div class="col-sm-4 mb-2"><strong>Gender / جنس:</strong> {{ $member->gender === 'male' ? 'Male / مرد' : ($member->gender === 'female' ? 'Female / عورت' : 'Other / دیگر') }}</div>
                        @php
                            $memberTypeLabels = [
                                'householder' => 'Householder / سربراہِ خاندان',
                                'family_member' => 'Family Member / خاندان کا فرد',
                                'child' => 'Child / بچہ',
                                'spouse' => 'Spouse / شریکِ حیات',
                                'sibling' => 'Sibling / بہن بھائی',
                            ];
                            $maritalStatusLabels = [
                                'single' => 'Single / غیر شادی شدہ',
                                'married' => 'Married / شادی شدہ',
                                'divorced' => 'Divorced / طلاق یافتہ',
                                'widowed' => 'Widowed / بیوہ / رنڈوا',
                            ];
                        @endphp
                        <div class="col-sm-4 mb-2"><strong>Member Type / ممبر کی قسم:</strong> {{ $memberTypeLabels[$member->member_type] ?? ucfirst(str_replace('_', ' ', $member->member_type)) }}</div>
                        <div class="col-sm-4 mb-2"><strong>Marital Status / ازدواجی حیثیت:</strong>
                            {{ $maritalStatusLabels[$member->marital_status] ?? ucfirst($member->marital_status) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <strong>Family Tree / شجرہ نسب</strong>
            </div>
            <div class="d-flex flex-wrap gap-3 small text-muted">
                <span><span class="legend-dot" style="background:blue"></span>Member / Parent / Child (ممبر / والدین / بچہ)</span>
                <span><span class="legend-dot" style="background:green"></span>Spouse (شریکِ حیات)</span>
                <span><span class="legend-dot" style="background:purple"></span>Sibling (بہن بھائی)</span>
            </div>
        </div>
        <div class="card-body">
            @if ($treeData)
                <div id="family-tree"></div>
            @else
                <div class="alert alert-warning mb-0">No family relationships found for {{ $member->name }}. / کوئی خاندانی تعلق نہیں ملا۔</div>
            @endif
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('members.select-family-tree') }}" class="btn btn-secondary">Select Another Member / دوسرا ممبر منتخب کریں</a>
        <a href="{{ route('members.index') }}" class="btn btn-secondary ms-2">Back to Members / واپس ممبرز لسٹ</a>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.12/jstree.min.js"></script>
    <script>
        $(function() {
            try {
                const treeData = @json($treeData);
                console.log('Tree Data:', treeData);

                if (treeData && Object.keys(treeData).length > 0) {
                    $('#family-tree').jstree({
                        'core': {
                            'data': [treeData], // Wrap treeData in an array for jstree
                            'themes': {
                                'dots': true,
                                'icons': true
                            }
                        },
                        'types': {
                            'householder': {
                                'icon': 'jstree-file householder'
                            },
                            'non_householder': {
                                'icon': 'jstree-file non_householder'
                            },
                            'child': {
                                'icon': 'jstree-file child'
                            },
                            'sibling': {
                                'icon': 'jstree-file sibling'
                            },
                            'parent': {
                                'icon': 'jstree-file parent'
                            },
                            'spouse': {
                                'icon': 'jstree-file spouse'
                            }
                        },
                        'plugins': ['types']
                    }).on('ready.jstree', function(e, data) {
                        console.log('Family tree loaded successfully');
                    }).on('select_node.jstree', function(e, data) {
                        const nodeId = data.node.id;
                        console.log('Selected node:', nodeId);

                        // Extract member ID from nodeId (format: member_{id}, parent_{id}_{parentId}, spouse_{id}_{memberId}, sibling_{id}_{memberId})
                        let memberId = null;
                        if (nodeId.startsWith('member_')) {
                            memberId = nodeId.replace('member_', '');
                        } else if (nodeId.startsWith('parent_')) {
                            memberId = nodeId.split('_')[1]; // parent_{id}_{memberId}
                        } else if (nodeId.startsWith('spouse_')) {
                            memberId = nodeId.split('_')[1]; // spouse_{id}_{memberId}
                        } else if (nodeId.startsWith('sibling_')) {
                            memberId = nodeId.split('_')[1]; // sibling_{id}_{memberId}
                        }

                        if (memberId) {
                            const url = "{{ route('members.show', ':id') }}".replace(':id', memberId);
                            window.location.href = url;
                        }
                    }).on('error.jstree', function(e, data) {
                        console.error('JSTree error:', data);
                    });
                } else {
                    console.warn('No valid tree data available');
                    $('#family-tree').html('<div class="alert alert-warning">No family data to display.</div>');
                }
            } catch (error) {
                console.error('Error initializing family tree:', error);
                $('#family-tree').html('<div class="alert alert-danger">Error loading family tree: ' + error
                    .message + '</div>');
            }
        });
    </script>
@endsection
