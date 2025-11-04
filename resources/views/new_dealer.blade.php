<div id="new_dealer" class="modal fade" tabindex="-1" aria-labelledby="bs-example-modal-md" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header d-flex align-items-center">
        <h4 class="modal-title" id="myModalLabel">
          New Dealer
        </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"
          aria-label="Close"></button>
      </div>
      <form method='POST' action='{{url('new-dealer')}}' onsubmit='show()' enctype="multipart/form-data">
      @csrf
      <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
                <label class="form-label" for="wfirstName2"> Full Name  <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control required" id="wfirstName2" name="name" required/>
           
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="wemailAddress2"> Email Address <span class="text-danger">*</span>
                </label>
                <input type="email" class="form-control required" id="wemailAddress2" name="email_address" required/>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" for="wphoneNumber2">Phone Number <span class="text-danger">*</span></label>
                <input type="tel" class="form-control required" id="wphoneNumber2" name="phone_number" required/>
              </div>
            </div>
            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label" for="facebook2">Facebook <span class="text-danger">*</span></label>
                <input type="tel" class="form-control required" id="facebook2" name='facebook' required/>
              </div>
            </div>
          </div>
           <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                              <label class="form-label" for="store_name">Store Name  <span class="text-danger">*</span></label>
                              <input type="text" class="form-control required" name='store_name' id="store_name" />
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label class="form-label" for="store_type">Store Type  <span class="text-danger">*</span></label>
                              <input type="text" class="form-control required" name='store_type' id="store_type" />
                            </div>
                          </div>
                    </div>
          <div class="row">
            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label" for="wlocation2"> Address <span class="text-danger">*</span>
                </label>
                <textarea class="form-control required" name='address' required></textarea>
              </div>
            </div>
           
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn bg-danger-subtle text-danger  waves-effect"
          data-bs-dismiss="modal">
          Close
        </button>
        <button type="submit" class="btn bg-info-subtle text-info  waves-effect">
          Submit
        </button>
      </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
<!-- /.modal-dialog -->
</div>