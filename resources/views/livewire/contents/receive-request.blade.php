<div>
    <div class="modal fade" id="add-request-modal" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-xl custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                @if ($submit_func == 'add-request')
                                    <h4>Add Request</h4>
                                @else
                                    <h4>Edit Request</h4>
                                @endif
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form wire:submit.prevent="submit_request">
                                @csrf
                                <div class="card mb-0">
                                    <div class="card-body">
                                        <div class="new-request-field">
                                            <div class="card-title-head" wire:ignore>
                                                <h6><span><i data-feather="info" class="feather-edit"></i></span>Request
                                                    Information</h6>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="requesting_office_id">Requesting
                                                            Office</label>
                                                        <select class="form-control" id="requesting_office_id"
                                                            wire:model.lazy="requesting_office_id"
                                                            wire:change="populateAllotments">
                                                            <option value="">Choose</option>
                                                            @foreach ($requestingOffices as $office)
                                                                <option value="{{ $office->requesting_office_id }}">
                                                                    {{ $office->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('requesting_office_id')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="allotment_id">Allotment</label>
                                                        <select class="form-control allotment_id" id="allotment_id"
                                                            wire:model.lazy="allotment_id"
                                                            @if (!$requesting_office_id || $allotments->isEmpty()) disabled @endif>
                                                            
                                                            @if ($requesting_office_id)
                                                            <option value="">Choose</option>
                                                                @foreach ($allotments as $allotment)
                                                                    <option value="{{ $allotment->allotment_id }}">
                                                                        {{ $allotment->year }} - {{$allotment->fundSource->name}} - ₱{{ number_format($allotment->balance, 2) }}
                                                                    </option>
                                                                @endforeach
                                                            @else
                                                                <option value="" selected>Please select Requesting Office first</option>
                                                            @endif
                                                        </select>
                                                        @error('allotment_id')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="dts_date">DTS Date</label>
                                                        <input type="date" class="form-control" id="dts_date"
                                                            wire:model.lazy="dts_date">
                                                        @error('dts_date')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="dts_tracker_number">DTS Tracker
                                                            Number</label>
                                                        <input type="text" class="form-control" id="dts_tracker_number"
                                                            wire:model.lazy="dts_tracker_number">
                                                        @error('dts_tracker_number')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="sgod_date_received">SGOD Date
                                                            Received</label>
                                                        <input type="date" class="form-control" id="sgod_date_received"
                                                            wire:model.lazy="sgod_date_received">
                                                        @error('sgod_date_received')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="nature_of_request">Nature of
                                                            Request</label>
                                                        <input type="text" class="form-control" id="nature_of_request"
                                                            wire:model.lazy="nature_of_request">
                                                        @error('nature_of_request')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                               
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="amount">Requested Amount</label>
                                                        <input type="number" class="form-control" id="amount"
                                                            wire:model.lazy="amount" step="0.01">
                                                        @error('amount')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                               
                                              
                                             
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer-btn mb-4 mt-0">
                                    <button type="button" class="btn btn-cancel me-2"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-submit submit-request">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="utilize-fund-modal" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-md custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Utilize Funds</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form wire:submit.prevent="submit_request">
                                @csrf
                                <div class="card mb-0">
                                    <div class="card-body">
                                        <div class="new-request-field">
                                            <div class="card-title-head" wire:ignore>
                                                <h6 class="previousAmount"><span><i data-feather="info" class="feather-edit"></i></span>Request
                                                    Information</h6>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="utilize_funds">Utilize Funds</label>
                                                        <input type="number" class="form-control" id="utilize_funds"
                                                            wire:model.lazy="utilize_funds" step="0.01">
                                                        @error('utilize_funds')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                              
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer-btn mb-4 mt-0">
                                    <button type="button" class="btn btn-cancel me-2"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-submit submit-utilize-funds">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                handleRequestActions();
            });

            function handleRequestActions() {
                $(document).on('click', '.add-request', openAddRequestModal);
                $(document).on('click', '.edit-request', openEditRequestModal);
            }

            $(document).on('click', '.submit-request', function() {
                @this.call('checkIfExist').then((exists) => {
                    if (exists) {
                        @this.call('getPreviousAmount').then((previousAmount) => {
                            console.log(previousAmount);
                            $('.previousAmount').text('Previously Requested Amount: ₱ ' + new Intl.NumberFormat('en-PH', { minimumFractionDigits: 2 }).format(previousAmount));
                            $('#utilize-fund-modal').modal('show');
                        });
                    } else {
                        @this.call('submit_request');
                    }
                });
            });

            $(document).on('click', '.submit-utilize-funds', function() {
                @this.call('submit_request');
            });

            function openAddRequestModal() {
                @this.set('submit_func', 'add-request');
                @this.call('resetFields').then(() => {
                    $('#add-request-modal').modal('show');
                });
            }

            function openEditRequestModal() {
                const requestId = $(this).data('requestid');
                @this.set('submit_func', 'edit-request');

                @this.call('poplateAllAlotments').then(() => {
                    @this.call('getRequest', requestId).then(() => {
                        $('#add-request-modal').modal('show');
                        var allotment_id = @this.get('allotment_id');
                        populateField(allotment_id);
                    });
                });
            }

            const populateField = function(value) {
                $('.allotment_id').val(value).change();
            };

        </script>
    @endpush
</div>