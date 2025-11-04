<div id="new_customer" class="modal fade" tabindex="-1" aria-labelledby="bs-example-modal-md" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header d-flex align-items-center">
        <h4 class="modal-title" id="myModalLabel">
          New Customer
        </h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"
          aria-label="Close"></button>
      </div>
      <form method='POST' action='{{url('new-customer')}}' onsubmit='show()' enctype="multipart/form-data" class="validation-wizard wizard-circle mt-5">
      @csrf
      <div class="modal-body">
        <div class='row'>
           <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label" for="stoveId">Serial Number  <span class="text-danger">*</span></label>
                  <select class="js-example-basic-single w-100 form-control renz required chosen-select" name='serial_number'  required>
                      <option value="">Search Serial Number</option>
                      @foreach($stoves as $stove)
                        <option value="{{$stove->id}}">{{$stove->serial_number}}</option>
                        @endforeach
                  </select>
              </div>
            </div>
        </div>
          <div class="row">
            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label" for="wfirstName2"> Full Name  <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control required" id="wfirstName2" name="name" required/>
              </div>
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
                <input type="text" class="form-control required" id="wphoneNumber2" maxlength="11" pattern="\d{11}" name="phone_number" required oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);" />
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
            <div class="col-md-12">
              <div class="mb-3">
                <label class="form-label" for="wlocation2"> Address <span class="text-danger">*</span>
                </label>
                <textarea class="form-control required" name='address' required></textarea>
              </div>
            </div>
            
            <input style="display:none" value="Active" name="status" id="status">
           
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