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
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="tracking_number">Tracking Number</label>
                                                        <input type="text" class="form-control" id="tracking_number" wire:model.lazy="tracking_number" placeholder="Enter tracking number">
                                                        @error('tracking_number')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="requestor_name">Requestor</label>
                                                        <input type="text" class="form-control" id="requestor_name" wire:model.lazy="requestor_name" placeholder="Enter requestor name">
                                                        @error('requestor_name')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="current_office_id">Current Office</label>
                                                        <div wire:ignore>
                                                            <select class="form-control search-office document-tracker" id="current_office_id" wire:model="current_office_id" @if ($currentOffices->isEmpty()) disabled @endif>
                                                                <option value="">Choose</option>
                                                                @foreach ($currentOffices as $office)
                                                                    <option value="{{ $office->requesting_office_id }}">{{ $office->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @if ($currentOffices->isEmpty())
                                                            <span class="text-danger">No available office records. Please add a new office or set an existing one to active.</span>
                                                        @endif
                                                        @error('current_office_id')
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
                                                                    <option value="transmitted">Transmitted</option>
                                                                    <option value="returned">Returned</option>
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
                                    <button type="submit" class="btn btn-submit submit-document-tracker">Submit</button>
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
                                @else
                                    <h4>Transmit Document</h4>
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
                                            <div class="col-lg-12 col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label" for="target_office_id">Office</label>
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
                                        <button type="submit" class="btn btn-submit submit-transfer-document-tracker">Return</button>
                                    @else
                                        <button type="submit" class="btn btn-submit submit-transfer-document-tracker">Transmit</button>
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
            document.addEventListener('DOMContentLoaded', () => {
                handleDocumentTrackerActions();
            });

            $(document).ready(function() {
                $('.search-office.document-tracker').select2({
                    dropdownParent: $('#add-document-tracker-modal')
                });

                $('.search-office.transfer').select2({
                    dropdownParent: $('#transfer-document-tracker-modal')
                });
            });

            function handleDocumentTrackerActions() {
                $('.search-office.document-tracker').on('change', handleInputChange);
                $('.search-office.transfer').on('change', handleInputChange);
                $(document).on('click', '.add-document-tracker', openAddDocumentTrackerModal);
                $(document).on('click', '.edit-document-tracker', openEditDocumentTrackerModal);
                $(document).on('click', '.transmit-document-tracker', openTransmitDocumentTrackerModal);
                $(document).on('click', '.return-document-tracker', openReturnDocumentTrackerModal);
                $(document).on('click', '.delete-document-tracker', deleteDocumentTracker);
            }

            function handleInputChange(e) {
                if ($(e.target).is('.search-office.document-tracker') || $(e.target).is('select')) {
                    const property = e.target.id;
                    const value = e.target.value;
                    @this.set(property, value);
                }
            }

            $(document).on('click', '.submit-document-tracker', function() {
                confirmAlert('Confirm Submission', 'Are you sure you want to save this document tracker?', function() {
                    @this.call('submit_document_tracker');
                }, 'Submit');
            });

            $(document).on('click', '.submit-transfer-document-tracker', function() {
                const actionLabel = @this.get('transfer_action') === 'return' ? 'Return' : 'Transmit';

                confirmAlert('Confirm ' + actionLabel, 'Are you sure you want to ' + actionLabel.toLowerCase() + ' this document?', function() {
                    @this.call('submit_transfer_document_tracker');
                }, actionLabel);
            });

            function openAddDocumentTrackerModal() {
                showLoader();
                @this.set('submit_func', 'add-document-tracker');
                @this.call('resetFields').then(() => {
                    hideLoader();
                    $('#add-document-tracker-modal').modal('show');
                });
            }

            function openEditDocumentTrackerModal() {
                showLoader();
                const documentTrackerId = $(this).data('documenttrackerid');
                @this.set('submit_func', 'edit-document-tracker');

                @this.call('getDocumentTracker', documentTrackerId).then(() => {
                    hideLoader();
                    $('#add-document-tracker-modal').modal('show');
                    var current_office_id = @this.get('current_office_id');
                    var status = @this.get('status');
                    $('#current_office_id').val(current_office_id).change();
                    $('#status').val(status).change();
                });
            }

            function openTransmitDocumentTrackerModal() {
                showLoader();
                const documentTrackerId = $(this).data('documenttrackerid');
                @this.set('transfer_action', 'transmit');

                @this.call('getDocumentTracker', documentTrackerId).then(() => {
                    hideLoader();
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
                    $('#transfer-document-tracker-modal').modal('show');
                    $('#target_office_id').val(@this.get('target_office_id')).change();
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