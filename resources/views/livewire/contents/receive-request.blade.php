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
                                                        <div wire:ignore>
                                                            <select class="form-control select receive"
                                                                id="requesting_office_id" name="requesting_office_id"
                                                                wire:model.lazy="requesting_office_id"
                                                                @if ($requestingOffices->isEmpty()) disabled @endif>
                                                                <option value="">Choose</option>
                                                                @foreach ($requestingOffices as $office)
                                                                    <option value="{{ $office->requesting_office_id }}">
                                                                        {{ $office->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @if ($requestingOffices->isEmpty())
                                                            <span class="text-danger">No available requesting office
                                                                records. Please add a new requesting office or set an
                                                                existing one to active.</span>
                                                        @endif
                                                        @error('requesting_office_id')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="fund_source_id">Fund
                                                            Source</label>
                                                        <div wire:ignore>
                                                            <select class="form-control select receive"
                                                                id="fund_source_id" name="fund_source_id"
                                                                wire:model.lazy="fund_source_id"
                                                                @if ($fundSources->isEmpty()) disabled @endif>
                                                                <option value="">Choose</option>
                                                                @foreach ($fundSources as $source)
                                                                    <option value="{{ $source->fund_source_id }}">
                                                                        {{ $source->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @if ($fundSources->isEmpty())
                                                            <span class="text-danger">No available fund source records.
                                                                Please add a new fund source or set an existing one to
                                                                active.</span>
                                                        @endif
                                                        @error('fund_source_id')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="year">Allotment Year</label>
                                                        <input type="number" class="form-control"
                                                            placeholder="Enter year" id="allotment_year"
                                                            wire:model.lazy="allotment_year">
                                                        @error('year')
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
                                                        <input type="text" class="form-control"
                                                            id="dts_tracker_number"
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
                                                        <input type="date" class="form-control"
                                                            id="sgod_date_received"
                                                            wire:model.lazy="sgod_date_received">
                                                        @error('sgod_date_received')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="nature_of_request">Nature of
                                                            Request</label>
                                                        <textarea class="form-control" id="nature_of_request" rows="3"
                                                            wire:model.lazy="nature_of_request"></textarea>
                                                        @error('nature_of_request')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 col-md-12">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="amount">Requested
                                                            Amount</label>
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


    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                handleRequestActions();
            });

            function handleRequestActions() {
                $('select.receive').on('change', handleInputChange);
                $(document).on('click', '.add-request', openAddRequestModal);
                $(document).on('click', '.edit-request', openEditRequestModal);
            }

            $(document).on('click', '.submit-request', function() {

                confirmAlert('Confirm Submission', 'Are you sure you want to submit this request?', function() {
                    @this.call('submit_request');
                }, 'Submit');
            });

            function handleInputChange(e) {
                console.log(e.target);
                if ($(e.target).is('select.receive')) {
                    const property = e.target.id;
                    const value = e.target.value;
                    @this.set(property, value);

                    console.log(`${property}: ${value}`);
                }
            }



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

                @this.call('getRequest', requestId).then(() => {
                    $('#add-request-modal').modal('show');
                    var requesting_office_id = @this.get('requesting_office_id');
                    var fund_source_id = @this.get('fund_source_id');
                    $('#requesting_office_id').val(requesting_office_id).change();
                    $('#fund_source_id').val(fund_source_id).change();
                });
            }
        </script>
    @endpush
</div>
