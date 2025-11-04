<div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-lg">
    <form id="addTransactionForm" method="POST" action="{{ url('store-transaction') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addTransactionModalLabel">Add Transaction</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <!-- Customer Select -->
          <div class="mb-3">
            <label for="customerSelect" class="form-label">Select Customer</label>
            <select id="customerSelect" name="customer_id" class="form-select select2" style='font-size:10px;' required>
            <option value=''>Search</option>
              @foreach($customers as $customer)
                @php
                  $fullName = $customer->name;
                  $parts = explode(' ', $fullName);
                  $lastName = array_pop($parts);
                  $masked = str_repeat('*', strlen(implode(' ', $parts))) . ' ' . $lastName;
                  $serial = $customer->serial->serial_number ?? '';
                  $masked_serial = str_repeat('*', max(0, strlen($serial) - 5)) . substr($serial, -5);
                  $number = $customer->number;
                  $masked_number = str_repeat('*', max(0, strlen($number) - 5)) . substr($number, -5);
                @endphp
                <option value="{{ $customer->id }}">{{ $masked }} - {{ $masked_serial }} - {{ $masked_number }}</option>
              @endforeach
            </select>
          </div>

          <!-- Item Select -->
          {{-- <div class="mb-3">
            <label for="itemSelect" class="form-label">Select Item</label>
            <select id="itemSelect" name="item_id" class="form-select" required>
            <option value=''>Search</option>
              @foreach($items as $item)
                <option value="{{ $item->id }}">{{ $item->item }}</option>
              @endforeach
            </select>
          </div> --}}
           <div class="mb-3">
            <label for="itemSelect" class="form-label">Select Item</label> 
            <br>
            @foreach($items as $key => $item)
            <input type="radio" id="{{$item->id}}" name="item_id" @if($key == 0) checked  @endif value="{{$item->id}}" required>
            <label for="{{$item->id}}" class='mr-3'>{{$item->item}}</label>
            <br>
            @endforeach
          </div>

          <!-- Quantity -->
          <div class="mb-3">
            <label class="form-label">Quantity</label>
            <div style="max-width: 140px;">
              <div class="input-group">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="qtyMinus">-</button>
                <input type="number" name="qty" id="qtyInput" class="form-control form-control-sm text-center" value="1" min="1" required>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="qtyPlus">+</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn bg-danger-subtle text-danger" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn bg-info-subtle text-info">Save</button>
        </div>
      </div>
    </form>
  </div>
</div>