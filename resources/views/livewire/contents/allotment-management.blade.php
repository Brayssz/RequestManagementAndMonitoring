<div class="modal fade" id="add-annual-allotment-modal" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-xl custom-modal-two">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            @if ($submit_func == 'add-annual-allotment')
                                <h4>Add Annual Allotment</h4>
                            @else
                                <h4>Edit Annual Allotment</h4>
                            @endif
                        </div>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="submit_annual_allotment">
                            @csrf
                            <div class="card mb-0">
                                <div class="card-body">
                                    <div class="new-employee-field">
                                        <div class="card-title-head" wire:ignore>
                                            <h6><span><i data-feather="info" class="feather-edit"></i></span>Annual
                                                Allotment Information</h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="requesting_office_id">Requesting
                                                        Office</label>
                                                    <div wire:ignore>
                                                        <select class="select" id="requesting_office_id"
                                                            name="requesting_office_id"
                                                            wire:model="requesting_office_id">
                                                            <option value="">Choose</option>
                                                            @foreach ($requestingOffices as $office)
                                                                <option value="{{ $office->requesting_office_id }}">
                                                                    {{ $office->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('requesting_office_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="amount">Amount</label>
                                                    <input type="text" class="form-control currency"
                                                        placeholder="Enter amount" id="amount currency"
                                                        wire:model.lazy="amount">
                                                    @error('amount')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="year">Year</label>
                                                    <input type="number" class="form-control" placeholder="Enter year"
                                                        id="year" wire:model.lazy="year">
                                                    @error('year')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            @if ($submit_func == 'edit-annual-allotment')
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="status">Status</label>
                                                        <div wire:ignore>
                                                            <select class="select" id="status" name="status"
                                                                wire:model="status">
                                                                <option value="">Choose</option>
                                                                <option value="active">Active</option>
                                                                <option value="inactive">Inactive</option>
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
                                <button type="button" class="btn btn-cancel me-2"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                handleAnnualAllotmentActions();
            });

            // function formatCurrency(input) {
            //     let value = input.val().replace(/[^\d.-]/g, "");
            //     value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");

            //     input.val(value);
            // }

            // $(".currency").on("input", function () {
            //     formatCurrency($(this)); 
            // });

            function initSelect() {
                $('.select').select2({
                    minimumResultsForSearch: -1,
                    width: '100%'
                });
            }

            function handleAnnualAllotmentActions() {
                $(document).on('change', '[id]', handleInputChange);
                $(document).on('click', '.add-allotment', openAddAnnualAllotmentModal);
                $(document).on('click', '.edit-allotment', openEditAnnualAllotmentModal);
            }

            function handleInputChange(e) {
                if ($(e.target).is('select')) {
                    const property = e.target.id;
                    const value = e.target.value;
                    @this.set(property, value);

                    console.log(`${property}: ${value}`);
                }
            }

            function openAddAnnualAllotmentModal() {
                @this.set('submit_func', 'add-annual-allotment');

                @this.call('resetFields').then(() => {
                    initSelectVal("", "", "");
                    $('#add-annual-allotment-modal').modal('show');
                });
            }

            function openEditAnnualAllotmentModal() {
                const allotmentId = $(this).data('allotmentid');

                @this.set('submit_func', 'edit-annual-allotment');

                @this.call('getAnnualAllotment', allotmentId).then(() => {
                    populateEditForm();

                    $('#add-annual-allotment-modal').modal('show');
                });
            }

            function initSelectVal(requesting_office_id, year, status) {
                $('#requesting_office_id').val(requesting_office_id).change();
                $('#year').val(year).change();
                $('#status').val(status).change();
            }

            function populateEditForm() {
                const requesting_office_id = @this.get('requesting_office_id');
                const year = @this.get('year');
                const status = @this.get('status');

                initSelect();
                initSelectVal(requesting_office_id, year, status);
            }
        </script>
    @endpush
</div>