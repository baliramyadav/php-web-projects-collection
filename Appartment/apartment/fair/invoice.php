<?php 
include('../header.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_add_fare.php');
if(!isset($_SESSION['objLogin'])){
	header("Location: " . WEB_URL . "logout.php");
	die();
}

//rent info
$invoice_id = '';
$invoice_created_date = '';
$invoice_issue_date = '';
$invoice_paid_date = '';
$renter_name = '';
$renter_phone = '';
$renter_email = '';
$renter_floor = '';
$renter_unit = '';
$renter_bill_month = '';
$renter_rent_bill = '0.00';
$water_bill = '0.00';
$electric_bill = '0.00';
$gas_bill = '0.00';
$guard_bill = '0.00';
$utility_bill = '0.00';
$other_bill = '0.00';
$total_bill = '0.00';
$bill_status = 0;

if(isset($_GET['rentid']) && (int)$_GET['rentid'] > 0){
	$result = mysqli_query($link,"Select *,ar.image as r_image,ar.r_name,fl.floor_no as fl_floor,u.unit_no as u_unit,m.month_name from tbl_add_fair f inner join tbl_add_floor fl on fl.fid = f.floor_no inner join tbl_add_unit u on u.uid = f.unit_no inner join tbl_add_month_setup m on m.m_id = f.month_id inner join tbl_add_rent ar on ar.rid = f.rid where f.type = 'Rented' and f.f_id = ".(int)$_GET['rentid']);
	if($row = mysqli_fetch_assoc($result)){
		$invoice_id = $_GET['rentid'];
		$invoice_created_date = date("M d, Y", strtotime($row['added_date']));
		$invoice_issue_date = date("M d, Y", strtotime($ams_helper->datepickerDateToMySqlDate($row['issue_date'])));
		$invoice_paid_date = date("M d, Y", strtotime($ams_helper->datepickerDateToMySqlDate($row['paid_date'])));
		$renter_name = $row['r_name'];
		$renter_phone = $row['r_contact'];
		$renter_email = $row['r_email'];
		$renter_floor = $row['fl_floor'];
		$renter_unit = $row['u_unit'];
		$renter_bill_month = $row['month_name'].', '. $row['xyear'];
		$renter_rent_bill = $row['rent'];
		$water_bill = $row['water_bill'];
		$electric_bill = $row['electric_bill'];
		$gas_bill = $row['gas_bill'];
		$guard_bill = $row['security_bill'];
		$utility_bill = $row['utility_bill'];
		$other_bill = $row['other_bill'];
		$total_bill = $row['total_rent'];
		$bill_status = $row['bill_status'];
	}
	mysqli_close($link);
} else {
	echo 'Wrong Request';
	die();
}
//	
?>
<!-- Content Header (Page header) -->
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->

