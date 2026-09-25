<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<?php

$op_nonce = wp_create_nonce( 'op_nonce' );
wp_enqueue_media(); // for the Logo image picker in the visual builder
?>
<div class="op-admin-wrap wrap op-wc-composer">
    <div id="wrap-loading">
        <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
    </div>
    <h1 class="wp-heading-inline"><?php echo __( 'Composer Receipt', 'openpos' ); ?></h1>
    <br class="clear" />
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-lg-6 col-xl-6 col-sm-6 col-xs-12 op-composer-form">
                <h4 class="op-card-h"><?php echo __( 'Settings & Code', 'openpos' ); ?></h4>
                <form class="form-horizontal" id="template-frm">
                    <input type="hidden" name="temp_id" value="<?php echo $default['id']; ?>">
                    <input type="hidden" name="id" value="<?php echo $default['id']; ?>">
                    <input type="hidden" name="op_nonce" value="<?php echo $op_nonce; ?>">
                    <div class="op-paper">
                        <div class="op-paper__title">
                            <span class="dashicons dashicons-media-text"></span>
                            <span class="op-paper__name"><?php echo esc_html( $default['name'] ); ?></span>
                        </div>
                        <div class="op-paper__grid">
                            <div class="op-paper__field">
                                <label class="op-paper__lbl"><?php echo __( 'Paper width', 'openpos' ); ?></label>
                                <div class="op-paper__inputwrap">
                                    <input type="text" name="paper_width" value="<?php echo esc_attr( $default['paper_width'] ); ?>">
                                    <span class="op-paper__unit"><?php echo __( 'in', 'openpos' ); ?></span>
                                </div>
                            </div>
                            <div class="op-paper__field">
                                <label class="op-paper__lbl"><?php echo __( 'Padding', 'openpos' ); ?> <span class="op-paper__hint">(<?php echo __( 'inch', 'openpos' ); ?>)</span></label>
                                <div class="op-paper__pad">
                                    <span class="op-paper__padcell"><input type="text" name="padding_top" value="<?php echo esc_attr( $default['padding_top'] ); ?>"><em><?php echo __( 'Top', 'openpos' ); ?></em></span>
                                    <span class="op-paper__padcell"><input type="text" name="padding_right" value="<?php echo esc_attr( $default['padding_right'] ); ?>"><em><?php echo __( 'Right', 'openpos' ); ?></em></span>
                                    <span class="op-paper__padcell"><input type="text" name="padding_bottom" value="<?php echo esc_attr( $default['padding_bottom'] ); ?>"><em><?php echo __( 'Bottom', 'openpos' ); ?></em></span>
                                    <span class="op-paper__padcell"><input type="text" name="padding_left" value="<?php echo esc_attr( $default['padding_left'] ); ?>"><em><?php echo __( 'Left', 'openpos' ); ?></em></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label"><?php echo __( 'Template', 'openpos' ); ?></label>
                        <div class="col-sm-10">
                            <?php $op_type_label = ( isset($receipt_types) && isset($receipt_types[$default['type']]) ) ? $receipt_types[$default['type']]['label'] : $default['type']; ?>
                            <div class="op-rb-tabs">
                                <button type="button" class="op-rb-tab is-active" data-mode="builder"><span class="dashicons dashicons-layout"></span> <?php echo __( 'Builder', 'openpos' ); ?></button>
                                <button type="button" class="op-rb-tab" data-mode="code"><span class="dashicons dashicons-editor-code"></span> <?php echo __( 'Code', 'openpos' ); ?></button>
                                <span class="op-rb-typebadge" title="<?php echo esc_attr__( 'Blocks available for this receipt type', 'openpos' ); ?>"><span class="dashicons dashicons-media-default"></span> <?php echo esc_html( $op_type_label ); ?></span>
                            </div>
                            <div class="op-rb" data-pane="builder">
                                <div class="op-rb-palette"></div>
                                <div class="op-rb-canvas"><div class="op-rb-empty"><?php echo __( 'Drag blocks here, or click a block on the left to add it.', 'openpos' ); ?></div></div>
                            </div>
                            <div class="op-rb-code" data-pane="code" style="display:none;">
                                <textarea class="form-control" name="content" rows="3" id="receipt-template-content"><?php echo $default['content']; ?></textarea>
                                <span id="helpBlock2" class="help-block"><a href="javascript:void(0)" data-sample="<?php echo esc_url($sample_template_url); ?>" id="load-sample"><?php echo __( 'Load Sample', 'openpos' ); ?></a></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label"><?php echo __( 'CSS', 'openpos' ); ?></label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="custom_css" rows="3" id="receipt-template-css"><?php echo $default['custom_css']; ?></textarea>
                            <span id="helpBlock2" class="help-block"><a href="javascript:void(0)" data-sample="<?php echo esc_url($sample_css_url); ?>" id="load-sample-css"><?php echo __( 'Load Sample', 'openpos' ); ?></a></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-8 col-sm-4 text-right">
                            <button type="submit"  class="btn btn-primary"><?php echo __( 'Update', 'openpos' ); ?></button>
                            <!-- <button type="button" id="preview-receipt" class="btn btn-warning"><?php echo __( 'Preview', 'openpos' ); ?></button> -->
                        </div>
                    </div>
                </form>

            </div>
            <div class="col-md-6 col-lg-6 col-xl-6 col-sm-6 col-xs-12 op-composer-preview">
                <h4 class="op-card-h"><?php echo __( 'Live Preview', 'openpos' ); ?></h4>
                <div class="preview-live">
                    <iframe id="preview-frame" src="<?php echo admin_url('admin-ajax.php?action=openpos_update_receipt_preview&op_nonce='.$op_nonce.'&order_id='.$default['order_id'].'&id='.$default['id']); ?>" style="width:calc(100% - 1px);height:100%;min-height:490px;    background: #fff;
                    border: none;" src="">Preview</iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    /* ---- WooCommerce-style skin for Receipt Composer (CodeMirror/JS untouched) ---- */
    .op-wc-composer { margin-right: 20px; }
    .op-wc-composer .container-fluid { padding: 0; margin-top: 16px; }
    .op-wc-composer .row { display: flex; flex-wrap: wrap; gap: 20px; margin: 0; align-items: stretch; }
    .op-wc-composer .row > [class*="col-"] { padding: 0; float: none; }
    .op-wc-composer .op-composer-form,
    .op-wc-composer .op-composer-preview {
        flex: 1 1 420px; min-width: 0;
        background: #fff !important; border: 1px solid #e0e0e0; border-radius: 8px; padding: 0 !important; margin-bottom: 16px;
    }
    .op-wc-composer .op-card-h {
        margin: 0; padding: 14px 18px; border-bottom: 1px solid #f0f0f1; font-size: 15px; font-weight: 600; color: #1e1e1e;
    }
    .op-wc-composer #template-frm { padding: 18px 20px 20px; }

    /* Form fields -> stacked WC */
    .op-wc-composer .form-group { display: block; margin: 0 0 18px; }
    .op-wc-composer .form-group::after { content: ""; display: table; clear: both; }
    .op-wc-composer .form-group > [class*="col-"] { float: none; }
    /* padding inputs (4 across) */
    .op-wc-composer .form-group > .col-sm-2 { display: inline-block; width: 22.5%; margin-right: 2.5%; vertical-align: top; }
    .op-wc-composer .form-group > .col-sm-2:last-child { margin-right: 0; }
    /* stacked single-column fields */
    .op-wc-composer .form-group > .control-label { display: block; float: none; width: auto; text-align: left; padding: 0 0 6px; font-weight: 600; font-size: 13px; color: #1e1e1e; }
    .op-wc-composer .form-group > .col-sm-8,
    .op-wc-composer .form-group > .col-sm-10 { display: block; width: 100%; padding: 0; }
    .op-wc-composer .form-control {
        width: 100%; max-width: 100%; border: 1px solid #8c8f94; border-radius: 4px; padding: 7px 10px;
        font-size: 14px; line-height: 1.4; min-height: 38px; box-shadow: none; background: #fff; color: #2c3338;
    }
    .op-wc-composer .form-control:focus { border-color: #7f54b3; box-shadow: 0 0 0 1px #7f54b3; outline: none; }
    .op-wc-composer .help-block { color: #757575; font-size: 12px; margin-top: 4px; display: block; }
    .op-wc-composer .help-block a { color: #7f54b3; font-weight: 600; text-decoration: none; }
    .op-wc-composer .help-block a:hover { text-decoration: underline; }

    /* CodeMirror editors */
    .op-wc-composer .CodeMirror {
        border: 1px solid #8c8f94; border-radius: 4px; height: 230px;
        font-family: Menlo, Consolas, monospace; font-size: 13px;
    }
    .op-wc-composer .CodeMirror-focused { border-color: #7f54b3; box-shadow: 0 0 0 1px #7f54b3; }

    /* Buttons */
    .op-wc-composer #template-frm > .form-group:last-child {
        margin: 24px 0 0; padding-top: 18px; border-top: 1px solid #e0e0e0; text-align: right;
    }
    .op-wc-composer .btn {
        display: inline-flex; align-items: center; gap: 6px; border-radius: 4px; font-weight: 600;
        padding: 8px 18px; font-size: 13px; border: 1px solid #7f54b3; background: #7f54b3; color: #fff; cursor: pointer; text-decoration: none;
    }
    .op-wc-composer .btn:hover { background: #6b4794; border-color: #6b4794; color: #fff; }
    /* Import / Export buttons next to title */
    .op-wc-composer .add-new-h2 {
        display: inline-block; margin-left: 6px; padding: 4px 12px; border: 1px solid #c3c4c7; background: #f6f7f7;
        color: #2c3338; border-radius: 4px; font-size: 13px; font-weight: 600; text-decoration: none; cursor: pointer; vertical-align: middle;
    }
    .op-wc-composer .add-new-h2:hover { background: #f0eaf8; color: #7f54b3; border-color: #7f54b3; }

    /* Live preview -> clean frame */
    .op-wc-composer .op-composer-preview { display: flex; flex-direction: column; }
    .op-wc-composer .preview-live {
        float: none !important; display: block !important; width: auto !important; height: auto !important;
        min-height: 480px !important; flex: 1 1 auto;
        background: #f6f7f7 !important; border: 0 !important; padding: 16px !important; margin: 0;
    }
    .op-wc-composer #preview-frame {
        background: #fff !important; border: 1px solid #e0e0e0 !important; border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0,0,0,.08); width: 100% !important;
    }

    @media screen and (max-width: 960px) {
        .op-wc-composer .row > [class*="col-"] { flex: 0 0 100%; max-width: 100%; }
    }

    /* Paper settings panel (width + padding) */
    .op-wc-composer .op-paper { background: #f6f7f7; border: 1px solid #e0e0e0; border-radius: 8px; padding: 14px 16px; margin-bottom: 18px; }
    .op-paper__title { display: flex; align-items: center; gap: 7px; font-size: 14px; font-weight: 600; color: #1e1e1e; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid #e6e6e6; }
    .op-paper__title .dashicons { color: #7f54b3; font-size: 18px; width: 18px; height: 18px; }
    .op-paper__name { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .op-paper__grid { display: grid; grid-template-columns: 140px 1fr; gap: 18px; align-items: start; }
    .op-paper__lbl { display: block; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .03em; color: #757575; margin-bottom: 7px; }
    .op-paper__hint { font-weight: 400; text-transform: none; color: #a7aaad; }
    .op-paper__inputwrap { position: relative; display: flex; align-items: center; }
    .op-paper__inputwrap input { width: 100%; border: 1px solid #c3c4c7; border-radius: 6px; padding: 8px 40px 8px 11px; font-size: 14px; min-height: 38px; box-shadow: none; background: #fff; }
    .op-paper__inputwrap input:focus { border-color: #7f54b3; box-shadow: 0 0 0 1px #7f54b3; outline: none; }
    .op-paper__unit { position: absolute; right: 7px; font-size: 11px; font-weight: 700; color: #7f54b3; background: #f0eaf8; padding: 2px 7px; border-radius: 4px; pointer-events: none; }
    .op-paper__pad { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }
    .op-paper__padcell { display: flex; flex-direction: column; align-items: center; gap: 4px; }
    .op-paper__padcell input { width: 100%; text-align: center; border: 1px solid #c3c4c7; border-radius: 6px; padding: 8px 4px; font-size: 14px; min-height: 38px; box-shadow: none; background: #fff; }
    .op-paper__padcell input:focus { border-color: #7f54b3; box-shadow: 0 0 0 1px #7f54b3; outline: none; }
    .op-paper__padcell em { font-style: normal; font-size: 10px; text-transform: uppercase; letter-spacing: .02em; color: #757575; font-weight: 600; }
    @media screen and (max-width: 720px){ .op-paper__grid { grid-template-columns: 1fr; } }

    /* ===== Drag & drop receipt block builder ===== */
    .op-rb-tabs { display: flex; align-items: center; gap: 4px; margin-bottom: 10px; }
    .op-rb-tab {
        display: inline-flex; align-items: center; gap: 5px; border: 1px solid #dcdcde; background: #fff; color: #50575e;
        padding: 5px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; cursor: pointer;
    }
    .op-rb-tab .dashicons { font-size: 15px; width: 15px; height: 15px; }
    .op-rb-tab.is-active { background: #7f54b3; border-color: #7f54b3; color: #fff; }
    .op-rb-hint { margin-left: auto; font-size: 11px; color: #999; font-style: italic; }
    .op-rb-typebadge { margin-left: auto; display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 700; color: #7f54b3; background: #f0eaf8; padding: 3px 10px; border-radius: 12px; text-transform: uppercase; letter-spacing: .02em; }
    .op-rb-typebadge .dashicons { font-size: 13px; width: 13px; height: 13px; }

    .op-rb { display: grid; grid-template-columns: 150px 1fr; gap: 12px; }
    .op-rb-palette {
        display: flex; flex-direction: column; gap: 6px; max-height: 460px; overflow: auto;
        padding: 8px; background: #f6f7f7; border: 1px solid #e0e0e0; border-radius: 6px;
    }
    .op-rb-chip {
        display: flex; align-items: center; gap: 7px; padding: 8px 10px; background: #fff; border: 1px solid #dcdcde;
        border-radius: 5px; font-size: 12px; font-weight: 600; color: #2c3338; cursor: grab; user-select: none;
    }
    .op-rb-chip:hover { border-color: #7f54b3; color: #7f54b3; background: #faf8fd; }
    .op-rb-chip .dashicons { font-size: 16px; width: 16px; height: 16px; color: #7f54b3; }

    .op-rb-canvas {
        min-height: 300px; max-height: 460px; overflow: auto; padding: 10px; background: #fff;
        border: 2px dashed #dcdcde; border-radius: 6px;
    }
    .op-rb-canvas.op-rb-over { border-color: #7f54b3; background: #faf8fd; }
    .op-rb-empty { color: #a7aaad; text-align: center; padding: 40px 10px; font-size: 13px; }

    .op-rb-block {
        background: #fff; border: 1px solid #e0e0e0; border-radius: 6px; margin-bottom: 8px; box-shadow: 0 1px 1px rgba(0,0,0,.03);
    }
    .op-rb-block.op-rb-dragging { opacity: .4; }
    .op-rb-block.op-rb-drop-before { box-shadow: 0 -3px 0 #7f54b3; }
    .op-rb-block.op-rb-drop-after { box-shadow: 0 3px 0 #7f54b3; }
    .op-rb-block__bar { display: flex; align-items: center; gap: 8px; padding: 8px 10px; }
    .op-rb-block__handle { cursor: grab; color: #a7aaad; }
    .op-rb-block__handle .dashicons { font-size: 18px; width: 18px; height: 18px; }
    .op-rb-block__title { font-size: 13px; font-weight: 600; color: #1e1e1e; display: flex; align-items: center; gap: 6px; }
    .op-rb-block__title .dashicons { font-size: 15px; width: 15px; height: 15px; color: #7f54b3; }
    .op-rb-block__actions { margin-left: auto; display: flex; gap: 4px; }
    .op-rb-iconbtn { border: 0; background: transparent; cursor: pointer; color: #757575; padding: 3px; border-radius: 4px; }
    .op-rb-iconbtn:hover { background: #f0f0f1; color: #1e1e1e; }
    .op-rb-iconbtn.op-rb-del:hover { color: #d63638; }
    .op-rb-block__opts { padding: 10px 12px 12px 36px; display: none; border-top: 1px solid #f0f0f1; background: #fcfcfd; }
    .op-rb-block.is-open .op-rb-block__opts { display: block; }
    .op-rb-block.is-open { border-color: #c9b6e4; }
    .op-rb-optgrid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px 12px; }
    .op-rb-opt { display: flex; flex-direction: column; gap: 3px; margin: 0; font-size: 11px; color: #50575e; }
    .op-rb-opt > span { font-weight: 600; }
    .op-rb-opt.op-rb-wide { grid-column: 1 / -1; }
    .op-rb-opt.op-rb-check { flex-direction: row; align-items: center; gap: 6px; }
    .op-rb-opt.op-rb-check > span { font-weight: 500; }
    .op-rb-opt.op-rb-check input { margin: 0; }
    .op-rb-opt input[type="text"], .op-rb-opt textarea, .op-rb-opt select {
        width: 100%; border: 1px solid #c3c4c7; border-radius: 4px; padding: 4px 7px; font-size: 12px; margin: 0; min-height: 30px; box-shadow: none;
    }
    .op-rb-opt textarea { min-height: 48px; font-family: Menlo, Consolas, monospace; }
    .op-rb-opthead {
        margin: 12px 0 8px; padding-top: 10px; border-top: 1px dashed #e0e0e0;
        font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .03em; color: #7f54b3;
        display: flex; align-items: center; gap: 5px;
    }
    .op-rb-opthead .dashicons { font-size: 14px; width: 14px; height: 14px; }
    .op-rb-nametag { background: #f0eaf8; color: #7f54b3; padding: 0 5px; border-radius: 3px; font-size: 11px; font-weight: 600; }
    .op-rb-imgrow { display: flex; align-items: center; gap: 8px; }
    .op-rb-imgprev { width: 48px; height: 48px; border: 1px solid #dcdcde; border-radius: 4px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #fafafa; flex: 0 0 auto; }
    .op-rb-imgprev img { max-width: 100%; max-height: 100%; }
    .op-rb-noimg { font-size: 9px; color: #a7aaad; text-align: center; padding: 2px; }
    .op-rb-imgbtns { display: flex; flex-direction: column; gap: 4px; }

    /* collapsible option groups */
    .op-rb-group { border: 1px solid #ececec; border-radius: 5px; margin-bottom: 6px; overflow: hidden; background: #fff; }
    .op-rb-grouphead {
        width: 100%; display: flex; align-items: center; gap: 6px; background: #f6f7f7; border: 0; border-bottom: 1px solid transparent;
        padding: 7px 10px; cursor: pointer; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .03em; color: #50575e;
    }
    .op-rb-grouphead:hover { background: #f0eaf8; color: #7f54b3; }
    .op-rb-grouphead .dashicons { font-size: 14px; width: 14px; height: 14px; }
    .op-rb-grouptitle { flex: 1; text-align: left; }
    .op-rb-groupchev { margin-left: auto; transition: transform .15s ease; color: #a7aaad; }
    .op-rb-group.is-open .op-rb-grouphead { border-bottom-color: #f0f0f1; color: #7f54b3; }
    .op-rb-group.is-open .op-rb-groupchev { transform: rotate(180deg); color: #7f54b3; }
    .op-rb-groupbody { display: none; padding: 10px; }
    .op-rb-group.is-open .op-rb-groupbody { display: block; }

    /* sortable show/hide list (columns / rows / fields) */
    .op-rb-sortlist { gap: 4px; }
    .op-rb-sl { display: flex; flex-direction: column; gap: 4px; margin-top: 3px; }
    .op-rb-slrow { display: flex; align-items: center; gap: 8px; padding: 4px 6px; border: 1px solid #e6e6e6; border-radius: 4px; background: #fff; }
    .op-rb-slrow.is-off { background: #fafafa; opacity: .65; }
    .op-rb-slmove { display: flex; gap: 2px; }
    .op-rb-slbtn { display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; padding: 0; border: 1px solid #dcdcde; background: #f6f7f7; border-radius: 3px; cursor: pointer; color: #50575e; }
    .op-rb-slbtn:hover { background: #7f54b3; border-color: #7f54b3; color: #fff; }
    .op-rb-slbtn .dashicons { font-size: 14px; width: 14px; height: 14px; line-height: 22px; }
    .op-rb-slcheck { display: flex; align-items: center; margin: 0; cursor: pointer; }
    .op-rb-slcheck input { margin: 0; }
    .op-rb-slbase { flex: 0 0 76px; max-width: 76px; font-size: 11px; font-weight: 600; color: #50575e; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .op-rb-sortlist .op-rb-sllabel { flex: 1 1 auto; width: auto; min-width: 0; min-height: 26px; padding: 3px 6px; }
    .op-rb-slrow.is-off .op-rb-slbase { color: #a7aaad; }
</style>
<script type="text/javascript">
    function downloadObjectAsJson(exportObj, exportName){
        var dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(exportObj));
        var downloadAnchorNode = document.createElement('a');
        downloadAnchorNode.setAttribute("href",     dataStr);
        downloadAnchorNode.setAttribute("download", exportName + ".json");
        document.body.appendChild(downloadAnchorNode); // required for firefox
        downloadAnchorNode.click();
        downloadAnchorNode.remove();
    }
    (function($) {
        "use strict";
        $($(".wp-heading-inline")[0]).after("<a  id='import-addon-btn' class='add-new-h2 '><?php echo __( 'Import', 'openpos' ); ?></a><a  id='export-addon-btn' class='add-new-h2 '><?php echo __( 'Export', 'openpos' ); ?></a>");
        $('body').append("<input id='import_file' style='display:none;' type='file' name='import_file' />");
        var frame_url = '<?php echo admin_url('admin-ajax.php?action=openpos_update_receipt_preview&op_nonce='.$op_nonce.'&id='.$default['id']); ?>' ;

        var receipt_content = CodeMirror.fromTextArea(document.getElementById("receipt-template-content"), {
                    mode: "text/html",
                    styleActiveLine: true,
                    lineNumbers: true,
                    lineWrapping: true,
                    autoRefresh: true
                });
        var receipt_css = CodeMirror.fromTextArea(document.getElementById("receipt-template-css"), {
            mode: "text/css",
            styleActiveLine: true,
            lineNumbers: true,
            lineWrapping: true,
            autoRefresh: true
        });

        $(document).ready(function(){
            


            $('#template-frm').on('submit',function(){
                var data = $(this).serialize();
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php?action=openpos_update_receipt_content'); ?>',
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    beforeSend:function(){
                        $('body').addClass('op_loading');
                    },
                    success:function(data){
                        if(data.status == 1)
                        {
                            $('body').removeClass('op_loading');
                            var tmp_t = new Date().getTime();
                            $('#preview-frame').attr('src',frame_url+'&t='+tmp_t);
                        }else {
                            alert(data.message);
                            $('body').removeClass('op_loading');
                        }
                    },
                    error:function(){
                        $('body').removeClass('op_loading');
                    }
                });
              
               return false;
            });

            $(document).on('click','#load-sample',function(){
                var sample_url = $(this).data('sample');
               
                $.ajax({
                    url: sample_url,
                    type: 'get',
                    dataType: 'text',
                    beforeSend:function(){
                        $('body').addClass('op_loading');
                    },
                    success:function(data){
                        
                        receipt_content.getDoc().setValue(data);
                       

                        
                       $('body').removeClass('op_loading');
                       
                        
                    },
                    error:function(){
                        $('body').removeClass('op_loading');
                    }
                });
            });

            $(document).on('click','#load-sample-css',function(){
                var sample_url = $(this).data('sample');
                $.ajax({
                    url: sample_url,
                    type: 'get',
                    dataType: 'text',
                    beforeSend:function(){
                        $('body').addClass('op_loading');
                    },
                    success:function(data){
                        
                        receipt_css.getDoc().setValue(data);
                       $('body').removeClass('op_loading');
                       
                        
                    },
                    error:function(){
                        $('body').removeClass('op_loading');
                    }
                });
            });

            $(document).on('click','#preview-receipt',function(){
                var tmp_t = new Date().getTime();
                $('input[name="temp_id"]').val(tmp_t);
                var data = $('#template-frm').serialize();
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php?action=openpos_update_receipt_draft'); ?>',
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    beforeSend:function(){
                        $('body').addClass('op_loading');
                    },
                    success:function(data){
                        if(data.status == 1)
                        {
                            
                            $('body').removeClass('op_loading');
                            var tmp_t = new Date().getTime();
                            $('#preview-frame').attr('src',frame_url+'&t='+tmp_t);

                        }else {
                            alert(data.message);
                            $('body').removeClass('op_loading');
                        }
                    },
                    error:function(){
                        $('body').removeClass('op_loading');
                    }
                });

                var form_values = $('#template-frm').serialize();
                
                console.log('click');
            });

            var form_height = $('#template-frm').height();
            if(form_height > 500)
            {
                $('.preview-live').css('height',form_height +'px');
            }

        });

        $(document).on('click','#import-addon-btn',function(){
           
            $(document).find('#import_file').trigger('click');
        });
        $(document).on('click','#export-addon-btn',function(){
            var formData = $('#template-frm').serializeArray();
            downloadObjectAsJson(formData,'template-<?php echo $default['id']; ?>');
        });
        $(document).find('#import_file').on('change',function(e){
            let file = e.target.files[0];
            
            file.text().then(function(data){
                let data_obj = JSON.parse(data);
                for(let i = 0 ; i< data_obj.length; i++)
                {
                    let field_name = data_obj[i]['name'];
                    let field_value = data_obj[i]['value'];
                    if(field_name != 'id'&& field_name != 'name')
                    {
                        let input_field = $('input[name="'+field_name+'"]');
                        let text_area_field = $('textarea[name="'+field_name+'"]');
                        if(input_field)
                        {
                            input_field.val(field_value);
                        }
                        if(text_area_field)
                        {
                            if(field_name == 'content'){
                                receipt_content.getDoc().setValue(field_value);
                            }
                            if(field_name == 'custom_css'){
                                receipt_css.getDoc().setValue(field_value);
                            }
                            if(field_name != 'content' && field_name != 'custom_css'){
                                text_area_field.text(field_value);
                            }
                        }
                        
                    }
                }
                
            });
        });

        /* ============ Drag & drop receipt block builder ============ */
        var OP_RB_TPL_ID = <?php echo (int) $default['id']; ?>;
        var OP_RB_TYPE = '<?php echo esc_js( isset($default['type']) ? $default['type'] : 'receipt' ); ?>';
        function rbBlockOk(b){ var t=b.types||['receipt']; return t.indexOf('*')>-1 || t.indexOf(OP_RB_TYPE)>-1; }

        function rbItemsTable(){
            return [
            '<table class="items">',
            '  <tr class="tabletitle items-table-label">',
            '    <td class="item"><h2>Item</h2></td>',
            '    <td class="qty"><h2>Qty</h2></td>',
            '    <td class="qty"><h2>Price</h2></td>',
            '    <td class="total"><h2>Total</h2></td>',
            '  </tr>',
            '  <% items.forEach(function(item){ %>',
            '  <tr class="service">',
            '    <td class="tableitem item-name"><p class="itemtext"><%= item.name %></p>',
            '      <% if(item.sub_name.length > 0){ %><p class="option-item"><%- item.sub_name %></p><% } %>',
            '    </td>',
            '    <td class="tableitem item-qty"><p class="itemtext"><%= item.qty %></p></td>',
            '    <td class="tableitem item-price"><p class="itemtext"><%= item.final_price_currency_formatted %></p></td>',
            '    <td class="tableitem item-total"><p class="itemtext"><%= item.total_incl_tax_currency_formatted %></p></td>',
            '  </tr>',
            '  <% }); %>',
            '</table>'].join('\n');
        }
        function rbTotals(){
            return [
            '<table class="totals">',
            '  <tr class="tabletitle"><td class="Rate" style="text-align:right;padding-right:5px;" colspan="3"><h2>Sub Total</h2></td><td class="payment"><h2><%= sub_total_currency_formatted %></h2></td></tr>',
            '  <tr class="tabletitle"><td class="Rate" style="text-align:right;padding-right:5px;" colspan="3"><h2>Discount</h2></td><td class="payment"><h2><%= final_discount_amount_currency_formatted %></h2></td></tr>',
            '  <tr class="tabletitle"><td class="Rate" style="text-align:right;padding-right:5px;" colspan="3"><h2>Total Tax</h2></td><td class="payment"><h2><%= tax_amount_currency_formatted %></h2></td></tr>',
            '  <tr class="tabletitle"><td class="Rate" style="text-align:right;padding-right:5px;" colspan="3"><h2>Grand Total</h2></td><td class="payment"><h2><%= grand_total_currency_formatted %></h2></td></tr>',
            '</table>'].join('\n');
        }
        var OP_RB_BLOCKS = {
            logo:      { label: 'Logo', icon: 'format-image', align: 'center', types:['*'],
                            opts: [ {k:'img',label:'Logo image',type:'image'}, {k:'width',label:'Max width',type:'text',def:'120px'} ],
                            html: function(o){ return o.img ? '<img src="'+o.img+'" style="max-width:'+(o.width||'120px')+';height:auto;" />' : '<div class="logo"></div>'; } },
            title:     { label: 'Order Title', icon: 'tag', align: 'center',
                            html: function(){ return '<h2><%= order_number_format %></h2>'; } },
            order_info:{ label: 'Order Info', icon: 'info', align: 'left',
                            opts: [ {k:'date',label:'Order date',type:'check',def:true},{k:'register',label:'Register',type:'check',def:true},{k:'cashier',label:'Cashier',type:'check',def:true},{k:'note',label:'Note',type:'check',def:false} ],
                            html: function(o){ var r='';
                                if(o.note)r+='<p>Note: <%= note %></p>\n';
                                if(o.date)r+='<p>Order Date: <%= created_at %></p>\n';
                                if(o.register)r+='<p>Register: <%= register.name %></p>\n';
                                if(o.cashier)r+='<p>Cashier: <% if(typeof sale_person_name != "undefined"){ %><%= sale_person_name %><% } %></p>\n';
                                return r.replace(/\n$/,''); } },
            customer:  { label: 'Customer Info', icon: 'businessperson', align: 'left',
                            rows: {
                                name:     {label:'Customer', row:function(L){return '<p>'+(L?L+': ':'')+'<%= customer.name %></p>';}},
                                phone:    {label:'Phone',    row:function(L){return '<p>'+(L?L+': ':'')+'<%= customer.phone %></p>';}},
                                email:    {label:'Email',    row:function(L){return '<p>'+(L?L+': ':'')+'<%= customer.email %></p>';}},
                                address:  {label:'Address',  row:function(L){return '<p>'+(L?L+': ':'')+'<%= customer.address %></p>';}},
                                address_2:{label:'Address 2',row:function(L){return '<p>'+(L?L+': ':'')+'<%= customer.address_2 %></p>';}},
                                city:     {label:'City',     row:function(L){return '<p>'+(L?L+': ':'')+'<%= customer.city %></p>';}},
                                state:    {label:'State',    row:function(L){return '<p>'+(L?L+': ':'')+'<%= customer.state %></p>';}},
                                postcode: {label:'Postcode', row:function(L){return '<p>'+(L?L+': ':'')+'<%= customer.postcode %></p>';}},
                                country:  {label:'Country',  row:function(L){return '<p>'+(L?L+': ':'')+'<%= customer.country %></p>';}},
                                point:    {label:'Point',    row:function(L){return '<p>'+(L?L+': ':'')+'<%= customer.point %></p>';}}
                            },
                            defaultList: ['name','phone'],
                            opts: [ {k:'fields', label:'Fields (show / rename / order)', type:'sortlist', source:'rows'} ],
                            html: function(o){ var rows=OP_RB_BLOCKS.customer.rows; var sel=rbList(o.fields,'customer').filter(function(c){return c.on&&rows[c.k];}); return sel.map(function(c){return rows[c.k].row(rbLabel(c,rows));}).join('\n'); } },
            items:     { label: 'Items Table', icon: 'list-view', align: 'left',
                            cols: {
                                name:     {label:'Item',            th:function(L){return '<td class="item"><h2>'+L+'</h2></td>';},   td:'<td class="tableitem item-name"><p class="itemtext"><%= item.name %></p><% if(item.sub_name.length > 0){ %><p class="option-item"><%- item.sub_name %></p><% } %></td>'},
                                qty:      {label:'Qty',             th:function(L){return '<td class="qty"><h2>'+L+'</h2></td>';},    td:'<td class="tableitem item-qty"><p class="itemtext"><%= item.qty %></p></td>'},
                                price:    {label:'Price',           th:function(L){return '<td class="qty"><h2>'+L+'</h2></td>';},    td:'<td class="tableitem item-price"><p class="itemtext"><%= item.final_price_currency_formatted %></p></td>'},
                                price_tax:{label:'Price (incl tax)',th:function(L){return '<td class="qty"><h2>'+L+'</h2></td>';},    td:'<td class="tableitem item-qty"><p class="itemtext"><%= item.final_price_incl_tax_currency_formatted %></p></td>'},
                                total:    {label:'Total',           th:function(L){return '<td class="total"><h2>'+L+'</h2></td>';},  td:'<td class="tableitem item-total"><p class="itemtext"><%= item.total_currency_formatted %></p></td>'},
                                discount: {label:'Discount',        th:function(L){return '<td class="total"><h2>'+L+'</h2></td>';},  td:'<td class="tableitem item-total"><p class="itemtext"><%= item.final_discount_amount_currency_formatted %></p></td>'},
                                tax:      {label:'Tax',             th:function(L){return '<td class="total"><h2>'+L+'</h2></td>';},  td:'<td class="tableitem item-total"><p class="itemtext"><%= item.tax_amount_currency_formatted %></p></td>'},
                                total_tax:{label:'Total (incl tax)',th:function(L){return '<td class="total"><h2>'+L+'</h2></td>';},  td:'<td class="tableitem item-total"><p class="itemtext"><%= item.total_incl_tax_currency_formatted %></p></td>'}
                            },
                            defaultList: ['name','qty','price','total_tax'],
                            opts: [ {k:'columns', label:'Columns (show / rename / order)', type:'sortlist', source:'cols'} ],
                            html: function(o){ var cols=OP_RB_BLOCKS.items.cols; var sel=rbList(o.columns,'items').filter(function(c){return c.on&&cols[c.k];});
                                if(!sel.length) return '';
                                var ths=sel.map(function(c){return '    '+cols[c.k].th(rbLabel(c,cols));}).join('\n');
                                var tds=sel.map(function(c){return '    '+cols[c.k].td;}).join('\n');
                                return '<table class="items">\n  <tr class="tabletitle items-table-label">\n'+ths+'\n  </tr>\n  <% items.forEach(function(item){ %>\n  <tr class="service">\n'+tds+'\n  </tr>\n  <% }); %>\n</table>'; } },
            totals:    { label: 'Totals', icon: 'money-alt', align: 'left',
                            rows: {
                                sub:     {label:'Sub Total',     row:function(L){return '<tr class="tabletitle"><td class="Rate" style="text-align:right;padding-right:5px;"><h2>'+L+'</h2></td><td class="payment"><h2><%= sub_total_currency_formatted %></h2></td></tr>';}},
                                shipping:{label:'Shipping',      row:function(L){return '<tr class="tabletitle"><td class="Rate" style="text-align:right;padding-right:5px;"><h2>'+L+'</h2></td><td class="payment"><h2><%= shipping_cost_currency_formatted %></h2></td></tr>';}},
                                discount:{label:'Discount',      row:function(L){return '<tr class="tabletitle"><td class="Rate" style="text-align:right;padding-right:5px;"><h2>'+L+'</h2></td><td class="payment"><h2><%= final_discount_amount_currency_formatted %></h2></td></tr>';}},
                                tax:     {label:'Total Tax',     row:function(L){return '<tr class="tabletitle"><td class="Rate" style="text-align:right;padding-right:5px;"><h2>'+L+'</h2></td><td class="payment"><h2><%= tax_amount_currency_formatted %></h2></td></tr>';}},
                                grand:   {label:'Grand Total',   row:function(L){return '<tr class="tabletitle"><td class="Rate" style="text-align:right;padding-right:5px;"><h2>'+L+'</h2></td><td class="payment"><h2><%= grand_total_currency_formatted %></h2></td></tr>';}},
                                paid:    {label:'Customer Paid', row:function(L){return '<% if(typeof customer_total_paid_currency_formatted != "undefined"){ %><tr class="tabletitle"><td class="Rate" style="text-align:right;padding-right:5px;"><h2>'+L+'</h2></td><td class="payment"><h2><%= customer_total_paid_currency_formatted %></h2></td></tr><% } %>';}}
                            },
                            defaultList: ['sub','discount','tax','grand'],
                            opts: [ {k:'rows', label:'Rows (show / rename / order)', type:'sortlist', source:'rows'} ],
                            html: function(o){ var rows=OP_RB_BLOCKS.totals.rows; var sel=rbList(o.rows,'totals').filter(function(c){return c.on&&rows[c.k];});
                                if(!sel.length) return '';
                                return '<table class="totals">\n'+sel.map(function(c){return '  '+rows[c.k].row(rbLabel(c,rows));}).join('\n')+'\n</table>'; } },
            payments:  { label: 'Payments', icon: 'money', align: 'left',
                            html: function(){ return '<ul class="payment-methods">\n<% payment_method.forEach(function(payment){ %>\n  <li><%= payment.name %>: <%= payment.paid_currency_formatted %></li>\n<% }); %>\n</ul>'; } },
            barcode:   { label: 'Barcode', icon: 'tickets-alt', align: 'center', html: function(){ return '<%= barcode %>'; } },
            text:      { label: 'Custom Text', icon: 'editor-textcolor', align: 'center', types:['*'],
                            opts: [ {k:'content',label:'Text / HTML',type:'textarea',def:'Thank you for your business!'} ],
                            html: function(o){ return (o.content||''); } },
            divider:   { label: 'Divider', icon: 'minus', align: 'center', types:['*'], html: function(){ return '<hr/>'; } },

            /* ===== Kitchen Receipt ===== */
            k_table:   { label: 'Table / Desk', icon: 'editor-table', align: 'center', types:['kitchen_receipt'],
                            html: function(){ return '<h2><%= desk.name %></h2>'; } },
            k_note:    { label: 'Kitchen Note', icon: 'edit', align: 'left', types:['kitchen_receipt'],
                            html: function(){ return '<p>Note: <%= note %></p>'; } },
            k_items:   { label: 'Kitchen Items', icon: 'list-view', align: 'left', types:['kitchen_receipt'],
                            rows: {
                                main:  {label:'Qty x Name', row:function(L){return '<p class="k-name"><b><%= item.qty %> x <%= item.name %></b></p>';}},
                                sub:   {label:'Options',    row:function(L){return '<% if(item.sub_name.length > 0){ %><p class="option-item"><%- item.sub_name %></p><% } %>';}},
                                time:  {label:'Order time',  row:function(L){return '<p class="k-time">'+(L?L+': ':'')+'<%= item.order_time %></p>';}},
                                seller:{label:'Seller',      row:function(L){return '<p class="k-seller">'+(L?L+': ':'')+'<%= item.seller_name %></p>';}},
                                state: {label:'State',       row:function(L){return '<p class="k-state">'+(L?L+': ':'')+'<%= item.state %></p>';}}
                            },
                            defaultList: ['main','sub'],
                            opts: [ {k:'fields', label:'Lines (show / rename / order)', type:'sortlist', source:'rows'} ],
                            html: function(o){ var rows=OP_RB_BLOCKS.k_items.rows; var sel=rbList(o.fields,'k_items').filter(function(c){return c.on&&rows[c.k];});
                                var inner=sel.map(function(c){return '    '+rows[c.k].row(rbLabel(c,rows));}).join('\n');
                                return '<% items.forEach(function(item){ %>\n  <div class="kitchen-item">\n'+inner+'\n  </div>\n<% }); %>'; } },

            /* ===== Delivery Receipt ===== */
            d_number:  { label: 'Order Number', icon: 'tag', align: 'center', types:['delivery_receipt'],
                            html: function(){ return '<h2>#<%= order_number %></h2>'; } },
            d_shipping:{ label: 'Shipping Info', icon: 'location', align: 'left', types:['delivery_receipt'],
                            rows: {
                                name:    {label:'Name',     row:function(L){return '<p>'+(L?L+': ':'')+'<%= shipping.shipping_information.firstname %> <%= shipping.shipping_information.lastname %></p>';}},
                                phone:   {label:'Phone',    row:function(L){return '<p>'+(L?L+': ':'')+'<%= shipping.shipping_information.phone %></p>';}},
                                address: {label:'Address',  row:function(L){return '<p>'+(L?L+': ':'')+'<%= shipping.shipping_information.address %></p>';}},
                                city:    {label:'City',     row:function(L){return '<p>'+(L?L+': ':'')+'<%= shipping.shipping_information.city %></p>';}},
                                postcode:{label:'Postcode', row:function(L){return '<p>'+(L?L+': ':'')+'<%= shipping.shipping_information.postcode %></p>';}},
                                state:   {label:'State',    row:function(L){return '<p>'+(L?L+': ':'')+'<%= shipping.shipping_information.state %></p>';}}
                            },
                            defaultList: ['name','phone','address','city'],
                            opts: [ {k:'fields', label:'Fields (show / rename / order)', type:'sortlist', source:'rows'} ],
                            html: function(o){ var rows=OP_RB_BLOCKS.d_shipping.rows; var sel=rbList(o.fields,'d_shipping').filter(function(c){return c.on&&rows[c.k];}); return sel.map(function(c){return rows[c.k].row(rbLabel(c,rows));}).join('\n'); } },
            d_items:   { label: 'Items Table', icon: 'list-view', align: 'left', types:['delivery_receipt'],
                            cols: {
                                name:  {label:'Item',  th:function(L){return '<td class="item"><h2>'+L+'</h2></td>';},  td:'<td class="tableitem item-name"><p class="itemtext"><%= item.name %></p></td>'},
                                qty:   {label:'Qty',   th:function(L){return '<td class="qty"><h2>'+L+'</h2></td>';},   td:'<td class="tableitem item-qty"><p class="itemtext"><%= item.qty %></p></td>'},
                                price: {label:'Price', th:function(L){return '<td class="qty"><h2>'+L+'</h2></td>';},   td:'<td class="tableitem item-price"><p class="itemtext"><%= item.final_price_incl_tax.toFixed(2) %></p></td>'},
                                total: {label:'Total', th:function(L){return '<td class="total"><h2>'+L+'</h2></td>';}, td:'<td class="tableitem item-total"><p class="itemtext"><%= item.total_incl_tax.toFixed(2) %></p></td>'}
                            },
                            defaultList: ['name','qty','price','total'],
                            opts: [ {k:'columns', label:'Columns (show / rename / order)', type:'sortlist', source:'cols'} ],
                            html: function(o){ var cols=OP_RB_BLOCKS.d_items.cols; var sel=rbList(o.columns,'d_items').filter(function(c){return c.on&&cols[c.k];});
                                if(!sel.length) return '';
                                var ths=sel.map(function(c){return '    '+cols[c.k].th(rbLabel(c,cols));}).join('\n');
                                var tds=sel.map(function(c){return '    '+cols[c.k].td;}).join('\n');
                                return '<table class="items">\n  <tr class="tabletitle items-table-label">\n'+ths+'\n  </tr>\n  <% items.forEach(function(item){ %>\n  <tr class="service">\n'+tds+'\n  </tr>\n  <% }); %>\n</table>'; } },
            d_subtotal:{ label: 'Sub Total', icon: 'money-alt', align: 'right', types:['delivery_receipt'],
                            html: function(){ return '<p><b>Sub Total: <%= sub_total_incl_tax.toFixed(2) %></b></p>'; } },

            /* ===== Product Decal (label) ===== */
            decal:     { label: 'Product Label', icon: 'tag', align: 'center', types:['product_decal'],
                            rows: {
                                name:  {label:'Name',  row:function(L){return '<p class="decal-name"><b><%= item.name %></b></p>';}},
                                sub:   {label:'Options',row:function(L){return '<% if(item.sub_name.length > 0){ %><p class="option-item"><%- item.sub_name %></p><% } %>';}},
                                qty:   {label:'Qty',   row:function(L){return '<p class="decal-qty">'+(L?L+': ':'')+'<%= item.qty %></p>';}},
                                price: {label:'Price', row:function(L){return '<p class="decal-price">'+(L?L+': ':'')+'<%= item.final_price_incl_tax.toFixed(2) %></p>';}}
                            },
                            defaultList: ['name','qty','price'],
                            opts: [ {k:'fields', label:'Lines (show / rename / order)', type:'sortlist', source:'rows'} ],
                            html: function(o){ var rows=OP_RB_BLOCKS.decal.rows; var sel=rbList(o.fields,'decal').filter(function(c){return c.on&&rows[c.k];});
                                var inner=sel.map(function(c){return '    '+rows[c.k].row(rbLabel(c,rows));}).join('\n');
                                return '<% items.forEach(function(item){ %>\n  <div class="decal-item">\n'+inner+'\n  </div>\n<% }); %>'; } },

            /* ===== X-Report (Z report) ===== */
            z_header:  { label: 'Report Header', icon: 'info', align: 'left', types:['zreport'],
                            rows: {
                                register:{label:'Register',     row:function(L){return '<p>'+(L?L+': ':'')+'<%= register_id %></p>';}},
                                cashier: {label:'Cashier',      row:function(L){return '<p>'+(L?L+': ':'')+'<%= cashier_user_name %></p>';}},
                                login:   {label:'Clock In',     row:function(L){return '<p>'+(L?L+': ':'')+'<%= login_time %></p>';}},
                                logout:  {label:'Clock Out',    row:function(L){return '<p>'+(L?L+': ':'')+'<%= logout_time %></p>';}},
                                open:    {label:'Open Balance', row:function(L){return '<p>'+(L?L+': ':'')+'<%= open_balance_currency_formatted %></p>';}},
                                close:   {label:'Close Balance',row:function(L){return '<p>'+(L?L+': ':'')+'<%= close_balance_currency_formatted %></p>';}}
                            },
                            defaultList: ['register','cashier','login','logout','open','close'],
                            opts: [ {k:'fields', label:'Fields (show / rename / order)', type:'sortlist', source:'rows'} ],
                            html: function(o){ var rows=OP_RB_BLOCKS.z_header.rows; var sel=rbList(o.fields,'z_header').filter(function(c){return c.on&&rows[c.k];}); return sel.map(function(c){return rows[c.k].row(rbLabel(c,rows));}).join('\n'); } },
            z_sales:   { label: 'Sales Total', icon: 'money-alt', align: 'left', types:['zreport'],
                            html: function(){ return '<p>Sales Total: <%= sale_total_currency_formatted %></p>\n<p>Custom Transactions: <%= custom_transaction_total_currency_formatted %></p>'; } },
            z_payments:{ label: 'Payments Summary', icon: 'money', align: 'left', types:['zreport'],
                            html: function(){ return '<table class="z-payments">\n  <% sale_payments.forEach(function(payment){ %>\n  <tr><td><%= payment.payment_name %></td><td><%= payment.total_currency_formatted %></td></tr>\n  <% }); %>\n</table>'; } },
            z_products:{ label: 'Products Summary', icon: 'cart', align: 'left', types:['zreport'],
                            cols: {
                                name:    {label:'Product', th:function(L){return '<td><h2>'+L+'</h2></td>';},                   td:'<td><%= product.name %><br/><%= product.barcode %></td>'},
                                qty:     {label:'Qty',     th:function(L){return '<td style="text-align:center;"><h2>'+L+'</h2></td>';},  td:'<td style="text-align:center;"><%= product.qty %></td>'},
                                total:   {label:'Total',   th:function(L){return '<td style="text-align:center;"><h2>'+L+'</h2></td>';},  td:'<td style="text-align:center;"><%= product.total_incl_tax_sale_currency_formatted %></td>'}
                            },
                            defaultList: ['name','qty','total'],
                            opts: [ {k:'columns', label:'Columns (show / rename / order)', type:'sortlist', source:'cols'} ],
                            html: function(o){ var cols=OP_RB_BLOCKS.z_products.cols; var sel=rbList(o.columns,'z_products').filter(function(c){return c.on&&cols[c.k];});
                                if(!sel.length) return '';
                                var ths=sel.map(function(c){return '    '+cols[c.k].th(rbLabel(c,cols));}).join('\n');
                                var tds=sel.map(function(c){return '    '+cols[c.k].td;}).join('\n');
                                return '<table class="z-products">\n  <tr class="tabletitle">\n'+ths+'\n  </tr>\n  <% var product_keys = Object.keys(products); %>\n  <% product_keys.forEach(function(key){ var product = products[key]; %>\n  <tr>\n'+tds+'\n  </tr>\n  <% }); %>\n</table>'; } }
        };

        /* Common layout & style options applied to every block */
        var OP_RB_FONTS = [
            {v:'', l:'Default'},
            {v:'inherit', l:'Inherit'},
            {v:'monospace', l:'Monospace (generic)'},
            {v:'\'Courier New\', Courier, monospace', l:'Courier New (thermal)'},
            {v:'\'Consolas\', monospace', l:'Consolas'},
            {v:'\'Roboto Mono\', monospace', l:'Roboto Mono'},
            {v:'\'DejaVu Sans Mono\', monospace', l:'DejaVu Sans Mono'},
            {v:'\'Liberation Mono\', monospace', l:'Liberation Mono'},
            {v:'\'PT Mono\', monospace', l:'PT Mono'},
            {v:'Arial, Helvetica, sans-serif', l:'Arial / Helvetica'},
            {v:'Tahoma, sans-serif', l:'Tahoma'},
            {v:'Verdana, sans-serif', l:'Verdana'},
            {v:'\'Times New Roman\', serif', l:'Times New Roman'}
        ];
        var OP_RB_GROUPS = [
            { title:'Spacing & Align', icon:'editor-aligncenter', open:true, fields:[
                {k:'_align', label:'Align', type:'select', options:[{v:'left',l:'Left'},{v:'center',l:'Center'},{v:'right',l:'Right'}]},
                {k:'_padding',label:'Padding', type:'text', def:'', ph:'e.g. 4px 0'},
                {k:'_margin', label:'Margin', type:'text', def:'', ph:'e.g. 0 0 8px'}
            ]},
            { title:'Typography', icon:'editor-textcolor', open:false, fields:[
                {k:'_ff', label:'Font family', type:'select', options: OP_RB_FONTS},
                {k:'_fs', label:'Font size', type:'text', def:'', ph:'e.g. 14px'},
                {k:'_fw', label:'Font weight', type:'select', options:[{v:'',l:'Default'},{v:'normal',l:'Normal'},{v:'300',l:'Light (300)'},{v:'500',l:'Medium (500)'},{v:'600',l:'Semibold (600)'},{v:'bold',l:'Bold'}]}
            ]},
            { title:'Advanced', icon:'editor-code', open:false, fields:[
                {k:'_name', label:'CSS class (name)', type:'text', def:'', ph:'e.g. my-header'}
            ]}
        ];

        var $rb = $('.op-rb'), $palette = $('.op-rb-palette'), $canvas = $('.op-rb-canvas');
        var rbState = [];
        var rbUid = 1;
        var rbPreviewTimer = null;
        var rbManaged = false; // true once the user intentionally uses the builder for this template

        function rbDefaultList(type){
            var b=OP_RB_BLOCKS[type]; var src=b.cols||b.rows||{}; var keys=Object.keys(src); var def=b.defaultList||keys;
            var arr=[];
            def.forEach(function(k){ if(src[k]) arr.push({k:k,on:true,label:src[k].label}); });
            keys.forEach(function(k){ if(def.indexOf(k)<0) arr.push({k:k,on:false,label:src[k].label}); });
            return arr;
        }
        function rbList(list,type){ return (list&&list.length)?list:rbDefaultList(type); }
        function rbLabel(c,map){ return (c.label!==undefined && c.label!==null) ? c.label : (map[c.k]?map[c.k].label:c.k); }
        function rbDefaults(type){
            var b=OP_RB_BLOCKS[type];
            var d={ _name:'', _align:(b.align||'left'), _padding:'', _margin:'', _ff:'', _fs:'', _fw:'' };
            (b.opts||[]).forEach(function(o){
                if(o.type==='sortlist'){ d[o.k]=rbDefaultList(type); }
                else { d[o.k]=(o.def!==undefined?o.def:''); }
            });
            return d;
        }
        function rbEsc(s){ return $('<div/>').text(s==null?'':s).html(); }

        function rbBuildPalette(){
            Object.keys(OP_RB_BLOCKS).forEach(function(type){
                var b=OP_RB_BLOCKS[type];
                if(!rbBlockOk(b)) return;
                var $c=$('<div class="op-rb-chip" draggable="true"><span class="dashicons dashicons-'+b.icon+'"></span>'+rbEsc(b.label)+'</div>');
                $c.attr('data-type',type);
                $c.on('dragstart',function(e){ e.originalEvent.dataTransfer.setData('text/op-new',type); e.originalEvent.dataTransfer.effectAllowed='copy'; });
                $c.on('click',function(){ rbState.push({uid:rbUid++,type:type,opts:rbDefaults(type)}); rbRender(); rbGenerate(); });
                $palette.append($c);
            });
        }

        function rbOptControl(o, item){
            var val=item.opts[o.k];
            if(o.type==='check'){
                var $l=$('<label class="op-rb-opt op-rb-check"><input type="checkbox"><span></span></label>');
                $l.find('span').text(o.label);
                $l.find('input').prop('checked',!!val).on('change',function(){ item.opts[o.k]=$(this).prop('checked'); rbGenerate(); });
                return $l;
            }
            if(o.type==='textarea'){
                var $t=$('<label class="op-rb-opt op-rb-wide"><span></span><textarea rows="2"></textarea></label>');
                $t.find('span').text(o.label);
                $t.find('textarea').val(val).on('input',function(){ item.opts[o.k]=$(this).val(); rbGenerate(); });
                return $t;
            }
            if(o.type==='select'){
                var $s=$('<label class="op-rb-opt"><span></span><select></select></label>');
                $s.find('span').text(o.label);
                var $sel=$s.find('select');
                o.options.forEach(function(opt){ var ov=(typeof opt==='string')?{v:opt,l:opt}:opt; $('<option>').val(ov.v).text(ov.l).prop('selected',ov.v===(val||'')).appendTo($sel); });
                $sel.on('change',function(){ item.opts[o.k]=$(this).val(); rbGenerate(); });
                return $s;
            }
            if(o.type==='image'){
                var $w=$('<div class="op-rb-opt op-rb-wide op-rb-imgopt"><span></span><div class="op-rb-imgrow"><div class="op-rb-imgprev"></div><div class="op-rb-imgbtns"></div></div></div>');
                $w.children('span').text(o.label);
                var $prev=$w.find('.op-rb-imgprev');
                function refresh(){ var u=item.opts[o.k]; $prev.html(u?('<img src="'+u+'"/>'):'<span class="op-rb-noimg">'+'<?php echo esc_js( __( 'No image', 'openpos' ) ); ?>'+'</span>'); }
                refresh();
                var $choose=$('<button type="button" class="button button-small"><?php echo esc_js( __( 'Choose', 'openpos' ) ); ?></button>');
                var $rm=$('<button type="button" class="button button-small"><?php echo esc_js( __( 'Remove', 'openpos' ) ); ?></button>');
                $choose.on('click',function(){
                    if(typeof wp==='undefined'||!wp.media){ alert('Media library not available'); return; }
                    var fr=wp.media({ title:'<?php echo esc_js( __( 'Select logo image', 'openpos' ) ); ?>', button:{ text:'<?php echo esc_js( __( 'Use image', 'openpos' ) ); ?>' }, multiple:false, library:{ type:'image' } });
                    fr.on('select',function(){ var a=fr.state().get('selection').first().toJSON(); item.opts[o.k]=a.url; refresh(); rbGenerate(); });
                    fr.open();
                });
                $rm.on('click',function(){ item.opts[o.k]=''; refresh(); rbGenerate(); });
                $w.find('.op-rb-imgbtns').append($choose).append($rm);
                return $w;
            }
            if(o.type==='sortlist'){
                if(!item.opts[o.k] || !item.opts[o.k].length){ item.opts[o.k]=rbDefaultList(item.type); }
                var list=item.opts[o.k];
                var src=OP_RB_BLOCKS[item.type][o.source]||{};
                var $w=$('<div class="op-rb-opt op-rb-wide op-rb-sortlist"><span></span><div class="op-rb-sl"></div></div>');
                $w.children('span').text(o.label);
                var $sl=$w.find('.op-rb-sl');
                function slRender(){
                    $sl.empty();
                    list.forEach(function(c,i){
                        var def=src[c.k];
                        var $r=$('<div class="op-rb-slrow">'+
                            '<span class="op-rb-slmove">'+
                              '<button type="button" class="op-rb-slbtn op-rb-slup"><span class="dashicons dashicons-arrow-up-alt2"></span></button>'+
                              '<button type="button" class="op-rb-slbtn op-rb-sldown"><span class="dashicons dashicons-arrow-down-alt2"></span></button>'+
                            '</span>'+
                            '<label class="op-rb-slcheck"><input type="checkbox" class="op-rb-slon"></label>'+
                            '<span class="op-rb-slbase"></span>'+
                            '<input type="text" class="op-rb-sllabel" />'+
                            '</div>');
                        if(!c.on) $r.addClass('is-off');
                        $r.find('.op-rb-slbase').text(def?def.label:c.k);
                        $r.find('.op-rb-slon').prop('checked',!!c.on).on('change',function(){ c.on=$(this).prop('checked'); $r.toggleClass('is-off',!c.on); rbGenerate(); });
                        $r.find('.op-rb-sllabel').val(rbLabel(c,src)).attr('placeholder',(def?def.label:'')).on('input',function(){ c.label=$(this).val(); rbGenerate(); });
                        $r.find('.op-rb-slup').on('click',function(){ if(i>0){ var t=list.splice(i,1)[0]; list.splice(i-1,0,t); slRender(); rbGenerate(); } });
                        $r.find('.op-rb-sldown').on('click',function(){ if(i<list.length-1){ var t=list.splice(i,1)[0]; list.splice(i+1,0,t); slRender(); rbGenerate(); } });
                        $sl.append($r);
                    });
                }
                slRender();
                return $w;
            }
            var $i=$('<label class="op-rb-opt"><span></span><input type="text"></label>');
            $i.find('span').text(o.label);
            $i.find('input').val(val).attr('placeholder',o.ph||'').on('input',function(){ item.opts[o.k]=$(this).val(); rbGenerate(); });
            return $i;
        }

        function rbGroup(title, icon, fields, open, item){
            var $g=$('<div class="op-rb-group'+(open?' is-open':'')+'"></div>');
            var $head=$('<button type="button" class="op-rb-grouphead"><span class="dashicons dashicons-'+icon+'"></span><span class="op-rb-grouptitle"></span><span class="op-rb-groupchev dashicons dashicons-arrow-down-alt2"></span></button>');
            $head.find('.op-rb-grouptitle').text(title);
            $g.append($head);
            var $gb=$('<div class="op-rb-groupbody"><div class="op-rb-optgrid"></div></div>');
            var $grid=$gb.find('.op-rb-optgrid');
            fields.forEach(function(o){ $grid.append(rbOptControl(o,item)); });
            $g.append($gb);
            return $g;
        }

        function rbRender(){
            $canvas.empty();
            if(!rbState.length){ $canvas.html('<div class="op-rb-empty"><?php echo esc_js( __( 'Drag blocks here, or click a block on the left to add it.', 'openpos' ) ); ?></div>'); return; }
            rbState.forEach(function(item,idx){
                var b=OP_RB_BLOCKS[item.type];
                var $b=$('<div class="op-rb-block"></div>').attr('data-idx',idx);
                var nameTag=item.opts._name ? ' <code class="op-rb-nametag">.'+rbEsc(item.opts._name)+'</code>' : '';
                var $bar=$('<div class="op-rb-block__bar" draggable="true">'+
                        '<span class="op-rb-block__handle dashicons dashicons-menu"></span>'+
                        '<span class="op-rb-block__title"><span class="dashicons dashicons-'+b.icon+'"></span>'+rbEsc(b.label)+nameTag+'</span>'+
                        '<span class="op-rb-block__actions">'+
                        '<button type="button" class="op-rb-iconbtn op-rb-cfg" title="Options"><span class="dashicons dashicons-admin-generic"></span></button>'+
                        '<button type="button" class="op-rb-iconbtn op-rb-del" title="Remove"><span class="dashicons dashicons-trash"></span></button>'+
                        '</span></div>');
                $b.append($bar);
                var $opts=$('<div class="op-rb-block__opts"></div>');
                if(b.opts&&b.opts.length){ $opts.append(rbGroup('<?php echo esc_js( __( 'Content', 'openpos' ) ); ?>', b.icon, b.opts, true, item)); }
                OP_RB_GROUPS.forEach(function(grp){ $opts.append(rbGroup(grp.title, grp.icon, grp.fields, grp.open, item)); });
                $b.append($opts);
                $canvas.append($b);
            });
        }

        function rbWrap(inner,o){
            var s='';
            if(o._align && o._align!=='left') s+='text-align:'+o._align+';';
            if(o._padding) s+='padding:'+o._padding+';';
            if(o._margin) s+='margin:'+o._margin+';';
            if(o._ff) s+='font-family:'+o._ff+';';
            if(o._fs) s+='font-size:'+o._fs+';';
            if(o._fw) s+='font-weight:'+o._fw+';';
            var cls=o._name ? ' class="'+o._name+'"' : '';
            if(s||cls) return '<div'+cls+(s?(' style="'+s+'"'):'')+'>\n'+inner+'\n</div>';
            return inner;
        }
        function rbGenerate(){
            var parts=rbState.map(function(item){ return rbWrap(OP_RB_BLOCKS[item.type].html(item.opts||{}), item.opts||{}); });
            var ejs=parts.join('\n\n');
            receipt_content.getDoc().setValue(ejs);
            try{ localStorage.setItem('op_rb_v1_'+OP_RB_TPL_ID, JSON.stringify(rbState)); }catch(e){}
            rbSchedulePreview();
        }

        function rbSchedulePreview(){
            if(!rbManaged) return; // never auto-save until the builder is intentionally in control
            if(rbPreviewTimer) clearTimeout(rbPreviewTimer);
            rbPreviewTimer=setTimeout(function(){
                receipt_content.save();
                $.ajax({ url:'<?php echo admin_url('admin-ajax.php?action=openpos_update_receipt_content'); ?>', type:'post', dataType:'json',
                    data:$('#template-frm').serialize(),
                    success:function(d){ if(d.status==1){ $('#preview-frame').attr('src', frame_url+'&t='+new Date().getTime()); } } });
            },700);
        }

        /* drag reorder + drop from palette */
        var rbDragIdx=null;
        $canvas.on('dragstart','.op-rb-block',function(e){ rbDragIdx=parseInt($(this).attr('data-idx'),10); $(this).addClass('op-rb-dragging'); e.originalEvent.dataTransfer.effectAllowed='move'; e.originalEvent.dataTransfer.setData('text/op-move',rbDragIdx); });
        $canvas.on('dragend','.op-rb-block',function(){ $(this).removeClass('op-rb-dragging'); $('.op-rb-block').removeClass('op-rb-drop-before op-rb-drop-after'); });
        function rbInsertPos(y){
            var blocks=$canvas.find('.op-rb-block'); var pos=blocks.length;
            blocks.each(function(i){ var r=this.getBoundingClientRect(); if(y < r.top + r.height/2){ pos=i; return false; } });
            return pos;
        }
        $canvas.on('dragover',function(e){ e.preventDefault(); $canvas.addClass('op-rb-over');
            var pos=rbInsertPos(e.originalEvent.clientY); var blocks=$canvas.find('.op-rb-block');
            blocks.removeClass('op-rb-drop-before op-rb-drop-after');
            if(pos<blocks.length){ blocks.eq(pos).addClass('op-rb-drop-before'); } else if(blocks.length){ blocks.eq(blocks.length-1).addClass('op-rb-drop-after'); }
        });
        $canvas.on('dragleave',function(e){ if(e.target===$canvas[0]){ $canvas.removeClass('op-rb-over'); } });
        $canvas.on('drop',function(e){ e.preventDefault(); $canvas.removeClass('op-rb-over'); $('.op-rb-block').removeClass('op-rb-drop-before op-rb-drop-after');
            var dt=e.originalEvent.dataTransfer; var pos=rbInsertPos(e.originalEvent.clientY);
            var newType=dt.getData('text/op-new');
            if(newType){ rbState.splice(pos,0,{uid:rbUid++,type:newType,opts:rbDefaults(newType)}); }
            else if(rbDragIdx!==null){ var moved=rbState.splice(rbDragIdx,1)[0]; if(rbDragIdx<pos)pos--; rbState.splice(pos,0,moved); }
            rbDragIdx=null; rbRender(); rbGenerate();
        });

        $canvas.on('click','.op-rb-del',function(){ var idx=parseInt($(this).closest('.op-rb-block').attr('data-idx'),10); rbState.splice(idx,1); rbRender(); rbGenerate(); });
        $canvas.on('click','.op-rb-cfg',function(){ $(this).closest('.op-rb-block').toggleClass('is-open'); });
        $canvas.on('click','.op-rb-grouphead',function(){ $(this).closest('.op-rb-group').toggleClass('is-open'); });

        /* tabs */
        $('.op-rb-tab').on('click',function(){
            var mode=$(this).data('mode');
            if(mode==='builder' && !rbManaged && $.trim(receipt_content.getValue()).length>0){
                if(!confirm('<?php echo esc_js( __( 'Builder mode rebuilds this receipt from blocks and will replace your current template code when you add or change blocks. Continue?', 'openpos' ) ); ?>')){
                    return;
                }
                rbManaged = true;
            }
            if(mode==='builder'){ rbManaged = true; }
            $('.op-rb-tab').removeClass('is-active'); $(this).addClass('is-active');
            $('.op-rb').toggle(mode==='builder');
            $('.op-rb-code').toggle(mode==='code');
            if(mode==='code'){ setTimeout(function(){ receipt_content.refresh(); },10); }
        });

        rbBuildPalette();
        (function rbInit(){
            var saved=null; try{ saved=JSON.parse(localStorage.getItem('op_rb_v1_'+OP_RB_TPL_ID)); }catch(e){}
            if(saved && saved.length){ rbManaged=true; rbState=saved; rbState.forEach(function(it){ it.uid=rbUid++; }); rbRender(); }
            else {
                var hasContent=$.trim(receipt_content.getValue()).length>0;
                if(hasContent){
                    $('.op-rb-tab').removeClass('is-active'); $('.op-rb-tab[data-mode="code"]').addClass('is-active');
                    $('.op-rb').hide(); $('.op-rb-code').show(); setTimeout(function(){ receipt_content.refresh(); },10);
                } else {
                    rbManaged = true; // empty template -> safe to let builder own it
                }
                rbRender();
            }
        })();
    })( jQuery );
</script>