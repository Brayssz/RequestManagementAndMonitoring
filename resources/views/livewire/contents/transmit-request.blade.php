<div>
    <!-- Transmit Request Modal -->
    <div class="modal fade" id="transmit-request-modal" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-md custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Transmit Request</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form wire:submit.prevent="transmit_request">
                                @csrf
                                <div class="card mb-0">
                                    <div class="card-body">
                                        <div class="new-request-field">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="signed_chief_date">Signed Chief Date</label>
                                                        <input type="date" class="form-control" id="signed_chief_date"
                                                            wire:model.lazy="signed_chief_date">
                                                        @error('signed_chief_date')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="date_transmitted">Date Transmitted</label>
                                                        <input type="date" class="form-control" id="date_transmitted"
                                                            wire:model.lazy="date_transmitted">
                                                        @error('date_transmitted')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="transmitted_office_id">Transmitted Office</label>
                                                        <div wire:ignore>
                                                            <select id="transmitted_office_id" class="form-control select"
                                                                wire:model="transmitted_office_id">
                                                                <option value="">Choose</option>
                                                                @foreach ($requestingOffices as $office)
                                                                    <option value="{{ $office->requesting_office_id }}">
                                                                        {{ $office->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @error('transmitted_office_id')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="remarks">Remarks</label>
                                                        <textarea class="form-control" id="remarks" wire:model.lazy="remarks"></textarea>
                                                        @error('remarks')
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
                                    <button type="submit" class="btn btn-submit">Transmit</button>
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
                handleTransmitActions();
            });

            function handleTransmitActions() {
                $(document).on('click', '.transmit-request', openTransmitRequestModal);
            }

            function openTransmitRequestModal() {
                const requestId = $(this).data('requestid');
                @this.call('getRequest', requestId).then(() => {
                    $('#transmit-request-modal').modal('show');
                });
            }
        </script>
    @endpush
</div>