<div class="row">
  <div class="col-md-12">
    <div align="right" style="margin-bottom:1%;"><a data-original-title="<?php echo $_data['invoice_print_text'];?>" data-toggle="tooltip" class="btn btn-success btn_save" onclick="javascript:printContent('PrintRentInvoice','<?php echo $_data['invoice_print_text'];?>');" href="javascript:void(0);"><i class="fa fa-print"></i> </a></div>
    <h1 align="center"> <u><?php echo $_data['invoice_title'];?></u> </h1>
    <div class="invoice-box" id="PrintRentInvoice">
      <table cellpadding="0" cellspacing="0">
        <tr class="top">
          <td colspan="2"><table>
              <tr>
                <td class="title" style="text-transform:uppercase;font-weight:bold;overflow:hidden;font-size:32px;"><?php echo $building_name; ?></td>
                <td><?php echo $_data['invoice_number'];?> # <?php echo $invoice_id; ?><br>
                  <?php echo $_data['invoice_issue_date'];?>: <?php echo $invoice_issue_date; ?><br>
                  <?php if(!empty($invoice_paid_date)) { ?>
                  <?php echo $_data['invoice_paid_date'];?>: <?php echo $invoice_paid_date; ?><br>
                  <?php } ?>
                  <?php echo $_data['invoice_bill_date'];?>: <?php echo $renter_bill_month; ?> </td>
              </tr>
            </table></td>
        </tr>
        <tr class="information">
          <td colspan="2"><table>
              <tr>
                <td><?php echo $row_apartment['b_address']; ?><br/>
                  <?php echo $_data['invoice_phone'];?>: <?php echo $row_apartment['b_contact_no']; ?><br/>
                  <?php echo $_data['invoice_email'];?>: <?php echo $row_apartment['b_email']; ?> </td>
                <td><?php echo $renter_name; ?><br>
                  <?php echo $_data['invoice_floor'];?>: <?php echo $renter_floor; ?>, <?php echo $_data['invoice_unit'];?>: <?php echo $renter_unit; ?><br>
                  <?php echo $renter_phone; ?><br>
                  <?php echo $renter_email; ?> </td>
              </tr>
            </table></td>
        </tr>
        <tr class="heading">
          <td><?php echo $_data['invoice_payment_method'];?> </td>
          <td><?php echo $_data['invoice_payment_method_name'];?> # </td>
        </tr>
        <tr class="details">
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr class="heading">
          <td><?php echo $_data['invoice_bill_details'];?> </td>
          <td><?php echo $_data['invoice_bill_amount'];?> </td>
        </tr>
        <tr class="item">
          <td><?php echo $_data['invoice_home_rent'];?> </td>
          <td><?php echo $ams_helper->currency($localization, $renter_rent_bill); ?> </td>
        </tr>
        <tr class="item">
          <td><?php echo $_data['invoice_water_bill'];?> </td>
          <td><?php echo $ams_helper->currency($localization, $water_bill); ?> </td>
        </tr>
        <tr class="item">
          <td><?php echo $_data['invoice_electric_bill'];?> </td>
          <td><?php echo $ams_helper->currency($localization, $electric_bill); ?> </td>
        </tr>
        <tr class="item">
          <td><?php echo $_data['invoice_gas_bill'];?> </td>
          <td><?php echo $ams_helper->currency($localization, $gas_bill); ?> </td>
        </tr>
        <tr class="item">
          <td><?php echo $_data['invoice_guard_bill'];?> </td>
          <td><?php echo $ams_helper->currency($localization, $guard_bill); ?> </td>
        </tr>
        <tr class="item">
          <td><?php echo $_data['invoice_utility_bill'];?> </td>
          <td><?php echo $ams_helper->currency($localization, $utility_bill); ?> </td>
        </tr>
        <tr class="item last">
          <td><?php echo $_data['invoice_other_bill'];?> </td>
          <td><?php echo $ams_helper->currency($localization, $other_bill); ?> </td>
        </tr>
        <tr class="total">
          <td></td>
          <td><?php echo $_data['invoice_total_bill'];?>: <?php echo $ams_helper->currency($localization, $total_bill); ?> </td>
        </tr>
      </table>
      <?php if($bill_status=='0') { ?>
      <div class="bill-status-logo"> <img style="width:100px;" src="<?php echo WEB_URL ?>img/due.png"> </div>
      <?php } else { ?>
      <div class="bill-status-logo"> <img style="width:100px;" src="<?php echo WEB_URL ?>img/paid.png"> </div>
      <?php } ?>
      <div class="invoice-signature">
        <div>-------------------------</div>
        <div class="signature-text"><?php echo $_data['invoice_signature'];?></div>
      </div>
    
	<style>
	.signature-text{
		padding-right:5%;
	}
	.invoice-signature{
		text-align:right;
		margin-top:30px;
	}
	.bill-status-logo{
		padding-left:10%;
	}
	.invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px !important;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 16px;
        line-height: 24px;
        color: #555;
		background:#fff;
		margin-top:35px;
    }
    
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }
    
    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }
    
    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }
    
    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }
    
    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }
    
    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    
    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }
    
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    
    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }
    
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }
    
    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }
    
    .rtl table {
        text-align: right;
    }
    
    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
    </style>
	
	</div>
  </div>
  <!-- /.box -->
</div>
<!-- /.row -->
<?php include('../footer.php'); ?>
