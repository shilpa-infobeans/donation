<?php
/**
 * Template part for displaying donation report
 *
 *
 * @package Base_Theme
 */
ob_start(); 
?>

<div class="history__Sec">
  <div class="row">
    <div class="col-xs-12 ">
      <div id="history" class="tab-pane fade in active">
        <h3>Donation Report</h3>
        <div class="list_history">
          <table id="donation_data" class="display" style="width:100%">
            <thead>
              <tr>
                <th>Sr No.</th>
                <th>Donator name</th>
                <th>Donator Phone Number</th>
                <th>Donator Email</th>
                <th>Donation Amount</th>
                <th>Payment Status</th>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th>Sr No.</th>
                <th>Donator name</th>
                <th>Donator Phone Number</th>
                <th>Donator Email</th>
                <th>Donation Amount</th>
                <th>Payment Status</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php  echo ob_get_clean(); 