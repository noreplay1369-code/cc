<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<?php
global $OPENPOS_SETTING;
global $op_warehouse;
$sheet_width = $OPENPOS_SETTING->get_option('sheet_width','openpos_label',8.5);
$sheet_height = $OPENPOS_SETTING->get_option('sheet_height','openpos_label',11);
$sheet_padding_top = $OPENPOS_SETTING->get_option('sheet_margin_top','openpos_label',0.5);
$sheet_padding_right = $OPENPOS_SETTING->get_option('sheet_margin_right','openpos_label',0.188);
$sheet_padding_bottom = $OPENPOS_SETTING->get_option('sheet_margin_bottom','openpos_label',0.5);
$sheet_padding_left = $OPENPOS_SETTING->get_option('sheet_margin_left','openpos_label',0.188);
$vertical_space = $OPENPOS_SETTING->get_option('sheet_vertical_space','openpos_label',0);
$horizontal_space = $OPENPOS_SETTING->get_option('sheet_horizontal_space','openpos_label',0.125);
$label_width = $OPENPOS_SETTING->get_option('barcode_label_width','openpos_label',2.625);
$label_height = $OPENPOS_SETTING->get_option('barcode_label_height','openpos_label',1);

$label_padding_top = $OPENPOS_SETTING->get_option('barcode_label_padding_top','openpos_label',0.1);
$label_padding_right = $OPENPOS_SETTING->get_option('barcode_label_padding_right','openpos_label',0.1);
$label_padding_bottom = $OPENPOS_SETTING->get_option('barcode_label_padding_bottom','openpos_label',0.1);
$label_padding_left = $OPENPOS_SETTING->get_option('barcode_label_padding_left','openpos_label',0.1);

$barcode_width = $OPENPOS_SETTING->get_option('barcode_width','openpos_label',2);
$barcode_height = $OPENPOS_SETTING->get_option('barcode_height','openpos_label',0.5);
$barcode_label_template = $OPENPOS_SETTING->get_option('barcode_label_template','openpos_label','<p style="padding: 0; margin: 0;">[op_product attribute="name"]</p><p style="padding: 0; margin: 0;">[barcode]</p><p style="padding: 0; margin: 0;">[op_product attribute="barcode"]</p>');
$unit = $OPENPOS_SETTING->get_option('unit','openpos_label','in');
$default_qty = 30;
$product_id = isset($_GET['id']) ? 1*$_GET['id'] : 0;
if($product_id)
{
    $total_qty = $op_warehouse->get_total_qty($product_id);
    if($total_qty && $total_qty > 0)
    {
        $default_qty = $total_qty;
    }
}

