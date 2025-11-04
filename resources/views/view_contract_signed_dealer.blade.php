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
                    <th>Date: {{date('M d,Y',strtotime($dealer->created_at))}}</th>
                    <th></th>
                </tr>
                <tr>
                    <td colspan=4>Name of Dealer: {{$dealer->name}}</td>
                </tr>
                 <tr>
                    <td colspan=4>Address: {{$dealer->address}}</td>
                </tr>
                 <tr>
                    <td colspan=4>CONTACT NO.: {{$dealer->number}}</td>
                </tr>
                 <tr>
                    <td colspan=3>Store Name.: {{$dealer->store_name}}</td>
                    <td>ID No. DEALER-{{date('Y',strtotime($dealer->created_at))}}-{{$dealer->id}}</td>
                </tr>
                 <tr>
                    <td colspan=4 style='background-color:gray;'>KASUNDUAN SA PAKIKIBAHAGI SA PROYEKTO NG CARBON CREDIT GAZ LITE FEASIBILITY STUDY</td>
                </tr>
            </table>
          <div class="form-section">
                <p>
                    Ito ay pagpapatunay na ako, <b><u>{{$dealer->name}}</b></u>, ay pumapayag na makibahagi sa proyekto ng <strong>CARBON CREDIT GAZ LITE FEASIBILITY STUDY</strong>.
                </p>

                <h6 class="mt-4"><strong>Mga panuntunan na dapat sundin ng mga kalahok sa proyekto:</strong></h6>

                <ol>
                    <li>
                        Ako ay nagkukusang loob na magbebenta ng Gaz Lite LPG cookstove at micro-cylinders sa halip na kahoy at/o uling para sa pagluluto ng mga kalahok sa aking nasasakupan. 
                        <br><small class="text-muted">(I volunteer to sell and promote Gaz Lite LPG cookstove and micro-cylinders instead of firewood and/or charcoal for cooking to program participants in my area coverage)</small>
                    </li>

                    <li class="mt-3">
                        Ako ay nagkukusang loob na gagamit ng Gaz Lite Application upang maitala lahat ng mabibiling produkto ng Gaz Lite cookstove at micro-cylinders ng mga nakilahok sa programa ng Carbon Project Genesis.
                        <br><small class="text-muted">(I volunteer to use the Gaz Lite Application to record all cookstove and micro-cylinders sales from the participating household of the Project Genesis.)</small>
                    </li>

                    <li class="mt-3">
                        Ako ay sumasangayon na hindi magbebenta ng kahalintulad na produkto ng Gaz Lite habang ako ay kalahok dito sa programa ng Project Genesis. Ang aking paglabag ay nangangahulugan ng aking kusang loob na pagtigil ng pagbenta ng lahat produkto Gaz Lite at aking kikilalanin ang hakbang ng PREI na magtalaga ng bagong Dealer na mag seserbisyo sa mga kalahok ng 10 taong programa. 
                        <br><small class="text-muted">(I agree not to sell any product similar to Gaz Lite while I am participating in the Project Genesis. Any violation on my part means my voluntary cessation of selling all Gaz Lite products and I further acknowledge that PREI has the right to assign another dealer in my area to serve the participants of the ten year program.)</small>
                    </li>

                    <li class="mt-3">
                        Ako ay sumasangayon na sa bawat isang Gaz Lite micro-cylinder refill 330gm na aking maibebenta sa mga kalahok ng programa ay magkakaroon ako ng (1) isang reward point na maari kong gamitin sa marketing promos or pagbili ng produkto. 
                        <br><small class="text-muted">(I agree that for every Gaz Lite micro-cylinder refill (330g) that I sell to the program participants, I will receive 1 point. The total points may be redeemed to purchase gazlite products subject to the redemption policy of PREI.)</small>
                    </li>

                    <li class="mt-3">
                        Ako ay sumasangayon na kailangang mapanatili ang angkop na numero ng inbentaryo ng Gaz Lite upang maiwasan ang anumang aberya. 
                        <br><small class="text-muted">(I agree that I have to maintain an adequate inventory of Gaz Lite products to avoid running out of inventory. Any violation on my part means my voluntary cessation of selling all Gaz Lite products and I further acknowledge that PREI has the right to assign another dealer in my area to serve the participants of the ten year program.)</small>
                    </li>
                </ol>
            </div>
     

          <!-- Signature Pad -->
          <div class="mb-3 text-right">
             <img src="{{ asset($dealer->signature)}}" alt="Valid ID" class="img-fluid rounded shadow-sm" style="max-height: 250px;" />
           
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