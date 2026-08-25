<div>
    <div class="modal fade" id="add-document-tracker-modal" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-xl custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                @if ($submit_func == 'add-document-tracker')
                                    <h4>Add Document Tracker</h4>
                                @else
                                    <h4>Edit Document Tracker</h4>
                                @endif
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form wire:submit.prevent="submit_document_tracker">
                                @csrf
                                <div class="card mb-0">
                                    <div class="card-body">
                                        <div class="new-request-field">
                                            <div class="card-title-head" wire:ignore>
                                                <h6><span><i data-feather="info" class="feather-edit"></i></span>Document Tracker Information</h6>
                                            </div>

                                            <div class="row">
                                                {{-- Tracking number hidden; kept in model for submit --}}
                                                <input type="hidden" id="tracking_number" wire:model.lazy="tracking_number">

                                                <div class="col-lg-6 col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="selected_requesting_office_id">Requesting Office</label>
                                                        <div wire:ignore>
                                                            <select id="selected_requesting_office_id" class="form-control select requestor-select">
                                                                <option value="">Choose</option>
                                                                @foreach ($requestingOffices as $office)
                                                                    <option value="{{ $office->requesting_office_id }}">{{ ucfirst($office->type) }} - {{ $office->name }}</option>
                                                                @endforeach
                                                                <option value="external">External / Other</option>
                                                            </select>
                                                        </div>
                                                        @error('requesting_office_id')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                        <small class="text-muted">Select an office/school to autofill requestor details or choose External to enter manually.</small>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="requestor_name_input">Requestor Name</label>
                                                        <input type="text" class="form-control" id="requestor_name_input" wire:model.lazy="requestor_name" placeholder="Enter requestor name">
                                                        @error('requestor_name')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6" id="requestor_email_group">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="requestor_email">Requestor Email</label>
                                                        <input type="email" class="form-control" id="requestor_email" wire:model.lazy="requestor_email" placeholder="Enter requestor email">
                                                        @error('requestor_email')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="document_type">Document Type</label>
                                                        <input type="text" class="form-control" id="document_type" wire:model.lazy="document_type" placeholder="Enter document type">
                                                        @error('document_type')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="details">Details</label>
                                                        <textarea class="form-control" id="details" rows="4" wire:model.lazy="details" placeholder="Enter document details"></textarea>
                                                        @error('details')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                @if ($submit_func == 'edit-document-tracker')
                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="status">Status</label>
                                                            <div wire:ignore>
                                                                <select class="select" id="status" wire:model="status">
                                                                    <option value="">Choose</option>
                                                                    <option value="pending">Pending</option>
                                                                    <option value="received">Received</option>
                                                                    <option value="transmitted">Forwarded</option>
                                                                    <option value="returned">Returned</option>
                                                                    <option value="completed">Completed</option>
                                                                </select>
                                                            </div>
                                                            @error('status')
                                                                <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer-btn mb-4 mt-0">
                                    <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-submit submit-document-tracker">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="transfer-document-tracker-modal" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-md custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                @if ($transfer_action == 'return')
                                    <h4>Return Document</h4>
                                @elseif ($transfer_action == 'complete')
                                    <h4>Complete Document</h4>
                                @else
                                    <h4>Forward Document</h4>
                                @endif
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form wire:submit.prevent="submit_transfer_document_tracker">
                                @csrf
                                <div class="card mb-0">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12" id="target_office_group">
                                                <div class="mb-3">
                                                    <label class="form-label" for="target_office_id">Forward To</label>
                                                    <div wire:ignore>
                                                        <select class="form-control search-office transfer" id="target_office_id" wire:model="target_office_id">
                                                            <option value="">Choose</option>
                                                            @foreach ($currentOffices as $office)
                                                                <option value="{{ $office->requesting_office_id }}">{{ $office->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('target_office_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label" for="transfer_notes">Notes</label>
                                                    <textarea class="form-control" id="transfer_notes" rows="3" wire:model.lazy="transfer_notes" placeholder="Enter notes"></textarea>
                                                    @error('transfer_notes')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer-btn mb-4 mt-0">
                                    <button type="button" class="btn btn-cancel me-2" data-bs-dismiss="modal">Cancel</button>
                                    @if ($transfer_action == 'return')
                                        <button type="button" class="btn btn-submit submit-transfer-document-tracker">Return</button>
                                    @elseif ($transfer_action == 'complete')
                                        <button type="button" class="btn btn-submit submit-transfer-document-tracker">Complete</button>
                                    @else
                                        <button type="button" class="btn btn-submit submit-transfer-document-tracker">Forward</button>
                                    @endif
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
            // Set while we populate the office select programmatically so the change
            // handler only refreshes the UI state and does not re-autofill the requestor.
            let suppressRequestingOfficeSync = false;

            document.addEventListener('DOMContentLoaded', () => {
                handleDocumentTrackerActions();
            });

            function syncRequestingOfficeSelect(value) {
                suppressRequestingOfficeSync = true;
                $('#selected_requesting_office_id').val(value || '').trigger('change');
                suppressRequestingOfficeSync = false;
            }

            $(document).ready(function() {
                $('.search-office.document-tracker').select2({
                    dropdownParent: $('#add-document-tracker-modal')
                });

                $('.search-office.transfer').select2({
                    dropdownParent: $('#transfer-document-tracker-modal')
                });
                $('#selected_requesting_office_id').select2({
                    dropdownParent: $('#add-document-tracker-modal'),
                    width: '100%'
                });
            });

            function handleDocumentTrackerActions() {
                $('.search-office.document-tracker').on('change', handleInputChange);
                $('#selected_requesting_office_id').on('change', function(e) {
                    var val = $(this).val();

                    if (!suppressRequestingOfficeSync) {
                        @this.set('selected_requesting_office_id', val);
                    }

                    if (val === 'external') {
                        // External: only allow entering the requestor name
                        $('#requestor_name_input').prop('readonly', false).prop('disabled', false);
                        $('#requestor_email_group').hide();
                    } else if (!val) {
                        // No selection: make fields editable
                        $('#requestor_name_input').prop('readonly', false).prop('disabled', false);
                        $('#requestor_email_group').show();
                    } else {
                        // Office selected: autofill, make name readonly, show email
                        $('#requestor_name_input').prop('readonly', true).prop('disabled', false);
                        $('#requestor_email_group').show();
                    }
                });

                // apply initial visibility state
                $('#selected_requesting_office_id').trigger('change');
                $('.search-office.transfer').on('change', handleInputChange);
                $(document).on('click', '.add-document-tracker', openAddDocumentTrackerModal);
                $(document).on('click', '.edit-document-tracker', openEditDocumentTrackerModal);
                $(document).on('click', '.transmit-document-tracker', openTransmitDocumentTrackerModal);
                $(document).on('click', '.return-document-tracker', openReturnDocumentTrackerModal);
                $(document).on('click', '.complete-document-tracker', openCompleteDocumentTrackerModal);
                $(document).on('click', '.delete-document-tracker', deleteDocumentTracker);
            }

            function handleInputChange(e) {
                if ($(e.target).is('.search-office.document-tracker') || $(e.target).is('select')) {
                    const property = e.target.id;
                    const value = e.target.value;
                    @this.set(property, value);
                }
            }

            $(document).on('click', '.submit-document-tracker', function(e) {
                e.preventDefault();
                confirmAlert('Confirm Submission', 'Are you sure you want to save this document tracker?', function() {
                    @this.call('submit_document_tracker');
                }, 'Submit');
            });

            $(document).on('click', '.submit-transfer-document-tracker', function(e) {
                e.preventDefault();
                const action = @this.get('transfer_action');

                if (action === 'complete') {
                    confirmAlert('Confirm Complete', 'Mark this document as completed? It can no longer be forwarded or returned afterwards.', function() {
                        @this.call('submit_complete_document_tracker');
                    }, 'Complete');
                    return;
                }

                const actionLabel = action === 'return' ? 'Return' : 'Forward';

                confirmAlert('Confirm ' + actionLabel, 'Are you sure you want to ' + actionLabel.toLowerCase() + ' this document?', function() {
                    @this.call('submit_transfer_document_tracker');
                }, actionLabel);
            });

            function openAddDocumentTrackerModal() {
                showLoader();
                @this.set('submit_func', 'add-document-tracker');
                @this.call('resetFields').then(() => {
                    hideLoader();
                    syncRequestingOfficeSelect('');
                    $('#add-document-tracker-modal').modal('show');
                });
            }

            function openEditDocumentTrackerModal() {
                showLoader();
                const documentTrackerId = $(this).data('documenttrackerid');
                @this.set('submit_func', 'edit-document-tracker');

                @this.call('getDocumentTracker', documentTrackerId).then(() => {
                    hideLoader();
                    syncRequestingOfficeSelect(@this.get('selected_requesting_office_id'));
                    $('#add-document-tracker-modal').modal('show');
                    var status = @this.get('status');
                    $('#status').val(status).change();
                });
            }

            function openTransmitDocumentTrackerModal() {
                showLoader();
                const documentTrackerId = $(this).data('documenttrackerid');
                @this.set('transfer_action', 'transmit');

                @this.call('getDocumentTracker', documentTrackerId).then(() => {
                    hideLoader();
                    $('#target_office_group').show();
                    $('#transfer-document-tracker-modal').modal('show');
                    $('#target_office_id').val(@this.get('target_office_id')).change();
                });
            }

            function openReturnDocumentTrackerModal() {
                showLoader();
                const documentTrackerId = $(this).data('documenttrackerid');
                @this.set('transfer_action', 'return');

                @this.call('getDocumentTracker', documentTrackerId).then(() => {
                    hideLoader();
                    $('#target_office_group').show();
                    $('#transfer-document-tracker-modal').modal('show');
                    $('#target_office_id').val(@this.get('target_office_id')).change();
                });
            }

            function openCompleteDocumentTrackerModal() {
                showLoader();
                const documentTrackerId = $(this).data('documenttrackerid');
                @this.set('transfer_action', 'complete');

                @this.call('getDocumentTracker', documentTrackerId).then(() => {
                    hideLoader();
                    // Completion does not move the document, so no destination office.
                    // Hidden with JS rather than Blade so select2 stays bound to the element.
                    $('#target_office_group').hide();
                    $('#transfer-document-tracker-modal').modal('show');
                });
            }

            function deleteDocumentTracker() {
                const documentTrackerId = $(this).data('documenttrackerid');

                confirmAlert('Are you sure?', 'You want to delete this document tracker? You won\'t be able to retrieve it.', function() {
                    @this.call('deleteDocumentTracker', documentTrackerId);
                }, 'Yes, delete it!');
            }
        </script>
    @endpush
</div>