<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<?php
global $op_warehouse;
global $op_receipt;
global $op_woo;
global $OPENPOS_SETTING;
$openpos_type = $OPENPOS_SETTING->get_option('openpos_type','openpos_pos');
$op_nonce = wp_create_nonce( 'op_nonce' );
$templates = $op_receipt->templates();
$receipt_types = $op_receipt->receipt_types();

$default = array(
    'id' => 0,
    'name' => '',
    'type' => 'receipt',
    'status' => ''
);
$is_new = true;
if(isset($_GET['id']) && $id = $_GET['id'])
{
    $current_register = $op_receipt->get($id);
   
    if(!empty($current_register))
    {
        $default = $current_register;
        $is_new = false;
    }
}

?>
<style type="text/css">
    /* ---- WooCommerce-style skin for Receipt Templates (markup & JS untouched) ---- */
    .op-wc-receipts .register-name ul { list-style: none; display: block; margin: 6px 0 0; padding: 0; }
    .op-wc-receipts .register-name ul li { float: left; padding: 0 6px 0 0; display: inline-block; color: #c3c4c7; }

    .op-wc-receipts .container-fluid { padding: 0; margin-top: 16px; }
    .op-wc-receipts .row { display: flex; flex-wrap: wrap; gap: 20px; margin: 0; align-items: flex-start; }
    .op-wc-receipts .row > [class*="col-"] { padding: 0; float: none; }

    /* Panels -> WC cards (form:list = 1:2 with a real gap) */
    .op-wc-receipts .register-frm { flex: 1 1 280px; min-width: 0; }
    .op-wc-receipts .op-list-col { flex: 2 1 480px; min-width: 0; }
    .op-wc-receipts .register-frm,
    .op-wc-receipts .op-list-col {
        background: #fff !important; border: 1px solid #e0e0e0; border-radius: 8px; padding: 0 !important; margin-bottom: 16px;
    }
    .op-wc-receipts .register-frm > h4,
    .op-wc-receipts .op-list-col > h4 {
        margin: 0; padding: 14px 18px; border-bottom: 1px solid #f0f0f1; font-size: 15px; font-weight: 600; color: #1e1e1e;
    }
    .op-wc-receipts #register-frm { padding: 20px 22px 22px; }

    /* Form -> stacked WC fields */
    .op-wc-receipts .form-group { display: block; margin: 0 0 18px; }
    .op-wc-receipts .form-group .control-label {
        display: block; float: none; width: auto; text-align: left; padding: 0 0 6px; font-weight: 600; font-size: 13px; color: #1e1e1e;
    }
    .op-wc-receipts .form-group > div[class*="col-"] { width: 100%; float: none; padding: 0; margin-left: 0 !important; }
    .op-wc-receipts #register-frm > .form-group:last-child {
        margin: 26px 0 0; padding-top: 20px; border-top: 1px solid #e0e0e0; text-align: right;
    }
    .op-wc-receipts .form-control {
        width: 100%; max-width: 100%; border: 1px solid #8c8f94; border-radius: 4px; padding: 7px 10px;
        font-size: 14px; line-height: 1.4; min-height: 38px; box-shadow: none; background: #fff; color: #2c3338;
    }
    .op-wc-receipts .form-control:focus { border-color: #7f54b3; box-shadow: 0 0 0 1px #7f54b3; outline: none; }

    /* Buttons */
    .op-wc-receipts .btn {
        display: inline-flex; align-items: center; gap: 6px; border-radius: 4px; font-weight: 600; padding: 8px 16px;
        font-size: 13px; border: 1px solid #c3c4c7; background: #f6f7f7; color: #2c3338; cursor: pointer; text-decoration: none;
        transition: background .15s ease, border-color .15s ease;
    }
    .op-wc-receipts #register-frm button[type="submit"] { background: #7f54b3; border-color: #7f54b3; color: #fff; }
    .op-wc-receipts #register-frm button[type="submit"]:hover { background: #6b4794; border-color: #6b4794; }

    /* Table -> WC */
    .op-wc-receipts .template-list { width: 100%; border-collapse: collapse; border: 0; }
    .op-wc-receipts .template-list th {
        text-align: left; padding: 12px 16px; background: #fff; border-bottom: 1px solid #e0e0e0;
        font-size: 11px; text-transform: uppercase; letter-spacing: .03em; color: #757575; font-weight: 600;
    }
    .op-wc-receipts .template-list th.text-center { text-align: center; }
    .op-wc-receipts .template-list td { padding: 12px 16px; border-top: 1px solid #f0f0f1; vertical-align: top; color: #2c3338; }
    .op-wc-receipts .template-list tr:first-child td { border-top: 0; }
    .op-wc-receipts .template-list td .text-center { text-align: center; margin: 0; }
    .op-wc-receipts .template-list tr:hover td { background: #f9f7fc; }
    .op-wc-receipts .register-name > p { margin: 0; font-weight: 600; }
    .op-wc-receipts .register-name > p span { background: #7f54b3 !important; border-radius: 4px; font-weight: 600; }
    .op-wc-receipts .register-name ul li a { color: #7f54b3; text-decoration: none; font-weight: 500; }
    .op-wc-receipts .register-name ul li a:hover { text-decoration: underline; }
    .op-wc-receipts .delete-register-btn { color: #d63638 !important; }

    /* Status pills */
    .op-wc-receipts .template-list .status {
        display: inline-block; padding: 2px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; background: #e6e6e6; color: #50575e;
    }
    .op-wc-receipts .template-list .status-publish { background: #c6e1c6; color: #2c4700; }

    @media screen and (max-width: 960px) {
        .op-wc-receipts .row > [class*="col-"] { flex: 0 0 100%; max-width: 100%; }
    }
</style>
<div class="op-admin-wrap wrap op-wc-receipts">
    <div id="wrap-loading">
        <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
    </div>
    <h1 class="wp-heading-inline"><?php echo __( 'Receipt templates', 'openpos' ); ?></h1>
    <br class="clear" />
    <div class="container-fluid">
        
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-4 register-frm">

            <h4><?php echo ($is_new) ?  __( 'New template', 'openpos' ) : __( 'Edit template', 'openpos' ); ?></h4>
                <form class="form-horizontal" id="register-frm">
                    <input type="hidden" name="action" value="openpos_update_receipt_template">
                    <input type="hidden" name="id" value="<?php echo $default['id']; ?>">
                    <input type="hidden" name="op_nonce" value="<?php echo $op_nonce; ?>">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label"><?php echo __( 'Name', 'openpos' ); ?></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" value="<?php echo $default['name']; ?>" placeholder="Template Name">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo __( 'Type', 'openpos' ); ?></label>
                        <div class="col-sm-4">
                            <select class="form-control" name="type">
                                    <?php foreach( $receipt_types as $code => $type): ?> 
                                    <option <?php echo ($default['type'] == $code) ? 'selected':''; ?> value="<?php echo  $code; ?>"><?php echo $type['label']; ?></option>
                                    <?php endforeach; ?> 
                                    
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo __( 'Status', 'openpos' ); ?></label>
                        <div class="col-sm-4">
                            <select class="form-control" name="status">
                                    <option <?php echo ($default['status'] == 'publish') ? 'selected':''; ?> value="publish"><?php echo __('Active','openpos'); ?></option>
                                    <option <?php echo ($default['status'] == 'draft') ? 'selected':''; ?> value="draft"><?php echo __('Inactive','openpos'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-4 text-right">
                            <button type="submit" class="btn btn-default"><?php echo __( 'Save', 'openpos' ); ?></button>
                        </div>
                    </div>
                </form>

            
            </div>
            <div class="col-xs-12 col-sm-12 col-md-8 op-list-col">
                <h4><?php echo __( 'All Templates', 'openpos' ); ?></h4>
                <div class="table-responsive">
                    <table class="table template-list">
                        <tr>
                            <th><?php echo __( 'Receipt Name', 'openpos' ); ?></th>
                            <th class="text-center"><?php echo __( 'Type', 'openpos' ); ?></th>
                            <th class="text-center"><?php echo __( 'Created By', 'openpos' ); ?></th>
                            <th class="text-center"><?php echo __( 'Created At', 'openpos' ); ?></th>
                            <th class="text-center"><?php echo __( 'Status', 'openpos' ); ?></th>
                        </tr>
                        <?php foreach($templates as $template): ?>
                        <?php
                                $receipt_type_label = __('Unknown','openpos');
                                $receipt_type = isset($receipt_types[$template['type']]) ? $receipt_types[$template['type']] : null;
                                if($receipt_type != null)
                                {
                                    $receipt_type_label = $receipt_type['label'];
                                }
                        ?>
                        <tr>
                            <td class="register-name">
                                <p><span style="color: #fff;background: #009688;padding: 2px 6px;margin-right: 3px;"><?php echo $template['id']; ?></span><?php echo $template['name']; ?></p>
                                <ul>
                                    <li><a href="<?php echo admin_url('admin.php?page=op-receipt-template&op-action=composer&id='.esc_attr($template['id'])); ?>"><?php echo __('Composer','openpos'); ?></a></li>
                                    <li>|</li>
                                    <li><a href="<?php echo admin_url('admin.php?page=op-receipt-template&id='.esc_attr($template['id'])); ?>"><?php echo __('Edit','openpos'); ?></a></li>
                                    <li>|</li>
                                    <li><a href="javascript:void(0);" class="delete-register-btn" data-id="<?php echo $template['id']; ?>"><?php echo __('Delete','openpos'); ?></a></li>
                                    <li>|</li>
                                    <!-- <li><a href="javascript:void(0);" class="duplicate-register-btn" data-id="<?php echo $template['id']; ?>"><?php echo __('Duplicate','openpos'); ?></a></li>
                                    <li>|</li> -->
                                    <li><a target="_blank" href="<?php echo admin_url('admin-ajax.php?action=print_receipt&template_id='.esc_attr($template['id'])); ?>"><?php echo __('Print Sample','openpos'); ?></a></li>
                                </ul>
                            </td>
                            <td class="receipt-types">
                                <p class="text-center"><?php echo $receipt_type_label; ?></p>
                            </td>
                            <td class="cashiers">
                                <p class="text-center"><?php echo $template['created_by']; ?></p>
                            </td>
                            <td>
                                <p class="text-center"><?php echo $template['created_at']; ?></p>
                            </td>
                           
                            <td>
                                <p class="text-center"><span class="status status-<?php echo esc_attr($template['status']); ?>"><?php echo $template['status'] == 'publish' ? 'Active' : 'Inactive'; ?></span></p>
                            </td>
                        </tr>
                        <?php endforeach ?>
                        <?php if(count($templates) == 0): ?>
                            <tr>
                                <td colspan="4"><?php echo __('No template found','openpos'); ?></td>
                            </tr>
                        <?php endif; ?>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    (function($) {
        "use strict";
        $(document).ready(function(){
            $('#register-frm').on('submit',function(){
               var data = $(this).serialize();
                $.ajax({
                    url: openpos_admin.ajax_url,
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    beforeSend:function(){
                        $('body').addClass('op_loading');
                    },
                    success:function(data){
                        if(data.status == 1)
                        {
                            window.location.href = '<?php echo admin_url('admin.php?page=op-receipt-template'); ?>';


                        }else {
                            alert(data.message);
                            $('body').removeClass('op_loading');
                        }
                    },
                    error:function(){
                        $('body').removeClass('op_loading');
                    }
                });
               console.log(data);
               return false;
            });

            $(document).on('click','.delete-register-btn',function(){
                var id = $(this).data('id');

                if(confirm('Are you sure ? '))
                {
                    $.ajax({
                        url: openpos_admin.ajax_url,
                        type: 'post',
                        dataType: 'json',
                        //data:$('form#op-product-list').serialize(),
                        data: {action: 'openpos_delete_receipt',id:id, op_nonce : "<?php echo $op_nonce; ?>"},
                        beforeSend:function(){
                            $('body').addClass('op_loading');
                        },
                        success:function(data){
                            if(data.status == 1)
                            {
                                location.reload();
                            }else {
                                alert(data.message);
                                $('body').removeClass('op_loading');
                            }
                        },
                        error:function(){
                            $('body').removeClass('op_loading');
                        }
                    });
                }
            });

        });



    })( jQuery );
</script>