$qty = isset($_GET['qty']) ? 1 * $_GET['qty'] : apply_filters( 'op_print_label_default_qty', $default_qty);
$sample_template_url = OPENPOS_URL.'/default/barcode_label_template_sample.txt';
$op_nonce = wp_create_nonce( 'op_nonce' );
?>
<div class="op-admin-wrap wrap op-wc-barcode">
    <div id="wrap-loading">
        <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
    </div>

    <p  class="page-label"> <?php echo __( 'Barcode Label Composer', 'openpos' ); ?></p>
    <div class="label-container">
    <div class="label-setting">
        <div class="op-bc-head"><span class="dashicons dashicons-tag"></span> <?php echo __( 'Label Settings', 'openpos' ); ?></div>
        <form method="post" id="label-setting-frm">
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
            <input type="hidden" name="op_nonce" value="<?php echo $op_nonce; ?>" />

            <div class="form-row" style="width: 100%;margin: 0 auto;">
                <table style="width: 100%;">
                    <tr>
                        <th><?php echo __( 'Unit:', 'openpos' ); ?></th>
                        <td>
                            <select name="unit">
                                <option value="in" <?php echo ($unit == 'in')? 'selected':''; ?>><?php echo __( 'Inch', 'openpos' ); ?></option>
                                <option value="mm" <?php echo ($unit == 'mm')? 'selected':''; ?>><?php echo __( 'Millimeter', 'openpos' ); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo __( 'Sheet Width x Height:', 'openpos' ); ?></th>
                        <td><input type="text" value="<?php echo $sheet_width; ?>" name="sheet_width"> x <input name="sheet_height" type="text" value="<?php echo $sheet_height; ?>"></td>
                    </tr>
                    <tr>
                        <th><?php echo __( 'Vertical Spacing:', 'openpos' ); ?></th>
                        <td><input type="text" name="sheet_vertical_space" value="<?php echo $vertical_space; ?>"></td>
                    </tr>
                    <tr>
                        <th><?php echo __( 'Horizontal Spacing:', 'openpos' ); ?></th>
                        <td><input type="text" name="sheet_horizontal_space"  value="<?php echo $horizontal_space; ?>"></td>
                    </tr>


                    <tr>
                        <th><?php echo __( 'Sheet Margin (top x right x bottom x left):', 'openpos' ); ?></th>
                        <td>
                            <input type="text" name="sheet_margin_top"   value="<?php echo $sheet_padding_top; ?>"> x
                            <input type="text" name="sheet_margin_right" value="<?php echo $sheet_padding_right; ?>"> x
                            <input type="text" name="sheet_margin_bottom" value="<?php echo $sheet_padding_bottom; ?>"> x
                            <input type="text" name="sheet_margin_left" value="<?php echo $sheet_padding_left; ?>">
                        </td>
                    </tr>

                    <tr>
                        <th><?php echo __( 'Label Size (w x h):', 'openpos' ); ?></th>
                        <td>
                            <input type="text" name="barcode_label_width" value="<?php echo $label_width; ?>"> x <input name="barcode_label_height" type="text" value="<?php echo $label_height; ?>">
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo __( 'Label Padding (top x right x bottom x left):', 'openpos' ); ?></th>
                        <td>
                            <input type="text" name="barcode_label_padding_top"   value="<?php echo $label_padding_top; ?>"> x
                            <input type="text" name="barcode_label_padding_right" value="<?php echo $label_padding_right; ?>"> x
                            <input type="text" name="barcode_label_padding_bottom" value="<?php echo $label_padding_bottom; ?>"> x
                            <input type="text" name="barcode_label_padding_left" value="<?php echo $label_padding_left; ?>">
                        </td>
                    </tr>

                    <tr>
                        <th><?php echo __( 'Barcode Image Size ( w x h ):', 'openpos' ); ?></th>
                        <td>
                            <input type="text" name="barcode_width" value="<?php echo $barcode_width; ?>"> x <input name="barcode_height" type="text" value="<?php echo $barcode_height; ?>">
                        </td>
                    </tr>
                    <tr>
                        <th style="vertical-align:top;"><?php echo __( 'Template:', 'openpos' ); ?></th>
                        <td>
                            <div class="op-bb-tabs">
                                <button type="button" class="op-bb-tab is-active" data-mode="builder"><span class="dashicons dashicons-layout"></span> <?php echo __( 'Builder', 'openpos' ); ?></button>
                                <button type="button" class="op-bb-tab" data-mode="code"><span class="dashicons dashicons-editor-code"></span> <?php echo __( 'Code', 'openpos' ); ?></button>
                                <span class="op-bb-hint"><?php echo __( 'Drag blocks to build your label — no coding needed.', 'openpos' ); ?></span>
                            </div>
                            <div class="op-bb" data-pane="builder">
                                <div class="op-bb-palette"></div>
                                <div class="op-bb-canvas"><div class="op-bb-empty"><?php echo __( 'Drag blocks here, or click a block on the left to add it.', 'openpos' ); ?></div></div>
                            </div>
                            <div class="op-bb-code" data-pane="code" style="display:none;">
                                <textarea style="width: 100%;min-height:150px;" name="barcode_label_template"><?php echo $barcode_label_template; ?></textarea>
                                <p class="help">
                                    <?php echo __( 'use [barcode with="" height=""] to adjust barcode image, [op_product attribute="attribute_name"] with attribute name: <b>name, price ,regular_price, sale_price, width, height,length,weight</b> and accept html,inline style css string', 'openpos' ); ?>
                                </p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo __( 'Number Of Label:', 'openpos' ); ?></th>
                        <td><input type="number" name="total" value="<?php echo $qty; ?>"></td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                            <a href="javascript:void(0)" id="load-sample" data-sample="<?php echo esc_url($sample_template_url); ?>"><?php echo __( 'Load sample', 'openpos' ); ?></a>
                            <span style="margin: 0 5px;color: #ccc;">|</span>
                            <a href="javascript:void(0)" id="load-guide" data-sample="<?php echo esc_url($sample_template_url); ?>"><?php echo __( 'Quick Guide', 'openpos' ); ?></a>
                           
                            
                        </td>
                    </tr>
                    <tr>
                        <th></th>
                        <td>
                           
                            <button type="button"  id="preview-label-btn"><?php echo __( 'Save And Preview', 'openpos' ); ?></button>
                            <button type="button" id="print-label-btn" name="print" ><?php echo __( 'Print', 'openpos' ); ?></button>
                            
                        </td>
                    </tr>
                </table>

            </div>
        </form>
    </div>
    <div class="preview-live">
        <div class="op-bc-head"><span class="dashicons dashicons-visibility"></span> <?php echo __( 'Live Preview', 'openpos' ); ?></div>
        <iframe id="preview-frame" style="width:calc(100% - 1px);height:100%;min-height:490px;    background: #fff;
        border: none;" src=""><?php echo __( 'Preview', 'openpos' ); ?></iframe>
    </div>
    </div>


</div>


<!-- Modal HTML cho Quick Guide -->
<div id="op-guide-modal" class="op-modal" style="display:none;">
    <div class="op-modal-overlay"></div>
    <div class="op-modal-content">
        <span class="op-modal-close">&times;</span>
        <h2><?php echo __('Barcode Label Quick Guide', 'openpos'); ?></h2>
        <div class="op-modal-body">
            <img src="<?php echo OPENPOS_URL; ?>/assets/images/label-guide.png" alt="<?php esc_attr_e('Guide', 'openpos'); ?>" class="op-guide-image" onerror="this.style.display='none'" />
            
            <div class="op-guide-text">
                
                <h3><?php echo __('Sample Template:', 'openpos'); ?></h3>
                <pre style="background: #f5f5f5; padding: 10px; border-radius: 4px; overflow-x: auto; font-size: 12px;">
                    &lt;div style="text-align: center; padding: 5px;"&gt;
                        &lt;p style="margin: 0; font-weight: bold; font-size: 14px;"&gt;[op_product attribute="name"]&lt;/p&gt;
                        &lt;p style="margin: 5px 0;"&gt;[barcode]&lt;/p&gt;
                        &lt;p style="margin: 0; font-size: 12px;"&gt;[op_product attribute="barcode"]&lt;/p&gt;
                        &lt;p style="margin: 0; font-size: 10px;"&gt;Price: [op_product attribute="price"]&lt;/p&gt;
                    &lt;/div&gt;
                </pre>

                
            </div>
        </div>
    </div>
