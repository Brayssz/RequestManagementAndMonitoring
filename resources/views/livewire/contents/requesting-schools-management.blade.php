<div class="modal fade" id="add-requesting-office-modal" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-xl custom-modal-two">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            @if ($submit_func == 'add-requesting-office')
                                <h4>Add Requesting School</h4>
                            @else
                                <h4>Edit Requesting School</h4>
                            @endif
                        </div>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="submit_requesting_office">
                            @csrf
                            <div class="card mb-0">
                                <div class="card-body">
                                    <div class="new-employee-field">
                                        <div class="card-title-head" wire:ignore>
                                            <h6><span><i data-feather="info" class="feather-edit"></i></span>Requesting School Information</h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="name">Name</label>
                                                    <input type="text" class="form-control" placeholder="Enter name"
                                                        id="name" wire:model.lazy="name">
                                                    @error('name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                           
                                            <div class="col-lg-6 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="requestor">Requestor</label>
                                                    <div wire:ignore>
                                                        <select class="select requestor" id="requestor" name="requestor"
                                                            wire:model="requestor" 
                                                            @if ($requestors->isEmpty()) disabled @endif>
                                                            <option value="">Choose</option>
                                                            @foreach ($requestors as $requestor)
                                                                <option value="{{ $requestor->requestor_id }}">{{ $requestor->name }} - ({{$requestor->position}})</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @if ($requestors->isEmpty())
                                                        <span class="text-danger">No available requestor records. Please add a new requestor or set an existing one to active.</span>
                                                    @endif
                                                    @error('requestor')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            @if ($submit_func == 'edit-requesting-office')
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
                handleRequestingOfficeActions();
            });

            function initSelect() {
                $('.select').select2({
                    minimumResultsForSearch: -1,
                    width: '100%'
                });
            }

            function handleRequestingOfficeActions() {
                $(document).on('change', '[id]', handleInputChange);
                $(document).on('click', '.add-office', openAddRequestingOfficeModal);
                $(document).on('click', '.edit-office', openEditRequestingOfficeModal);
            }

            function handleInputChange(e) {
                if ($(e.target).is('select')) {
                    const property = e.target.id;
                    const value = e.target.value;
                    @this.set(property, value);

                    console.log(`${property}: ${value}`);
                }
            }

            function openAddRequestingOfficeModal() {
                @this.set('submit_func', 'add-requesting-office');

                @this.call('resetFields').then(() => {
                    initSelectVal("");
                    $('#add-requesting-office-modal').modal('show');
                });
            }

            function openEditRequestingOfficeModal() {
                const officeId = $(this).data('officeid');

                @this.set('submit_func', 'edit-requesting-office');
                
                @this.call('getRequestingOffice', officeId).then(() => {
                    @this.call("populateRequestor");
                    populateEditForm();

                    $('#add-requesting-office-modal').modal('show');
                });
            }

            function initSelectVal(requestor, status) {

                $('#requestor').val(requestor).change();
                $('#status').val(status).change();
            }

            function populateEditForm() {
                const requestor = @this.get('requestor');
                const status = @this.get('status');

                initSelect();
                initSelectVal(requestor, status);
            }
        </script>
    @endpush
</div>
