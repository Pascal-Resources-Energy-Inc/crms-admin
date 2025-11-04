<div id="viewValidId" class="modal fade" tabindex="-1" aria-labelledby="bs-example-modal-md" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header d-flex align-items-center">
        <h4 class="modal-title" id="myModalLabel">
           Valid ID
        </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"
          aria-label="Close"></button>
      </div>
      @csrf
        <div class="modal-body text-center">
                  <div class="card-body text-center">
      <h6 class="card-title"><i class="mdi mdi-card-account-details-outline"></i> Uploaded Valid ID</h6>
      <img src="{{ asset($dealer->valid_file)}}" alt="Valid ID" class="img-fluid rounded shadow-sm" style="max-height: 250px;" />
    </div>    
        </div>
      <div class="modal-footer">
        <button type="button" class="btn bg-danger-subtle text-danger  waves-effect"
          data-bs-dismiss="modal">
          Close
        </button>
      </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
<!-- /.modal-dialog -->
</div>