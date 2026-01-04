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
$o_name = '';
$o_email = '';
$o_contact = '';
$o_pre_address = '';
$month_name = '';
$y_xyear = '';
$total_amount = '';

if(isset($_GET['fundid']) && (int)$_GET['fundid'] > 0){
	$result = mysqli_query($link,"Select *,ow.o_name,ow.image,m.month_name,y.xyear as y_xyear from tbl_add_fund fu inner join tbl_add_owner ow on ow.ownid = fu.owner_id inner join tbl_add_month_setup m on m.m_id = fu.month_id inner join tbl_add_year_setup y on y.y_id = fu.xyear where fund_id = ".(int)$_GET['fundid']." and fu.branch_id = " . (int)$_SESSION['objLogin']['branch_id'] . " order by fu.fund_id desc");
	if($row = mysqli_fetch_assoc($result)){
		$invoice_id = $_GET['fundid'];
		$invoice_created_date = date("M d, Y", strtotime($ams_helper->datepickerDateToMySqlDate($row['f_date'])));
		$o_name = $row['o_name'];
		$o_email = $row['o_email'];
		$o_contact = $row['o_contact'];
		$o_pre_address = $row['o_pre_address'];
		$month_name = $row['month_name'];
		$y_xyear = $row['y_xyear'];
		$total_amount = $row['total_amount'];
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
    <h1 align="center"> <u><?php echo $_data['invoice_title_fund'];?></u> </h1>
    <div class="invoice-box" id="PrintRentInvoice">
      <table cellpadding="0" cellspacing="0">
        <tr class="top">
          <td colspan="2"><table>
              <tr>
                <td class="title" style="text-transform:uppercase;font-weight:bold;overflow:hidden;font-size:32px;"><?php echo $building_name; ?></td>
                <td><?php echo $_data['invoice_number'];?> # <?php echo $invoice_id; ?><br>
                  <?php echo $_data['invoice_issue_date'];?>: <?php echo $invoice_created_date; ?><br>
                  <?php echo $_data['invoice_bill_date'];?>: <?php echo $month_name; ?>, <?php echo $y_xyear; ?> </td>
              </tr>
            </table></td>
        </tr>
        <tr class="information">
          <td colspan="2"><table>
              <tr>
                <td><?php echo $row_apartment['b_address']; ?><br/>
                  <?php echo $_data['invoice_phone'];?>: <?php echo $row_apartment['b_contact_no']; ?><br/>
                  <?php echo $_data['invoice_email'];?>: <?php echo $row_apartment['b_email']; ?> </td>
                <td><?php echo $o_name; ?><br>
                  <?php echo $o_contact; ?><br>
                  <?php echo $o_email; ?> </td>
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
        <tr class="item last">
          <td><?php echo $_data['invoice_fund_details'];?> </td>
          <td><?php echo $ams_helper->currency($localization, $total_amount); ?> </td>
        </tr>
        <tr class="total">
          <td></td>
          <td><?php echo $_data['invoice_total_bill'];?>: <?php echo $ams_helper->currency($localization, $total_amount); ?> </td>
        </tr>
      </table>
      
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
		margin-top:50px;
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