</div>


<style>
    #print-label-btn{
        border: solid 1px #000;
        padding:  5px 7px;
        text-transform: uppercase;
        margin-top: 15px;
        background:  #000;
        color: #fff;
    }
    #preview-label-btn{
        border: solid 1px blue;
        padding:  5px 7px;
        text-transform: uppercase;
        margin-top: 15px;
        background:  blue;
        color: #fff;
    }
    form input{
        width: 100px;
        text-align: right;
        padding: 5px 2px;
    }
    .form-row tr:nth-child(odd){
        background: #e6e6e6;
    }
    .form-row tr td{
        padding: 5px;
    }
    .form-row th{
        text-align: left;
        font-size:10px;
        padding-left: 5px;
    }
    .label-setting{
        width: calc(50% - 2px);
        float: left;
        overflow: auto;
        background: #ccc;
        padding: 5px ;
        min-height: 500px;
        border:solid 1px #ccc;
    }
    .preview-live{
        float: left;
        height: fit-content;
        display: block;
        width: calc(50% - 2px);
        overflow: auto;
        min-height: 500px;
        border:solid 1px #00BCD4;
        padding: 5px 0;
        background: #00BCD4;
    }
    .page-label{
        font-size: 20px;
        font-weight: bold;
        padding: 20px 0;
    }
    .label-setting input{
        width: 60px!important;
    }

    /* Modal Styles */
    .op-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 999999;
    }
    
    .op-modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        animation: op-fadeIn 0.3s ease-in-out;
    }
    
    .op-modal-content {
        position: relative;
        background: #fff;
        max-width: 900px;
        max-height: 90vh;
        margin: 2% auto;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        overflow-y: auto;
        z-index: 1000000;
        animation: op-slideDown 0.3s ease-out;
    }
    
    @keyframes op-fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes op-slideDown {
        from { 
            transform: translateY(-50px);
            opacity: 0;
        }
        to { 
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    .op-modal-close {
        position: absolute;
        top: 10px;
        right: 20px;
        font-size: 32px;
        font-weight: bold;
        color: #999;
        cursor: pointer;
        line-height: 1;
        transition: color 0.2s;
        z-index: 10;
    }
    
    .op-modal-close:hover {
        color: #333;
    }
    
    .op-modal-content h2 {
        margin: 0 0 20px 0;
        padding: 0 0 15px 0;
        font-size: 24px;
        color: #333;
        border-bottom: 3px solid #00BCD4;
    }
    
    .op-modal-body {
        padding: 10px 0;
    }
    
    .op-guide-image {
        max-width: 100%;
        height: auto;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-bottom: 20px;
        display: block;
    }
    
    .op-guide-text h3 {
        color: #333;
        margin: 25px 0 15px 0;
        font-size: 18px;
        border-left: 4px solid #00BCD4;
        padding-left: 12px;
        font-weight: 600;
    }
    
    .op-guide-text ul {
        list-style: none;
        padding: 0;
        margin: 10px 0 20px 0;
    }
    
    .op-guide-text ul li {
        padding: 10px;
        border-bottom: 1px solid #f0f0f0;
        line-height: 1.6;
    }
    
    .op-guide-text ul li:last-child {
        border-bottom: none;
    }
    
    .op-guide-text ul li:hover {
        background-color: #f9f9f9;
    }
    
    .op-guide-text strong {
        color: #00BCD4;
        font-family: 'Courier New', monospace;
        background: #f5f5f5;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 13px;
    }
    
    .op-guide-text p {
        line-height: 1.8;
        color: #555;
        margin: 10px 0;
    }
    
    .op-guide-text pre {
        background: #f5f5f5;
        padding: 15px;
        border-radius: 4px;
        overflow-x: auto;
        font-size: 13px;
        border: 1px solid #e0e0e0;
        line-height: 1.5;
    }
    
    body.op-modal-open {
        overflow: hidden;
    }
    
    #load-guide, #load-sample {
        color: #0073aa;
        text-decoration: none;
        font-weight: 500;
        cursor: pointer;
    }
    
    #load-guide:hover, #load-sample:hover {
        color: #005177;
        text-decoration: underline;
    }
    
    @media (max-width: 768px) {
        .op-modal-content {
            margin: 10px;
            padding: 20px;
            max-width: calc(100% - 20px);
            max-height: calc(100vh - 20px);
        }
        
        .op-modal-content h2 {
            font-size: 20px;
        }
        
        .op-guide-text h3 {
            font-size: 16px;
        }
        
        .op-guide-text pre {
            font-size: 11px;
            padding: 10px;
        }
    }

    /* ===== WooCommerce-style skin (overrides) ===== */
    .op-wc-barcode { margin-right: 20px; }
    .op-wc-barcode .page-label { font-size: 23px; font-weight: 600; padding: 0; margin: 8px 0 16px; color: #1e1e1e; }
    .op-wc-barcode .label-container { display: flex; gap: 20px; align-items: stretch; }
    .op-wc-barcode .label-setting, .op-wc-barcode .preview-live {
        float: none !important; width: auto !important; flex: 1 1 0; min-width: 0; min-height: 0 !important;
        background: #fff !important; border: 1px solid #e0e0e0 !important; border-radius: 8px; padding: 0 !important; overflow: visible;
    }
    .op-wc-barcode .preview-live { display: flex; flex-direction: column; }
    .op-wc-barcode .op-bc-head {
        display: flex; align-items: center; gap: 7px; padding: 14px 18px; border-bottom: 1px solid #f0f0f1;
        font-size: 15px; font-weight: 600; color: #1e1e1e;
    }
    .op-wc-barcode .op-bc-head .dashicons { color: #7f54b3; font-size: 18px; width: 18px; height: 18px; }
    .op-wc-barcode #label-setting-frm { padding: 4px 18px 18px; }
    .op-wc-barcode .form-row { width: 100% !important; }
    .op-wc-barcode .form-row table { width: 100%; border-collapse: collapse; }
    .op-wc-barcode .form-row tr { background: transparent !important; }
    .op-wc-barcode .form-row tr th, .op-wc-barcode .form-row tr td { padding: 12px 0 !important; border-bottom: 1px solid #f0f0f1; vertical-align: middle; }
    .op-wc-barcode .form-row th { text-align: left; font-size: 12px !important; font-weight: 600; color: #1e1e1e; width: 44%; padding-left: 0 !important; }
    .op-wc-barcode .form-row td { color: #50575e; }
    .op-wc-barcode .label-setting input, .op-wc-barcode .form-row input {
        width: 58px !important; text-align: center !important; border: 1px solid #c3c4c7; border-radius: 4px; padding: 6px 6px; min-height: 32px; box-shadow: none;
    }
    .op-wc-barcode .form-row input[name="total"] { width: 80px !important; }
    .op-wc-barcode .form-row input:focus, .op-wc-barcode select:focus, .op-wc-barcode textarea:focus { border-color: #7f54b3 !important; box-shadow: 0 0 0 1px #7f54b3; outline: none; }
    .op-wc-barcode select[name="unit"] { border: 1px solid #c3c4c7; border-radius: 4px; min-height: 32px; padding: 4px 8px; }
    .op-wc-barcode textarea[name="barcode_label_template"] {
        width: 100% !important; border: 1px solid #c3c4c7; border-radius: 4px; font-family: Menlo, Consolas, monospace; font-size: 12px; padding: 10px; min-height: 150px;
    }
    .op-wc-barcode .help { font-size: 11px; color: #757575; margin-top: 6px; line-height: 1.5; }
    .op-wc-barcode #load-sample, .op-wc-barcode #load-guide { color: #7f54b3; font-weight: 600; text-decoration: none; }
    .op-wc-barcode #load-sample:hover, .op-wc-barcode #load-guide:hover { text-decoration: underline; }
    .op-wc-barcode #preview-label-btn, .op-wc-barcode #print-label-btn {
        border-radius: 4px; padding: 9px 18px; font-weight: 600; font-size: 13px; text-transform: none; margin-top: 10px; cursor: pointer; border: 1px solid transparent;
    }
    .op-wc-barcode #preview-label-btn { background: #f6f7f7; color: #2c3338; border-color: #c3c4c7; margin-right: 8px; }
    .op-wc-barcode #preview-label-btn:hover { background: #f0eaf8; color: #7f54b3; border-color: #7f54b3; }
    .op-wc-barcode #print-label-btn { background: #7f54b3; color: #fff; border-color: #7f54b3; }
    .op-wc-barcode #print-label-btn:hover { background: #6b4794; border-color: #6b4794; }
    .op-wc-barcode #preview-frame { flex: 1 1 auto; border-radius: 0 0 8px 8px; background: #fff !important; }
    @media screen and (max-width: 900px){ .op-wc-barcode .label-container { flex-direction: column; } }

    /* ===== Label block builder ===== */
    .op-wc-barcode .op-bb-tabs { display:flex; align-items:center; gap:4px; margin-bottom:10px; }
    .op-wc-barcode .op-bb-tab { display:inline-flex; align-items:center; gap:5px; border:1px solid #dcdcde; background:#fff; color:#50575e; padding:5px 12px; border-radius:4px; font-size:12px; font-weight:600; cursor:pointer; }
    .op-wc-barcode .op-bb-tab .dashicons { font-size:15px; width:15px; height:15px; }
    .op-wc-barcode .op-bb-tab.is-active { background:#7f54b3; border-color:#7f54b3; color:#fff; }
    .op-wc-barcode .op-bb-hint { margin-left:auto; font-size:11px; color:#999; font-style:italic; }
    .op-wc-barcode .op-bb { display:grid; grid-template-columns:140px 1fr; gap:12px; }
    .op-wc-barcode .op-bb-palette { display:flex; flex-direction:column; gap:6px; max-height:420px; overflow:auto; padding:8px; background:#f6f7f7; border:1px solid #e0e0e0; border-radius:6px; }
    .op-wc-barcode .op-bb-chip { display:flex; align-items:center; gap:7px; padding:8px 10px; background:#fff; border:1px solid #dcdcde; border-radius:5px; font-size:12px; font-weight:600; color:#2c3338; cursor:grab; user-select:none; }
    .op-wc-barcode .op-bb-chip:hover { border-color:#7f54b3; color:#7f54b3; background:#faf8fd; }
    .op-wc-barcode .op-bb-chip .dashicons { font-size:16px; width:16px; height:16px; color:#7f54b3; }
    .op-wc-barcode .op-bb-canvas { min-height:260px; max-height:420px; overflow:auto; padding:10px; background:#fff; border:2px dashed #dcdcde; border-radius:6px; }
    .op-wc-barcode .op-bb-canvas.op-bb-over { border-color:#7f54b3; background:#faf8fd; }
    .op-wc-barcode .op-bb-empty { color:#a7aaad; text-align:center; padding:36px 10px; font-size:13px; }
    .op-wc-barcode .op-bb-block { background:#fff; border:1px solid #e0e0e0; border-radius:6px; margin-bottom:8px; }
    .op-wc-barcode .op-bb-block.is-open { border-color:#c9b6e4; }
    .op-wc-barcode .op-bb-block.op-bb-dragging { opacity:.4; }
    .op-wc-barcode .op-bb-block.op-bb-drop-before { box-shadow:0 -3px 0 #7f54b3; }
    .op-wc-barcode .op-bb-block.op-bb-drop-after { box-shadow:0 3px 0 #7f54b3; }
    .op-wc-barcode .op-bb-bar { display:flex; align-items:center; gap:8px; padding:8px 10px; }
    .op-wc-barcode .op-bb-handle { cursor:grab; color:#a7aaad; }
    .op-wc-barcode .op-bb-title { font-size:13px; font-weight:600; color:#1e1e1e; display:flex; align-items:center; gap:6px; }
    .op-wc-barcode .op-bb-title .dashicons { font-size:15px; width:15px; height:15px; color:#7f54b3; }
    .op-wc-barcode .op-bb-nametag { background:#f0eaf8; color:#7f54b3; padding:0 5px; border-radius:3px; font-size:11px; font-weight:600; }
    .op-wc-barcode .op-bb-actions { margin-left:auto; display:flex; gap:4px; }
    .op-wc-barcode .op-bb-iconbtn { border:0; background:transparent; cursor:pointer; color:#757575; padding:3px; border-radius:4px; }
    .op-wc-barcode .op-bb-iconbtn:hover { background:#f0f0f1; color:#1e1e1e; }
    .op-wc-barcode .op-bb-iconbtn.op-bb-del:hover { color:#d63638; }
    .op-wc-barcode .op-bb-opts { padding:10px 12px 12px 36px; display:none; border-top:1px solid #f0f0f1; background:#fcfcfd; }
    .op-wc-barcode .op-bb-block.is-open .op-bb-opts { display:block; }
    .op-wc-barcode .op-bb-grid { display:grid; grid-template-columns:1fr 1fr; gap:8px 12px; }
    .op-wc-barcode .op-bb-opt { display:flex; flex-direction:column; gap:3px; margin:0; font-size:11px; color:#50575e; }
    .op-wc-barcode .op-bb-opt > span { font-weight:600; }
    .op-wc-barcode .op-bb-opt.op-bb-wide { grid-column:1 / -1; }
    .op-wc-barcode .op-bb-opt input[type="text"], .op-wc-barcode .op-bb-opt textarea, .op-wc-barcode .op-bb-opt select { width:100% !important; text-align:left !important; border:1px solid #c3c4c7; border-radius:4px; padding:4px 7px; font-size:12px; min-height:30px; box-shadow:none; }
    .op-wc-barcode .op-bb-grouphead { width:100%; display:flex; align-items:center; gap:5px; background:#f6f7f7; border:0; padding:7px 10px; cursor:pointer; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.03em; color:#50575e; border-radius:5px; margin:8px 0 0; }
    .op-wc-barcode .op-bb-grouphead:hover { background:#f0eaf8; color:#7f54b3; }
    .op-wc-barcode .op-bb-grouphead .dashicons { font-size:13px; width:13px; height:13px; }
    .op-wc-barcode .op-bb-groupchev { margin-left:auto; transition:transform .15s ease; color:#a7aaad; }
    .op-wc-barcode .op-bb-group.is-open .op-bb-groupchev { transform:rotate(180deg); color:#7f54b3; }
    .op-wc-barcode .op-bb-groupbody { display:none; padding:10px 6px 4px; }
    .op-wc-barcode .op-bb-group.is-open .op-bb-groupbody { display:block; }
</style>
<script type="text/javascript">
    (function($) {
        var form_values = 'product_id=<?php echo (int)$_GET['id']; ?>';// $('#label-setting-frm').serialize();
            let total = $('#label-setting-frm').find('input[name="total"]').first().val();
            total = 1 * total;
            if(!total)
            {
                total = 1;
            }
            form_values += '&total='+total;
            form_values += "&is_preview=1&is_print=0&action=print_barcode&op_nonce=<?php echo $op_nonce;?>" ;
            var frame_url = '<?php echo admin_url('admin-ajax.php'); ?>?'+form_values;
            $('#preview-frame').attr('src',frame_url);

        // Handle Quick Guide click
        $(document).on('click', '#load-guide', function(e) {
            e.preventDefault();
            $('#op-guide-modal').fadeIn(300);
            $('body').addClass('op-modal-open');
        });

        // Handle modal close
        $(document).on('click', '.op-modal-close, .op-modal-overlay', function() {
            $('#op-guide-modal').fadeOut(300);
            $('body').removeClass('op-modal-open');
        });

        // Close modal on ESC key
        $(document).on('keydown', function(e) {
            if (e.keyCode === 27 && $('#op-guide-modal').is(':visible')) {
                $('#op-guide-modal').fadeOut(300);
                $('body').removeClass('op-modal-open');
            }
        });

        $('body').on('click','#preview-label-btn',function () {
                var _form_values_save = $('#label-setting-frm').serialize();
                var _form_values = 'product_id=<?php echo (int)$_GET['id']; ?>';
                let total = $('#label-setting-frm').find('input[name="total"]').first().val();
                total = 1 * total;
                if(!total)
                {
                    total = 1;
                }
                _form_values += '&total='+total;
                
                form_values = _form_values+"&is_preview=1&is_print=0&action=print_barcode&op_nonce=<?php echo $op_nonce;?>" ;

                $.ajax({
                        url: '<?php echo admin_url( 'admin-ajax.php?action=save_bacode_setting' ); ?>',
                        type: 'post',
                        dataType: 'json',
                        data: _form_values_save,
                        beforeSend:function(){
                            $('body').addClass('op_loading');
                        },
                        success:function(data){
                           
                            $('body').removeClass('op_loading');
                            var  t = new Date().getTime();
                            var frame_url = '<?php echo admin_url('admin-ajax.php'); ?>?t='+t+'&'+form_values;
                            $('#preview-frame').attr('src',frame_url);
                        }
                });


               
        })

        $('body').on('click','#print-label-btn',function () {
                let total = $('#label-setting-frm').find('input[name="total"]').first().val();
                //var form_values = $('#label-setting-frm').serialize();
                var form_values = 'product_id=<?php echo (int)$_GET['id']; ?>';
                total = 1 * total;
                if(!total)
                {
                    total = 1;
                }
                form_values += '&total='+total;
                form_values += "&is_preview=0&is_print=1&action=print_barcode&op_nonce=<?php echo $op_nonce;?>" ;
                var frame_url = '<?php echo admin_url('admin-ajax.php'); ?>?'+form_values;
                window.open(frame_url);
        })
        var form_height = $('#label-setting-frm').height();
        if(form_height > 400)
        {
            form_height -= 5;
            $('#preview-frame').css('height',form_height+'px');
        }

        function downloadObjectAsJson(exportObj, exportName){
        var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(exportObj));
        var downloadAnchorNode = document.createElement('a');
        downloadAnchorNode.setAttribute("href",     dataStr);
        downloadAnchorNode.setAttribute("download", exportName + ".json");
        document.body.appendChild(downloadAnchorNode); // required for firefox
        downloadAnchorNode.click();
        downloadAnchorNode.remove();
    }
        $(document).on('click','#load-sample',function(){
                var sample_url = $(this).data('sample');

                //var formData = $('#label-setting-frm').serializeArray();
                //downloadObjectAsJson(formData,'template');
                var form = $('#label-setting-frm');
                $.ajax({
                    url: sample_url,
                    type: 'get',
                    dataType: 'json',
                    beforeSend:function(){
                        $('body').addClass('op_loading');
                    },
                    success:function(data){
                        
                        for(let i=0;i<data.length;i++)
                        {
                            let field = data[i];
                            
                            form.find('input[name="'+field['name']+'"]').val(field['value']);
                            form.find('select[name="'+field['name']+'"]').val(field['value']);
                            form.find('textarea[name="'+field['name']+'"]').val(field['value']);
                        }
                       

                        
                       $('body').removeClass('op_loading');
                       
                        
                    },
                    error:function(){
                        $('body').removeClass('op_loading');
                    }
                });
            });
        console.log(form_height);
    }(jQuery));
</script>
<script type="text/javascript">
    (function($){
        "use strict";
        var OP_BB_PID = <?php echo (int) ( isset($_GET['id']) ? $_GET['id'] : 0 ); ?>;
        var OP_BB_W = '<?php echo esc_js( $barcode_width ); ?>', OP_BB_H = '<?php echo esc_js( $barcode_height ); ?>';
        var $ta = $('textarea[name="barcode_label_template"]');
        var $palette = $('.op-bb-palette'), $canvas = $('.op-bb-canvas');
        var bbState = [], bbUid = 1, bbTimer = null, bbManaged = false, bbDragIdx = null;

        var OP_BB_BLOCKS = {
            name:         { label:'Product Name', icon:'tag', align:'center', fw:'bold', content:function(){ return '[op_product attribute="name"]'; } },
            barcode:      { label:'Barcode', icon:'tickets-alt', align:'center',
                            opts:[ {k:'w',label:'Width',type:'text',def:OP_BB_W}, {k:'h',label:'Height',type:'text',def:OP_BB_H} ],
                            content:function(o){ return '[barcode width="'+(o.w||OP_BB_W)+'" height="'+(o.h||OP_BB_H)+'"]'; } },
            barcode_text: { label:'Barcode Number', icon:'editor-code', align:'center', content:function(){ return '[op_product attribute="barcode"]'; } },
            price:        { label:'Price', icon:'money-alt', align:'center', fw:'bold', content:function(){ return '[op_product attribute="price"]'; } },
            regular_price:{ label:'Regular Price', icon:'money', align:'center', content:function(){ return '[op_product attribute="regular_price"]'; } },
            sale_price:   { label:'Sale Price', icon:'money', align:'center', content:function(){ return '[op_product attribute="sale_price"]'; } },
            attr:         { label:'Attribute', icon:'admin-settings', align:'center',
                            opts:[ {k:'a',label:'Attribute',type:'select',options:['sku','weight','width','height','length','regular_price','sale_price'],def:'weight'} ],
                            content:function(o){ return '[op_product attribute="'+(o.a||'weight')+'"]'; } },
            text:         { label:'Custom Text', icon:'editor-textcolor', align:'center',
                            opts:[ {k:'content',label:'Text / HTML',type:'textarea',def:'Text'} ],
                            content:function(o){ return (o.content||''); } },
            divider:      { label:'Divider', icon:'minus', align:'center', content:function(){ return '<hr style="margin:2px 0;border:0;border-top:1px solid #000;"/>'; } }
        };
        var OP_BB_GROUPS = [
            { title:'Spacing & Align', icon:'editor-aligncenter', open:true, fields:[
                {k:'_align',label:'Align',type:'select',options:[{v:'left',l:'Left'},{v:'center',l:'Center'},{v:'right',l:'Right'}]},
                {k:'_margin',label:'Margin',type:'text',ph:'e.g. 0 0 2px'},
                {k:'_padding',label:'Padding',type:'text',ph:'e.g. 0'} ]},
            { title:'Typography', icon:'editor-textcolor', open:false, fields:[
                {k:'_fs',label:'Font size',type:'text',ph:'e.g. 12px'},
                {k:'_fw',label:'Font weight',type:'select',options:[{v:'',l:'Default'},{v:'normal',l:'Normal'},{v:'bold',l:'Bold'}]} ]},
            { title:'Advanced', icon:'editor-code', open:false, fields:[
                {k:'_name',label:'CSS class',type:'text',ph:'e.g. my-label'} ]}
        ];

        function bbEsc(s){ return $('<div/>').text(s==null?'':s).html(); }
        function bbDefaults(type){
            var b=OP_BB_BLOCKS[type];
            var d={ _name:'', _align:(b.align||'left'), _margin:'', _padding:'', _fs:'', _fw:(b.fw||'') };
            (b.opts||[]).forEach(function(o){ d[o.k]=(o.def!==undefined?o.def:''); });
            return d;
        }
        function bbWrap(inner, o){
            var s='margin:'+(o._margin||'0')+';';
            if(o._padding) s+='padding:'+o._padding+';';
            if(o._align && o._align!=='left') s+='text-align:'+o._align+';';
            if(o._fs) s+='font-size:'+o._fs+';';
            if(o._fw) s+='font-weight:'+o._fw+';';
            var cls=o._name ? ' class="'+o._name+'"' : '';
            return '<div'+cls+' style="'+s+'">'+inner+'</div>';
        }
        function bbGenerate(silent){
            var html=bbState.map(function(it){ return bbWrap(OP_BB_BLOCKS[it.type].content(it.opts||{}), it.opts||{}); }).join('\n');
            $ta.val(html);
            try{ localStorage.setItem('op_bb_v1_'+OP_BB_PID, JSON.stringify(bbState)); }catch(e){}
            if(!silent) bbSchedulePreview();
        }
        function bbSchedulePreview(){
            if(!bbManaged) return;
            if(bbTimer) clearTimeout(bbTimer);
            bbTimer=setTimeout(function(){ $('#preview-label-btn').trigger('click'); }, 800);
        }

        function bbOpt(o, item){
            var val=item.opts[o.k];
            if(o.type==='textarea'){
                var $t=$('<label class="op-bb-opt op-bb-wide"><span></span><textarea rows="2"></textarea></label>');
                $t.find('span').text(o.label); $t.find('textarea').val(val).on('input',function(){ item.opts[o.k]=$(this).val(); bbGenerate(); });
                return $t;
            }
            if(o.type==='select'){
                var $s=$('<label class="op-bb-opt"><span></span><select></select></label>');
                $s.find('span').text(o.label); var $sel=$s.find('select');
                o.options.forEach(function(op){ var ov=(typeof op==='string')?{v:op,l:op}:op; $('<option>').val(ov.v).text(ov.l).prop('selected',ov.v===(val||'')).appendTo($sel); });
                $sel.on('change',function(){ item.opts[o.k]=$(this).val(); bbGenerate(); });
                return $s;
            }
            var $i=$('<label class="op-bb-opt"><span></span><input type="text"></label>');
            $i.find('span').text(o.label); $i.find('input').val(val).attr('placeholder',o.ph||'').on('input',function(){ item.opts[o.k]=$(this).val(); bbGenerate(); });
            return $i;
        }
        function bbGroup(title, icon, fields, open, item){
            var $g=$('<div class="op-bb-group'+(open?' is-open':'')+'"></div>');
            var $h=$('<button type="button" class="op-bb-grouphead"><span class="dashicons dashicons-'+icon+'"></span><span class="op-bb-grouptitle"></span><span class="op-bb-groupchev dashicons dashicons-arrow-down-alt2"></span></button>');
            $h.find('.op-bb-grouptitle').text(title); $g.append($h);
            var $b=$('<div class="op-bb-groupbody"><div class="op-bb-grid"></div></div>'); var $grid=$b.find('.op-bb-grid');
            fields.forEach(function(o){ $grid.append(bbOpt(o,item)); }); $g.append($b);
            return $g;
        }
        function bbRender(){
            $canvas.empty();
            if(!bbState.length){ $canvas.html('<div class="op-bb-empty"><?php echo esc_js( __( 'Drag blocks here, or click a block on the left to add it.', 'openpos' ) ); ?></div>'); return; }
            bbState.forEach(function(item,idx){
                var b=OP_BB_BLOCKS[item.type];
                var $bl=$('<div class="op-bb-block"></div>').attr('data-idx',idx);
                var nameTag=item.opts._name ? ' <code class="op-bb-nametag">.'+bbEsc(item.opts._name)+'</code>' : '';
                $bl.append($('<div class="op-bb-bar" draggable="true">'+
                    '<span class="op-bb-handle dashicons dashicons-menu"></span>'+
                    '<span class="op-bb-title"><span class="dashicons dashicons-'+b.icon+'"></span>'+bbEsc(b.label)+nameTag+'</span>'+
                    '<span class="op-bb-actions"><button type="button" class="op-bb-iconbtn op-bb-cfg"><span class="dashicons dashicons-admin-generic"></span></button>'+
                    '<button type="button" class="op-bb-iconbtn op-bb-del"><span class="dashicons dashicons-trash"></span></button></span></div>'));
                var $opts=$('<div class="op-bb-opts"></div>');
                if(b.opts&&b.opts.length){ $opts.append(bbGroup('<?php echo esc_js( __( 'Content', 'openpos' ) ); ?>', b.icon, b.opts, true, item)); }
                OP_BB_GROUPS.forEach(function(g){ $opts.append(bbGroup(g.title, g.icon, g.fields, g.open, item)); });
                $bl.append($opts); $canvas.append($bl);
            });
        }
        function bbBuildPalette(){
            Object.keys(OP_BB_BLOCKS).forEach(function(type){
                var b=OP_BB_BLOCKS[type];
                var $c=$('<div class="op-bb-chip" draggable="true"><span class="dashicons dashicons-'+b.icon+'"></span>'+bbEsc(b.label)+'</div>').attr('data-type',type);
                $c.on('dragstart',function(e){ e.originalEvent.dataTransfer.setData('text/op-new',type); e.originalEvent.dataTransfer.effectAllowed='copy'; });
                $c.on('click',function(){ bbState.push({uid:bbUid++,type:type,opts:bbDefaults(type)}); bbRender(); bbGenerate(); });
                $palette.append($c);
            });
        }
        function bbInsertPos(y){ var blocks=$canvas.find('.op-bb-block'); var pos=blocks.length; blocks.each(function(i){ var r=this.getBoundingClientRect(); if(y < r.top + r.height/2){ pos=i; return false; } }); return pos; }
        $canvas.on('dragstart','.op-bb-bar',function(e){ var $b=$(this).closest('.op-bb-block'); bbDragIdx=parseInt($b.attr('data-idx'),10); $b.addClass('op-bb-dragging'); e.originalEvent.dataTransfer.effectAllowed='move'; e.originalEvent.dataTransfer.setData('text/op-move',bbDragIdx); });
        $canvas.on('dragend','.op-bb-bar',function(){ $('.op-bb-block').removeClass('op-bb-dragging op-bb-drop-before op-bb-drop-after'); });
        $canvas.on('dragover',function(e){ e.preventDefault(); $canvas.addClass('op-bb-over'); var pos=bbInsertPos(e.originalEvent.clientY); var blocks=$canvas.find('.op-bb-block'); blocks.removeClass('op-bb-drop-before op-bb-drop-after'); if(pos<blocks.length){ blocks.eq(pos).addClass('op-bb-drop-before'); } else if(blocks.length){ blocks.eq(blocks.length-1).addClass('op-bb-drop-after'); } });
        $canvas.on('dragleave',function(e){ if(e.target===$canvas[0]){ $canvas.removeClass('op-bb-over'); } });
        $canvas.on('drop',function(e){ e.preventDefault(); $canvas.removeClass('op-bb-over'); $('.op-bb-block').removeClass('op-bb-drop-before op-bb-drop-after'); var dt=e.originalEvent.dataTransfer; var pos=bbInsertPos(e.originalEvent.clientY); var nt=dt.getData('text/op-new'); if(nt){ bbState.splice(pos,0,{uid:bbUid++,type:nt,opts:bbDefaults(nt)}); } else if(bbDragIdx!==null){ var m=bbState.splice(bbDragIdx,1)[0]; if(bbDragIdx<pos)pos--; bbState.splice(pos,0,m); } bbDragIdx=null; bbRender(); bbGenerate(); });
        $canvas.on('click','.op-bb-del',function(){ var idx=parseInt($(this).closest('.op-bb-block').attr('data-idx'),10); bbState.splice(idx,1); bbRender(); bbGenerate(); });
        $canvas.on('click','.op-bb-cfg',function(){ $(this).closest('.op-bb-block').toggleClass('is-open'); });
        $canvas.on('click','.op-bb-grouphead',function(){ $(this).closest('.op-bb-group').toggleClass('is-open'); });

        $('.op-bb-tab').on('click',function(){
            var mode=$(this).data('mode');
            if(mode==='builder' && !bbManaged && $.trim($ta.val()).length>0){
                if(!confirm('<?php echo esc_js( __( 'Builder mode rebuilds this label from blocks and will replace your current template when you add or change blocks. Continue?', 'openpos' ) ); ?>')){ return; }
                bbManaged=true;
            }
            if(mode==='builder'){ bbManaged=true; }
            $('.op-bb-tab').removeClass('is-active'); $(this).addClass('is-active');
            $('.op-bb').toggle(mode==='builder'); $('.op-bb-code').toggle(mode==='code');
        });

        bbBuildPalette();
        (function bbInit(){
            var saved=null; try{ saved=JSON.parse(localStorage.getItem('op_bb_v1_'+OP_BB_PID)); }catch(e){}
            if(saved && saved.length){ bbManaged=true; bbState=saved; bbState.forEach(function(it){ it.uid=bbUid++; }); bbRender(); bbGenerate(true); }
            else {
                if($.trim($ta.val()).length>0){ $('.op-bb-tab').removeClass('is-active'); $('.op-bb-tab[data-mode="code"]').addClass('is-active'); $('.op-bb').hide(); $('.op-bb-code').show(); }
                else { bbManaged=true; }
                bbRender();
            }
        })();
    })(jQuery);
</script>