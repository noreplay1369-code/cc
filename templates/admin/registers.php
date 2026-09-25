<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<?php
global $op_warehouse;
global $op_register;
global $op_woo;
global $OPENPOS_SETTING;
$op_nonce = wp_create_nonce( 'op_nonce' );
$warehouses = $op_warehouse->warehouses();
$cashiers = $op_woo->get_cashiers();
$registers = $op_register->registers();
$openpos_type = $OPENPOS_SETTING->get_option('openpos_type','openpos_pos');
$default = array(
    'id' => 0,
    'name' => '',
    'warehouse' => 0,
    'cashiers' => array(),
    'register_mode' => 'cashier',
    'status' => 'publish',
);
$is_new = true;
if(isset($_GET['id']) && $id = $_GET['id'])
{
    $current_register = $op_register->get($id);
    if(!empty($current_register))
    {
        $default = $current_register;
        $is_new = false;
    }
}
$modes = $op_register->get_modes();
?>
<style type="text/css">
    /* ---- WooCommerce-style skin for Registers (markup & hooks untouched) ---- */
    .op-wc-registers .register-name ul { list-style: none; display: block; margin: 6px 0 0; padding: 0; }
    .op-wc-registers .register-name ul li { float: left; padding: 0 6px 0 0; display: inline-block; color: #c3c4c7; }
    .op-wc-registers .cashiers ul { list-style: none; margin: 0; padding: 0; }

    .op-wc-registers .container-fluid { padding: 0; margin-top: 16px; }
    .op-wc-registers .row { display: flex; flex-wrap: wrap; gap: 20px; margin: 0; align-items: flex-start; }
    .op-wc-registers .row > [class*="col-"] { padding: 0; float: none; }

    /* Panels -> WC cards (flex ratios provide form:table = 1:2 with a real gap) */
    .op-wc-registers .register-frm { flex: 1 1 300px; min-width: 0; }
    .op-wc-registers .op-main-content { flex: 2 1 460px; min-width: 0; }
    .op-wc-registers .register-frm,
    .op-wc-registers .op-main-content {
        background: #fff !important; border: 1px solid #e0e0e0; border-radius: 8px; padding: 0 !important; margin-bottom: 16px;
    }
    .op-wc-registers .register-frm > h4,
    .op-wc-registers .op-main-content > h4 {
        margin: 0; padding: 14px 18px; border-bottom: 1px solid #f0f0f1; font-size: 15px; font-weight: 600; color: #1e1e1e;
    }
    .op-wc-registers #register-frm { padding: 20px 22px 22px; }

    /* Form -> stacked WC fields */
    .op-wc-registers .form-group { display: block; margin: 0 0 18px; }
    .op-wc-registers .form-group .control-label {
        display: block; float: none; width: auto; text-align: left; padding: 0 0 6px;
        font-weight: 600; font-size: 13px; color: #1e1e1e;
    }
    .op-wc-registers .form-group > div[class*="col-"] { width: 100%; float: none; padding: 0; margin-left: 0 !important; }
    .op-wc-registers #register-frm > .form-group:last-child {
        margin: 26px 0 0; padding-top: 20px; border-top: 1px solid #e0e0e0; text-align: right;
    }
    .op-wc-registers #register-frm .pull-right { float: none; }
    .op-wc-registers .form-control {
        width: 100%; max-width: 100%; border: 1px solid #8c8f94; border-radius: 4px; padding: 7px 10px;
        font-size: 14px; line-height: 1.4; min-height: 38px; box-shadow: none; background: #fff; color: #2c3338;
    }
    .op-wc-registers .form-control:focus { border-color: #7f54b3; box-shadow: 0 0 0 1px #7f54b3; outline: none; }
    .op-wc-registers .checkbox { margin: 4px 0; font-size: 13px; }
    .op-wc-registers .form-text, .op-wc-registers .help-block, .op-wc-registers .text-muted { color: #757575; font-size: 12px; margin-top: 4px; display: block; }

    /* Buttons */
    .op-wc-registers .btn {
        display: inline-flex; align-items: center; gap: 6px; border-radius: 4px; font-weight: 600;
        padding: 8px 16px; font-size: 13px; border: 1px solid #c3c4c7; background: #f6f7f7; color: #2c3338; cursor: pointer;
        text-decoration: none; transition: background .15s ease, border-color .15s ease, color .15s ease;
    }
    .op-wc-registers #register-frm button[type="submit"] { background: #7f54b3; border-color: #7f54b3; color: #fff; }
    .op-wc-registers #register-frm button[type="submit"]:hover { background: #6b4794; border-color: #6b4794; }

    /* Table -> WC */
    .op-wc-registers .register-list { width: 100%; border-collapse: collapse; border: 0; }
    .op-wc-registers .register-list th {
        text-align: left; padding: 12px 16px; background: #fff; border-bottom: 1px solid #e0e0e0;
        font-size: 11px; text-transform: uppercase; letter-spacing: .03em; color: #757575; font-weight: 600;
    }
    .op-wc-registers .register-list td { padding: 12px 16px; border-top: 1px solid #f0f0f1; vertical-align: top; color: #2c3338; }
    .op-wc-registers .register-list tr:hover td { background: #f9f7fc; }
    .op-wc-registers .register-name > p { margin: 0; font-weight: 600; }
    .op-wc-registers .register-name > p span,
    .op-wc-registers .warehouse-name > p span { background: #7f54b3 !important; border-radius: 4px; font-weight: 600; }
    .op-wc-registers .register-name ul li a,
    .op-wc-registers .cashiers a { color: #7f54b3; text-decoration: none; font-weight: 500; }
    .op-wc-registers .register-name ul li a:hover,
    .op-wc-registers .cashiers a:hover { text-decoration: underline; }
    .op-wc-registers .delete-register-btn { color: #d63638 !important; }

    /* Status pills */
    .op-wc-registers .status-publish, .op-wc-registers .status-draft {
        display: inline-block; padding: 2px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;
    }
    .op-wc-registers .status-publish { background: #c6e1c6; color: #2c4700; }
    .op-wc-registers .status-draft { background: #e6e6e6; color: #50575e; }

    @media screen and (max-width: 960px) {
        .op-wc-registers .row > [class*="col-"] { flex: 0 0 100%; max-width: 100%; }
    }
</style>
<div class="op-admin-wrap wrap op-wc-registers">
    <div id="wrap-loading">
        <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
    </div>
    <h1 class="wp-heading-inline"><?php echo __( 'Registers', 'openpos' ); ?></h1>
    <br class="clear" />
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-4 register-frm">
                <h4><?php echo ($is_new) ?  __( 'New Register', 'openpos' ) : __( 'Edit Register', 'openpos' ); ?></h4>
                <form class="form-horizontal" id="register-frm">
                    <input type="hidden" name="action" value="openpos_update_register">
                    <input type="hidden" name="op_nonce" value="<?php echo $op_nonce; ?>">
                    <input type="hidden" name="id" value="<?php echo $default['id']; ?>">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label"><?php echo __( 'Name', 'openpos' ); ?></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" value="<?php echo $default['name']; ?>" placeholder="<?php echo __( 'Register Name', 'openpos' ); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label"><?php echo __( 'Outlet', 'openpos' ); ?></label>
                        <div class="col-sm-10">
                            <select class="form-control" name="warehouse">
                                <?php foreach ($warehouses as $w): ?>
                                <option <?php echo ($default['warehouse'] == $w['id'] ) ? 'selected':''; ?> value="<?php echo $w['id']; ?>"><?php echo $w['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small id="emailHelp" class="form-text text-muted"><?php echo __( 'Default online store = Online woocommerce website stock', 'openpos' ); ?></small>
                        </div>

                    </div>

                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label"><?php echo __( 'Cashiers', 'openpos' ); ?></label>
                        <div class="col-sm-10">
                            <?php foreach($cashiers as $cashier):?>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" <?php echo in_array( $cashier->ID,$default['cashiers']) ? 'checked':''; ?> name="cashiers[]" value="<?php echo $cashier->ID; ?>"><?php echo $cashier->display_name; ?>
                                </label>
                            </div>
                           <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><?php echo __( 'Mode', 'openpos' ); ?></label>
                        <div class="col-sm-8">
                            <select class="form-control" name="register_mode">
                                <?php foreach($modes as $code => $label): ?>
                                    <?php 
                                        if($openpos_type != 'restaurant' && $code == 'waiter'){
                                            continue;
                                        } 
                                    ?>
                                    <option <?php echo (!isset($default['register_mode']) || !$default['register_mode'] || $default['register_mode'] == $code  ) ? 'selected':''; ?> value="<?php echo $code; ?>"><?php echo $label; ?></option>
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
                    <?php do_action('op_register_form_end',$default,$warehouses,$cashiers); ?>
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-4">
                            <button type="submit" class="btn btn-default"><?php echo __( 'Save', 'openpos' ); ?></button>
                        </div>
                    </div>
                </form>
                <?php do_action('op_register_form_after',$default,$warehouses,$cashiers); ?>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-8 op-main-content">
                <h4><?php echo __( 'All Registers', 'openpos' ); ?></h4>
                <div class="table-responsive">
                    <table class="table register-list">
                        <tr>
                            <th><?php echo __( 'Name', 'openpos' ); ?></th>
                            <th><?php echo __( 'Cashiers', 'openpos' ); ?></th>
                            <th><?php echo __( 'Outlet', 'openpos' ); ?></th>
                            <th><?php echo __( 'Balance', 'openpos' ); ?></th>
                            <th><?php echo __( 'Status', 'openpos' ); ?></th>
                        </tr>
                        <?php foreach($registers as $register): ?>
                        <?php
                            $register_cashiers = array();

                            $meta_cashiers = $register['cashiers'];
                            foreach($meta_cashiers as $user_id)
                            {
                                $user = get_userdata($user_id);
                                if($user)
                                {
                                    $register_cashiers[] = $user;
                                }
                            }
                            $outlet = $op_warehouse->get($register['warehouse']);
                            $all_menu  = $op_register->admin_register_menu($register,$openpos_type);
                        ?>
                        <tr>
                            <td class="register-name">
                                <p><span style="color: #fff;background: #009688;padding: 2px 6px;margin-right: 3px;"><?php echo $register['id']; ?></span><?php echo $register['name']; ?></p>
                                <ul>
                                    <?php foreach( $all_menu as $k => $menu ): $attributes = isset($menu['attributes']) ? $menu['attributes'] : array(); ?>
                                        <li>
                                            <a href="<?php echo $menu['url']; ?>" <?php foreach($attributes as $attr_key => $att_value): ?> <?php echo $attr_key.'="'.$att_value.'"'; ?> <?php endforeach;?> >
                                                <?php echo $menu['label']; ?>
                                            </a>
                                        </li>
                                        <?php if($k+1 < count($all_menu)): ?>
                                        <li>|</li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                    
                                </ul>
                            </td>
                            <td class="cashiers">
                                <ul>
                                    <?php foreach($register_cashiers as $register_cashier): ?>
                                    <li><a href="<?php echo admin_url('user-edit.php?user_id='.$register_cashier->ID); ?>"><?php echo $register_cashier->display_name; ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                            <td>
                                <p><?php echo $outlet['name']; ?></p>
                            </td>
                            <td>
                                <p><?php echo wc_price($register['balance']); ?></p>
                            </td>
                            <td>
                                <span class="status-<?php echo esc_attr($register['status']); ?>"><?php echo $register['status'] == 'publish' ? 'Active' : 'Inactive'; ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(count($registers) == 0): ?>
                            <tr>
                                <td colspan="4"><?php echo __('No register found','openpos'); ?></td>
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
                            window.location.href = '<?php echo admin_url('admin.php?page=op-registers'); ?>';

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
                        data: {action: 'openpos_delete_register',id:id, op_nonce : "<?php echo $op_nonce; ?>"},
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