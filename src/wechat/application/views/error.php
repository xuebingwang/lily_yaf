<div class="page">
    <div class="weui_msg">
        <div class="weui_icon_area"><i class="weui_icon_safe weui_icon_safe_warn"></i></div>
        <div class="weui_text_area">
            <h2 class="weui_msg_title">提示</h2>
            <p class="weui_msg_desc"><?=$message?></p>
        </div>
        <div class="weui_opr_area">
            <p class="weui_btn_area">
                <a href="<?=empty($jumpUrl) ? 'javascript:back();' : $jumpUrl?>" class="weui_btn weui_btn_default"><?=isset($data['btn_text']) ? $data['btn_text'] : '返回'?></a>
            </p>
        </div>
    </div>
</div>