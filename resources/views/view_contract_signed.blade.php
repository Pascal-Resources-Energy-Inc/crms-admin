<div class="modal fade" id="contractView" tabindex="-1" aria-labelledby="contractModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="contractModalLabel"><i class="bi bi-file-earmark-text"></i> Contract Viewing</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="max-height: 75vh; overflow-y: auto;font-size:12px;">
            <table class="table table-bordered" border="1">
                <tr>
                    <th colspan="2">GAZ LITE PILOT PARTICIPATION AGREEMENT FORM</th>
                    <th>Date: {{date('M d,Y',strtotime($customer->created_at))}}</th>
                    <th>Serial Number: {{$customer->serial->serial_number ?? ''}}</th>
                </tr>
                <tr>
                    <td colspan=4>Name of Customer: {{$customer->name}}</td>
                </tr>
                 <tr>
                    <td colspan=4>Address: {{$customer->address}}</td>
                </tr>
                 <tr>
                    <td colspan=4>CONTACT NO.: {{$customer->number}}</td>
                </tr>
                 <tr>
                    <td colspan=4 style='background-color:gray;'>KASUNDUAN SA PAKIKIBAHAGI SA PROYEKTO NG CARBON CREDIT GAZ LITE FEASIBILITY STUDY</td>
                </tr>
            </table>
            <div class="form-section">
            <p>
                Ito ay pagpapatunay na ako, <b><u>{{$customer->name}}</b></u>, ay pumapayag na makibahagi sa proyekto ng <strong>CARBON CREDIT GAZ LITE FEASIBILITY STUDY</strong>.
            </p>

            <h6 class="mt-4"><strong>Mga panuntunan na dapat sundin ng mga kalahok sa proyekto:</strong></h6>

            <ol>
                <li>
                Ako ay nagkukusang loob na gagamit ng Gaz Lite LPG cookstove at micro-cylinders sa halip na kahoy at/o uling para sa pagluluto.
                <br><small class="text-muted">(I volunteer to use the Gaz Lite LPG cookstove and micro-cylinders instead of firewood and/or charcoal for cooking)</small>
                </li>

                <li class="mt-3">
                Ako ay nagkukusang loob na gagamit ng Gaz Lite LPG cookstove at micro-cylinders upang ihanda ang aming pangunahing pagkain (karaniwang tanghalian) kada araw sa loob ng limang (5) araw. Kung magiging angkop, malugod na tinatanggap ang pagpapalawak ng paggamit ng LPG cookstoves at micro-cylinders sa ibang mga pagkain kung ang mga ito ay inihanda gamit ang kahoy at/o uling.
                <br><small class="text-muted">(I volunteer to use the Gaz Lite LPG cookstove and micro-cylinders to prepare at least our main meal (usually lunch) every day over a five (5) day period. If I find it suitable, I welcome to extend the use of LPG cookstoves and micro-cylinders to other meals that were cooked with firewood and/or charcoal.)</small>
                </li>

                <li class="mt-3">
                Ako ay sumasangayon na hindi gagamit ng kahalintulad na produkto maliban sa Gaz Lite. Ang aking paglabag ay nangangahulugan ng aking pagtigil at boluntaryong pag sauli ng mga naibigay sa akin na mga produckto ng Gaz Lite.
                <br><small class="text-muted">(I agree not to use any similar product other than Gaz Lite. Any violation on my part means I will cease participation and voluntarily return the Gaz Lite products that were given to me.)</small>
                </li>

                <li class="mt-3">
                Pananatilihin ko ang aking karaniwang paraan ng pagluluto sa panahon ng pilot (halimbawa, bilang ng mga myembrong pinagluluto, uri ng mga nilutong ulam).
                <br><small class="text-muted">(I will maintain my usual cooking practices during the pilot (e.g., number of people served, type(s) of dishes cooked).)</small>
                </li>
            </ol>

            {{-- <div class="signature-box mt-5">
                Lagda / Pirma (Signature)
            </div> --}}
            </div>
     

          <!-- Signature Pad -->
          <div class="mb-3 text-right">
             <img src="{{ asset($customer->signature)}}" alt="Valid ID" class="img-fluid rounded shadow-sm" style="max-height: 250px;" />
           
          </div>

          <!-- Hidden input to store signature -->
          <input type="hidden" name="contract_signature" id="contract_signature" />

        </div>
         <div class="modal-footer">
        <button type="button" class="btn bg-danger-subtle text-danger  waves-effect"
          data-bs-dismiss="modal">
          Close
        </button>
      </div>
      </div>
  </div>
</div>